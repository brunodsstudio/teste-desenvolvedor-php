@extends('layouts.gafa')
@section('contentSection1')

<hr>
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Pedidos</li>
    </ol>
    </nav>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-11">
            <table id="clientes"  style="width:100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome Cliente</th>
                        <th>Data do pedido</th>
                        <th>Status</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pedidos as $p)
                    <tr>
                        <td>{{$p->id}}</td>
                        <td>{{$p->nomeCliente}}</td>
                        <td>{{$p->dtPedido}}</td>
                        <td>{{$p->status}}</td>
                        <td><a class="button btn btn-primary "  idItem="{{$p->id}}" href="{{asset('/pedido').'/'.$p->id.'/'.$p->clienteId}}">Editar</a></td>
                        <td><a class="button btn btn-danger removePedido" href="javascript:void(0)" idItem="{{$p->id}}">Excluir</a></td>
                    </tr> 
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th>Id</th>
                        <th>Nome Cliente</th>
                        <th>Data do pedido</th>
                        <th>Status</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>



<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="js/bootstrap-maskinput.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){
        $("#clientes").DataTable();

        $(".removePedido").click(function(){

           var idItem = $(this).attr("idItem");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/removePedido',
                    dataType: 'json',
                    data:"&IdPedido=" + idItem ,
                    success: function(data) {
                        alert("Pedido Excluido com sucesso!");
                        console.log(data)
                        document.location.reload(true);
                    },
                });
        });


    })
</script>

@endsection