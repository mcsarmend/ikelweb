<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pedidosController;
use App\Http\Controllers\historicoController;
use App\Http\Controllers\repartidoresController;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home')->middleware('auth');
    } else {
        return redirect('/login');
    }
})->middleware('auth');



// pedidos

Route::get('pedidos/proceso', [pedidosController::class, 'proceso'])->middleware('auth');
Route::get('pedidos/asignar', [pedidosController::class, 'asignar'])->middleware('auth');
Route::get('pedidos/ruta', [pedidosController::class, 'ruta'])->middleware('auth');
Route::get('pedidos/percances', [pedidosController::class, 'percances'])->middleware('auth');



// historico
Route::get('historico', [historicoController::class, 'historico'])->middleware('auth');
Route::get('gethistoricorders', [historicoController::class, 'gethistoricorders']);

//Repartidores
Route::get('repartidores', [repartidoresController::class, 'repartidores'])->middleware('auth');
Route::get('repartidoresname', [repartidoresController::class, 'repartidoresname']);




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
