<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\MonthlyBudgetController;

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

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::get('/', 'ExpenseController@index')->name('pages.index');
    Route::get('/about', 'PagesController@about')->name('pages.about');
    Route::get('/login', 'PagesController@login')->name('pages.login');
    Route::get('/register', 'PagesController@register')->name('pages.register');
    Route::get('/login', 'AuthController@showLoginForm')->name('login');
    Route::post('/login', 'AuthController@login');
    Route::get('/register', 'AuthController@showRegistrationForm')->name('register');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // test
    Route::get('/create', [ExpenseController::class, 'create'])->name('pages.expense.create');
    Route::post('/create', [ExpenseController::class, 'store'])->name('pages.expense.store');
    Route::get('/edit/{expense}', [ExpenseController::class, 'edit'])->name('pages.expense.edit');
    Route::put('/edit/{expense}', [ExpenseController::class, 'update'])->name('pages.expense.update');
    Route::delete('/delete/{expense}', [ExpenseController::class, 'destroy'])->name('pages.expense.destroy');

    Route::get('/add', [ExpenseCategoryController::class, 'create'])->name('pages.category.create');
    Route::post('/add', [ExpenseCategoryController::class, 'store'])->name('pages.category.store');
    Route::get('/categories/{category}/edit', 'ExpenseCategoryController@edit')->name('pages.category.edit');
    Route::put('/categories/{category}', 'ExpenseCategoryController@update')->name('pages.category.update');
    Route::delete('/categories/{category}', 'ExpenseCategoryController@destroy')->name('pages.category.destroy');

    Route::get('/budget', [MonthlyBudgetController::class, 'index'])->name('pages.monthly.index');
    Route::get('/set', [MonthlyBudgetController::class, 'create'])->name('pages.monthly.create');
    Route::post('/set', [MonthlyBudgetController::class, 'store'])->name('pages.monthly.store');

});

