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

//USUARIO
Route::post('usuario/crear', 'UsuarioController@store'); //Crea un nuevo usuario
Route::post('login', 'UsuarioController@login'); //Hace el logeo
Route::get('usuario', 'UsuarioController@index'); // Obtiene todos los usuarios
Route::get('usuario/{idUser}', 'UsuarioController@show'); //Obtiene un usuario por su id o correo
Route::put('usuario/actualizar', 'UsuarioController@update'); //Actualiza un usuario
Route::delete('usuario/eliminar/{email}', 'UsuarioController@delete'); //Elimina un usuario por su correo
Route::get('usuario/restaurante/{idRestaurante}', 'UsuarioController@getUserByRestaurante');

//MENÚS
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


//RESTAURANTES
Route::get('restaurante', 'RestauranteController@index'); //Devuelve todos los restaurantes
Route::post('restaurante/crear', 'RestauranteController@store'); //Crea un restaurante
Route::get('restaurante/{idRestaurante}', 'RestauranteController@show'); //Devuelve un restaurante por id
Route::get('restaurante/encargado/{idOrMail}',
    'RestauranteController@getRestauranteByEncargado');//Devuelve un restaurantes por id o email de encargado
Route::put('restaurante/actualizar', 'RestauranteController@update'); //Actualiza un restaurante por su ID
Route::delete('restaurante/eliminar/{idRestaurante}',
    'RestauranteController@delete'); //Elimina un restaurante por su ID

//PEDIDOS
Route::post('pedido/ingreso', 'PedidosController@store'); //Guardo pedidos de cliente
Route::get('pedido/obtenerbyfechaestado/{fecha}/{estado}/{idRestaurante}', 'PedidosController@getPedidosByFecha'); //Obtener pedidos por fecha y restaurante
Route::get('pedido/obtenerbycliente/{idCliente}', 'PedidosController@obtenerByCliente'); //Obtener pedidos cliente
Route::put('pedido/actualizarestado', 'PedidosController@update'); //Actualiza estado de pedido

//DETALLE DE PEDIDOS
Route::get('detallepedido/{idPedido}', 'DetallePedidosController@show'); //Obtener pedidos por fecha
