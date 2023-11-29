<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\cliente;
use Illuminate\Http\Request;
use App\Models\orden;
use App\Models\mecanico;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return view('frontends.inicio');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontends.register');
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
            'name' => 'required|unique:users',
            'password' => 'required',
            'email' => 'required|unique:clientes',
            'ci' => 'required|unique:clientes',
            'nombre' => 'required',
            'genero' => 'required',
            'celular' => 'required|unique:clientes',
            'fecha' => 'required'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->assignRole('cliente');
        $cliente = new cliente();
        $cliente->email = $request->email;
        $cliente->nombre = $request->nombre;
        $cliente->ci = $request->ci;
        $cliente->celular = $request->celular;
        $cliente->fecha = $request->fecha;
        $cliente->genero = $request->genero;
        $cliente->user_id = $user->id;
        $cliente->save();
        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(orden $orden)
    {
        $orden->mecanico_id=DB::table('mecanicos')->where('id',$orden->mecanico_id)->value('nombre');
        $mecanicos=mecanico::all();
        $mecanicoos=DB::table('mecanico_orden')->where('orden_id',$orden->id)->get();
        $servicios=Servicio::all();
        $servicioos=DB::table('orden_servicio')->where('orden_id',$orden->id)->get();
        return view('ordens.show',compact('orden','mecanicos','mecanicoos','servicioos','servicios'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(orden $orden)
    {
        $orden->mecanico_id=DB::table('mecanicos')->where('id',$orden->mecanico_id)->value('ci');
        return view('ordens.edit',compact('orden'));
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
        $request->validate([
            'estado' => 'required',
            'descripcion' => 'required',
            'fechai' => 'required',
            'ci' => 'required',
        ]);
        /*$request->mecanico_id=DB::table('mecanicos')->where('ci',$request->ci)->value('id');*/
        $mecanico = DB::table('mecanicos')->where('ci',$request->ci)->first();
        $orden->estado = $request->estado;
        $orden->descripcion = $request->descripcion;
        $orden->fechai = $request->fechai;
        $orden->fechaf =$request->fechaf;
        $orden->mecanico_id = $mecanico->id;
        $orden->save();
       // $orden ->update($request->all());
        return redirect()->route('ordens.index',$orden);
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
}
