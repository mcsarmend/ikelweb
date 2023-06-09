@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <h1>Repartidores</h1>


    <div class="form-group">
        <input id="search-input" type="text" class="form-control" placeholder="Buscar repartidor">
    </div>

    <ul id="pedido-list" class="list-group"></ul>

@stop

@section('css')
    <link rel="stylesheet" href="/css/ap.css">
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

        .csspedido {
            color: #78a729;
            font-size: 24px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            repartidores=[];
            $.ajax({
                url: "repartidoresname",
                method: "GET",
                dataType: "JSON",
                data: {},
                success: function(data) {
                    console.log(data);
                    repartidores=data;
                    generarLista(repartidores);
                }
            });


            function generarLista(repartidores) {
                $('#pedido-list').empty();

                repartidores.forEach(function(repartidor) {
                    var listItem = $('<li>').addClass('list-group-item');
                    var info = $('<div>').addClass('d-flex justify-content-between align-items-center');
                    var rep = $('<div class="csspedido">').html('<strong >Repartidor: ' + repartidor.name +'</strong>');
                    var email = $('<div style = "color:#6da6cb">').text('Correo: ' + repartidor.email);


                    info.append(rep);
                    listItem.append(info, email);
                    $('#pedido-list').append(listItem);
                });
            }

            generarLista(repartidores);

            $('#search-input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                var filteredPedidos = repartidores.filter(function(repartidores) {
                    return repartidores.name.toLowerCase().includes(searchTerm);
                });
                generarLista(filteredPedidos);
            });
        });
    </script>
@stop
