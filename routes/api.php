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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/clientes', 'App\Http\Controllers\ClientesController@APIClientes');
Route::get('/clientes/{id?}', 'App\Http\Controllers\ClientesController@APIClientes');

Route::get('/produtos', 'App\Http\Controllers\ProdutosController@APIProdutos');
Route::get('/produtos/{id?}', 'App\Http\Controllers\ProdutosController@APIProdutos');

Route::get('/pedidos', 'App\Http\Controllers\PedidosItensController@APIPedidos');
Route::get('/pedidos/{id?}', 'App\Http\Controllers\PedidosItensController@APIPedidos');
Route::get('/pedidosItens/{id?}', 'App\Http\Controllers\PedidosItensController@APIPedidosItens');

