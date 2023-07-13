<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\AccountController;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/members', [MembersController::class, 'index'])->name('members.index');

Route::post('/members', [MembersController::class, 'save'])->name('members.save');

Route::get('/members/search', [MembersController::class, 'search'])->name('members.search');

Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');

Route::post('/transactions', [TransactionsController::class, 'save'])->name('transactions.save');

Route::get('/account', [AccountController::class, 'index'])->name('account.index');

Route::post('/account/update-password', [AccountController::class, 'updatePassword'])->name('account.password');



