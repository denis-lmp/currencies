<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Repositories\BankRepository;
use App\Services\BankingAPIService;
use Illuminate\Database\Seeder;

class BanksSeeder extends Seeder
{

    protected BankingAPIService $bankingAPIService;

    public function __construct(BankingAPIService $bankingAPIService)
    {
        $this->bankingAPIService = $bankingAPIService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedBanks();
    }

    private function seedBanks(): void
    {
        $banksInfo = $this->bankingAPIService->getBanksInfo();

        foreach ($banksInfo as $slug => $bank) {
            Bank::create([
                'name'          => $bank['title'] ?? '',
                'slug'          => $slug,
                'description'   => $bank['title'] ?? '',
                'logo'          => is_array($bank['logo']) ? $bank['logo'][0] : $bank['logo'],
                'website'       => $bank['site'] ?? '',
                'phone_number'  => $bank['phone'] ?? '',
                'email'         => $bank['email'] ?? '',
                'legal_address' => $bank['legalAddress'] ?? '',
                'rating'        => $bank['ratingBank'] ?? '',
            ]);
        }
    }
}
