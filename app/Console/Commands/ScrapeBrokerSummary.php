<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BrokerSummary;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class ScrapeBrokerSummary extends Command
{
    // Tambahkan opsi --force
    protected $signature = 'scrape:broker-sum {ticker} {--date= : Format YYYY-MM-DD} {--force : Paksa scrape ulang meski data sudah ada}';
    protected $description = 'Scrape IPOT (With Avg Price & Duplicate Check)';

    public function handle()
    {
        $ticker = strtoupper($this->argument('ticker'));
        $inputDate = $this->option('date') ?? Carbon::today()->format('Y-m-d');
        $ipotDate = Carbon::parse($inputDate)->format('m/d/Y'); 
        $force = $this->option('force');

        // --- 1. CEK DUPLIKAT DATA ---
        // Cek apakah sudah ada setidaknya 1 baris data untuk Ticker & Tanggal ini
        $exists = BrokerSummary::where('ticker', $ticker)
                               ->where('date', $inputDate)
                               ->exists();

        if ($exists && !$force) {
            $this->warn("⚠️  SKIP: Data untuk $ticker tanggal $inputDate sudah ada di database.");
            $this->line("Gunakan flag --force jika ingin menimpa data yang ada.");
            return; // Berhenti di sini, tidak lanjut buka browser
        }

        if ($force) {
            $this->warn("🔥 FORCE MODE: Data lama akan di-update.");
        }

        $baseUrl = "https://www.indopremier.com/ipotnews/newsSmartSearch.php?code={$ticker}";
        
        // URL API Internal
        $targetApiUrl = "/module/saham/include/data-brokersummary.php?code={$ticker}&start={$ipotDate}&end={$ipotDate}&fd=all&board=all";

        $this->info("1. Fetching data for $ticker ($ipotDate)...");

        try {
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

            $responseHtml = Browsershot::url($baseUrl)
                ->setNodeBinary(trim(shell_exec('which node') ?? '/usr/local/bin/node'))
                ->npmBinary(trim(shell_exec('which npm') ?? '/usr/local/bin/npm'))
                ->windowSize(1920, 1080)
                ->userAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36')
                ->dismissDialogs()
                ->waitUntilNetworkIdle()
                ->evaluate($jsFetch);

        } catch (\Exception $e) {
            $this->error("Browser Error: " . $e->getMessage());
            return;
        }

        if (str_starts_with($responseHtml, 'ERROR:')) {
            $this->error("Fetch Failed: " . $responseHtml);
            return;
        }

        $this->info("2. Parsing Data...");

        $crawler = new Crawler($responseHtml);
        $dataToInsert = [];
        
        $rows = $crawler->filter('tr');

        if ($rows->count() == 0) {
            $this->error("Data kosong/Libur.");
            return;
        }

        $rows->each(function (Crawler $row) use (&$dataToInsert) {
            $cols = $row->filter('td');
            
            if ($cols->count() < 8) return;

            // --- BUYER (Kiri) ---
            $buyCode = trim($cols->eq(0)->text());
            if (preg_match('/^[A-Z0-9]{2}$/', $buyCode)) { 
                $buyVol = $this->parseVolumeValue($cols->eq(1)->text());
                $buyVal = $this->parseVolumeValue($cols->eq(2)->text());
                $buyAvg = $this->parseVolumeValue($cols->eq(3)->text());
                
                if (!isset($dataToInsert[$buyCode])) $dataToInsert[$buyCode] = $this->initData($buyCode);
                $dataToInsert[$buyCode]['buy_vol'] += $buyVol;
                $dataToInsert[$buyCode]['buy_val'] += $buyVal;
                $dataToInsert[$buyCode]['buy_avg'] = $buyAvg; 
            }

            // --- SELLER (Kanan) ---
            $sellCode = trim($cols->eq(5)->text());
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
            $this->error("Tidak ada data yang diproses.");
            return;
        }

        // Simpan
        $count = count($dataToInsert);
        $this->info("3. Menyimpan $count data...");
        
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
        $this->info("✅ SUKSES!");
    }

    private function parseVolumeValue($text)
    {
        $text = trim($text);
        if (empty($text) || $text == '-') return 0;

        $suffix = strtoupper(substr($text, -1));
        $cleanStr = preg_replace('/[^0-9.,]/', '', $text);
        $number = (float) str_replace(',', '', $cleanStr); 

        switch ($suffix) {
            case 'T': return $number * 1000000000000;
            case 'B': return $number * 1000000000;
            case 'M': return $number * 1000000;
            case 'K': return $number * 1000;
            default:  return $number;
        }
    }

    private function initData($code)
    {
        return [
            'broker_code' => $code, 
            'buy_vol' => 0, 'buy_val' => 0, 'buy_avg' => 0,
            'sell_vol' => 0, 'sell_val' => 0, 'sell_avg' => 0
        ];
    }
}