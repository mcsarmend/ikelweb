<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use DB;
class LikeController extends Controller
{
    // like or unlike
    public function likeOrUnlike($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ], 200);
        }
        // else dislike it
        $like->delete();

        return response([
            'message' => 'Disliked'
        ], 200);
    }


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
        $iduser = DB::table("users")
        ->select("id")
        ->where("email", $request->email)
        ->get();
        $data = DB::table("address")
            ->select("idaddress")
            ->where("iduser", $iduser[0]->id)
            ->get();

        if ($data == "[]") {
            DB::table("address")->insert([
                "iduser" => $request->iduser,
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
                    "iduser" => $iduser[0]->id,
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
        
        $iduser = DB::table("users")
        ->select("id")
        ->where("email", $request->email)
        ->get();

        $data = DB::table("address as a")
            ->select("a.calle","a.numero","a.colonia","m.municipio","e.estado")
            ->leftjoin('cat_municipios as m','a.idmunicipio','=','m.idmunicipio')
            ->leftjoin('cat_estados as e','a.idestado','=','e.idestado')
            ->where("iduser", $iduser[0]->id)
            ->get();
        return $data;
    }
}
