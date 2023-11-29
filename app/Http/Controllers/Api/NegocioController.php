<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NegocioController extends Controller
{

    public function getReportes()
    {

		
      //CANTIDAD DE REPARACIONES EN LOS ULTIMOS  5 MESES  (formato de fecha YYYY-MM-DD)
        $fechaActual = Carbon::now();
        $fechaInicial = $fechaActual->subMonth(6);      //desde 16 DE JUNIO 

        $cantOrdenTotal = DB::table('ordens')
            ->where('fechai', '>=', $fechaInicial)
            ->count();

         //CANTIDAD DE REPARACIONES CONCLUIDAS EN EL ULTIMO MES   
        $fechaOrdenMasReciente = Carbon::parse(DB::table('ordens')->orderByDesc('fechai')->value('fechai'));
        $fechaTreintAntes = $fechaOrdenMasReciente->subDays(30);
        $cantOrdenMes = DB::table('ordens')
            ->where('fechai', '>=', $fechaTreintAntes)
            ->count();


        //CANTIDAD DE NUEVOS CLIENTES EN EL ULTIMO MES   
        $cantNewClientes = DB::table('clientes')
            ->where('created_at', '>=', $fechaActual->subDay(30))
            ->count();

         //CANTIDAD TOTAL DE VEHICULOS REGISTRADOS po
        $canTotalVehiculos = DB::table('vehiculos')
            ->where('created_at', '>=', $fechaActual->subDay(30))
            ->count();
            

         //CANTIDAD TOTAL DE usuaios  REGISTRADOS 
        $canTotalUsers = DB::table('users')->count();


        //REPARACIONES MAS SOLICITADAS (SERVICIOS) GRAFICA  BARRAS HORIZONTALES
        //usar tabla orden_servicio, ordens para la fecha, servicios para obtener el nombre
        $reparacionesMasSolicitadas = DB::table('ordens')
            ->join('orden_servicio', 'ordens.id', '=', 'orden_servicio.orden_id')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio.servicio_id')
            ->select('servicios.id', 'servicios.nombre', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderBy('cantidad', 'desc')
            ->get();

         
         //- AUTOS QUE LLEGAN AL TALLER POR MARCAS  (Gráfico de torta).		
        // ordens, vehículos
        $cantAutosByMarca = DB::table('ordens')
            ->join('vehiculos', 'ordens.vehiculo_id', '=', 'vehiculos.id')
            ->select('vehiculos.marca', DB::raw('COUNT(vehiculos.marca) as cantidad'))
            ->groupBy('vehiculos.marca')
            ->orderBy('cantidad', 'desc')
            ->get();

        $datos = [
            'cantOrdenTotal' => $cantOrdenTotal,
            'cantOrdenMes' => $cantOrdenMes,
            'cantNewClientes' => $cantNewClientes,
            'canTotalUsers' => $canTotalUsers,
            'canTotalVehiculos' => $canTotalVehiculos,
            'reparacionesMasSolicitadas' => $reparacionesMasSolicitadas,
            'cantAutosByMarca' => $cantAutosByMarca

        ];

        return response()->json($datos);

    }


    public function getReportes2()
    {
        $repuestosMasSolicitados = DB::table('ordens')
            ->join('orden_repuestos', 'ordens.id', '=', 'orden_repuestos.id_orden')
            ->join('repuestos', 'repuestos.id', '=', 'orden_repuestos.id_repuestos')
            ->select('repuestos.id', 'repuestos.nombre', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('repuestos.id', 'repuestos.nombre')
            ->orderBy('cantidad', 'desc')
            ->get();
        //PORCENTAJE DE CLIENTES RECURRENTES 
        $totalClientes = DB::table('clientes')->count();

        $clientRecurrentes = DB::table('clientes')
            ->join('vehiculos', 'clientes.id', '=', 'vehiculos.cliente_id')
            ->join('ordens', 'vehiculos.id', '=', 'ordens.vehiculo_id')
            ->distinct('clientes.id')
            ->count();
        $porClientRecurrentes = ROUND(($clientRecurrentes / $totalClientes) * 100, 2);


        $resultado = DB::table('factura')
            ->select(DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"), DB::raw('SUM(total) as ingreso_total'))
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get();

        $ingresosPorMes = [];
        foreach ($resultado as $item) {
            $mes = $item->mes;
            $ingresoTotal = $item->ingreso_total;
            $ingresosPorMes[] = [
                'mes' => $mes,
                'ingreso_total' => $ingresoTotal,
            ];
        }


        $beneficioTotal = DB::table('factura')
        ->whereRaw("fecha >= CURDATE() - INTERVAL 1 MONTH AND fecha < CURDATE()")
        ->sum('total');
    

      

        $datos = [
            'porClientRecurrentes' => $porClientRecurrentes,
            'beneficioTotal' => $beneficioTotal,           
            'repuestosMasSolicitados' => $repuestosMasSolicitados,
            'ingresosPorMes' => $ingresosPorMes
        ];
        return response()->json($datos);

    }


}
