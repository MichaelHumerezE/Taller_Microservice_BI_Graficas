<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\cliente;
use App\Models\Factura;
use Illuminate\Http\Request;
use App\Models\orden;
use App\Models\mecanico;
use App\Models\Reserva;
use App\Models\Servicio;

use App\Models\vehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Database\Eloquent\Collection;


class OrdenController extends Controller
{
    public static $FINALIZADO = 'Finalizado';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orden = orden::all();
        $mecanico = mecanico::all();

        return view('ordens.index', compact('orden', 'mecanico'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $reserva = DB::table('reservas')->where('estado', 'Por Confirmar')->first();
        $mecanico = mecanico::all();
        return view('ordens.create', compact('mecanico', 'reserva'));
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
            'estado' => 'required',
            'descripcion' => 'required',
            'fechai' => 'required',
        ]);
        $reserva = Reserva::findorfail($request->reserva);
        $reserva->estado = "Confirmado";
        $reserva->save();
        $mecanicos = DB::table('mecanicos')->where('ci', $request->ci)->first();
        $orden = new orden();
        $orden->estado = $request->estado;
        $orden->descripcion = $request->descripcion;
        $orden->fechai = $request->fechai;
        $orden->fechaf = $request->fechaf;
        $orden->mecanico_id = $mecanicos->id;
        $orden->reserva_id = $reserva->id;
        $orden->save();
        if ($request->mecanico) {
            $orden->mecanicos()->attach($request->mecanico);
        }
        return redirect()->route('ordens.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(orden $orden)
    {
        $orden->mecanico_id = DB::table('mecanicos')->where('id', $orden->mecanico_id)->value('nombre');
        $mecanicos = mecanico::all();
        $mecanicoos = DB::table('mecanico_orden')->where('orden_id', $orden->id)->get();
        $servicios = Servicio::all();
        $servicioos = DB::table('reserva_servicio')->where('reserva_id', $orden->reserva_id)->get();
        return view('ordens.show', compact('orden', 'mecanicos', 'mecanicoos', 'servicioos', 'servicios'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(orden $orden)
    {
        //$reserva = DB::table('reservas')->where('id',$orden->reserva_id)->first();
        //return $reserva;
        $vehiculo = DB::table('vehiculos')->where('id', $orden->vehiculo_id)->first();
        //return $vehiculo;
        $cliente = DB::table('clientes')->where('id', $vehiculo->cliente_id)->first();
        //return $cliente;

        $orden->mecanico_id = DB::table('mecanicos')->where('id', $orden->mecanico_id)->value('ci');
        $ordenesRepuestos = DB::table('orden_repuestos')->where('ordenTrabajoId', $orden->id)->get();
        //return $orden;
        //return $ordenesRepuestos;
        return view('ordens.edit', compact('orden', 'ordenesRepuestos', 'vehiculo', 'cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, orden $orden)
    {
        $vehiculo = vehiculo::findOrFail($orden->reserva->vehiculo_id);
        $cliente = cliente::findOrFail($orden->reserva->cliente_id);

        $request->validate([
            'estado' => 'required',
            'descripcion' => 'required',
            'fechai' => 'required',
            'ci' => 'required',
        ]);



        /*$request->mecanico_id=DB::table('mecanicos')->where('ci',$request->ci)->value('id');*/
        $mecanico = DB::table('mecanicos')->where('ci', $request->ci)->first();
        $orden->estado = $request->estado;
        $orden->descripcion = $request->descripcion;
        $orden->fechai = $request->fechai;
        $orden->fechaf = $request->fechaf;
        $orden->mecanico_id = $mecanico->id;
        $orden->save();
        // $orden ->update($request->all());


        if (self::$FINALIZADO == $request->estado) {
            Mail::send('mails.estado', ['orden' => $orden, 'vehiculo' => $vehiculo, 'cliente' => $cliente], function ($mail) use ($cliente) {
                $mail->to($cliente->email, $cliente->nombre)->subject('Orden de Trabajo');
            });
        }


        return redirect()->route('ordens.index', $orden);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordens.index');
    }

    public function getOrdenTrabajo($id)
    {
        return orden::find($id);
    }

    public function getOrdenesTrabajoByIdUser($id)
    { //el $id es de un user
        //primero traer las reservas del cliente con id $id
        if (!DB::table('clientes')->where('user_id', $id)->exists()) {
            return [];
        }
        $cliente = DB::table('clientes')->where('user_id', $id)->first();
        $reservas = DB::table('reservas')->where('cliente_id', $cliente->id)->get();
        if (count($reservas) < 1) {
            return []; //response()->json(['message' => 'Ya existe un usuario con ese nombre'], 404);
        }
        $ordenes = new Collection();
        foreach ($reservas as $e) {
            $orden = DB::table('ordens')->where('reserva_id', $e->id)->first();
            $ordenes->add($orden);
        }
        return $ordenes;
    }

    public function getClienteByIdUser($id)
    {
        return Cliente::find($id);
    }
}
