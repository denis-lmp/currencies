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
        return Bank::with(['currencyRates.currency', 'branches'])->where('slug', $slug)->first();
    }

    /**
     * Get 10 closest bank branches
     * @param $userLatitude
     * @param $userLongitude
     * @return Collection
     */
    public function findClosestBranchesByCoordinates($userLatitude, $userLongitude): Collection
    {
        // Retrieve all bank branches
        $branches = BankBranch::all();

        // Calculate distances between the user's location and each bank branch
        foreach ($branches as $branch) {
            list($branchLatitude, $branchLongitude) = explode(',', $branch->coordinates);
            $branch->distance_to_branch = $this->calculateDistance($userLatitude, $userLongitude, $branchLatitude,
                $branchLongitude);
        }

        // Sort bank branches by distance
        // Return the top 10 closest bank branches
        return $branches->sortBy('distance_to_branch')->take(10);

    }

    /**
     * Calculate distances between user coordinates and branches
     *
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return float
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $theta = $lon1 - $lon2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1.609344); // Convert miles to kilometers
    }
}
