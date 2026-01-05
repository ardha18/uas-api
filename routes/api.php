<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/obat', [ObatController::class, 'index']);
Route::post('/obat', [ObatController::class, 'store']);
Route::get('/obat/{idobat}', [ObatController::class, 'show']);
Route::put('/obat/{idobat}', [ObatController::class, 'update']);
Route::delete('/obat/{idobat}', [ObatController::class, 'destroy']);
