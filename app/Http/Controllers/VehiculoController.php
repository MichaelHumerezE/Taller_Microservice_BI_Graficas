<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use App\Models\vehiculo;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculo=vehiculo::all();
        $cliente=cliente::all();
        return view('vehiculos.index',compact('vehiculo','cliente'));
    }

    public function indexreport()
    {
        $marca=vehiculo::all()->unique('marca')->pluck('marca');
        $caja=vehiculo::all()->unique('caja')->pluck(('caja'));
        $combustible=vehiculo::all()->unique('combustible')->pluck('combustible');

        


        $vehiculo = vehiculo::all();
        $cliente = cliente::all();
      
        return view('vehiculos.report', ['vehiculo' => $vehiculo, 'marca' => $marca, 'caja' => $caja, 'combustible' => $combustible,'flag'=>true]);
    }

    public function filter(Request $request)
    {
       
        
        $caj=$request->input('caja');
        $combust=$request->input('combustible');
        $mar=$request->input('marca');

        $vehiculo=vehiculo::all();
        if($caj!=null){
           
            $vehiculo = vehiculo::all()->where('caja',$caj);
            
            
        } else{
            $caj='vacio';
        }
        if($combust!=null){
            $vehiculo = $vehiculo->where('combustible',$combust);
        }else{
            $combust='vacio';
        }
        if($mar!=null){
            $vehiculo = $vehiculo->where('marca', $mar);
        }else{
            $mar='vacio';
        }
        
        $marca = vehiculo::all()->unique('marca')->pluck('marca');
        $caja = vehiculo::all()->unique('caja')->pluck(('caja'));
        $combustible = vehiculo::all()->unique('combustible')->pluck('combustible');

        return view('vehiculos.report',['vehiculo'=>$vehiculo,'marca'=>$marca,'caja'=>$caja,'combustible'=>$combustible,'caj'=>$caj,'combust'=>$combust,'mar'=>$mar,'flag'=>false]);
    }

    public function dowloadPDF($marca,$combustible,$caja){

        $caj = $caja;
        $combust = $combustible;
        $mar = $marca;
        
        $vehiculo = vehiculo::all();
        if ($caj != 'vacio') {

            $vehiculo = vehiculo::all()->where('caja', $caj);
        }
        if ($combust != 'vacio') {
            $vehiculo = $vehiculo->where('combustible', $combust);
        }
        if ($mar != 'vacio') {
            $vehiculo = $vehiculo->where('marca', $mar);
        }

        $pdf = PDF::loadView('vehiculo', compact('vehiculo'));
        return $pdf->download('vehiculo.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('frontends.vehiculo');
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
            'matricula' => 'required|unique:vehiculos',
            'marca' => 'required',
            'modelo' => 'required',
            'year' => 'required',
            'combustible' => 'required',
            'caja' => 'required',
            'color' => 'required',
            'tipo' =>'required',
        ]);
        $user = Auth::user();
        $cliente=DB::table('clientes')->where('user_id',$user->id)->first();
        $vehiculo = new vehiculo();
        $vehiculo->matricula = $request->matricula;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo =$request->modelo;
        $vehiculo->year = $request->year;
        $vehiculo->combustible=$request->combustible;
        $vehiculo->caja=$request->caja;
        $vehiculo->color=$request->color;
        $vehiculo->tipo=$request->tipo;
        $vehiculo->cliente_id = $cliente->id;
        $vehiculo->save();
        //$a=DB::table('clientes')->where('ci',$request->ci)->first();
        //$request->ci=$a->nombre;
        //return $request;
        //vehiculo::create($request->all());
        return redirect()->route('domicilios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(vehiculo $vehiculo)
    {
        return view('vehiculos.show',compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(vehiculo $vehiculo)
    {
        //$cliente=cliente::all();
        $vehiculo->ci=DB::table('clientes')->where('id',$vehiculo->ci)->value('ci');
        return view('vehiculos.edit',compact('vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, vehiculo $vehiculo)
    {
        $request->validate([
            'matricula' => "required|unique:vehiculos,matricula,$vehiculo->id",
            'marca' => 'required',
            'modelo' => 'required',
            'year' => 'required',
            'combustible' => 'required',
            'caja' => 'required',
            'color' => 'required',
            'tipo' =>'required',
            'ci' =>'required',
        ]);
        $request->ci=DB::table('clientes')->where('ci',$request->ci)->value('id');
        $vehiculo ->update($request->all());
        return redirect()->route('vehiculos.index',$vehiculo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index');
    }
}
