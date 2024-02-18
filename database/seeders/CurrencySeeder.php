<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define currency data
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'GBP', 'name' => 'British Pound'],
            ['code' => 'CHF', 'name' => 'Swiss Franc'],
            ['code' => 'PLN', 'name' => 'Polish Zloty'],
        ];

        // Create currency records
        foreach ($currencies as $currencyData) {
            Currency::create($currencyData);
        }
    }
}
