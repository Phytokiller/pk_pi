<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BathController;
use App\Http\Controllers\PaletteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/noaccount', [WelcomeController::class, 'noaccount'])->name('noaccount');

// ACCOUNT
Route::put('accounts/{account}/switch', [AccountController::class, 'switch'])->name('accounts.switch');

// USER
Route::put('users/{user}/switch', [UserController::class, 'switch'])->name('users.switch');

// PALETTES
Route::get('palettes/{palette}/start', [PaletteController::class, 'start'])->name('palettes.start');
Route::get('palettes/{palette}/next', [PaletteController::class, 'next'])->name('palettes.next');
Route::get('palettes/{palette}/prev', [PaletteController::class, 'prev'])->name('palettes.prev');

// BATHS
Route::post('baths', [BathController::class, 'store'])->name('baths.store');
Route::get('baths', [BathController::class, 'show'])->name('baths.show'); // For tests
Route::post('baths/{bath}/measure', [BathController::class, 'measure'])->name('baths.measure');
Route::put('baths/{bath}', [BathController::class, 'finish'])->name('baths.finish');

// SETTINGS
Route::get('/settings', [SettingController::class, 'index'])->name('settings');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
