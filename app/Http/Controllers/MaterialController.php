<?php

namespace App\Http\Controllers;
use App\Models\Material;
use App\Models\TipoMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    
    {
        $materiales =Material::all();
   //    return view('materiales.index',['materiales'=>$materiales]);
      return view('materiales.index',compact('materiales'));
  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos = TipoMaterial::all();
         return view('materiales.create',compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $material=new Material();
       $material->id=$request->input('id');
        $material->nombre=$request->input('name');
        $material->cantidad=$request->input('cantidad');
        $material->tipo=$request->input('tipo_material');
        $material->save();

    
        return redirect()->route('materiales.index');
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
        $materiales=Material::findOrFail($id);
        return view('materiales.edit',['materiales'=>$materiales]);
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
        $materiales=Material::findOrFail($id);
       // $materiales->id=$request->input('id');
       // $materiales->descripcion=$request->input('descripcion');
       $materiales->cantidad=$request->input('cantidad');
        $materiales->save();

        return redirect()->route('materiales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material=Material::findOrFail($id);
        $material->delete();
        return redirect()->route('materiales.index');
    }
}

