<?php

namespace App\Livewire\Cashflow;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use App\Models\FileImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;

class GeminiQuotaException extends \Exception {}

class Import extends Component
{
    use LivewireAlert, WithFileUploads;

    public $fileImports;

    public $import;

    public function mount()
    {
        $this->loadFile();
    }

    public function loadFile()
    {
        $this->fileImports = FileImport::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updatedImport($import)
    {
        $this->validate([
            'import' => 'required|mimes:pdf|max:10240',
        ]);

        $originalName = $import->getClientOriginalName();
        Log::info("--- START IMPORT PROCESS: $originalName ---");

        $path = 'cashflow/' . date('Y/m');
        $filename = uniqid() . '_' . $originalName;

        DB::beginTransaction();

        try {
            Log::info("Uploading file to S3...", ['path' => $path . '/' . $filename]);
            $fullPath = $import->storeAs($path, $filename, 's3');
            
            $fileContent = $import->get();
            Log::info("File size for extraction: " . strlen($fileContent) . " bytes");

            Log::info("Starting Gemini AI Extraction...");
            $transactions = $this->extractWithGemini($fileContent);
            Log::info("Extraction successful. Found " . count($transactions) . " potential transactions.");

            if (empty($transactions)) {
                Log::warning("No transactions detected by Gemini for file: $originalName");
                throw new \Exception('Tidak ada transaksi terdeteksi.');
            }

            Log::info("Creating FileImport record in database.");
            $fileRecord = FileImport::create([
                'user_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $import->getSize(),
                'path' => $fullPath,
                'url' => Storage::disk('s3')->url($fullPath),
            ]);

            Log::info("Saving individual transactions to Cashflows table...");
            $this->saveCashflows($transactions);

            DB::commit();
            Log::info("--- IMPORT SUCCESSFUL: $originalName ---");

            $this->alert('success', 'Import & ekstraksi cashflow berhasil 🎉');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("--- IMPORT FAILED: $originalName ---");
            Log::error("Error Message: " . $e->getMessage());
            Log::error("Stack Trace: " . $e->getTraceAsString());

            if (isset($fullPath)) {
                Log::info("Rolling back S3: Deleting uploaded file.");
                Storage::disk('s3')->delete($fullPath);
            }

            $this->alert('error', 'Gagal import: ' . $e->getMessage());
        }

        $this->loadFile();
    }

    private function extractWithGemini(string $fileContent): array
    {
        $models = [
            'gemini-2.0-flash',
            'gemini-1.5-flash', // fallback
        ];

        $maxRetries = 3;

        foreach ($models as $model) {
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

                try {
                    Log::info("Gemini attempt {$attempt} using model {$model}");
                    return $this->callGeminiModel($model, $fileContent);

                } catch (GeminiQuotaException $e) {

                    Log::warning("Gemini quota hit (model: {$model}, attempt: {$attempt})");
                    sleep($attempt * 5); // exponential backoff

                    if ($attempt === $maxRetries) {
                        break; // lanjut ke model berikutnya
                    }
                }
            }
        }

        throw new \Exception('Quota AI sedang penuh. Silakan coba beberapa menit lagi.');
    }

    private function callGeminiModel(string $model, string $fileContent): array
    {
        $apiKey = config('services.gemini.key');
        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::timeout(60)->post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' =>
"Anda adalah parser laporan transaksi bank Indonesia.

PDF ini berisi TABEL riwayat transaksi dengan kolom:
- Tanggal & Waktu
- Sumber/Tujuan
- Rincian Transaksi
- Catatan (opsional)
- Jumlah

TUGAS ANDA:
- Setiap BARIS transaksi = 1 objek JSON
- Gabungkan SEMUA informasi penting

ATURAN PENGISIAN FIELD:
- counterparty:
  Gabungkan isi kolom **Sumber/Tujuan**
  termasuk nama, e-wallet, nomor, kode akun jika ada

- description:
  Gabungkan:
  - Rincian Transaksi
  - Catatan (jika ada)
  - ID# transaksi

FORMAT OUTPUT (JSON ARRAY SAJA):
[
  {
    \"date\": \"YYYY-MM-DD HH:mm\",
    \"counterparty\": \"Sumber/Tujuan lengkap\",
    \"description\": \"Rincian + Catatan + ID#\",
    \"amount\": -500000
  }
]

ATURAN WAJIB:
- Dana masuk → amount positif
- Dana keluar → amount negatif
- Jangan beri penjelasan apa pun
- Jangan gunakan markdown"
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => 'application/pdf',
                                'data' => base64_encode($fileContent),
                            ],
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.1,
            ],
        ]);

        Log::info("json", $response->json());

        if ($response->status() === 429) {
            throw new GeminiQuotaException('Gemini quota exceeded');
        }

        if (!$response->successful()) {
            $msg = $response->json()['error']['message'] ?? 'Gemini API error';
            throw new \Exception($msg);
        }

        $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';

        $cleanJson = trim(preg_replace('/```json|```/', '', $text));
        $data = json_decode($cleanJson, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new \Exception('JSON hasil Gemini tidak valid');
        }

        return $data;
    }



    private function saveCashflows(array $transactions): void
    {
        $successCount = 0;
        foreach ($transactions as $index => $trx) {

            if (
                empty($trx['date']) ||
                empty($trx['description']) ||
                empty($trx['counterparty']) ||
                !isset($trx['amount'])
            ) {
                Log::warning("Skipping malformed transaction at index $index", $trx);
                continue;
            }

            $amount = (int) $trx['amount'];
            $isIncome = $amount > 0;

            $text = strtolower(
                $trx['description'] . ' ' . $trx['counterparty']
            );

            if ($this->isInvestment($text) || $this->isPindahKantong($text)) {
                continue;
            }

            if ($isIncome) {
                $category = CashflowCategory::SALARY;
            } else {
                $category = $this->detectExpenseCategory($text);
            }

            Log::debug("Processing Trx #$index", [
                'date' => $trx['date'],
                'amount' => $amount,
                'category_detected' => $category,
                'is_income' => $isIncome
            ]);
            Cashflow::create([
                'user_id' => Auth::id(),
                'cashflow_category_id' => $category,
                'transaction_date' => Carbon::parse($trx['date']),
                'description' => $trx['description'],

                'source_account' => $isIncome
                    ? $trx['counterparty']
                    : 'Bank Jago',

                'destination_account' => $isIncome
                    ? 'Bank Jago'
                    : $trx['counterparty'],

                'amount' => $amount,
            ]);

            $successCount++;
        }
        Log::info("Database insertion finished. Saved $successCount transactions.");
    }


    private function isInvestment(string $text): bool
    {
        return str_contains($text, 'bibit')
            || str_contains($text, 'reksa')
            || str_contains($text, 'reksadana')
            || str_contains($text, 'stockbit')
            || str_contains($text, 'invest');
    }

    private function isPindahKantong(string $text): bool
    {
        return str_contains($text, 'pindah uang antar kantong');
    }


    private function detectExpenseCategory(string $text): ?int
    {
        return match (true) {

            str_contains($text, 'gofood')
            || str_contains($text, 'gopay')
            || str_contains($text, 'grab')
                => CashflowCategory::FOOD,

            str_contains($text, 'alfamart')
            || str_contains($text, 'indomaret')
            || str_contains($text, 'uang bulanan')
                => CashflowCategory::GROCERIES,

            str_contains($text, 'shopee')
            || str_contains($text, 'tokopedia')
            || str_contains($text, 'dnid')
                => CashflowCategory::ITEMS,

            str_contains($text, 'netflix')
            || str_contains($text, 'spotify')
                => CashflowCategory::ENTERTAINMENT,

            default => CashflowCategory::OTHERS,
        };
    }

    public function download($id)
    {
        $file = FileImport::findOrFail($id);

        $url = Storage::disk('s3')->url($file->path);
        return redirect()->to($url);
    }

    public function delete($id)
    {
        $file = FileImport::find($id);

        if (! $file) {
            $this->alert('error', 'File not found!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

            return;
        }

        try {
            // Hapus file dari S3
            Storage::disk('s3')->delete($file->path);

            // Hapus record di database
            $file->delete();

            // Refresh daftar
            $this->loadFile();

            // Alert sukses
            $this->alert('success', 'File successfully deleted!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to delete file: '.$e->getMessage(), [
                'position' => 'top-end',
                'timer' => 4000,
                'toast' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.cashflow.import');
    }
}
