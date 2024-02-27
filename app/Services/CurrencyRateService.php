<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyRateService
{
    protected string $exchangeRatesUrl;
    protected string $exchangeRatesNbuUrl;

    public function __construct()
    {
        $this->exchangeRatesUrl    = config('banking.exchange_rates_url');
        $this->exchangeRatesNbuUrl = config('banking.exchange_rates_nbu_url');
    }

    public function getCurrencyRates($currencyCode): ?array
    {
        return $this->getHttpRequest($this->exchangeRatesUrl.$currencyCode);
    }

    /**
     * @param  string  $url
     * @return array|null
     */
    public function getHttpRequest(string $url): ?array
    {
        $response = Http::get($url);

        return $this->handleResponse($response);
    }

    private function handleResponse($response)
    {
        return $response->successful() ? $response->json() : null;
    }

    public function getCurrencyRatesNbu(): ?array
    {
        return $this->getHttpRequest($this->exchangeRatesNbuUrl);
    }
}
