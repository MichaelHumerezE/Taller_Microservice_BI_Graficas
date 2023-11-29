<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ReservaApiController extends Controller
{
    public function getServicios(){
        $servicios = Servicio::where('tipo','Domicilio')->get();
        //$servicios=Servicio::all();
        return response($servicios,200);
    }
    public function registerReserva(Request $request){
        return $request;
        $request->validate([
            'fecha' => 'required',
            'hora' => 'required',
            'userId' =>'required',
        ]);
        
        if(!DB::table('users')->where('id', $request->userId)->exists()){
            return response()->json(['message' => 'El Usuario No Existe'], 404);
        }    
        $cliente=DB::table('clientes')->where('user_id', $request->userId)->first();
        $vehiculo =DB::table('vehiculos')->where('cliente_id', $cliente->id)->first();
        $reserva = new Reserva();
        $reserva->nombre = $request->name;
        $reserva->fecha = $request->fecha;
        $reserva->hora=$request->hora;
        $reserva->tipo='Domicilio';
        $reserva->estado='Por Confirmar';
        $reserva->cliente_id=$request->userId;
        $reserva->vehiculo_id=$vehiculo->id;
        $reserva->save();
        if ($request->servicios) {
            $reserva->servicios()->attach($request->servicios->id);
        }
        /* return $user;
        return $cliente; */
        return $reserva;
    }
}
