<?php

use Illuminate\Http\Request;

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

// Login
Route::get('login/logout', 'LoginController@logout');
Route::get('login/check', 'LoginController@check');

// USUARIOS
Route::resource('usuarios','UsuariosController');

// EMPLEADOS
Route::get('empleados/{id}/sueldo', 'EmpleadosController@obtenerSueldo');
Route::resource('empleados','EmpleadosController');
// MOVIMIENTOS
Route::resource('movimientos','MovimientosController');
