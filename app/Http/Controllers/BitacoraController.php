<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;



class BitacoraController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('permission:Ver bitacora')->only(['index']);
    } */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return "hola mundo";
        $actividades = DB::table('activity_log')
             ->join('users', 'activity_log.causer_id', '=', 'users.id')->select('activity_log.*', 'users.name')->get();

        //return $actividades;
        return view('Bitacora.index', compact('actividades'));
    }
}
