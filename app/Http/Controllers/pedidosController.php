<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
    public function getpedidosasignar(Request $request)
    {
        $data = DB::table('assignations AS a')
            ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ', 'o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor')
            ->leftJoin('orders AS o', 'a.order_id', '=', 'o.id')
            ->leftJoin('address AS ad', 'ad.iduser', '=', 'o.client_number')
            ->leftJoin('cat_municipios AS m', 'ad.idmunicipio', '=', 'm.idmunicipio')
            ->leftJoin('cat_estados AS e', 'ad.idestado', '=', 'e.idestado')
            ->leftJoin('users AS cliente', 'cliente.id', '=', 'o.client_number')
            ->leftJoin('users AS rep', 'rep.id', '=', 'a.delivery')
            ->where('a.status', 2)
            ->get();
        return $data;

    }
    public function getpedidosruta(Request $request)
    {
        $data = DB::table('assignations AS a')
            ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ', 'o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor')
            ->leftJoin('orders AS o', 'a.order_id', '=', 'o.id')
            ->leftJoin('address AS ad', 'ad.iduser', '=', 'o.client_number')
            ->leftJoin('cat_municipios AS m', 'ad.idmunicipio', '=', 'm.idmunicipio')
            ->leftJoin('cat_estados AS e', 'ad.idestado', '=', 'e.idestado')
            ->leftJoin('users AS cliente', 'cliente.id', '=', 'o.client_number')
            ->leftJoin('users AS rep', 'rep.id', '=', 'a.delivery')
            ->where('a.status', 1)
            ->get();
        return $data;

    }
    public function getpedidospercances(Request $request)
    {
        $data = DB::table('assignations AS a')
            ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ', 'o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor')
            ->leftJoin('orders AS o', 'a.order_id', '=', 'o.id')
            ->leftJoin('address AS ad', 'ad.iduser', '=', 'o.client_number')
            ->leftJoin('cat_municipios AS m', 'ad.idmunicipio', '=', 'm.idmunicipio')
            ->leftJoin('cat_estados AS e', 'ad.idestado', '=', 'e.idestado')
            ->leftJoin('users AS cliente', 'cliente.id', '=', 'o.client_number')
            ->leftJoin('users AS rep', 'rep.id', '=', 'a.delivery')
            ->where('a.status', 1)
            ->orWhere('a.status', 0)
            ->get();
        return $data;

    }

    public function vernota(Request $request)
    {
        try {

            $order = strval($request->pedido);
            $data = DB::table('binnacle')
                ->select('note')
                ->where('order_id', $order)
                ->get();
            return $data;

        } catch (\Throwable $th) {
            return $th;
        };

    }

    public function repartidoresname(Request $request)
    {
        $posts = DB::table("users as u")
            ->select("u.id", "u.name", "u.email")
            ->leftJoin('disponibility AS d', 'd.user_id', '=', 'u.id')
            ->where("u.type", 2)
            ->where("d.active", 0)
            ->get();
        $data = [];
        foreach ($posts as $post) {
            $nestedData['name'] = $post->name;
            $nestedData['id'] = Crypt::encrypt($post->id);
            $data[] = $nestedData;
        }
        return $data;
    }
    public function asignarrepartidor(Request $request)
    {
        $delivery = DB::table("disponibility")
        ->select("user_id","active")
        ->get();
        $array = json_decode($delivery, true);
        $longitud = count($array);
        $del = 0;
        $t = 10;
        foreach ($array as $element) {
            if($element["active"] == 0){
                    $del = $element["user_id"];
                    break;
                }
        }
        if($del!=0){
            // Insertar en las asignaciones
            DB::table("assignations")->insert([
                "user_id" => $request->client_number,
                "order_id" => $id['id'],
                "status" => "0",
            ]);
            // Cambiar estatus del conductor
            DB::update('UPDATE disponibility SET active = ? WHERE user_id = ?', [1, $del]);
            DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [1,0]);

            // asignar el conductor a la orden
            DB::update('UPDATE assignations SET delivery = ? WHERE order_id = ?', [$del, $id['id']]);

            // Cambiar el estatus de la orden
            DB::update('UPDATE assignations SET status = ? WHERE order_id = ?', [1, $id['id']]);

        }else{
            // Insertar en las asignaciones
            DB::table("assignations")->insert([
                "user_id" => $request->client_number,
                "order_id" => $id['id'],
                "status" => "2",
            ]);
        }
    }
    public function aux1(Request $request)
    {}
    public function aux2(Request $request)
    {}
    public function aux3(Request $request)
    {}
}
