<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MovieController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/admin/login',[LoginController::class, 'index'])->name('admin.login');
Route::post('/admin/login',[LoginController::class, 'authenticate'])->name('admin.login.auth');


Route::group(['prefix' => 'admin', 'middleware' => 'admin.auth'], function(){
    Route::get('/',[DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.login.logout');
    Route::resource('/movie', MovieController::class);
});
