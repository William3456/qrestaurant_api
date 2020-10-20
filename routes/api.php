<?php

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

//Route::apiResource('usuario', 'UsuarioController');
Route::middleware('auth:api')->get('/usuario', function (Request $request) {
    return $request->user();
});

//Usuario
Route::post('usuario/crear', 'UsuarioController@store'); //Crea un nuevo usuario
Route::post('login', 'UsuarioController@login'); //Hace el logeo
Route::get('usuario', 'UsuarioController@index'); // Obtiene todos los usuarios
Route::get('usuario/{idUser}', 'UsuarioController@show'); //Obtiene un usuario por su id o correo
Route::put('usuario/actualizar', 'UsuarioController@update'); //Actualiza un usuario
Route::delete('usuario/eliminar/{email}', 'UsuarioController@delete'); //Elimina un usuario por su correo
Route::get('usuario/restaurante/{idRestaurante}', 'UsuarioController@getUserByRestaurante');
//Menús
Route::get('menu', 'MenuRestauranteController@index'); //Devuelve todos los menús
Route::get('menu/{idMenu}', 'MenuRestauranteController@show'); //Devuelve un menú por id

Route::get('menu/restaurante/{idrestaurante}/tipo/{idTipo}', 'MenuRestauranteController@menuByTipoByRest'); //Devuelve menús por restaurante y tipo

Route::get('menu/restaurante/{idRestaurante}',
    'MenuRestauranteController@menuByRestaurante'); //Devuelve los menús ligados al restaurante
Route::get('menu/tipo/{idTipo}',
    'MenuRestauranteController@menuByTipo'); //Devuelve los menús por tipo
Route::post('menu/crear', 'MenuRestauranteController@store'); //Crea un menú
Route::post('menu/menuscanner', 'MenuRestauranteController@menuByScanner'); //Crea un menú
Route::put('menu/actualizar', 'MenuRestauranteController@update'); //Actualiza un menú por su ID
Route::delete('menu/eliminar/{idMenu}', 'MenuRestauranteController@delete'); //Elimina un menú por su ID
