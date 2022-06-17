@extends('layouts.gafa')
@section('contentSection1')

<hr>
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Clientes</li>
    </ol>
    </nav>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-11">
            <a class="button btn btn-primary addCliente" data-toggle="modal" idItem ="0" data-target="#exampleModalLong">Adicionar</a>
            <table id="clientes"  style="width:100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Editar</th>
                        <th>Pedidos</th>
                        <th>Novo Pedido</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($clientes as $cl)
                    <tr>
                        <td>{{$cl->id}}</td>
                        <td>{{$cl->nomeCliente}}</td>
                        <td>{{$cl->CPF}}</td>
                        <td>{{$cl->Email}}</td>
                        
                     
                        <td><a class="button btn btn-primary editarCliente" data-toggle="modal" idItem="{{$cl->id}}" data-target="#exampleModalLong">Editar</a></td>
                        <td><a class="button btn btn-success" href="/pedidos/{{$cl->id}}">Pedidos</a></td>
                        <td><a class="button btn btn-info" href="/pedido/{{$cl->id}}">Novo Pedido</a></td>
                        <td><a class="button btn btn-danger excluirCliente" href="javascript:void(0)" idItem="{{$cl->id}}">Excluir</a></td>
                    </tr> 
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Editar</th>
                        <th>Pedidos</th>
                        <th>Novo Pedido</th>
                        <th>Excluir</th>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="clienteForm" class="needs-validation" >
                <input type="hidden" class="form-control" id="idcliente" value="" >
                <div class="form-group" >
                    <label for="exampleInputEmail1">Nome do Cliente</label>
                    <input type="Text" class="form-control" id="nomeCliente" aria-describedby="emailHelp" placeholder="Nome do Cliente" required>
                   
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">CPF</label>
                    <input type="Text" class="form-control" id="cpf" placeholder="CPF" required>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="seunome@seuemail.com.br" required>
                </div>
               
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bclickootstrap4.min.js" crossorigin="anonymous"></script>
<script src="{{asset('/')}}js/bootstrap-maskinput.js" crossorigin="anonymous"></script>

<script>

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
    })();

    $(document).ready(function () {
    
        $("#cpf").mask("000.000.000-00");
        

        $( ".editarCliente" ).click(function(){
           var cliente_id =  $(this).attr("idItem");
           
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/clientes',
                    dataType: 'json',
                    data: {
                        'cliente_id': cliente_id,
                    },
                    success: function(data) {
                       
                        $("#cpf").val(data['data'][0].CPF);
                        $("#nomeCliente").val(data['data'][0].nomeCliente);
                        $("#email").val(data['data'][0].Email);
                        $('#idcliente').val(data['data'][0].id);

                    },
                });
        });

        $("#clienteForm").on("submit", function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/clientesAddUpt',
                    dataType: 'json',
                    data: "cpf="+ $("#cpf").val() + "&nomecliente=" + $("#nomeCliente").val() + "&email=" + $("#email").val() + "&cliente_id=" + $("#idcliente").val(),
                    success: function(data) {
                        alert("cliente adicionado com sucesso!");
                        location.href = "/clientes";
                    },
            });
        });
        
        $(".excluirCliente").click(function(){
            var cliente_id =  $(this).attr("idItem");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/clientesDel',
                    dataType: 'json',
                    data:"cliente_id=" + cliente_id,
                    success: function(data) {
                        alert("cliente excluido com sucesso!");
                        location.href = "/clientes";
                    },
                });
        });

        $( ".addCliente" ).click(function(){ //so chama o modal

            $("#cpf").val("");
            $("#nomeCliente").val("");
            $("#email").val("");
            $('#idcliente').val("");
        });

    });

       

</script>
@endsection