<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::resource('events', EventController::class);
Route::resource('events/{event}/tickets', TicketController::class);
Route::resource('events/{event}/channels', ChannelController::class);
Route::resource('events/{event}/rooms', RoomController::class);
Route::resource('events/{event}/sessions', SessionController::class);