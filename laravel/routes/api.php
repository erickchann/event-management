<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {
    Route::get('events', [EventController::class, 'index']);    

    Route::get('organizers/{organizer}/events/{event}', [EventController::class, 'show']);
    Route::post('organizers/{organizer}/events/{event}/registration', [EventController::class, 'registration']);

    Route::get('registrations', [EventController::class, 'getRegistrations']);

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});