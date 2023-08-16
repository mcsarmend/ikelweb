@extends('adminlte::page')

@section('title', 'En ruta')

@section('content_header')

@stop

@section('content')
    <h1>Pedidos en ruta</h1>
    <div class="container">
        <h2>Lista de Pedidos</h2>

        <div class="form-group">
            <input id="search-input" type="text" class="form-control" placeholder="Buscar por n煤mero de pedido">
        </div>

        <ul id="pedido-list" class="list-group"></ul>


        <!-- Modal -->
        <div class="modal" id="mapModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubicaci贸n</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="pedido"></div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

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

        .csspedido {
            color: #78a729;
            font-size: 24px;
        }

        #map {
            height: 400px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBl0IgYJqu-RST8MQ_iIPjHWWcazxsO0KA&callback=initMap" async
        defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

    <script>
        $(document).ready(function() {
            pedidoabierto="";
            pedidos = [];
            $.ajax({
                url: "getpedidosruta",
                method: "GET",
                dataType: "JSON",
                data: {},
                success: function(data) {
                    console.log(data);
                    pedidos = data;
                    generarLista(pedidos);
                }
            });


            function generarLista(pedidos) {
                $('#pedido-list').empty();

                pedidos.forEach(function(pedido) {
                    var listItem = $('<li>').addClass('list-group-item');
                    var info = $('<div>').addClass('d-flex justify-content-between align-items-center');
                    var pedidoInfo = $('<div class="csspedido">').html('<strong >Pedido #' + pedido.pedido +
                        '</strong>');
                    var fechaHora = $('<div style = "color:#6da6cb">').text('Fecha: ' + pedido.fecha);
                    var direccion = $('<div>').text('Direcci贸n: ' + pedido.direccion);
                    var contacto = $('<div style = "color:#6da6cb">').text('Contacto: ' + pedido.numero +
                        " " + pedido.cliente);
                    // var valida = $('<div style = "color:#6da6cb">').text('Valida: ' + pedido.valida);
                    var repartidor = $('<div style = "color:#78a729">').text('Repartidor: ' + pedido
                        .repartidor);
                    var button = $('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">')
                                  .addClass('btn btn-primary')
                                  .text('Ver')
                                  .on('click', function() {
                                    // Obtener los valores de latitud y longitud de algún lugar
                                    var latitud = pedido.latitude;
                                    var longitud = -pedido.longitude;
                                    
                                    // Llamar a la función showmodalmap con los valores de latitud y longitud
                                    showmodalmap(latitud, longitud);
                                  });


                    info.append(pedidoInfo, button);
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

        function initMap(latitude,longitude) {
            
            var ubicacion = {
                lat: latitude,
                lng: longitude
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: ubicacion
            });
            var marker = new google.maps.Marker({
                position: ubicacion,
                map: map,
                title: 'Ubicaci贸n'
            });
        }
        function showmodalmap(latitud, longitud){
            console.log(latitud, longitud);
            longitud = longitud*-1;
            initMap(latitud, longitud);
        };



    </script>

@stop
