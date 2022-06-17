@extends('layouts.gafa')
@section('contentSection1')

<hr>
<div class="container">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Produtos</li>
    </ol>
    </nav>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-11">
            <a class="button btn btn-primary addProduto" data-toggle="modal" idItem ="0" data-target="#exampleModalLong">Adicionar</a>
            <table id="produtos" style="width:100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>R$</th>
                        <th>Codigo de Barras</th>
                
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($produtos as $cl)
                    <tr>
                        <td>{{$cl->id}}</td>
                        <td>{{$cl->nomeProduto}}</td>
                        <td>{{$cl->valor}}</td>
                        <td>{{$cl->codigoBarras}}</td>
             
                        <td><a class="button btn btn-primary editarProduto" data-toggle="modal" idItem ="{{$cl->id}}" data-target="#exampleModalLong">Editar</a></td>
                        <td><a class="button btn btn-danger deleteProduto" idItem ="{{$cl->id}}" href="javascript:void(0)">Excluir</a></td>
                    </tr> 
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>R$</th>
                        <th>Codigo de Barras</th>
       
                        <th>Editar</th>
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
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="produtoForm" class="needs-validation">
                <input type="hidden" class="form-control" id="idproduto" value="" >
                <div class="form-group" >
                    <label for="exampleInputEmail1">Nome de Produto</label>
                    <input type="Text" class="form-control" id="nomeproduto" aria-describedby="emailHelp" placeholder="Nome do Produto" required>
                    <div class="valid-feedback">ok!</div>
                   
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Valor R$</label>
                    <input type="Text" class="form-control" id="valor" placeholder="R$" required>
                    <div class="valid-feedback">ok!</div>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Codigo de Barras</label>
                    <input type="Text" class="form-control" id="bcd" placeholder="00000000000" required>
                    <div class="valid-feedback">ok!</div>
                </div>
               
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="{{asset('/')}}js/bootstrap-maskinput.js" crossorigin="anonymous"></script>
<script src="{{asset('/')}}js/jquery.maskMoney.min.js" type="text/javascript"></script>


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
        
        $("#valor").maskMoney({prefix:'R$ ', allowNegative: true,  decimal:'.', affixesStay: false});
        $("#bcd").mask("0000000000000");
        $('#produtos').DataTable();

        $( ".editarProduto" ).click(function(){
           var produto_id =  $(this).attr("idItem");
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/produtos',
                    dataType: 'json',
                    data: {
                        'produto_id': produto_id,
                    },
                    success: function(data) {
                       
                        $("#bcd").val(data['data'][0].codigoBarras);
                        $("#nomeproduto").val(data['data'][0].nomeProduto);
                        $("#valor").val(data['data'][0].valor);
                        $('#idproduto').val(data['data'][0].id);

                    },
                });
        });

        $("#produtoForm").on("submit", function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           
            $.ajax({
                    type: "POST",
                    url: '/produtosAddUpt',
                    dataType: 'json',
                    data: "codigoBarras="+ $("#bcd").val() + "&nomeproduto=" + $("#nomeproduto").val() + "&idproduto=" + $("#idproduto").val() +  "&valor=" +  $("#valor").val(),
                    success: function(data) {
                        alert("Produto atualizado com sucesso!");
                        location.href = "/produtos";
                },
            });
        });
        
        $(".deleteProduto").click(function(){

            var produto_id =  $(this).attr("idItem");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/produtosDel',
                    dataType: 'json',
                    data:"idproduto=" + produto_id,
                    success: function(data) {
                        alert("Produto excluido com sucesso!");
                        location.href = "/produtos";
                    },
                });
        });

        $( ".addProduto" ).click(function(){ //so chama o modal
            $("#bcd").val("");
            $("#nomeproduto").val("");
            $("#valor").val("");
            $('#idproduto').val("");
        /*/   

           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '/produtosAddUpt',
                    dataType: 'json',
                    data:"codigoBarras="+ $("#bcd").val() + "&nomeproduto=" + $("#nomeproduto").val() +  "&valor=" +  $("#valor").val(),
                    success: function(data) {
                        alert("Produto adicionado com sucesso!");
                        location.href = "/produtos";
                    },
                });*/
        });

    });

       




</script>
@endsection