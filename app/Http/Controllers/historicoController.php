<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class historicoController extends Controller
{
    public function historico()
    {
        return view('historico.historico');
    }
    public function gethistoricorders(Request $request){
        $data = DB::table('assignations AS a')
        ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ','o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor')
        ->leftJoin('orders AS o', 'a.order_id', '=', 'o.id')
        ->leftJoin('address AS ad', 'ad.iduser', '=', 'o.client_number')
        ->leftJoin('cat_municipios AS m', 'ad.idmunicipio', '=', 'm.idmunicipio')
        ->leftJoin('cat_estados AS e', 'ad.idestado', '=', 'e.idestado')
        ->leftJoin('users AS cliente', 'cliente.id', '=', 'o.client_number')
        ->leftJoin('users AS rep', 'rep.id', '=', 'a.delivery')
        ->where('a.status', 0)
        ->get();
        return $data;
    }
}

