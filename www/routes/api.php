<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaletteController;
use App\Http\Controllers\BathController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ping', [BathController::class, 'ping']);
//Route::get('baths', [BathController::class, 'getNextNumber']);


// From manager
Route::put('synchronize', [AccountController::class, 'synchronize'])->name('api.synchronize');

Route::put('account', [AccountController::class, 'update']);
Route::put('palettes', [PaletteController::class, 'update']);
Route::put('baths', [BathController::class, 'update']);


Route::get('baths', [BathController::class, 'index']);
