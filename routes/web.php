<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVFilterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('inicio');
});

Route::post('/uploadFile', [CSVFilterController::class, 'uploadFile'])->name('csvfilter.upload');
// Route::post('/uploadFile', [CSVFilterController::class, 'uploadCSVFile'])->name('csvfilter.uploadCsv');
Route::post('/uploadFile', [CSVFilterController::class, 'uploadCSV'])->name('csvfilter.uploadCsv');
