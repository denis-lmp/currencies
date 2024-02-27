<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 11:33
 */

namespace App\Repositories;

use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BankRepository extends BaseRepository
{
    public function __construct(Bank $bank)
    {
        parent::__construct($bank);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getBankIdBySlug($slug): mixed
    {
        return Bank::where('slug', $slug)->value('id');
    }

    /**
     * Get bank info with currency rates and branches
     *
     * @param $slug
     * @return Model|null
     */
    public function findBySlugWithCurrenciesAndBranches($slug): Model|null
    {
        $lastCreatedAtDate = CurrencyRate::max('created_at');
        // Calculate the time one hour before the last created_at date
        $oneHourBeforeLastCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $lastCreatedAtDate)->subHour();

        return Bank::with(['currencyRates.currency', 'branches'])
            ->whereHas('currencyRates', function ($query) use ($oneHourBeforeLastCreatedAt, $lastCreatedAtDate){
                $query->whereBetween('created_at', [$oneHourBeforeLastCreatedAt, $lastCreatedAtDate]);
            })
            ->where('slug', $slug)->first();
    }

}
