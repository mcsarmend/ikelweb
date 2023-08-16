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
            ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ', 'o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor','a.id as asg')
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
            ->select('o.internal_id as pedido', 'o.order_description as descripcion', 'o.cost as costo ', 'o.created_at as fecha', DB::raw('CONCAT(ad.cp, " ", ad.calle, " ", ad.numero, " ", ad.colonia, " ", m.municipio, " ", e.estado) AS direccion'), 'cliente.number as numero', 'cliente.name as cliente', 'rep.name as repartidor', 'cp.latitude', 'cp.longitude')
            ->leftJoin('orders AS o', 'a.order_id', '=', 'o.id')
            ->leftJoin('address AS ad', 'ad.iduser', '=', 'o.client_number')
            ->leftJoin('cat_municipios AS m', 'ad.idmunicipio', '=', 'm.idmunicipio')
            ->leftJoin('cat_estados AS e', 'ad.idestado', '=', 'e.idestado')
            ->leftJoin('users AS cliente', 'cliente.id', '=', 'o.client_number')
            ->leftJoin('users AS rep', 'rep.id', '=', 'a.delivery')
            ->leftJoinSub(function($query) {
                $query->select('latitude', 'longitude', 'order_id') // Asegúrate de seleccionar 'order_id'
                    ->from('current_positions')
                    ->orderByDesc('id') // Ordenar de manera descendente por el id
                    ->limit(1);
            }, 'cp', function ($join) { // Agregar el tercer argumento para la condición de unión
                $join->on('cp.order_id', '=', 'o.internal_id'); // Cambiar por la columna correcta
            })
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
    public function asignarrepartidorback(Request $request)
    {


        try {
            
            // Obtenci贸n de datos del delivery
            
            $iddelivery = Crypt::decrypt($request->iddelivery);

            // Obtencion de datos de la orden 
            
            $posts = DB::table("orders as o")
            ->select("o.client_number","o.id")
            ->where("o.internal_id", $request->pedidio)
            ->first();
    
            $client_number = $posts->client_number;
            $order_id = $posts->id;
            //Obteni贸n de datos de asignacion
            
            $asgid = $request->asg;
            
            
            // Cambiar estatus del conductor
            DB::update('UPDATE disponibility SET active = ? WHERE user_id = ?', [1, $iddelivery]);
            DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [1,0]);
    
            // asignar el conductor a la orden
            // DB::update('UPDATE assignations SET delivery = ? WHERE order_id = ?', [$iddelivery, $order_id]);
            
            
            // Asignar el conductor a la orden con otro criterio en el WHERE
            DB::update('UPDATE assignations SET delivery = ?, status = ? WHERE id = ?', [$iddelivery, "1", $asgid]);

            
            return response()->json(["success" => "Pedidio asignado correctamente",]);
            
        } catch (\Throwable $th) {
            return $th;
        }

    }
    // setnote
    public function aux1(Request $request)
    {
        
         try {

            DB::table('binnacle')->insert([
                'order_id' => $request->order_id,
                'note' => $request->note
            ]);
            return response()->json(["success" => "Nota agregada correctamente"]);
            
        } catch (\Throwable $th) {
            return $th;
        };
    }
    //setcurrentpositions
    public function aux2(Request $request)
    {
         try {

            DB::table('current_positions')->insert([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'delivery_id' => $request->delivery_id,
                'order_id' => $request->order_id
            ]);
            return response()->json(["success" => "posicion agregada correctamente"]);
            
        } catch (\Throwable $th) {
            return $th;
        };
        
    }
    // getcurrentposition
    public function aux3(Request $request)
    {
        try {
            $posts = DB::table("current_positions")
                ->select("latitude", "longitude")
                ->where("order_id", $request->pedidio)
                ->orderByDesc("id") // Agrega el ORDER BY descendente por la columna "id"
                ->first();
            $data = $posts;
            return response()->json(["success" => $data]);
            
        } catch (\Throwable $th) {
            return $th;
        };
    }
}
