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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route for user registration
Route::post('/register', [LoginController::class, 'register']);

// Route for user login
Route::post('/login', [LoginController::class, 'login']);

// Routes for storing, listing, updating, and deleting expenses
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store', [ExpenseController::class, 'store']); // Store an expense
    Route::get('/listing', [ExpenseController::class, 'listing']); // List all expenses
  
    Route::put('/editexpenses/{id}', [ExpenseController::class, 'update']);


    Route::delete('/deleteexpenses', [ExpenseController::class, 'destroy']); // Delete an expense
});

