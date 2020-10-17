<?php

use App\Http\Controllers\API\V1\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix("company")->group(function () {

    Route::get("symbols",[CompanyController::class, 'getCompaniesSymbols']);
    Route::get("filter",[CompanyController::class, 'filter']);

});

