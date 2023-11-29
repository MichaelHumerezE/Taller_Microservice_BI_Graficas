<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mecanico;
class MecanicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mecanico=mecanico::all();
        return view('mecanicos.index',compact('mecanico'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mecanicos.create');
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
            'ci' => 'required|unique:mecanicos',
            'nombre' => 'required',
            'email' => 'required',
            'fecha' => 'required',
            'especialidad' => 'required',
            'genero' => 'required',
            'celular' => 'required',
        ]);
        $mecanico = mecanico::create($request->all());
        return redirect()->route('mecanicos.index',$mecanico);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(mecanico $mecanico)
    {
        return view('mecanicos.show',compact('mecanico'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(mecanico $mecanico)
    {
        return view('mecanicos.edit',compact('mecanico'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mecanico $mecanico)
    {
        $request->validate([
            'ci' => "required|unique:mecanicos,ci,$mecanico->id",
            'nombre' => 'required',
            'email' => 'required',
            'fecha' => 'required',
            'especialidad'=>'required',
            'genero' => 'required',
            'celular' => 'required',
        ]);
        $mecanico ->update($request->all());
        return redirect()->route('mecanicos.index',$mecanico);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(mecanico $mecanico)
    {
        $mecanico->delete();
        return redirect()->route('mecanicos.index');
    }
}
