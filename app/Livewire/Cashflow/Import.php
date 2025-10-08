<?php

namespace App\Livewire\Cashflow;

use App\Models\FileImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

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
        $this->fileImports = FileImport::all();
    }

    public function updatedImport($import)
    {
        $this->validate([
            'import' => 'required', // max 10MB
        ]);

        $path = 'cashflow/'.date('Y').'/'.date('m');
        $filename = uniqid().'_'.$import->getClientOriginalName();

        try {
            // Store the file in S3
            $fullPath = $import->storeAs($path, $filename, 's3');
            $url = Storage::disk('s3')->url($fullPath);

            // Save file metadata to the database
            DB::beginTransaction();

            FileImport::create([
                'user_id' => 1,
                'filename' => $filename,
                'size' => $import->getSize(),
                'path' => $fullPath,
                'url' => $url,
            ]);

            DB::commit();
            $this->loadFile();

            // Emit a Livewire event for the frontend
            $this->alert('success', 'File successfully uploaded and saved!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        } catch (\Exception $e) {
            Storage::disk('s3')->delete($path.$filename);
            DB::rollBack();

            $this->alert('error', $e, [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }

    public function download($id)
    {
        $file = FileImport::findOrFail($id);

        $tempUrl = Storage::disk('s3')->temporaryUrl($file->path, now()->addMinutes(5));

        // Tentukan path sementara di server
        $tempFilePath = storage_path('app/temp/'.basename($file->path));

        // Download file dari S3
        file_put_contents($tempFilePath, file_get_contents($tempUrl));

        // Convert PDF ke gambar per halaman menggunakan Imagick
        $imagick = new \Imagick();
        $imagick->readImage($tempFilePath);
        $imagick->setResolution(300, 300);
        $imagick->setImageFormat('png');

        $transactions = [];
        $current_balance = null;

        foreach ($imagick as $index => $page) {
            // Simpan halaman sebagai gambar sementara
            $tempImagePath = storage_path("app/temp_page_{$index}.png");
            $page->writeImage($tempImagePath);

            // Ekstrak teks dari gambar menggunakan Tesseract
            $text = (new TesseractOCR($tempImagePath))
                ->lang('eng')
                ->run();

            // Hapus file sementara
            unlink($tempImagePath);

            // Pisahkan per baris
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                $line = preg_replace('/\s+/', ' ', trim($line));

                // Regex untuk menangkap transaksi
                if (preg_match('/^(\d{2} \w{3} \d{4}) (\d{2}\.\d{2}) (.+?) (ID# (\w+)) ([\d,\.]+) ([\d,\.]+)$/', $line, $matches)) {
                    $date = $matches[1];
                    $time = $matches[2];
                    $source_destination = trim($matches[3]);
                    $transaction_details = $matches[4];
                    $transaction_id = $matches[5];
                    $amount = floatval(str_replace(',', '', $matches[6]));
                    $balance = floatval(str_replace(',', '', $matches[7]));

                    // Hitung nilai transaksi
                    if ($current_balance !== null) {
                        $amount = $balance - $current_balance;
                    }
                    $current_balance = $balance;

                    // Tambahkan transaksi ke array
                    $transactions[] = [
                        'date' => $date,
                        'time' => $time,
                        'source_destination' => $source_destination,
                        'transaction_details' => $transaction_details,
                        'note' => '',
                        'amount' => $amount,
                        'balance' => $balance,
                        'transaction_id' => $transaction_id,
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $transactions,
        ]);

        // $this->getPdfText($file->path);

        // $url = Storage::disk('s3')->url($file->path);
        // return redirect()->to($url);
    }

    public function getPdfText($filename)
    {
        try {
            // Buat URL sementara (misal 5 menit)
            $tempUrl = Storage::disk('s3')->temporaryUrl($filename, now()->addMinutes(5));

            // Tentukan path sementara di server
            $tempFilePath = storage_path('app/temp/'.basename($filename));

            // Download file dari S3
            file_put_contents($tempFilePath, file_get_contents($tempUrl));

            // Ambil teks dari file PDF lokal
            $rawText = Pdf::getText($tempFilePath);
            // dd($rawText);

            // Hapus file sementara
            unlink($tempFilePath);

            // Proses teks untuk diubah menjadi format array
            $processedData = $this->processPdfText($rawText);

            return response()->json($processedData);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function processPdfText($rawText)
    {
        $lines = preg_split('/\r\n|\r|\n/', $rawText);
        $transactions = [];
        $currentTransaction = [];
        $isTransactionData = false;
        $headerFound = false;
        $counter = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            // Cari header tabel
            if (! $headerFound && strpos($line, 'Tanggal & Waktu') !== false) {
                $headerFound = true;

                continue;
            }

            // Mulai mengumpulkan data transaksi setelah header ditemukan
            if ($headerFound) {
                // Skip baris kosong atau informasi non-transaksi
                if ($line === '' ||
                    strpos($line, 'Halaman') !== false ||
                    strpos($line, 'Menampilkan transaksi') !== false ||
                    strpos($line, 'Saldo terbaru') !== false ||
                    strpos($line, 'Info Penting') !== false) {
                    continue;
                }

                // Deteksi tanggal transaksi (format: 25 Feb 2025)
                if (preg_match('/^\d{1,2} [A-Za-z]{3} \d{4}$/', $line)) {
                    // Jika ada transaksi sebelumnya, simpan
                    if (! empty($currentTransaction)) {
                        $transactions[] = $currentTransaction;
                        $currentTransaction = [];
                    }
                    $currentTransaction['tanggal'] = $line;
                }
                // Deteksi waktu (format: 09.30)
                elseif (preg_match('/^\d{2}\.\d{2}$/', $line) && isset($currentTransaction['tanggal'])) {
                    $currentTransaction['waktu'] = str_replace('.', ':', $line);
                }
                // Deteksi sumber/tujuan (baris pertama setelah tanggal/waktu)
                elseif (! isset($currentTransaction['sumber_tujuan']) &&
                        ! empty($line) &&
                        ! isset($currentTransaction['waktu']) &&
                        ! preg_match('/^ID#/', $line) &&
                        ! preg_match('/^[+-]?\d+\.\d{3}/', $line)) {
                    $currentTransaction['sumber_tujuan'] = $line;
                }
                // Deteksi deskripsi transaksi
                elseif (strpos($line, 'SHOPEE') !== false ||
                    strpos($line, 'Bibit') !== false ||
                    strpos($line, 'Pencairan Reksa Dana') !== false ||
                    strpos($line, 'PT Tokopedia') !== false) {
                    $currentTransaction['deskripsi'] = $line;
                }
                // Deteksi jumlah (nominal dengan + atau -)
                elseif (preg_match('/^[+-]\d+\.\d{3}/', $line) && ! isset($currentTransaction['jumlah'])) {
                    $currentTransaction['jumlah'] = str_replace('.', ',', $line);
                }
                // Deteksi saldo (nominal tanpa + atau -)
                elseif (preg_match('/^\d+\.\d{3}/', $line) && isset($currentTransaction['jumlah']) && ! isset($currentTransaction['saldo'])) {
                    $currentTransaction['saldo'] = str_replace('.', ',', $line);
                    $transactions[] = $currentTransaction;
                    $currentTransaction = [];
                }
            }
        }

        // Format output sesuai yang diminta
        $result = [
            ['No', 'Tanggal', 'Waktu', 'Sumber/Tujuan', 'Deskripsi', 'Jumlah', 'Saldo'],
        ];

        foreach ($transactions as $index => $transaction) {
            $result[] = [
                ($index + 1),
                $transaction['tanggal'] ?? '',
                $transaction['waktu'] ?? '',
                $transaction['sumber_tujuan'] ?? '',
                $transaction['deskripsi'] ?? '',
                $transaction['jumlah'] ?? '',
                $transaction['saldo'] ?? '',
            ];
        }

        return $result;
    }

    // public function extractText($filePath)
    // {
    //     try {
    //         // Set path untuk file temporary
    //         $tempFilePath = storage_path('app/tmp/' . basename($filePath));

    //         // Download file dari S3
    //         Storage::disk('s3')->get($filePath, function ($stream) use ($tempFilePath) {
    //             file_put_contents($tempFilePath, $stream);
    //         });

    //         // dd($tempFilePath);

    //         // **Cek apakah file benar-benar ada di path**
    //         if (!file_exists($tempFilePath)) {
    //             throw new \Exception("File not found at $tempFilePath");
    //         }

    //         // Ekstrak teks dari file lokal
    //         $text = Pdf::getText($tempFilePath);
    //         dd($text);

    //         // Hapus file setelah diproses untuk menghemat ruang
    //         // unlink($tempFilePath);

    //         return response()->json(['text' => $text]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }

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
