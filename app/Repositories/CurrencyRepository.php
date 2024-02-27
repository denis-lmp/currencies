<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 11:01
 */

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    public function __construct(Currency $currency)
    {
        parent::__construct($currency);
    }

}
