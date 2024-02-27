<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BankingAPIService
{
    protected string $exchangeRatesUrl;
    protected string $exchangeRatesNbuUrl;
    protected string $banksListUrl;
    protected array $allowedBanks;
    protected string $banksBranchesUrl;

    public function __construct()
    {
        $this->exchangeRatesUrl    = config('banking.exchange_rates_url');
        $this->exchangeRatesNbuUrl = config('banking.exchange_rates_nbu_url');
        $this->banksListUrl        = config('banking.banks_list_url');
        $this->banksBranchesUrl    = config('banking.banks_branches_url');
        $this->allowedBanks        = config('banking.allowed_banks');
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

    public function getBanksInfo(): ?array
    {
        $response = $this->getHttpRequest($this->banksListUrl);

        return $this->filterBanks($response);
    }

    private function filterBanks($responseData): array
    {
        $result = [];

        foreach ($responseData['responseData'] as $item) {
            if (in_array($item['slug'], $this->allowedBanks)) {
                $result[$item['slug']] = $item;
            }
        }

        return $result;
    }

    public function getBankBranches(): array
    {
        $result = [];

        foreach ($this->allowedBanks as $bank) {
            $result[$bank] = $this->getHttpRequest($this->banksBranchesUrl . $bank);
        }

        return $result;
    }
}
