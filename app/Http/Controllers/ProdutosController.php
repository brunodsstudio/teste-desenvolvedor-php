<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use Illuminate\Http\Request;
use DB;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produtos = Produtos::all();
        return view('pages/produtos',  compact('produtos'));
    }
    
    public function showAPI(Request $request)
    {
        $produtos = Produtos::query()->where('id', '=', $request->produto_id)->get();
        return response()->json(['data' => $produtos]);
    }

    public function produtosAddUpdt(Request $request)
    {
        if(is_null($request->idproduto)){
            $produtos = DB::insert(
                DB::raw("insert into produtos (nomeProduto, valor, codigoBarras) values ('" . $request->nomeproduto . "', '" . str_replace(",", "", $request->valor) . "', '" . $request->codigoBarras . "')")
            );

        } else {
            $produtos = DB::update(
                DB::raw("update produtos set nomeProduto = '" . $request->nomeproduto . "', valor = '" . str_replace(",", "", $request->valor) . "', codigoBarras = '" . $request->codigoBarras . "' where id = $request->idproduto")
            );
        }

        return response()->json([
            'message' => 'produto alterado ok!'
            ], 200);
    }

    public function produtosDel(Request $request){

        $produtos = DB::update(
            DB::raw("delete from produtos where id = $request->idproduto")
        );
        return response()->json([
            'message' => 'produto excluido ok!'
            ], 200);
    }

    public function APIProdutos(Request $request){

        if(!isset($request->id)){
            $produtos = Produtos::all();
        }else{
            $produtos = Produtos::query()->where('id', '=', $request->id)->get(); 
        }
        return response()->json(['data' => $produtos]);
    }

    

}
