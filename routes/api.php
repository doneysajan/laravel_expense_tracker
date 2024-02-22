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


Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/getUser', [LoginController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/updateUser', [LoginController::class, 'updateUser']);


    Route::delete('/deleteexpense/{id}', [ExpenseController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/getUser', [LoginController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/changePassword', [LoginController::class, 'changePassword']);
Route::middleware('auth:sanctum')->post('/updateUser', [LoginController::class, 'updateUser']);
Route::middleware('auth:sanctum')->post('/deactivateAccount', [LoginController::class, 'deactivateAccount']);

