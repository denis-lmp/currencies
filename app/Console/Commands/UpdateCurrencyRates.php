<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use App\Models\CurrencyRatesChange;
use App\Repositories\BankRepository;
use App\Repositories\CurrencyRateRepository;
use App\Repositories\CurrencyRepository;
use App\Services\CurrencyRateService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;


class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency rates.';

    protected CurrencyRepository $currencyRepository;
    protected CurrencyRateRepository $currencyRateRepository;
    protected BankRepository $bankRepository;
    protected CurrencyRateService $currencyRateService;

    public function __construct(
        CurrencyRepository $currencyRepository,
        BankRepository $bankRepository,
        CurrencyRateService $currencyRateService,
        CurrencyRateRepository $currencyRateRepository
    ) {
        parent::__construct();
        $this->currencyRepository  = $currencyRepository;
        $this->bankRepository      = $bankRepository;
        $this->currencyRateService = $currencyRateService;
        $this->currencyRateRepository = $currencyRateRepository;
    }

    public function handle(): void
    {
        // Get all currencies in the system
        $currencies = $this->currencyRepository->all();

        // Update currency rates from NBU
        $this->updateCurrencyRatesNbu($currencies);

        // Update currency rates from banks
        $this->updateCurrencyRatesFromBanks($currencies);

        // Check for significant changes in currency rates
        $this->checkCurrencyRateChanges();

        $this->info('Currency rates updated successfully.');
    }

    protected function updateCurrencyRatesNbu(Collection $currencies): void
    {
        $ratesNbu = $this->currencyRateService->getCurrencyRatesNbu();

        foreach ($ratesNbu as $item) {
            $currency = $currencies->firstWhere('code', $item['cc']);

            if ($currency) {
                CurrencyRate::create([
                    'bank_id'       => null,
                    'currency_id'   => $currency->id,
                    'rate'          => $item['rate'],
                    'resource_type' => CurrencyRate::RATE_NBU
                ]);
            }
        }

        $this->info('NBU currency rates updated successfully.');
    }

    protected function updateCurrencyRatesFromBanks(Collection $currencies): void
    {
        $banks = $this->bankRepository->all();

        foreach ($currencies as $currency) {
            $currencyId   = $currency->id;
            $currencyCode = $currency->code;
            $rates        = $this->currencyRateService->getCurrencyRates($currencyCode);

            foreach ($rates['data'] as $currencyRaw) {
                $bank = $banks->firstWhere('slug', $currencyRaw['slug']);

                if ($bank && isset($currencyRaw['cash']['bid'])) {
                    CurrencyRate::create([
                        'bank_id'       => $bank->id,
                        'currency_id'   => $currencyId,
                        'rate'          => $currencyRaw['cash']['bid'],
                        'resource_type' => CurrencyRate::RATE_MINFIN
                    ]);
                }
            }
        }

        $this->info('MINFIN currency rates updated successfully.');
    }

    protected function checkCurrencyRateChanges(): void
    {
        // Get all currency rates
        $currencyRates = $this->currencyRateRepository->all();

        // Iterate through each currency rate
        foreach ($currencyRates as $currencyRate) {
            // Get previous rate for the same currency
            $previousRate = CurrencyRate::where('currency_id', $currencyRate->currency_id)
                ->where('bank_id', $currencyRate->bank_id)
                ->where('resource_type', $currencyRate->resource_type)
                ->where('created_at', '<', $currencyRate->created_at)
                ->orderBy('created_at', 'desc')
                ->first();

            // If previous rate exists, calculate percentage change
            if ($previousRate) {
                $percentageChange = abs(($currencyRate->rate - $previousRate->rate) / $previousRate->rate * 100);

                // Save the significant change to currency_rates_changes table if change exceeds 5%
                if ($percentageChange >= 5) {
                    CurrencyRatesChange::create([
                        'currency_id'   => $currencyRate->currency_id,
                        'bank_id'       => $currencyRate->bank_id,
                        'resource_type' => $currencyRate->resource_type,
                        'previous_rate' => $previousRate->rate,
                        'current_rate'  => $currencyRate->rate,
                        'change_percent' => $percentageChange,
                        'change_date'   => $currencyRate->created_at,
                    ]);
                }
            }
        }

        $this->info('Currency rate changes checked successfully.');
    }
}
