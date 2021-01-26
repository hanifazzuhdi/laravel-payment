<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

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

// Route breeze + autentikasi
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

// Route Declaration
Route::get('/', [DonationController::class, 'index']);
Route::post('/store', [DonationController::class, 'store'])->name('store');
