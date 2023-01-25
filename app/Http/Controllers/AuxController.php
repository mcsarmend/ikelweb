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
}
