<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Repositories\CurrencyRateRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CurrencyHistoryController extends Controller
{
    protected CurrencyRateRepository $currencyRateRepository;

    public function __construct(CurrencyRateRepository $currencyRateRepository)
    {
        $this->currencyRateRepository = $currencyRateRepository;
    }

    public function index(Request $request): View
    {
        $currencyCode = $request->input('currencyCode', 'USD');
        $startDate    = $request->input('startDate', Carbon::now()->startOfDay());
        $endDate      = $request->input('endDate', Carbon::now()->endOfDay());

        $userInfo   = Auth::user();
        $currencies = Currency::all();

        $historicalChanges = $this->currencyRateRepository->getHistoricalChanges($currencyCode, $startDate, $endDate);

        return view('currency.home', [
            'currencies' => $currencies,
            'historicalChanges' => $historicalChanges,
            'userInfo' => $userInfo
        ]);
    }

    public function getHistoricalChanges(Request $request): Collection|array
    {
        $currencyCode = $request->input('currencyCode', 'USD');
        $startDate    = $request->input('startDate', Carbon::now()->startOfDay());
        $endDate      = $request->input('endDate', Carbon::now()->endOfDay());

        return $this->currencyRateRepository->getHistoricalChanges($currencyCode, $startDate, $endDate);
    }

}
