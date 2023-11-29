<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemOrdenRepuesto;
use App\Models\OrdenRepuesto;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;





class OrdenRepuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdenesRepuestos($id){ //$id de una orden de trabajo
        $ordenesRepuestos = DB::table('orden_repuestos')->where('ordenTrabajoId', $id)->get();
        return $ordenesRepuestos;
    }

    public function getItemsOrdenRepuestos($id){
        $listaItems = DB::table('item_orden_repuesto')->where('ordenRepuestosId', $id)->get();
        return $listaItems;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        //return count($request->repuestos);
        //return $request->repuestos[0];
        $request->validate([
            'repuestos' => 'required',
            'cantidades' => 'required',
            'ordenId' => 'required'
        ]);

        /* $itemRepuesto = new ItemOrdenRepuesto();
            $itemRepuesto->nombre = $request->repuestos[0];
            $itemRepuesto->cantidad = $request->cantidades[0];
            // $itemRepuesto->orden_id = $request->ordenId;
            $itemRepuesto->save();
        return $itemRepuesto; */
        $nuevaOrden = new OrdenRepuesto();
        $nuevaOrden->estado = 'pendiente';
        $nuevaOrden->ordenTrabajoId = $request->ordenId;
        $nuevaOrden->save();

        for ($i=0; $i < count($request->repuestos); $i++) { 
            $itemRepuesto = new ItemOrdenRepuesto();
            $itemRepuesto->nombre = $request->repuestos[$i];
            $itemRepuesto->estado = 'pendiente';
            $itemRepuesto->cantidad = $request->cantidades[$i];
            $itemRepuesto->ordenRepuestosId = $nuevaOrden->id;

            $itemRepuesto->save();
        }

            activity()->useLog('Orden Repuesto')->log('Nuevo')->subject();
            $lastActivity = Activity::all()->last();
            $lastActivity->subject_id = OrdenRepuesto::all()->last()->id;
            $lastActivity->description = request()->ip();
            $lastActivity->save();

        return redirect()->route('ordens.edit',$request->ordenId);


        return $itemRepuesto;
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

    public function getItems($id){
        $items =DB::table('item_orden_repuesto')->where('ordenRepuestosId', $id)->get();
        return $items;
    }

    public function changeEstadoOrdenRepuesto(Request $request){
        //return $request->estado;
        //return $request;
        $orden = OrdenRepuesto::find($request->id);
        // $orden =DB::table('orden_repuestos')->where('id', $request->id)->update(['estado' => $request->estado]);
        $orden->estado = $request->estado;
        $orden->save();
        // return $orden;

    }
}
