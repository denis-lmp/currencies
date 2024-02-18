<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CurrencyHistoryController extends Controller
{

    protected CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function index(Request $request, CurrencyRepository $currencyRepository): View
    {
        $currencyCode = $request->input('currencyCode', 'USD');
        $startDate    = $request->input('startDate', Carbon::now()->startOfDay());
        $endDate      = $request->input('endDate', Carbon::now()->endOfDay());

        $userInfo   = Auth::user();
        $currencies = Currency::all();

        $historicalChanges = $currencyRepository->getHistoricalChanges($currencyCode, $startDate, $endDate);

        return view('currency.home', [
            'currencies' => $currencies,
            'historicalChanges' => $historicalChanges,
            'userInfo' => $userInfo
        ]);
    }

    public function getHistoricalChanges(Request $request, CurrencyRepository $currencyRepository): Collection|array
    {
        $currencyCode = $request->input('currencyCode', 'USD');
        $startDate    = $request->input('startDate', Carbon::now()->startOfDay());
        $endDate      = $request->input('endDate', Carbon::now()->endOfDay());

        return $currencyRepository->getHistoricalChanges($currencyCode, $startDate, $endDate);
    }

}
