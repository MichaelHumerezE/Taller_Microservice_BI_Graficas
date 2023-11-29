<?php

namespace App\Http\Controllers;
use App\Models\TipoMaterial;
use Illuminate\Http\Request;

class TipoMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoMaterial=TipoMaterial::all();
        return view('tipo_material.index',['tipoMateriales'=>$tipoMaterial]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_material.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $tipoMaterial=new TipoMaterial();
        $tipoMaterial->id=$request->input('id');
        $tipoMaterial->descripción=$request->input('descripción');
        $tipoMaterial->save();

        return redirect()->route('tipomateriales.index');

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
        $tipoMaterial=TipoMaterial::findOrFail($id);
        return view('tipo_material.edit',['tipoMaterial'=>$tipoMaterial]);
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
        $tipoMaterial=TipoMaterial::findOrFail($id);
     
        $tipoMaterial->descripción=$request->input('nombre');
        $tipoMaterial->save();
        
        return redirect()->route('tipomateriales.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoMaterial=tipoMaterial ::findOrFail($id);
        $tipoMaterial->delete();

        return redirect()->route('tipomateriales.index');
    }
}
