<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntradaSalida;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Models\Activity;




class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salidas = EntradaSalida::where('tipo', 2)->get();
        return view('salidas.index', compact('salidas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('salidas.create');
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
            'fecha' => 'required',
            'hora' => 'required',
            'tipo' => 'required',
        ]);

        $salida = new Salida();
        $salida->hora = $request->hora;
        $salida->fecha = $request->fecha;
        $salida->descripcion = $request->descripcion;
        $salida->tipo = $request->tipo;
        $salida->save();

        activity()->useLog('Salida')->log('Nuevo')->subject();
        $lastActivity = Activity::all()->last();
        $lastActivity->subject_id = Salida::all()->last()->id;
        $lastActivity->description = request()->ip();
        $lastActivity->save();

        return redirect()->route('salidas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salida = Salida::findOrFail($id);
        // return $salida;
        $images = DB::table('imagenes')->where('entradaSalidaId', $id)->get();

        return view('salidas.show', compact('salida', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salida = Salida::findOrFail($id);
        return view('salidas.edit', compact('salida'));
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
