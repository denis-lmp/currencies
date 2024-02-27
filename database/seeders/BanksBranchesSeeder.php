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
        $banksBranchesInfo = $this->bankingAPIService->getBankBranches();

        foreach ($banksBranchesInfo as $slug => $bankBranches) {
            $bankId = $this->bankRepository->getBankIdBySlug($slug);

            if (!isset($bankBranches['data'])) {
                continue;
            }

            foreach ($bankBranches['data'] as $branchCity) {
                $this->seedBranchesForCity($bankId, $branchCity);
            }
        }
    }

    private function seedBranchesForCity(int $bankId, array $branchCity): void
    {
        if (empty($branchCity['data'])) {
            return;
        }

        foreach ($branchCity['data'] as $branch) {
            $this->createBankBranch($bankId, $branch);
        }
    }

    private function createBankBranch(int $bankId, array $branch): void
    {
        BankBranch::create([
            'bank_id'      => $bankId,
            'name'         => $branch['branch_name'] ?? '',
            'address'      => $branch['address'] ?? '',
            'coordinates'  => $this->formatCoordinates($branch),
            'phone_number' => $branch['phone'] ?? ''
        ]);
    }

    private function formatCoordinates(array $branch): string
    {
        if (!isset($branch['lat'], $branch['lng'])) {
            return '';
        }

        return $branch['lat'] . ', ' . $branch['lng'];
    }
}
