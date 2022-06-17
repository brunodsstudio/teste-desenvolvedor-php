@extends('layouts.gafa')
@section('contentSection1')
<hr>
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Pedido</li>
        </ol>
    </nav>
    <div class="row">
    <div class="col-1"></div>
        <div class="col-6">
            <ul class="list-group">
                <li class="list-group-item"><b>Cliente</b>: {{$clientes[0]->nomeCliente}}</li>
                <li class="list-group-item"><b>CPF</b>: {{$clientes[0]->CPF}}</li>
                @if(count($itens) > 0)
                <li class="list-group-item"><b>Total geral do pedido R$</b>: {{$totalGeral[0]->totalGeral}}</li>
                <li class="list-group-item"><b>Status</b>: 
                <select class="form-control" id="status">
                       @foreach($aStatus as $k => $v)
                       <option value="{{$k}}" {{$v}}>{{$k}}</option>
                       @endforeach
                </select>
            
                </li>
                @endif
            </ul>
        </div>
    </div>

        

    <div class="row">
        <div class="col-1"></div>
        <div class="col-11">

        
    <hr>
        <div class="row">
            
        </div>

        <form id="pedidoForm">
        <div class="row">
            <div class="col-3">

            @if(count($itens) > 0)
            <input type="hidden"  id="id"  value="{{$itens[0]->idPedido}}">
            @endif
            <input type="hidden"  id="cliente_Id"  value="{{$clientes[0]->id}}">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Produtos</label>
                <select class="form-control" id="exampleFormControlSelect1">
                    <option value="">Selecione o Produto</option>
                    @foreach($produtos as $p)
                    <option value="{{$p->id}}">{{$p->nomeProduto}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group" >
                            <label for="exampleInputEmail1">R$ Valor</label>
                            <input type="Text" class="form-control" id="valor" readonly aria-describedby="emailHelp" placeholder="Valor do Produto">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group" >
                            <label for="exampleInputEmail1">Qtde</label>
                            <input type="Text" class="form-control" id="qtde" aria-describedby="emailHelp" placeholder="Qtde do Produto">
                </div>
            </div>

            <div class="col-2">
                <div class="form-group" >
                            <label for="exampleInputEmail1">Total</label>
                            <input type="Text" class="form-control" id="total" readonly aria-describedby="emailHelp" placeholder="Total">
                </div>
            </div>
            <div class="col-3">
                <label for="exampleInputEmail1">&nbsp;</label>
                <button type="submit" class="btn btn-primary"  style="margin-top: 31px;">Adicionar</button>
            </div>
          
        </div>
        </form>

        @if(count($itens) > 0)
      
        <table id="pedidosItens"  style="width:100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome Produto</th>
                        <th>Valor</th>
                        <th>Qtde</th>
                        <th>Total</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $i)
                        <tr>
                            <td>{{$i->id}}</td>
                            <td>{{$i->nomeProduto}}</td>
                            <td>{{$i->valor}}</td>
                            <td>{{$i->produtoQtde}}</td>
                            <td>{{$i->valorTotal}}</td> 
                            <td><a class="button btn btn-danger removePedidoIten" idItem ="{{$i->id}}" idPedido="{{$i->idPedido}}" href="javascript:void(0)">Excluir</a></td>
                     
                        </tr>
                    @endforeach
                <tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Nome Produto</th>
                        <th>Valor</th>
                        <th>Qtde</th>
                        <th>Total</th>
                        <th>Excluir</th>
                    </tr>
                </tfoot>
</table>

        @endif
       

        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="js/bootstrap-maskinput.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){
        $("#pedidosItens").DataTable();
    });
     $("#pedidoForm").on("submit", function (e) {

            e.preventDefault()
            var cliente_id = $("#cliente_Id").val();
            var pdId = $("#exampleFormControlSelect1").val();
            var pQtde = $("#qtde").val();
            var pValor = $("#valor").val();
            var pTotal = $("#total").val();

            if($("#id").val() !== undefined){
                var id = "&idPedido=" + $("#id").val();
            }else {
                var id = "";
            }

            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/novoPedido',
                    dataType: 'json',
                    data:"cliente_id=" + cliente_id +  "&id=" + pdId + "&produtoQtde=" + pQtde  + "&valor=" + pValor + "&total=" + pTotal + id,
                    success: function(data) {
                        alert("Item inserido com sucesso!");
                        console.log(data)
                        location.href = "{{asset('/')}}pedido/"+data.pedidoId+"/"+cliente_id;
                    },
                });
        });

        $(".removePedidoIten").click(function(){
           var idPedido = $(this).attr("idPedido");
           var idItem = $(this).attr("idItem");
           var cliente_id = $("#cliente_Id").val();

            $(this).attr("idItem");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/removePedidoIten',
                    dataType: 'json',
                    data:"idPedido=" + idPedido +  "&IdItem=" + idItem ,
                    success: function(data) {
                        alert("Item Excluido com sucesso!");
                        console.log(data)
                        location.href = "{{asset('/')}}pedido/"+ idPedido +"/"+cliente_id;
                    },
                });
        });

        $("#status").click(function(){
           var idPedido = $("#id").val();
           var cliente_id = $("#cliente_Id").val();
           var status = $(this).val();

            $(this).attr("idItem");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/alteraStatusPedido',
                    dataType: 'json',
                    data:"idPedido=" + idPedido + "&status=" + status ,
                    success: function(data) {
                        alert("Status alterado com sucesso!");
                        console.log(data)
                        location.href = "{{asset('/')}}pedido/"+ idPedido +"/"+cliente_id;
                    },
                });
        });
    
        $("#exampleFormControlSelect1").change(function(){
            
            pdId = $(this).val();
 
            if(pdId !== ""){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: "POST",
                        url: '/produtos',
                        dataType: 'json',
                        data:"produto_id=" + pdId,
                        success: function(data) {
                        // alert("Item inserido com sucesso!");
                            $("#valor").val(data['data'][0].valor);
                        },
                    });
            }
        });

        $("#qtde").change(function(){
            var total = $(this).val() * $("#valor").val();
            $("#total").val(total);
            
        });
</script>



@endsection