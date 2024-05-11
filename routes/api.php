<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
// routes/api.php



// User Signup
Route::post('/signup', [AuthController::class, 'signup']);

// User Login
Route::post('/login', [AuthController::class, 'login']);

// User Logout
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

// Password Reset
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

// Token Refresh
Route::middleware('auth:api')->post('/token/refresh', [AuthController::class, 'refreshToken']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
