<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pedidosController extends Controller
{

        public function proceso()
        {
            return view('pedidos.proceso');
        }
        public function asignar()
        {
            return view('pedidos.asignar');
        }
        public function ruta()
        {
            return view('pedidos.ruta');
        }
        public function percances()
        {
            return view('pedidos.percances');
        }
}
