<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AuxController extends Controller
{
    public function estados(Request $request)
    {
        $estados = DB::table("cat_estados")->select("idestado", "estado")->get();
        return $estados;
    }
    public function municipios(Request $request)
    {
        $estados = DB::table("cat_municipios")->select("idmunicipio", "municipio")
            ->where("idestado", $request->idestado)
            ->get();
        return $estados;
    }
    public function cps(Request $request)
    {
        $cps = DB::table("cat_cps")->select("idcp", "cp", "colonia")
            ->where("idmunicipio", $request->idmunicipio)
            ->get();
        return $cps;
    }

    public function insertupdateaddress(Request $request)
    {

        $data = [];
        $data = DB::table("address")
            ->select("idaddress")
            ->where("iduser", $request->userId)
            ->get();
        if ($data == "[]") {
            DB::table("address")->insert([
                "iduser" => $request->userId,
                "idmunicipio" => $request->city,
                "idestado" => $request->state,
                "cp" => $request->cp,
                "colonia" => $request->suburb,
                "calle" => $request->street,
                "numero" => $request->number,
            ]);
            return response()->json([
                "success" => "Se guard贸 el cliente correctamente!",
            ]);
        } else {
            try {
                $update = Address::where(
                    "idaddress",
                    $data[0]->idaddress
                )->update([
                    "iduser" => $request->userId,
                    "idmunicipio" => $request->city,
                    "idestado" => $request->state,
                    "cp" => $request->cp,
                    "colonia" => $request->suburb,
                    "calle" => $request->street,
                    "numero" => $request->number,
                ]);
                return response()->json([
                    "success" => "Se actualiz贸 el cliente correctamente!",
                ]);
            } catch (\Throwable $th) {
                return $th;
                $error = "No se pudo guardar la direcci贸n";
                return response()->json(["success" => $error]);
            }
        }
    }

    public function getaddress(Request $request)
    {
        $data = [];
        $data = DB::table("address")
            ->select("idaddress")
            ->where("iduser", $request->userId)
            ->get();
        if ($data == '[]') {
            $error = "Sin direcci贸n registrada";
            return response([
                'error' => $error,
            ], 422);
        } else {
            $data = DB::table("address as a")
                ->select("a.cp", "a.calle", "a.numero", "a.colonia", "m.municipio", "e.estado")
                ->leftjoin('cat_municipios as m', 'a.idmunicipio', '=', 'm.idmunicipio')
                ->leftjoin('cat_estados as e', 'a.idestado', '=', 'e.idestado')
                ->where("iduser", $request->userId)
                ->get();
            return response([
                'address' => $data[0],
            ], 200);
        }

    }
    public function getaddressbycp(Request $request)
    {

        $data = DB::table("cat_cps as cps")
            ->select("cps.cp", "cps.colonia", "m.municipio", "e.estado", "m.idmunicipio", "e.idestado")
            ->leftjoin('cat_municipios as m', 'm.idmunicipio', '=', 'cps.idmunicipio')
            ->leftjoin('cat_estados as e', 'cps.idestado', '=', 'e.idestado')
            ->where("cps.cp", $request->cp)
            ->get();
        return response([
            'address' => $data[0],
        ], 200);
    }
    public function getorders(Request $request)
    {
        $data = DB::table("assignations as a")
            ->select(
                "o.internal_id",
                "o.order_description",
                "o.cost",
                "o.lat_destiny",
                "o.lon_destiny",
                "a.status",
                "cp.latitude as current_latitude",
                "cp.longitude as current_longitude"
            )
            ->leftJoin('orders as o', 'a.order_id', '=', 'o.id')
            ->leftJoinSub(function ($query) {
                $query->select('latitude', 'longitude', 'order_id')
                    ->from('current_positions')
                    ->orderByDesc('id')
                    ->limit(1);
            }, 'cp', function ($join) {
                $join->on('o.internal_id', '=', 'cp.order_id');
            })
            ->where("a.user_id", $request->user_id)
            ->get();
        
        return response([
            'orders' => $data,
        ], 200);

    }
    public function setorders(Request $request)
    {
        $fechaActual = Carbon::now();
        $order = [];
        $delivery = [];
        define('i', 0);
        try {
            // Insertar orden
            DB::table("orders")->insert([
                "internal_id" => $request->internal_id,
                "client_name" => $request->client_name,
                "client_number" => $request->client_number,
                "order_description" => $request->order_description,
                "cost" => $request->cost,
                "lat_destiny" => $request->lat_destiny,
                "lon_destiny" => $request->lon_destiny,
                "created_at" => $fechaActual,
            ]);
            // Obtener el ID

            $order = DB::table("orders")
                ->select("id")
                ->where("client_number", $request->client_number)
                ->orderBy('id', 'desc')
                ->get();
            $array = json_decode($order, true);
            $id = $array[0];

            DB::table("assignations")->insert([
                "user_id" => $request->client_number,
                "order_id" => $id['id'],
                "status" => "2",
            ]);

// Valor 2 es un pedido por asignar

// valor 1 es un pedido en activo

// valor 0  es un pedido cerrado

            // // validar conductores disponibles
            // $delivery = DB::table("disponibility")
            // ->select("user_id","active")
            // ->get();
            // $array = json_decode($delivery, true);
            // $longitud = count($array);
            // $del = 0;
            // $t = 10;
            // foreach ($array as $element) {
            //     if($element["active"] == 0){
            //             $del = $element["user_id"];
            //             break;
            //         }
            // }
            // if($del!=0){
            //     // Insertar en las asignaciones
            //     DB::table("assignations")->insert([
            //         "user_id" => $request->client_number,
            //         "order_id" => $id['id'],
            //         "status" => "0",
            //     ]);
            //     // Cambiar estatus del conductor
            //     DB::update('UPDATE disponibility SET active = ? WHERE user_id = ?', [1, $del]);
            //     DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [1,0]);

            //     // asignar el conductor a la orden
            //     DB::update('UPDATE assignations SET delivery = ? WHERE order_id = ?', [$del, $id['id']]);

            //     // Cambiar el estatus de la orden
            //     DB::update('UPDATE assignations SET status = ? WHERE order_id = ?', [1, $id['id']]);

            // }else{
            //     // Insertar en las asignaciones
            //     DB::table("assignations")->insert([
            //         "user_id" => $request->client_number,
            //         "order_id" => $id['id'],
            //         "status" => "2",
            //     ]);
            // }
            return response()->json([
                "success" => "Se guardo la orden correctamente",
            ]);
        } catch (\Throwable $th) {
            return $th;
            $error = "No se pudo guardar la orden";
            return response()->json(["success" => $error]);
        }

    }
    public function aux1(Request $request)
    {

        $data = DB::table("assignations as a")
            ->select("o.internal_id", "o.order_description", "o.cost", "o.lat_destiny", "o.lon_destiny", "a.status", "u.number", "d.inprogress")
            ->leftjoin('orders as o', 'a.order_id', '=', 'o.id')
            ->leftjoin('users as u', 'u.id', 'o.client_number')
            ->leftjoin('disponibility as d', 'd.user_id', 'a.delivery')
            ->where("a.delivery", $request->user_id)
            ->where('a.status', 1)
            ->get();
        return response([
            'orders' => $data,
        ], 200);

        return "Hola1";
    }
    
    // empezar viaje
    public function aux2(Request $request)
    {

        try {
            // Cambiar del progreso
            DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [$request->status, $request->user_id]);
            // Cambiar el estatus de la orden
            DB::update('UPDATE assignations SET status = ? WHERE status = ? and delivery = ?', [$request->orderstatus, $request->status, $request->user_id]);
            // Cambiar el estatus del conductor
            DB::update('UPDATE disponibility SET active= ? WHERE user_id = ?', [$request->status, $request->user_id]);
            return response()->json([
                "success" => "Se actualizaron correctamente",
            ]);
        } catch (\Throwable $th) {
            return $th;
            $error = "No se pudo actualizar pedidos";
            return response()->json(["success" => $error]);
        }

    }
    
    // terminar viaje
    public function aux3(Request $request)
    {
        try {
            
            
            $order_id = DB::table("orders")
                ->select("id")
                ->where("internal_id", $request->order_id)
                ->orderBy('id', 'desc')
                ->value('id'); 

            
            // Cambiar del progreso
            DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [$request->status, $request->user_id]);
            
            // Cambiar el estatus de la orden
            DB::update('UPDATE assignations SET status = ? WHERE order_id = ? and delivery = ?', ["0", $order_id, $request->user_id]);
            // Cambiar el estatus del conductor
            DB::update('UPDATE disponibility SET active= ? WHERE user_id = ?', [$request->status, $request->user_id]);
            return response()->json([
                "success" => "Se actualizaron correctamente",
            ]);
        } catch (\Throwable $th) {
            return $th;
            $error = "No se pudo actualizar pedidos";
            return response()->json(["success" => $error . $th]);
        }
    }
}
