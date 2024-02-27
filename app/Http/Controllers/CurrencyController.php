<?php

namespace App\Http\Controllers;

use App\Repositories\CurrencyRateRepository;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyRepository $currencyRepository;
    protected CurrencyRateRepository $currencyRateRepository;

    public function __construct(CurrencyRepository $currencyRepository, CurrencyRateRepository $currencyRateRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->currencyRateRepository = $currencyRateRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->currencyRepository->all();

        return response()->json($data);
    }

    public function getCurrencyRates(Request $request): JsonResponse
    {
        $bankSlug     = $request->input('bank');
        $currencyCode = $request->input('currency');

        $data = $this->currencyRateRepository->getCurrencyRates($bankSlug, $currencyCode);

        return response()->json($data);
    }

    public function getAverageExchangeRate(Request $request): JsonResponse
    {
        $currencyCode = $request->input('currency');

        $data = $this->currencyRateRepository->getNBUAndAverageExchangeRate($currencyCode);

        return response()->json($data);
    }
}
