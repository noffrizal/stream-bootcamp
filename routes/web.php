<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Member\PricingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Member\RegisterController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Member\LoginController as MemberLoginController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\MovieController as MemberMovieController;

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

Route::view('/', 'index');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');


// member routes
Route::get('/register', [RegisterController::class, 'index'])->name('member.register');
Route::post('/register', [RegisterController::class, 'store'])->name('member.register.store');

Route::get('/login', [MemberLoginController::class, 'index'])->name('member.login');
Route::post('/login', [MemberLoginController::class, 'auth'])->name('member.login.auth');

Route::group(['prefix' => 'member', 'middleware' => 'auth'], function () {
    Route::get('/', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/movie/{id}', [MemberMovieController::class, 'show'])->name('member.movie.detail');
    Route::get('/movie/{id}/watch',[MemberMovieController::class, 'watch'])->name('member.movie.watch');
});


// admin routes
Route::get('/admin/login', [LoginController::class, 'index'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'authenticate'])->name('admin.login.auth');
// admin route groups
Route::group(['prefix' => 'admin', 'middleware' => 'admin.auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.login.logout');
    Route::resource('/movie', MovieController::class);
    Route::resource('/transaction', TransactionController::class);
});
