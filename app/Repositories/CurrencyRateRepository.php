<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 12:31
 */

namespace App\Repositories;

use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRateRepository extends BaseRepository
{

    public function __construct(CurrencyRate $currencyRate)
    {
        parent::__construct($currencyRate);
    }

    /**
     * Get a currency rates for specific bank and currency
     *
     * @param  string|null  $bankSlug
     * @param  string|null  $currencyCode
     * @return Collection
     */
    public function getCurrencyRates(?string $bankSlug, ?string $currencyCode): Collection
    {
        $lastCreatedAtDate = CurrencyRate::max('created_at');
        // Calculate the time one hour before the last created_at date
        $oneHourBeforeLastCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $lastCreatedAtDate)->subHour();

        $query = CurrencyRate::query()
            ->with(['currency' => function ($query) {
                $query->select('id', 'code', 'name');
            }])
            ->where('resource_type', CurrencyRate::RATE_MINFIN)
            ->whereBetween('created_at', [$oneHourBeforeLastCreatedAt, $lastCreatedAtDate]);

        if ($bankSlug) {
            $query->whereHas('bank', function ($query) use ($bankSlug) {
                $query->where('slug', $bankSlug);
            });
        }

        if ($currencyCode) {
            $query->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            });
        }

        return $query->get();
    }

    /**
     * Get a currency rates for specific bank and currency
     *
     */
    public function getNBUAndAverageExchangeRate(?string $currencyCode): array
    {
        $result = [];

        $lastCreatedAtDate = CurrencyRate::max('created_at');

        // If there are no records in the table, return an empty result
        if (!$lastCreatedAtDate) {
            return [
                'average' => null,
                'nbu'     => null
            ];
        }

        // Calculate the time one hour before the last created_at date
        $oneHourBeforeLastCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $lastCreatedAtDate)->subHour();

        $query = CurrencyRate::query()
            ->with(['currency' => function ($query) {
                $query->select('id', 'code', 'name');
            }])
            ->whereBetween('created_at', [$oneHourBeforeLastCreatedAt, $lastCreatedAtDate]);

        $baseQuery = clone $query;

        if ($currencyCode) {
            $query->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            });

            $baseQuery->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            });
        }

        $result['actual_rate_time'] = $lastCreatedAtDate;

        $result['average'] = $query->where('resource_type', CurrencyRate::RATE_MINFIN)
            ->groupBy('currency_id')
            ->select('currency_id', CurrencyRate::raw('AVG(rate) as average_rate'))
            ->get();

        $result['nbu'] = $baseQuery->where('resource_type', CurrencyRate::RATE_NBU)
            ->select('currency_id', CurrencyRate::raw('rate as average_rate'), 'created_at')
            ->get();

        return $result;
    }

    /**
     * Get a history of changes during a specified period
     *
     * @param  string|null  $currencyCode
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     * @return Collection|array
     */
    public function getHistoricalChanges(?string $currencyCode, ?string $startDate, ?string $endDate): Collection|array
    {
        $startDate = is_null($startDate) ? Carbon::now()->startOfDay() : Carbon::createFromFormat('Y-m-d H:i:s',
            $startDate)->startOfDay();
        $endDate   = is_null($endDate) ? Carbon::now()->endOfDay() : Carbon::createFromFormat('Y-m-d H:i:s',
            $endDate)->endOfDay();

        $query = CurrencyRate::query()
            ->with([
                'currency' => function ($query) {
                    $query->select('id', 'code', 'name');
                }
            ])->where('resource_type', CurrencyRate::RATE_NBU)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            });

        return $query->get();
    }
}
