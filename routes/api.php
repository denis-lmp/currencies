<?php

use App\Http\Controllers\BankBranchController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/currencies', [CurrencyController::class, 'index']);
Route::get('/banks', [BankController::class, 'index']);
Route::get('/branches', [BankBranchController::class, 'index']);

Route::get('bank/{slug}', [BankController::class, 'showBySlug']);
Route::get('/closest-branches', [BankController::class, 'getClosestBranches']);
Route::get('/currency-rates', [CurrencyController::class, 'currencyRates']);
Route::get('/average-rate', [CurrencyController::class, 'averageExchangeRate']);
