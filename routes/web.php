<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicCmsController;
use App\Http\Controllers\SerpOrganicResultController;
use App\Http\Controllers\AdsKeywordPlannerResultController;
use App\Http\Controllers\GscFullUrlController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('main.index');
});

Route::get('/serp-organic-results', [SerpOrganicResultController::class, 'index']);
Route::get('/serp-organic-results/csv', [SerpOrganicResultController::class, 'csv']);

Route::get('/ads-keyword-planner-results', [AdsKeywordPlannerResultController::class, 'index']);
Route::get('/ads-keyword-planner-results/csv', [AdsKeywordPlannerResultController::class, 'csv']);
Route::get('/gsc_qsha_oh/fullurl',[GscFullUrlController::class, 'index'])->name('gsc.qsha_oh.fullurl');


Route::get('/{slug}', [DynamicCmsController::class, 'handle'])->where('slug', '.*');
