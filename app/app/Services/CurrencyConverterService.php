<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // For logging errors

class CurrencyConverterService
{
    protected $baseUrl = 'https://api.frankfurter.app';

    public function convert(float $amount, string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return $amount;
        }

        if ($amount == 0) {
            return 0;
        }

        $cacheKey = "currency_rate_{$from}_{$to}";

        $rate = Cache::remember($cacheKey, now()->addHours(6), function () use ($from, $to) {
            try {
                $response = Http::get("{$this->baseUrl}/latest", [
                    'from' => $from,
                    'to' => $to,
                ]);

                if ($response->failed() || !isset($response->json()['rates'][$to])) {
                    Log::error("Failed to get currency conversion rate from {$from} to {$to}. Response: " . $response->body());
                    return 1;
                }
                
                return $response->json()['rates'][$to];

            } catch (\Exception $e) {
                Log::error("Exception in CurrencyConverterService: " . $e->getMessage());
                return 1;
            }
        });

        return $amount * $rate;
    }

    /**
     * Get all supported currency codes from the API.
     * The result is cached for 24 hours.
     *
     * @return array
     */
    public function getSupportedCurrencies(): array
    {
        return Cache::remember('supported_currencies', now()->addDay(), function () {
            try {
                $response = Http::get("{$this->baseUrl}/currencies");

                if ($response->failed()) {
                    Log::error("Failed to fetch supported currencies. Response: " . $response->body());
                    return ['USD', 'EUR', 'GBP', 'JPY'];
                }

                return array_keys($response->json());

            } catch (\Exception $e) {
                Log::error("Exception in getSupportedCurrencies: " . $e->getMessage());
                return ['USD', 'EUR', 'GBP', 'JPY'];
            }
        });
    }
}