<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\orden;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Models\mecanico;
use PDF;


class PDFOrdenTrabajoController extends Controller
{
    public function ordenesTrabajoPdf()
    {
        $orden=orden::all();
        $mecanico=mecanico::all();
        $pdf = PDF::loadView('ordens.pdf', ['orden' => $orden, 'mecanico' => $mecanico]);
        return $pdf->stream();
        return view('ordens.pdf',compact('orden','mecanico'));
    }

    public function ordenTrabajoPdf($id)
    {
        //return $id;
        $orden = orden::find($id);
        $reserva = DB::table('reservas')->where('id',$orden->reserva_id)->first();
        //return $reserva;
        $vehiculo = DB::table('vehiculos')->where('id',$reserva->vehiculo_id)->first();
        //return $vehiculo;
        $cliente = DB::table('clientes')->where('id',$vehiculo->cliente_id)->first();
        //return $cliente;

        $orden->mecanico_id=DB::table('mecanicos')->where('id',$orden->mecanico_id)->value('ci');
        $ordenesRepuestos = DB::table('orden_repuestos')->where('ordenTrabajoId', $orden->id)->get();
        $pdf = PDF::loadView('ordens.ordenPdf', ['orden' => $orden, 'vehiculo' => $vehiculo, 'cliente' => $cliente, 'ordenesRepuestos' => $ordenesRepuestos]);
        return $pdf->stream();
        return view('ordens.ordenPdf',compact('orden','mecanico'));
    }
}
