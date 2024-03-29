@extends('adminlte::page')

@section('title', 'Asignar (Cocina)')

@section('content_header')

@stop

@section('content')
    <h1>Pedidos por asignar (cocina)</h1>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            pedidos = [];
            $.ajax({
                url: "getpedidosasignar",
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
                    var pedidoInfo = $('<div class="csspedido">').html('<strong >Pedido #' + pedido.pedido +
                        '</strong>');
                    var fechaHora = $('<div style = "color:#6da6cb">').text('Fecha: ' + pedido.fecha);
                    var direccion = $('<div>').text('Dirección: ' + pedido.direccion);
                    var contacto = $('<div style = "color:#6da6cb">').text('Contacto: ' + pedido.numero +
                        " " + pedido.cliente);
                    // var valida = $('<div style = "color:#6da6cb">').text('Valida: ' + pedido.valida);
                    var button = $('<button onClick="asignarRepartidor(\'' + pedido.pedido + '\', \'' + pedido.asg + '\')">').addClass('btn btn-primary').text('Asignar');
                    var button2 = $('<button onClick="verDetalle(\'' + pedido.descripcion + '\', \'' +pedido.pedido + '\')">').addClass('btn btn-info').text('Ver detalle');

                    info.append(pedidoInfo, button2, button);
                    listItem.append(info, fechaHora, direccion, contacto);
                    $('#pedido-list').append(listItem);
                });
            }

            generarLista(pedidos);

            $('#search-input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                var filteredPedidos = pedidos.filter(function(pedido) {
                    return pedido.pedido.toLowerCase().includes(searchTerm);
                });
                generarLista(filteredPedidos);
            });
        });

        function verDetalle(detalle, numero_pedido) {
            Swal.fire({
                title: 'Detalle del Pedido #' + numero_pedido,
                html: '<p>' + detalle + '</p>',
                confirmButtonText: 'Cerrar'
            });
        }

        function asignarRepartidor(pedido,asg) {
            // Realizar la consulta AJAX al backend para obtener la lista de nombres
            $.ajax({
                url: 'repartidoresname', // Ruta al backend que devuelve la lista de nombres
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Crear el contenido HTML para la lista de nombres
                    var namesList = $('<ul id="repartidores-list" class="list-group">');

                    var info = $('<div>').addClass('d-flex justify-content-between align-items-center');
                    response.forEach(function(element) {
                        var info = $('<div>').addClass(
                            'd-flex justify-content-between align-items-center');
                        var listItem = $('<li>').addClass('list-group-item');
                        var repartidor = $('<div style = "color:#78a729">').text('Repartidor: ' +
                            element.name);
                        var button = $('<button onClick="asignar(\'' + element.id + '\', \'' + pedido + '\', \'' + element.name + '\', \'' + asg + '\')">').addClass('btn btn-primary').text('Asignar');
                        info.append(repartidor, button);
                        listItem.append(info);
                        namesList.append(listItem);
                    });


                    // Mostrar el pop-up de SweetAlert2
                    Swal.fire({
                        title: 'Lista de Nombres',
                        html: namesList,
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: false,
                        showConfirmButton: false,
                        preConfirm: function() {
                            // Lógica para realizar la asignación
                            // Puedes hacer otra llamada AJAX aquí o ejecutar cualquier otra acción necesaria
                            return new Promise(function(resolve) {
                                // Simulación de una llamada AJAX con un retardo de 2 segundos
                                setTimeout(function() {
                                    resolve();
                                }, 2000);
                            });
                        },
                        allowOutsideClick: false
                    }).then(function(result) {
                        // Si el botón "Asignar" fue clicado y la asignación fue exitosa
                        if (result.isConfirmed) {
                            Swal.fire('Asignación Exitosa', 'La asignación se realizó correctamente.',
                                'success');
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Manejar el error en caso de que la consulta AJAX falle
                    Swal.fire('Error', 'Ocurrió un error al obtener la lista de nombres.', 'error');
                }
            });
        }


        function asignar(id, pedido, nombre,asg) {


            mensajeHTML = 'Se asignará la orden ' + pedido + ' al repartidor ' + nombre;
            // Mostrar el pop-up de SweetAlert2
            Swal.fire({
                title: 'Confirmar asignación',
                html: mensajeHTML,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true, // Mostrar un spinner mientras se realiza la petición
                showConfirmButton: true,
                confirmButtonText: "Asignar",
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        // Realizar la petición AJAX aquí
                        $.ajax({
                            url: 'asignarrepartidorback',
                            type: 'GET', // O GET según tu necesidad
                            data: {
                                "iddelivery" :id,
                                "pedidio":pedido,
                                "asg":asg
                            },
                            success: function(response) {
                                setTimeout(function() {
                                  window.location.reload();
                                }, 3000); //
                                console.log("Se asignó correctamente");
                                resolve(); // Resuelve la promesa si la asignación fue exitosa
                            },
                            error: function(error) {
                                console.error("Error en la asignación", error);
                                reject("Hubo un error en la asignación"); // Rechaza la promesa en caso de error
                            }
                        });
                    });
                },
                allowOutsideClick: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire('Asignación Exitosa', 'La asignación se realizó correctamente.', 'success');
                }
            }).catch(function(error) {
                Swal.fire('Error', error, 'error');
            });



        }
    </script>
@stop
