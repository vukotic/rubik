<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CubeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('cube/recreate', [CubeController::class, 'recreate']);
Route::get('cube/display', [CubeController::class, 'display']);
Route::get('cube/display/side/{side}', [CubeController::class, 'displaySide']);
Route::get('cube/rotate/{axis}/{row}/{direction}', [CubeController::class, 'rotate']);
