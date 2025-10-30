<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\InvestmentCode;
use App\Models\MarketPrice;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class FetchMarketPrices extends Command
{
    protected $signature = 'app:fetch-market-prices';
    protected $description = 'Fetch current market prices from various APIs';

    public function handle()
    {
        $this->info('Fetching market prices...');
        $this->line('Fetching USD to IDR exchange rate...');
        $usdToIdrRate = $this->fetchUsdToIdrRate();

        if ($usdToIdrRate === null) {
            $this->error('Gagal mendapatkan kurs USD/IDR. Konversi USD akan dilewati!');
        } else {
            $this->info(" -> Current USD/IDR Rate: {$usdToIdrRate}");
        }
        
        $investmentCodes = InvestmentCode::all();

        foreach ($investmentCodes as $code) {
            $price = null;
            $this->line("Processing: {$code->name} ({$code->source})");

            try {
                switch ($code->source) {
                    case 'coingecko':
                        $price = $this->fetchCoinGeckoPrice($code->investment_code);
                        break;
                    case 'google_scrape':
                        $price = $this->scrapeGoogleFinancePrice($code->investment_code);
                        break;
                    case 'apised':
                        $price = $this->fetchApisedPrice($code->investment_code);
                        break;
                    default:
                        $this->warn("No fetcher defined for code: {$code->name} ({$code->source})");
                }

                if ($price !== null) {
                    $finalPrice = $price;
                    if (strtolower($code->currency) === 'usd' && $usdToIdrRate !== null) {
                        $finalPrice = $price * $usdToIdrRate;
                        $this->info(" -> Converted USD to IDR (Rate: {$usdToIdrRate}): {$price} -> {$finalPrice}");
                    } else if (strtolower($code->currency) === 'usd' && $usdToIdrRate === null) {
                        $this->warn(" -> Scraped price is USD, but failed to get IDR rate. Saving USD value as is.");
                    } else {
                        $this->info(" -> Price is already IDR.");
                    }

                    MarketPrice::updateOrCreate(
                        [
                            'investment_code_id' => $code->id,
                            'current_price' => $finalPrice,
                            'last_update' => now()
                        ]
                    );
                    $this->info(" -> Success. Saved Price: {$finalPrice}");
                } else {
                    $this->error(" -> Failed to fetch price.");
                }

            } catch (Exception $e) {
                Log::error("Failed to fetch price for {$code->name}: " . $e->getMessage());
                $this->error(" -> Error: " . $e->getMessage());
            }
        }

        $this->info('All prices fetched.');
        return 0;
    }

    private function fetchUsdToIdrRate()
    {
        try {
            $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'usd',
                'vs_currencies' => 'idr',
            ]);

            if ($response->successful() && isset($response->json()['usd']['idr'])) {
                return (float) $response->json()['usd']['idr'];
            }
            
            Log::error('Gagal mengambil kurs USD ke IDR dari CoinGecko.');
            return null;
        } catch (Exception $e) {
            Log::error('Error saat fetchUsdToIdrRate: ' . $e->getMessage());
            return null;
        }
    }

    private function fetchCoinGeckoPrice(string $id)
    {
        $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
            'ids' => $id,
            'vs_currencies' => 'idr',
        ]);

        if ($response->successful() && isset($response->json()[$id]['idr'])) {
            return $response->json()[$id]['idr'];
        }
        return null;
    }

    private function scrapeGoogleFinancePrice(string $symbol)
    {
        try {
            $url = "https://www.google.com/finance/quote/" . $symbol;

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9'
            ])->get($url);

            if (!$response->successful()) {
                $this->error(" -> Failed to fetch Google page, status: " . $response->status());
                return null;
            }

            $htmlContent = $response->body();
            $crawler = new Crawler($htmlContent);

            $isConversion = strpos($symbol, '?c=') !== false;
            
            if ($isConversion) {
                $selector = '.P6K39c'; 
            } else {
                $selector = '.YMlKec.fxKbKc';
            }

            $node = $crawler->filter($selector)->first();

            if ($node->count() > 0) {
                $priceText = $node->text(); 
                $priceText = preg_replace('/[^0-9,.]/', '', $priceText); 

                $last_comma_pos = strrpos($priceText, ',');
                $last_period_pos = strrpos($priceText, '.');

                if ($last_comma_pos !== false && $last_period_pos !== false) {
                    if ($last_comma_pos > $last_period_pos) {
                        $priceText = str_replace('.', '', $priceText);
                        $priceText = str_replace(',', '.', $priceText);
                    } else {
                        $priceText = str_replace(',', '', $priceText);
                    }
                } elseif ($last_period_pos !== false && $last_comma_pos === false) {
                    $dot_count = substr_count($priceText, '.');
                    if ($dot_count > 1) {
                        $priceText = str_replace('.', '', $priceText);
                    } elseif ($dot_count == 1) {
                        $decimals = strlen(substr($priceText, $last_period_pos + 1));
                        if ($decimals == 3) {
                            $priceText = str_replace('.', '', $priceText);
                        }
                    }
                } elseif ($last_comma_pos !== false && $last_period_pos === false) {
                    $comma_count = substr_count($priceText, ',');
                    if ($comma_count > 1) {
                        $priceText = str_replace(',', '', $priceText);
                    } elseif ($comma_count == 1) {
                        $decimals = strlen(substr($priceText, $last_comma_pos + 1));
                        if ($decimals < 3) {
                            $priceText = str_replace(',', '.', $priceText);
                        } else {
                             $priceText = str_replace(',', '', $priceText);
                        }
                    }
                }
                
                return (float) $priceText;
            }

            $this->warn(" -> Google scraping selector '{$selector}' not found for {$symbol}. Halaman/class mungkin berubah.");
            return null;

        } catch (Exception $e) {
            $this->error(" -> Google Scraping failed for {$symbol}: " . $e->getMessage());
            return null;
        }
    }

    private function fetchApisedPrice(string $identifier)
    {
        try {
            $apiKey = env('APISED_API_KEY'); 
            if (!$apiKey) {
                throw new Exception('APISED_API_KEY not set in .env file.');
            }

            $response = Http::withHeaders([
                'x-api-key' => $apiKey
            ])->get('https://gold.g.apised.com/v1/latest', [
                'metals' => $identifier,
                'base_currency' => 'IDR',
                'currencies' => 'IDR',
                'weight_unit' => 'gram'
            ]);

            if (!$response->successful()) {
                $this->error(" -> Failed to fetch Apised API, status: " . $response->status());
                Log::error("Apised API Error: " . $response->body()); 
                return null;
            }

            $data = $response->json();
            $metalKey = strtoupper($identifier);

            if (isset($data['data']['metal_prices'][$metalKey]['price'])) {
                return (float) $data['data']['metal_prices'][$metalKey]['price'];
            }

            $this->warn(" -> Apised API: Struktur JSON tidak dikenal atau 'data[metal_prices][{$metalKey}][price]' tidak ditemukan.");
            Log::info("Apised JSON Response: " . $response->body());
            return null;

        } catch (Exception $e) {
            $this->error(" -> Apised API failed: " . $e->getMessage());
            return null;
        }
    }
}