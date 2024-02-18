<?php

namespace App\Http\Controllers;

use App\Repositories\CurrencyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->currencyRepository->all();

        return response()->json($data);
    }

    public function currencyRates(Request $request): JsonResponse
    {
        $bankSlug     = $request->input('bank');
        $currencyCode = $request->input('currency');

        $data = $this->currencyRepository->getCurrencyRates($bankSlug, $currencyCode);

        return response()->json($data);
    }

    public function averageExchangeRate(Request $request): JsonResponse
    {
        $currencyCode = $request->input('currency');

        $data = $this->currencyRepository->getNBUAndAverageExchangeRate($currencyCode);

        return response()->json($data);
    }
}
