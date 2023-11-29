<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use Illuminate\Http\Request;

class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoServicio=TipoServicio::all();
        return view('tipo_servicio.index',['tipoServicios'=>$tipoServicio]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_servicio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $tipoServicio=new TipoServicio();
        $tipoServicio->nombre=$request->input('nombre');
        $tipoServicio->descripcion=$request->input('descripcion');
       // $tipoServicio->domicilio=$request->input('domicilio');
       $tipoServicio->domicilio= $request->has('domicilio');
       
       $tipoServicio->save();

        return redirect()->route('tiposervicios.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipoServicio=TipoServicio::findOrFail($id);
        return view('tipo_servicio.edit',['tipoServicio'=>$tipoServicio]);
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
        $tipoServicio=TipoServicio::findOrFail($id);
        $tipoServicio=new TipoServicio();
        $tipoServicio->nombre=$request->input('nombre');
        $tipoServicio->descripcion=$request->input('descripcion');
        $tipoServicio->domicilio=$request->input('domicilio');
        $tipoServicio->save();
        
        return redirect()->route('tiposervicios.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoServicio=TipoServicio ::findOrFail($id);
        $tipoServicio->delete();

        return redirect()->route('tiposervicios.index');
    }
}
