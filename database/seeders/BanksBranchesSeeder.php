<?php

namespace Database\Seeders;

use App\Models\BankBranch;
use App\Repositories\BankRepository;
use App\Services\BankingAPIService;
use Illuminate\Database\Seeder;

class BanksBranchesSeeder extends Seeder
{
    protected BankingAPIService $bankingAPIService;
    protected BankRepository $bankRepository;

    public function __construct(BankingAPIService $bankingAPIService, BankRepository $bankRepository)
    {
        $this->bankingAPIService = $bankingAPIService;
        $this->bankRepository    = $bankRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedBanksBranches();
    }

    private function seedBanksBranches(): void
    {
        $banksBranchesInfo = $this->bankingAPIService->getBankBranches();


        foreach ($banksBranchesInfo as $slug => $bankBranches) {
            $bankId = $this->bankRepository->getBankIdBySlug($slug);

            if (isset($bankBranches['data'])) {
                foreach ($bankBranches['data'] as $branchCity) {
                    if ($branchCity['data']) {
                        foreach ($branchCity['data'] as $branch) {
                            BankBranch::create([
                                'bank_id'      => $bankId,
                                'name'         => $branch['branch_name'] ?? '',
                                'address'      => $branch['address'] ?? '',
                                'coordinates'  => $branch['lat'] && $branch['lng'] ? $branch['lat'].', '.$branch['lng'] : '',
                                'phone_number' => $branch['phone'] ?? ''
                            ]);
                        }
                    }
                }
            }
        }
    }
}
