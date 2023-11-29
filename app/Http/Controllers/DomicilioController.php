<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class DomicilioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $user = Auth::user();
        $cliente=DB::table('clientes')->where('user_id',$user->id)->first();
        $vehiculo=DB::table('vehiculos')->where('cliente_id',$cliente->id)->get();
        $reserva=DB::table('reservas')->where('cliente_id',$cliente->id)->get();
        return view('frontends.perfil',compact('vehiculo','reserva','user','cliente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $servicio=Servicio::all();
        $cliente=DB::table('clientes')->where('user_id',$user->id)->first();
        $vehiculo=DB::table('vehiculos')->where('cliente_id',$cliente->id)->get();
        return view('frontends.domicilio',compact('servicio','vehiculo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha'=> 'required',
            'hora'=> 'required',
            'longitud'=> 'required',
            'latitud'=> 'required',
            'matricula'=> 'required',
        ]);
        $user = Auth::user();
        $cliente=DB::table('clientes')->where('user_id',$user->id)->first();
        $reserva=new Reserva();
        $reserva->fecha=$request->fecha;
        $reserva->hora=$request->hora;
        $reserva->longitud=$request->longitud;
        $reserva->latitud=$request->latitud;
        $reserva->tipo='Domicilio';
        $reserva->estado='Por Confirmar';
        $reserva->vehiculo_id=$request->matricula;
        $reserva->cliente_id=$cliente->id;
        $reserva->save();
        if ($request->servicio) {
            $reserva->servicios()->attach($request->servicio);
        }
        return redirect()->route('frontends.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
