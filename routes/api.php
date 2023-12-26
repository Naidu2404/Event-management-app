<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthController;
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



//route for the event controller
Route::apiResource('events',EventController::class);

//rooute for login controller
Route::post('/login',[AuthController::class,'login']);

//route for logout 
Route::post('/logout',[AuthController::class,'logout'])
->middleware('auth:sanctum');


//a scoped route for the attendee controller
Route::apiResource('events.attendees',AttendeeController::class)
->scoped()->except(['update']);