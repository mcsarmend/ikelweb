<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use DB;
class AuxController extends Controller
{
    public function estados(Request $request)
    {
        $estados = DB::table("cat_estados")->select("idestado","estado")->get();
        return $estados;
    }
    public function municipios(Request $request)
    {
        $estados = DB::table("cat_municipios")->select("idmunicipio","municipio")
        ->where("idestado",$request->idestado)
        ->get();
        return $estados;
    }
    public function cps(Request $request){
        $cps = DB::table("cat_cps")->select("idcp","cp","colonia")
        ->where("idmunicipio",$request->idmunicipio)
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
                "numero" => $request->number
            ]);
            return response()->json([
                "success" => "Se guardó el cliente correctamente!",
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
                    "numero" => $request->number
                ]);
                return response()->json([
                    "success" => "Se actualizó el cliente correctamente!",
                ]);
            } catch (\Throwable $th) {
                return $th;
                $error = "No se pudo guardar la dirección";
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
            if ($data=='[]') {
                $error = "Sin dirección registrada";
                return response([
                    'error' => $error
                ], 422);
            }else {
                $data = DB::table("address as a")
                ->select("a.cp","a.calle","a.numero","a.colonia","m.municipio","e.estado")
                ->leftjoin('cat_municipios as m','a.idmunicipio','=','m.idmunicipio')
                ->leftjoin('cat_estados as e','a.idestado','=','e.idestado')
                ->where("iduser", $request->userId)
                ->get();
                return response([
                    'address' => $data[0]
                ], 200);
            }

    }
    public function getaddressbycp(Request $request)
    {

        $data = DB::table("cat_cps as cps")
            ->select("cps.cp","cps.colonia","m.municipio","e.estado","m.idmunicipio","e.idestado")
            ->leftjoin('cat_municipios as m','m.idmunicipio', '=','cps.idmunicipio')
            ->leftjoin('cat_estados as e','cps.idestado','=','e.idestado')
            ->where("cps.cp", $request->cp)
            ->get();
            return response([
                'address' => $data[0]
            ], 200);
    }
    public function getorders(Request $request){
        $data = DB::table("assignations as a")
            ->select("o.internal_id","o.order_description","o.cost","o.lat_destiny","o.lon_destiny","a.status")
            ->leftjoin('orders as o','a.order_id', '=','o.id')
            ->where("a.user_id", $request->user_id)
            ->get();
        return response([
            'orders' => $data
        ], 200);
    }
    public function setorders(Request $request){
        $order = [];
        $delivery=[];
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
                "lon_destiny" => $request->lon_destiny
            ]);
                            // Obtener el ID
            $order = DB::table("orders")
            ->select("id")
            ->where("client_number", $request->client_number)
            ->orderBy('id', 'desc')
            ->get();
            $array = json_decode($order, true);
            $id = $array[0];



            // validar conductores disponibles
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
            return response()->json([
                "success" => "Se guardo la orden correctamente",
            ]);
        }  catch (\Throwable $th) {
            return $th;
            $error = "No se pudo guardar la orden";
            return response()->json(["success" => $error]);
        }

    }
    public function aux1(Request $request){

	    $data = DB::table("assignations as a")
            ->select("o.internal_id","o.order_description","o.cost","o.lat_destiny","o.lon_destiny","a.status","u.number","d.inprogress")
            ->leftjoin('orders as o','a.order_id', '=','o.id')
            ->leftjoin('users as u','u.id','o.client_number')
            ->leftjoin('disponibility as d','d.user_id','a.delivery')
            ->where("a.delivery", $request->user_id)
            ->where('a.status', 1)
            ->get();
            return response([
                'orders' => $data
            ], 200);

        return "Hola1";
    }
    public function aux2(Request $request){

        try {
	 	    // Cambiar del progreso
	    	DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [ $request->status,  $request->user_id]);
	    	// Cambiar el estatus de la orden
            DB::update('UPDATE assignations SET status = ? WHERE status = ? and delivery = ?' , [$request->orderstatus, $request->status, $request->user_id]);
                // Cambiar el estatus del conductor
            DB::update('UPDATE disponibility SET active= ? WHERE user_id = ?', [ $request->status,  $request->user_id]);
            return response()->json([
	            "success" => "Se actualizaron correctamente",
	        ]);
        }  catch (\Throwable $th) {
            return $th;
            $error = "No se pudo actualizar pedidos";
            return response()->json(["success" => $error]);
        }

    }
    public function aux3(Request $request){
        try {
	 	    // Cambiar del progreso
	    	DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [ $request->status,  $request->user_id]);
	    	// Cambiar el estatus de la orden
		    DB::update('UPDATE assignations SET status = ? WHERE status = ? and delivery = ?' , [$request->orderstatus, 1, $request->user_id]);
	        // Cambiar el estatus del conductor
		    DB::update('UPDATE disponibility SET active= ? WHERE user_id = ?', [ $request->status,  $request->user_id]);

            // Verifica si hay pedidos en cola
            $order = DB::table("assignations")
            ->select("order_id")
            ->where("status",2)
            ->orderBy('id', 'desc')
            ->get();

            $array = json_decode($order, true);
            $id = $array[0]["order_id"];


	        if(count($array) >0){
	        // Cambiar estatus del conductor
                DB::update('UPDATE disponibility SET active = ? WHERE user_id = ?', [1, $request->user_id]);
                DB::update('UPDATE disponibility SET inprogress= ? WHERE user_id = ?', [1,0]);

                // asignar el conductor a la orden
                DB::update('UPDATE assignations SET delivery = ? WHERE order_id = ?', [$request->user_id, $id]);

                // Cambiar el estatus de la orden
                DB::update('UPDATE assignations SET status = ? WHERE order_id = ?', [1, $id]);
	        }

	        return response()->json([
	            "success" => "Se actualizaron correctamente",
	        ]);
        }  catch (\Throwable $th) {
                return $th;
                $error = "No se pudo actualizar pedidos";
                return response()->json(["success" => $error]);
        }
    }
}
