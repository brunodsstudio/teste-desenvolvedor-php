<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Clientes::all();

        return view('pages/clientes',  compact('clientes'));
    }

    public function showAPI(Request $request)
    {
        $produtos = Clientes::query()->where('id', '=', $request->cliente_id)->get();
        return response()->json(['data' => $produtos]);
    }

    public function APIClientes(Request $request){

        if(!isset($request->id)){
            $produtos = Clientes::all();
        }else{
            $produtos = Clientes::query()->where('id', '=', $request->id)->get(); 
        }
        return response()->json(['data' => $produtos]);
    }

    public function clientesAddUpdt(Request $request)
    {
        if(is_null($request->cliente_id)){


            $produtos = DB::insert(
                DB::raw("insert into clientes (nomeCliente, CPF, Email) values ('" . str_replace("'", "", $request->nomecliente) . "', '$request->cpf', '" . $request->email . "')")
            );

        } else {
            $produtos = DB::update(
                DB::raw("update clientes set nomeCliente = '" . str_replace("'", "", $request->nomecliente). "', CPF = '$request->cpf', Email = '" . $request->email . "' where id = $request->cliente_id")
            );
        }

        if($produtos){
        return response()->json([
            'message' => 'cliente alterado/criado ok!'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Erro ao alterar/criar cliente!'
                ], 500);
        }
    }

    public function clientesDel(Request $request){

        $produtos = DB::update(
            DB::raw("delete from clientes where id = $request->cliente_id")
        );
        if($produtos){
        return response()->json([
            'message' => 'cliente excluido ok!'
            ], 200);
        } else{
            return response()->json([
                'message' => 'Erro ao excluir cliente!'
                ], 500);
        }
    }


}
