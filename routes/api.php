<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route to get authenticated user
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route for user registration
Route::post('/register', [LoginController::class, 'register']);

// Route for user login
Route::post('/login', [LoginController::class, 'login']);



// Routes for storing expenses
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store', [ExpenseController::class, 'store']);
    Route::get('/listing', [ExpenseController::class, 'listing']);
    Route::put('/editexpenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/deleteexpense/{id}', [ExpenseController::class, 'destroy']);
    Route::get('/getFilteredExpenseData', [ExpenseController::class, 'getFilteredExpenseData']);
    Route::get('/getSortedExpenseData', [ExpenseController::class, 'getSortedExpenseData']);
    Route::get('/graph', [ExpenseController::class, 'graph']);
    Route::get('/line', [ExpenseController::class, 'line']);
    Route::get('/bar', [ExpenseController::class, 'bar']);
    Route::get('/doughnut', [ExpenseController::class, 'doughnut']);
    Route::get('/budget', [ExpenseController::class, 'radar']);
    Route::get('/reports', [ExpenseController::class, 'reports']);



});

Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/getUser', [LoginController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/changePassword', [LoginController::class, 'changePassword']);
Route::middleware('auth:sanctum')->post('/updateUser', [LoginController::class, 'updateUser']);
Route::middleware('auth:sanctum')->post('/removeProfileImage', [LoginController::class, 'removeProfileImage']);
Route::middleware('auth:sanctum')->post('/deactivateAccount', [LoginController::class, 'deactivateAccount']);




