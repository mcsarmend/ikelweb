<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estado;
use DB;
class AuxController extends Controller
{
    public function estados(Request $request){
        $estados = DB::table('cat_cps')->select('cp','colonia','idmunicipio','idestado')
        ->get();
        return $estados;
    }
}
