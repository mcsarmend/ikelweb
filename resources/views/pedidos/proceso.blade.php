@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <h1>Proceso</h1>
    <div>
        Dashboard
    </div>

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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@stop
