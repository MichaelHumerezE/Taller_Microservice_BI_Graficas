<?php

namespace App\Http\Controllers;

use App\Models\DetalleServicioTipo;
use App\Models\Servicio;
use App\Models\TipoServicio;
use Illuminate\Http\Request;

class DetalleServicioTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $detalleServicioTipos=DetalleServicioTipo::with('tipoServicio')->with('servicio')->get();
        
        return view('detalle_servicio_tipo.index',['detallesservicioTipos'=>$detalleServicioTipos]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $servicios=Servicio::all();
        $tipoServicios=TipoServicio::all();

        return view('detalle_servicio_tipo.create',['servicios'=>$servicios,'tipoServicios'=>$tipoServicios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $detalleServicioTipo=new DetalleServicioTipo();
        $detalleServicioTipo->precio=$request->input('precio');
        $detalleServicioTipo->servicio_id=$request->input('servicio_id');
        $detalleServicioTipo->tipo_servicio_id=$request->input('tipo_servicio_id');
        $detalleServicioTipo->save();

        return redirect()->route('detalle.tipo.servicios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $tipos=TipoServicio::with('detallesServicioTipo.servicio')->get();
        
        return view('detalle_servicio_tipo.show',['tipos'=>$tipos]);
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
