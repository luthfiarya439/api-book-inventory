<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoanController;
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

Route::post('login', [AuthController::class, 'login']);
  
Route::middleware('auth:sanctum')->group(function () {

  Route::post('logout', [AuthController::class, 'logout']);
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::middleware('checkrole:Admin')->group(function () {
    Route::resource('admin/books', BookController::class);
    Route::get('admin/book/loaned', [BookController::class, 'getLoaned']);
    Route::resource('admin/loans', LoansController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('admin/users', UserController::class);
    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    Route::post('admin/users/import', [UserController::class, 'importUser']);
  });

  Route::middleware('checkrole:User')->group(function () {
    Route::get('user/books', [BookController::class, 'index']);
    Route::get('user/myloans', [UserLoanController::class, 'index']);
    Route::resource('user/loans', LoansController::class)->only('store');
  });
});