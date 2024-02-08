<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\MonthlyBudgetControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Users;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get/user', [UserController::class, 'index']);
Route::post('/post/user', [UserController::class, 'store']);
Route::delete('/delete/user/{id}', [UserController::class, 'destroy']);
Route::put('/user/{id}', [UserController::class, 'update']);

Route::get('/get/category', [ExpenseCategoriesController::class, 'index']);
Route::get('/get/category/{id}', [ExpenseCategoriesController::class, 'getid']);
Route::post('/post/category', [ExpenseCategoriesController::class, 'store']);
Route::delete('/delete/category/{id}', [ExpenseCategoriesController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/expenses', function (Request $request) {
    return $request->expenses();
});

Route::get('/get/expenses', [ExpensesController::class, 'index']);
Route::post('/post/expenses', [ExpensesController::class, 'store']);
Route::delete('/delete/expenses/{id}', [ExpensesController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/budget', function (Request $request) {
    return $request->budget();
});

Route::get('/get/budget', [MonthlyBudgetControler::class, 'index']);
Route::post('/post/budget', [MonthlyBudgetControler::class, 'store']);
Route::delete('/delete/budget/{id}', [MonthlyBudgetControler::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/notification', function (Request $request) {
    return $request->notification();
});

