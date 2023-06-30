@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <h1>Percances</h1>
    <div class="container">
        <h2>Lista de Pedidos</h2>

        <div class="form-group">
            <input id="search-input" type="text" class="form-control" placeholder="Buscar por número de pedido">
        </div>

        <ul id="pedido-list" class="list-group"></ul>
    </div>
    <br><br><br><br>
@stop

@section('css')
    <link rel="stylesheet" href="/css/ap.css">
    <style>
        /* Estilos adicionales para el texto debajo del navbar */
        .content {
            padding: 20px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            pedidos = [];
            $.ajax({
                url: "getpedidospercances",
                method: "GET",
                dataType: "JSON",
                data: {},
                success: function(data) {
                    pedidos = data;
                    generarLista(pedidos);
                }
            });


            function generarLista(pedidos) {
                $('#pedido-list').empty();

                pedidos.forEach(function(pedido) {
                    var listItem = $('<li>').addClass('list-group-item');
                    var info = $('<div>').addClass('d-flex justify-content-between align-items-center');
                    var pedidoInfo = $('<div class="csspedido">').html('<strong >Pedido # ' + pedido
                        .pedido + '</strong>');
                    var fechaHora = $('<div style = "color:#6da6cb">').text('Fecha: ' + pedido.fecha);
                    var direccion = $('<div>').text('Dirección: ' + pedido.direccion);
                    var contacto = $('<div style = "color:#6da6cb">').text('Contacto: ' + pedido.numero +
                        " " + pedido.cliente);
                    // var valida = $('<div style = "color:#6da6cb">').text('Valida: ' + pedido.valida);
                    var repartidor = $('<div style = "color:#78a729">').text('Repartidor: ' + pedido
                        .repartidor);
                    b = '<button onClick="vernota(\'' + pedido.pedido + '\')">';
                    var button = $(b).addClass('btn btn-primary').text('Ver notas');

                    info.append(pedidoInfo, button);
                    //info.append(pedidoInfo);
                    listItem.append(info, fechaHora, direccion, contacto, repartidor);
                    $('#pedido-list').append(listItem);
                });
            }

            generarLista(pedidos);

            $('#search-input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                var filteredPedidos = pedidos.filter(function(pedido) {
                    return pedido.numero.toLowerCase().includes(searchTerm);
                });
                generarLista(filteredPedidos);
            });
        });


        function vernota(pedido) {
            console.log(pedido);

            $.ajax({
                url: "vernota",
                method: "GET",
                dataType: "JSON",
                data: {
                    "pedido": pedido.toString()
                },
                success: function(data) {
                    if(data.length ==0){
                        Swal.fire({
                        title: 'Nota del repartidor',
                        text: "El repartidor no añadio notas a esta entrega",
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    })
                    }else{
                        Swal.fire({
                        title: 'Nota del repartidor',
                        text: data[0].note,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    })
                    }



                }
            });
        }
    </script>
@stop
