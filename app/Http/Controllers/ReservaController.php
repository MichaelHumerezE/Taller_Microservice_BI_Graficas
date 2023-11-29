<?php

namespace App\Http\Controllers;

use Darryldecode\Cart\Cart;
use App\Models\cliente;
use App\Models\Reserva;
use App\Models\ReservaServicioDetalle;
use App\Models\vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculo=vehiculo::all();
        $reserva=Reserva::all();
        return view('reservas.index',compact('reserva','vehiculo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $vehiculos=vehiculo::all();
        return view('reservas.create',['vehiculos'=>$vehiculos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $reserva=new Reserva();
        $reserva->fecha=$request->fecha;
        $reserva->hora=$request->hora;
        $reserva->tipo='Normal';
        $reserva->estado='En proceso';
        $reserva->vehiculo_id=$request->vehiculo_id;
        $reserva->cliente_id=auth()->user()->cliente->id;
        $reserva->save();

        $cotizacionCollection = \Cart::getContent();
        foreach ($cotizacionCollection as $key => $value) {
            $detalle_reserva=new ReservaServicioDetalle();
            $detalle_reserva->reserva_id=$reserva->id;
            $detalle_reserva->detalle_servicio_id=$value->id;
            $detalle_reserva->precio=$value->price;
            $detalle_reserva->save();
        }
        
        \Cart::clear();

        return redirect()->route('reservas.index');
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
