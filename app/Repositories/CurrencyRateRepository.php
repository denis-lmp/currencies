<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 12:31
 */

namespace App\Repositories;

use App\Models\CurrencyRate;

class CurrencyRateRepository extends BaseRepository
{
    public function getRatesByBankSlug($bankSlug)
    {
        return CurrencyRate::whereHas('bank', function ($query) use ($bankSlug) {
            $query->where('slug', $bankSlug);
        })->get();
    }
}
