<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuxController;

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
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Localidades
Route::get('/estados', [AuxController::class, 'estados']);
Route::get('/municipios', [AuxController::class, 'municipios']);
Route::get('/cps', [AuxController::class, 'cps']);
Route::post('/address', [AuxController::class, 'getaddress']);
Route::post('/addressbycp', [AuxController::class, 'getaddressbycp']);
Route::post('/insertupdateaddress', [AuxController::class, 'insertupdateaddress']);
Route::post('/getorders', [AuxController::class, 'getorders']);
Route::post('/setorders', [AuxController::class, 'setorders']);
Route::post('/getordersdelivery', [AuxController::class, 'getordersdelivery']);
Route::post('/aux1', [AuxController::class, 'aux1']);
Route::post('/aux2', [AuxController::class, 'aux2']);
Route::post('/aux3', [AuxController::class, 'aux3']);



// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function() {

    // User
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

