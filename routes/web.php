<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
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
Route::get("/", function () {
    return view("welcome");
});


Route::get('login', [LoginController::class, 'index']);
Route::post('autenticar', [LoginController::class,'autenticar']);
Route::get('logout', [LoginController::class, 'logout']);


Route::middleware(['middleware' => 'noAuth'])->group(function () {
    Route::get('usuario', [UsuarioController::class, 'index']);
    Route::get('eliminar', [UsuarioController::class, 'eliminarUsuario'])->middleware('Token');
    Route::get('obtenerId', [UsuarioController::class, 'obtenerIdUsuario']);
    Route::post('crear', [UsuarioController::class, 'crearUsuario'])->middleware('Token');
    Route::post('actualizar', [UsuarioController::class, 'actualizarUsuario'])->middleware('Token');
});




