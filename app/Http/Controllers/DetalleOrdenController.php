<?php

namespace App\Http\Controllers;

use App\Models\DetalleOrden;
use App\Models\Material;
use App\Models\orden;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;

class DetalleOrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(orden $ordenTrabajo)
    {
        $materiales=Material::all();
        $detalles=DetalleOrden::with('materiales')->where('orden_trabajo_id',$ordenTrabajo->id)->get();
        return view('detalle_orden.index',['ordenTrabajo'=>$ordenTrabajo,'detalles'=>$detalles,'materiales'=>$materiales]);
    }

    public function indexOrden()
    {
        $ordenTrabajos=orden::all();
        //OrdenTrabajo::all();
        
        return view('detalle_orden.trabajo_index',['ordenTrabajos'=>$ordenTrabajos]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(orden $ordenTrabajo)
    {

        return view('detall_orden.create',['id',$ordenTrabajo->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, orden $ordenTrabajo)
    {

        $validate=$request->validate([
            'cantidad'=>'required|numeric|integer|min:1',
            'material_id'=>'required',
        ]);
        
       
        $material =Material::findOrFail($request['material_id']);
        if($material->cantidad>=$request['cantidad']){
            
            $detalleOrden=new DetalleOrden();
            $detalleOrden->cantidad=$request->input('cantidad');
            $detalleOrden->orden_trabajo_id=$ordenTrabajo->id;
            $detalleOrden->material_id=$request->input('material_id');
            $detalleOrden->save();
            
            $material->cantidad=$material->cantidad-$request['cantidad'];
            $material->save();

            return redirect()->route('detalle.orden.index',['ordenTrabajo'=>$ordenTrabajo]);
        } 
        else
        {
            return back()->withErrors(['message'=>'Cantidad Superada, se tiene en stock '.$material->cantidad.' Uni.']);
        }
        
        
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, orden $ordenTrabajo, $id)
    {

        $request->validate([
            'cantidad_edit' => 'required|numeric|integer|min:1',
        ]);

        
        $detalleOrden = DetalleOrden::findOrFail($id);
        $material = Material::findOrFail($detalleOrden->material_id);
        
        $total=$material->cantidad+$detalleOrden->cantidad;
        
        
        if ($total >=$request['cantidad_edit']) {

            $detalleOrden->cantidad = $request->input('cantidad_edit');
            $detalleOrden->orden_trabajo_id = $ordenTrabajo->id;
            
            $material->cantidad = $total - $request['cantidad_edit'];
            $material->save();

            $detalleOrden->save();
            return redirect()->route('detalle.orden.index', ['ordenTrabajo' => $ordenTrabajo]);
        
        } else {
            return back()->withErrors(['messages'=> 'Cantidad Superada, se tiene en stock ' . $material->cantidad . ' Uni.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(orden $ordenTrabajo,$id)
    {
        
        $detalleOrden=DetalleOrden::findOrFail($id);
        $material=Material::findOrFail($detalleOrden->material_id);
       

        $material->cantidad=$material->cantidad+$detalleOrden->cantidad;
        $material->save();

        $detalleOrden->delete();
        return redirect()->route('detalle.orden.index',['ordenTrabajo'=>$ordenTrabajo]);

    }
}
