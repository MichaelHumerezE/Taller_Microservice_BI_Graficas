<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EntradaApiController extends Controller
{
    public function uploadImage(Request $request){
        if($request->hasFile('file')){
            $path = $request->file('file')->store("$request->entradaId", 's3');
            $imagen = new Imagen();
            $id = (int)$request->entradaId;
            // return $id;
            $imagen->url = Storage::disk('s3')->url($path);
            $imagen->entradaSalidaId = $id;
            $imagen->save();
            // return $actuacion;
            return response()->json(['mensaje' => 'archivo subido con exito']);
        }else{
            return response()->json(['message' => 'Error al subir el aaarchivo']);
        }
    }

    public function getEntrada($id){ 
        if(!DB::table('entrada_salidas')->where('id', $id)->exists())
            return response()->json(['message' => 'no se encontró una entrada con ese id']);

        $entrada = Entrada::find($id);
        if(!$entrada->tipo == 1)
            return response()->json(['message' => 'no se encontró una entrada con ese id']);

        return $entrada;
    }

    public function getSalida($id){ 
        if(!DB::table('entrada_salidas')->where('id', $id)->exists())
            return response()->json(['message' => 'no se encontró una salida con ese id']);

        $salida = Entrada::find($id);
        if(!$entrada->tipo == 2)
            return response()->json(['message' => 'no se encontró una salida con ese id']);

        return $salida;
    }

    public function getImages($id){
        $images = DB::table('imagenes')->where('entradaSalidaId', $id)->select('url')->get();
        // return $images;

        $listaImages = new Collection();
        $imagenes = DB::table('imagenes')->where('entradaSalidaId', $id)->get();
        foreach ($imagenes as $imagen) {
            $listaImages->add($imagen->url);
        }
        return $listaImages;
    }
}
