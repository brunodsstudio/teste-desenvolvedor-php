<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\PedidosItensController;

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
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');*/
Route::get('/dashboard', 'App\Http\Controllers\ClientesController@index')->middleware(['auth'])->name('clientes');
Route::get('/clientes', 'App\Http\Controllers\ClientesController@index')->middleware(['auth'])->name('clientes');
Route::post('/clientes', 'App\Http\Controllers\ClientesController@showAPI')->middleware(['auth'])->name('clientes');
Route::post('/clientesAddUpt', 'App\Http\Controllers\ClientesController@clientesAddUpdt')->middleware(['auth'])->name('clientesAddUpdt');
Route::post('/clientesDel', 'App\Http\Controllers\ClientesController@clientesDel')->middleware(['auth'])->name('clientesDel');

Route::get('/produtos', 'App\Http\Controllers\ProdutosController@index')->middleware(['auth'])->name('produtos');
Route::post('/produtos', 'App\Http\Controllers\ProdutosController@showAPI')->middleware(['auth'])->name('produtosAPI');
Route::post('/produtosAddUpt', 'App\Http\Controllers\ProdutosController@produtosAddUpdt')->middleware(['auth'])->name('produtoAddUpdt');
Route::post('/produtosDel', 'App\Http\Controllers\ProdutosController@produtosDel')->middleware(['auth'])->name('produtoDel');

Route::get('/pedidos', 'App\Http\Controllers\PedidosItensController@index')->middleware(['auth'])->name('produtos');
Route::get('/pedidos/{clientId?}', 'App\Http\Controllers\PedidosItensController@index')->middleware(['auth'])->name('produtos');

Route::get('/novopedido/{idCliente}', 'App\Http\Controllers\PedidosItensController@novoPedido')->middleware(['auth'])->name('produtos');
Route::get('/editarpedido/{id}', 'App\Http\Controllers\PedidosItensController@index')->middleware(['auth'])->name('produtos');
Route::post('/removePedido', 'App\Http\Controllers\PedidosItensController@removePedido')->middleware(['auth'])->name('produtos');

Route::get('/pedido/{idCliente}', [PedidosItensController::class, 'NovoPedido'])->middleware('auth');
Route::get('/pedido/{id}/{idCliente}', [PedidosItensController::class, 'NovoPedido'])->middleware('auth');
Route::post('/removePedidoIten', [PedidosItensController::class, 'PedidoRemoveIten'])->middleware('auth'); 
Route::post('/alteraStatusPedido', [PedidosItensController::class, 'alteraStatusPedido'])->middleware('auth'); 

Route::post('/novoPedido', [PedidosItensController::class, 'NovoPedidoSave'])->middleware('auth');


require __DIR__.'/auth.php';
