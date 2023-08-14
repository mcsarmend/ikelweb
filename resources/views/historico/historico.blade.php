@extends('adminlte::page')

@section('title', 'Historico')

@section('content_header')

@stop

@section('content')
    <h1>Historico</h1>
    <div class="container">
        <h2>Lista de Pedidos</h2>

        <div class="form-group">
            <input id="search-input" type="text" class="form-control" placeholder="Buscar por número de pedido">
        </div>

        <ul id="pedido-list" class="list-group"></ul>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/ap.css">
    <style>
        /* Estilos adicionales para el texto debajo del navbar */
        .container {
            margin-top: 50px;
        }

        .list-group-item {
            padding: 12px;
            border: 10px solid #9ec3dc;
        }

        .csspedido{
            color:#78a729;
            font-size: 24px;
        }

    </style>

@stop

@section('js')

    <script>
        $(document).ready(function() {
            pedidos=[];
            $.ajax({
                url: "gethistoricorders",
                method: "GET",
                dataType: "JSON",
                data: {},
                success: function(data) {
                    console.log(data);
                    pedidos=data;
                    generarLista(pedidos);
                }
            });


            function generarLista(pedidos) {
                $('#pedido-list').empty();

                pedidos.forEach(function(pedido) {
                    var listItem = $('<li>').addClass('list-group-item');
                    var info = $('<div>').addClass('d-flex justify-content-between align-items-center');
                    var pedidoInfo = $('<div class="csspedido">').html('<strong >Pedido #' + pedido.pedido + '</strong>');
                    var fechaHora = $('<div style = "color:#6da6cb">').text('Fecha: ' + pedido.fecha );
                    var direccion = $('<div>').text('Dirección: ' + pedido.direccion);
                    var contacto = $('<div style = "color:#6da6cb">').text('Contacto: ' + pedido.numero + " " + pedido.cliente);
                    // var valida = $('<div style = "color:#6da6cb">').text('Valida: ' + pedido.valida);
                    var repartidor = $('<div style = "color:#78a729">').text('Repartidor: ' + pedido.repartidor);
                    // var button = $('<button>').addClass('btn btn-primary').text('Aceptar');

                    // info.append(pedidoInfo, button);
                    info.append(pedidoInfo);
                    listItem.append(info, fechaHora, direccion, contacto,  repartidor);
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
    </script>
@stop
