<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class repartidoresController extends Controller
{
        public function repartidores()
        {
            return view('repartidores.repartidores');
        }
        public function repartidoresname()
        {
            $repartidores = DB::table("users")->select("name","email")
            ->where("type",2)
            ->get();
            return $repartidores;
        }
}
