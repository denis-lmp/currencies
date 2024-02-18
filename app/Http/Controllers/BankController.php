<?php

namespace App\Http\Controllers;

use App\Repositories\BankRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected BankRepository $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->bankRepository->all();

        $headers = ['Content-Type' => 'application/json; charset=utf-8'];

        return response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
    }

    public function showBySlug($slug): JsonResponse
    {
        $data = $this->bankRepository->findBySlugWithCurrenciesAndBranches($slug);


        if (!$data) {
            return response()->json(['message' => 'Bank not found'], 404);
        }

        $headers = ['Content-Type' => 'application/json; charset=utf-8'];

        return response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param  Request  $request
     * Should be provided params in the request:
     * latitude
     * longitude
     * @return JsonResponse
     */
    public function getClosestBranches(Request $request): JsonResponse
    {
        // Get user's location (assuming latitude and longitude are sent in the request)
        $userLatitude  = $request->input('latitude');
        $userLongitude = $request->input('longitude');

        $data = $this->bankRepository->findClosestBranchesByCoordinates($userLatitude, $userLongitude);

        $headers = ['Content-Type' => 'application/json; charset=utf-8'];

        return response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
    }
}