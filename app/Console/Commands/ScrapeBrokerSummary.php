<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BrokerSummary;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class ScrapeBrokerSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:broker-summary {ticker} {--date= : Format YYYY-MM-DD} {--force : Paksa scrape ulang meski data sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape IPOT Broker Summary (Auto-detect Docker/Local Environment)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticker = strtoupper($this->argument('ticker'));
        
        // Default ke hari ini jika opsi date tidak diisi
        $inputDate = $this->option('date') ?? Carbon::today()->format('Y-m-d');
        
        // Format tanggal untuk URL IPOT (MM/DD/YYYY)
        $ipotDate = Carbon::parse($inputDate)->format('m/d/Y'); 
        
        $force = $this->option('force');

        // --- 1. CEK DUPLIKAT DATA DI DATABASE ---
        $exists = BrokerSummary::where('ticker', $ticker)
                               ->where('date', $inputDate)
                               ->exists();

        if ($exists && !$force) {
            $this->warn("⚠️  SKIP: Data untuk $ticker tanggal $inputDate sudah ada di database.");
            $this->line("Gunakan flag --force jika ingin menimpa data yang ada.");
            return;
        }

        if ($force) {
            $this->warn("🔥 FORCE MODE: Data lama akan di-update.");
        }

        // URL Halaman Utama (untuk referensi Browsershot)
        $baseUrl = "https://www.indopremier.com/ipotnews/newsSmartSearch.php?code={$ticker}";
        
        // URL API/Endpoint Data Tabel
        $targetApiUrl = "/module/saham/include/data-brokersummary.php?code={$ticker}&start={$ipotDate}&end={$ipotDate}&fd=all&board=all";

        $this->info("1. Fetching data for $ticker ($ipotDate)...");

        try {
            // Script JS untuk mengambil data tabel via fetch di dalam browser
            $jsFetch = "
                (async () => {
                    try {
                        const response = await fetch('$targetApiUrl');
                        if (!response.ok) return 'ERROR: HTTP ' + response.status;
                        return await response.text();
                    } catch (err) {
                        return 'ERROR: ' + err.message;
                    }
                })();
            ";

            // --- INISIALISASI BROWSERSHOT ---
            $browsershot = Browsershot::url($baseUrl);

            // --- DETEKSI LINGKUNGAN (DOCKER vs LOCAL) ---
            // Path ini hanya ada di container Alpine Linux (Docker)
            $dockerChromePath = '/usr/bin/chromium-browser';

            if (file_exists($dockerChromePath)) {
                // SETTING KHUSUS DOCKER
                // Kita set path manual dan matikan sandbox karena user di docker biasanya root
                $browsershot->setChromePath($dockerChromePath)
                            ->noSandbox()
                            ->setOption('args', ['--disable-gpu', '--disable-dev-shm-usage']);
            } else {
                // SETTING KHUSUS LOCAL (Mac/Windows)
                // Kita biarkan Browsershot mencari Chrome/Puppeteer sendiri.
                // Tidak perlu setChromePath manual jika sudah install npm install puppeteer
                // Tidak perlu noSandbox() di Mac agar lebih aman dan default.
            }

            // --- EKSEKUSI ---
            $responseHtml = $browsershot
                ->windowSize(1920, 1080)
                ->dismissDialogs()
                ->waitUntilNetworkIdle()
                ->evaluate($jsFetch);

        } catch (\Exception $e) {
            $this->error("Browser Error: " . $e->getMessage());
            $this->line("Tip: Jika di local, pastikan sudah run 'npm install puppeteer'. Jika di Docker, pastikan image sudah terinstall chromium.");
            return;
        }

        // Cek apakah hasil fetch mengandung pesan error manual kita
        if (str_starts_with($responseHtml, 'ERROR:')) {
            $this->error("Fetch Failed: " . $responseHtml);
            return;
        }

        $this->info("2. Parsing Data...");

        // Menggunakan Symfony DomCrawler untuk parsing HTML table
        $crawler = new Crawler($responseHtml);
        $dataToInsert = [];
        
        $rows = $crawler->filter('tr');

        if ($rows->count() == 0) {
            $this->error("Data kosong/Libur atau struktur HTML berubah.");
            return;
        }

        $rows->each(function (Crawler $row) use (&$dataToInsert) {
            $cols = $row->filter('td');
            
            // Pastikan baris memiliki cukup kolom
            if ($cols->count() < 8) return;

            // --- BUYER (Kolom Kiri - Index 0,1,2,3) ---
            $buyCode = trim($cols->eq(0)->text());
            // Validasi kode broker (2 digit huruf/angka)
            if (preg_match('/^[A-Z0-9]{2}$/', $buyCode)) { 
                $buyVol = $this->parseVolumeValue($cols->eq(1)->text());
                $buyVal = $this->parseVolumeValue($cols->eq(2)->text());
                $buyAvg = $this->parseVolumeValue($cols->eq(3)->text());
                
                if (!isset($dataToInsert[$buyCode])) $dataToInsert[$buyCode] = $this->initData($buyCode);
                $dataToInsert[$buyCode]['buy_vol'] += $buyVol;
                $dataToInsert[$buyCode]['buy_val'] += $buyVal;
                $dataToInsert[$buyCode]['buy_avg'] = $buyAvg; 
            }

            // --- SELLER (Kolom Kanan - Index 5,6,7,8) ---
            $sellCode = trim($cols->eq(5)->text());
            // Validasi kode broker
            if (preg_match('/^[A-Z0-9]{2}$/', $sellCode)) {
                $sellVol = $this->parseVolumeValue($cols->eq(6)->text());
                $sellVal = $this->parseVolumeValue($cols->eq(7)->text());
                $sellAvg = $this->parseVolumeValue($cols->eq(8)->text());

                if (!isset($dataToInsert[$sellCode])) $dataToInsert[$sellCode] = $this->initData($sellCode);
                $dataToInsert[$sellCode]['sell_vol'] += $sellVol;
                $dataToInsert[$sellCode]['sell_val'] += $sellVal;
                $dataToInsert[$sellCode]['sell_avg'] = $sellAvg;
            }
        });

        if (empty($dataToInsert)) {
            $this->error("Tidak ada data valid yang diproses.");
            return;
        }

        // --- 3. PROSES SIMPAN KE DATABASE ---
        $count = count($dataToInsert);
        $this->info("3. Menyimpan $count data broker...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($dataToInsert as $code => $data) {
            BrokerSummary::updateOrCreate(
                [
                    'ticker' => $ticker,
                    'date' => $inputDate,
                    'broker_code' => $code
                ],
                [
                    'buy_vol'  => (int) $data['buy_vol'], 
                    'buy_val'  => (int) $data['buy_val'],
                    'buy_avg'  => (int) $data['buy_avg'], 
                    
                    'sell_vol' => (int) $data['sell_vol'],
                    'sell_val' => (int) $data['sell_val'],
                    'sell_avg' => (int) $data['sell_avg'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ SUKSES! Data untuk $ticker tanggal $inputDate tersimpan.");
    }

    /**
     * Helper untuk mengubah string angka format '10.5 M' menjadi integer murni
     */
    private function parseVolumeValue($text)
    {
        $text = trim($text);
        if (empty($text) || $text == '-') return 0;

        $suffix = strtoupper(substr($text, -1));
        // Hapus karakter selain angka, titik, dan koma
        $cleanStr = preg_replace('/[^0-9.,]/', '', $text);
        // Hapus koma (separator ribuan) agar bisa di-cast ke float
        $number = (float) str_replace(',', '', $cleanStr); 

        switch ($suffix) {
            case 'T': return $number * 1000000000000;
            case 'B': return $number * 1000000000;
            case 'M': return $number * 1000000;
            case 'K': return $number * 1000;
            default:  return $number;
        }
    }

    /**
     * Helper inisialisasi array data kosong
     */
    private function initData($code)
    {
        return [
            'broker_code' => $code, 
            'buy_vol' => 0, 'buy_val' => 0, 'buy_avg' => 0,
            'sell_vol' => 0, 'sell_val' => 0, 'sell_avg' => 0
        ];
    }
}