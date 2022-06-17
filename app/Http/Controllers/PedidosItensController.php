<?php

namespace App\Http\Controllers;

use App\Models\PedidosItens;
use Illuminate\Http\Request;
use App\Models\Produtos;
use App\Models\Clientes;
Use DB;

class PedidosItensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(isset($request->clientId)){
            $where = " where c.Id = " . $request->clientId; 
        } else {
            $where= "";
        }

        $pedidos = DB::select(
            DB::RAW( "select c.nomeCliente, c.Id as clienteId, pu.* from pedidosusers as pu left join clientes as c on pu.idCliente = c.id 
            $where
            order by pu.created_at desc"
            )
        );
        return view('pages/pedidos',  compact('pedidos'));
        
    }

    public function MostrarPedido(Request $request)
    {
        if($request->idPedido !== ""){
            $pedidos = DB::select(
                DB::RAW( "select c.nomeCliente, pu.* from pedidosusers as pu left join clientes as c on pu.idCliente = c.id 
                where pu.id = '" . $request->idPedido. "'
                order by pu.created_at desc"
                )
            );

        } elseif($request->idCliente !== ""){
            $pedidos = DB::select(
                DB::RAW( "select c.nomeCliente, pu.* from pedidosusers as pu left join clientes as c on pu.idCliente = c.id 
                where pu.idCliente = '" . $request->idClient . "'
                order by pu.created_at desc"
                )
            );

        }else {
            $pedidos = DB::select(
                DB::RAW( "select c.nomeCliente, pu.* from pedidosusers as pu left join clientes as c on pu.idCliente = c.id 
                order by pu.created_at desc"
                )
            );
        }
    }

    public function NovoPedido(Request $request){

        $produtos= Produtos::all();

        //dd($request->id);

        if(!is_null($request->idCliente)){
            $clientes = Clientes::where("id", "=", "$request->idCliente")->get();
        }

        if(!is_null($request->id)){


            $itens = DB::select(
                DB::RAW( "select pedidos_itens.*, produtos.nomeProduto, produtos.valor from pedidos_itens 
                inner join produtos on produtos.id = pedidos_itens.produtoId
                where idPedido = " . $request->id. " order by created_at desc"
                )
            );

            $totalGeral =  DB::select(
                        DB::RAW("Select sum(valorTotal) as totalGeral, count(*) as itensTotal from pedidos_itens 
                        where idPedido = " . $request->id));
            
            $status =  DB::select(
                            DB::RAW("select status from pedidosusers where id= " . $request->id));

                         

            $aStatus =  array("Aberto" => "", "Pago" => "", "Cancelado" => "");
            $aStatus[$status[0]->status] = "selected";


        } else {
            $itens = array();
            $totalGeral = array();
            $status = array();
            $aStatus= array();
        }
      
        return view('pages/pedido',  compact('produtos', 'clientes', 'itens', 'totalGeral', 'aStatus'));

    }

    //todo terminar inserts
    public function NovoPedidoSave(Request $request)
    {
 

        if(!is_null($request->idPedido)){
            $sql = true;
            $lastInsertId = $request->idPedido;
        }else {
            $sql = DB::insert(
                DB::raw(
                "insert into pedidosusers (idCliente, status, created_at, dtPedido) values (".$request->cliente_id.", 'Aberto',now(), now())"
                )
            );
            $lastInsertId = DB::getPdo()->lastInsertId();
        }
       
        if($sql){
            $sql2  = DB::insert(DB::raw(
                "insert into pedidos_itens (produtoId, produtoQtde, valorTotal, idPedido) values ('".$request->id."', '".$request->produtoQtde."','".$request->total."','".$lastInsertId."')"
            ));
        }
        if($sql2){
            return response()->json([
                'message' => 'produto excluido ok!', 'pedidoId' => $lastInsertId
                ], 200);
        }else {
            return response()->json([
                'message' => 'Erro inserindo item!'
                ], 500);
        }
    }

    public function PedidoAddIten(Request $request)
    {
        $sql2  = DB::insert(DB::raw(
            "insert into pedidos_itens (idCliente, status, created_at) values ('".$request."', '".$request."','".$request."')"
        ));
    }

    public function PedidoRemoveIten(Request $request)
    {

        $sql = DB::delete(DB::RAW(
            "delete from pedidos_itens where idPedido = $request->idPedido and id = $request->IdItem"
        ));

        if($sql){
            return response()->json([
                'message' => 'produto excluido ok!'
                ], 200);
        }else {
            return response()->json([
                'message' => 'Erro inserindo item!'
                ], 500);
        }
    }


    public function removePedido(Request $request)
    {
        $sql = DB::delete(DB::RAW(
            "delete from pedidos_itens where idPedido = $request->IdPedido"
        ));
        if($sql){
            $sql2 = DB::delete(DB::RAW(
                "delete from pedidosusers where id = $request->IdPedido"
            )); 
        }

        if($sql2){
            return response()->json([
                'message' => 'pedido excluido ok!'
                ], 200);
        }else {
            return response()->json([
                'message' => 'Erro excluindo pedido!'
                ], 500);
        }
    }

    
    public function alteraStatusPedido(Request $request)
    {
        $sql = DB::update(DB::RAW(
            "update pedidosusers set status = '".$request->status."' where id = $request->idPedido"
        ));

        if($sql){
            return response()->json([
                'message' => 'status alterado, ok!'
                ], 200);
        }else {
            return response()->json([
                'message' => 'Erro status!'
                ], 500);
        }
    }

    public function APIPedidos(Request $request){

        if(isset($request->id)){
            $where = " where c.Id = " . $request->id; 
        } else {
            $where= "";
        }

        $pedidos = DB::select(
            DB::RAW( "select c.nomeCliente, c.Id as clienteId, pu.* from pedidosusers as pu left join clientes as c on pu.idCliente = c.id 
            $where
            order by pu.created_at desc"
            )
        );

        return response()->json(['data' => $pedidos]);

    }

    public function APIPedidosItens(Request $request){

        $itens = DB::select(
            DB::RAW( "select pedidos_itens.*, produtos.nomeProduto, produtos.valor from pedidos_itens 
            inner join produtos on produtos.id = pedidos_itens.produtoId 
            where idPedido = " . $request->id. " order by created_at desc"
            )
        );

        return response()->json(['data' => $itens]);
        

    }





}
