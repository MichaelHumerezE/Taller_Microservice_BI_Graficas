<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultaApiController extends Controller
{
    public function getReparacionesData()
    {
        //cantidad de reparaciones en los últimos 30 días
        $fechaActual = Carbon::now();
        $fechaTreintaDiasAtras = $fechaActual->subDays(30);

        $cantidadOrdenes = DB::table('ordens')
            ->where('fechai', '>=', $fechaTreintaDiasAtras)
            ->count();
        //return $cantidadOrdenes;

        //cantidad de nuevos clientes en el último mes
        $fechaActual = Carbon::now();
        $fechaTreintaDiasAtras = $fechaActual->subDays(30);

        $cantidadNuevosClientes = DB::table('clientes')
            ->where('created_at', '>=', $fechaTreintaDiasAtras)
            ->count();
        //return $cantidadNuevosClientes;

        //cantidad de reparaciones en curso
        $cantidadOrdenEnCurso = DB::table('ordens')
            ->where('estado', '=', 'En Curso')
            ->count();
        //return $cantidadOrdenEnCurso;

        //promedio de duración de una reparación en general
        $resultado = DB::table('ordens')
            ->select(DB::raw('AVG(horas) as promedio_duracion'))
            ->first();
        $promedioDuracion = $resultado->promedio_duracion;
        //return $promedioDuracion;

        //Porcentaje de retrabajo último 3 meses
        $fechaActual = Carbon::now();
        $fechaTresMesesAtras = $fechaActual->subMonths(3);

        $totalOrdenes = DB::table('ordens')
            ->where('fechai', '>=', $fechaTresMesesAtras)
            ->count();

        $ordenesRework = DB::table('ordens')
            ->where('fechai', '>=', $fechaTresMesesAtras)
            ->where('rework', 1)
            ->count();

        $porcentajeRework = ($ordenesRework / $totalOrdenes) * 100;
        //return $porcentajeRework;


        //- Reparaciones mas solicitadas en los últimos 3 meses (gráfico de barras horizontales).
		
        // usar tabla orden_servicio, ordens para la fecha, servicios para obtener el nombre
        $fechaActual = Carbon::now();
        $fechaTresMesesAtras = $fechaActual->subMonths(3);
        $reparacionesMasSolicitadas = DB::table('ordens')
            ->join('orden_servicio', 'ordens.id', '=', 'orden_servicio.orden_id')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio.servicio_id')
            ->select('servicios.id', 'servicios.nombre', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderBy('cantidad', 'desc')
            ->get();
        //return $reparacionesMasSolicitadas;

        // -- consulta usando fecha
        /* $resultado = DB::table('ordens')
            ->join('orden_servicio', 'ordens.id', '=', 'orden_servicio.orden_id')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio.servicio_id')
            ->select('servicios.nombre', DB::raw('COUNT(*) as cantidad'))
            //->where('ordens.fechai', '>=', $fechaTresMesesAtras)
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderBy('cantidad', 'desc')
            ->get();
        return $resultado; */



        //- promedio de duración de reparación por tipo en horas (gráfico de barras verticales).
		
        // usando ordens, servicios, orden_servicio
        $promedioDuracionServicios = DB::table('ordens')
            ->join('orden_servicio', 'ordens.id', '=', 'orden_servicio.orden_id')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio.servicio_id')
            ->select('servicios.id', 'servicios.nombre', DB::raw('AVG(ordens.horas) as promedio_horas'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderBy('promedio_horas', 'desc')
            ->get();
        //return $promedioDuracionServicios;

        //- Autos que llegan al taller por marcas (Gráfico de torta).
		
        // ordens, vehículos
        $cantidadAutosByMarca = DB::table('ordens')
            ->join('vehiculos', 'ordens.vehiculo_id', '=', 'vehiculos.id')
            ->select('vehiculos.marca', DB::raw('COUNT(vehiculos.marca) as cantidad'))
            ->groupBy('vehiculos.marca')
            ->orderBy('cantidad', 'desc')
            ->get();
        //return $cantidadAutosByMarca;

        //- Tasa de reparaciones exitosas en el primer intento en los últimos 3 meses (Gráfico de barras).
        // ordens
        $fechaActual = Carbon::now();
        $fechaSeisMesesAtras = $fechaActual->subMonths(6);
        $reparacionesExitosas = DB::table('ordens')
            ->select(
                DB::raw('to_char(ordens.fechai, \'YYYY-MM\') as mes'),
                DB::raw('SUM(CASE WHEN ordens.rework = 0 THEN 1 ELSE 0 END) as no_rework'),
                DB::raw('COUNT(*) as total')
            )
            ->where('ordens.fechai', '>=', $fechaSeisMesesAtras)
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        //return $reparacionesExitosas;

        //- promedio de duración por reparación de los últimos 6 meses (Gráfico de líneas).
        //ordens
        $fechaActual = Carbon::now();
        $fechaSeisMesesAtras = $fechaActual->subMonths(6);
        $promedioDuracion6Meses = DB::table('ordens')
            ->select(
                DB::raw('to_char(ordens.fechai, \'YYYY-MM\') as mes'),
                DB::raw('AVG(ordens.horas) as promedio_duracion')
            )
            ->where('ordens.fechai', '>=', $fechaSeisMesesAtras)
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();
        //return $promedioDuracion6Meses;

        $datos = [
            'cantidadOrdenes' => $cantidadOrdenes,
            'cantidadNuevosClientes' => $cantidadNuevosClientes,
            'cantidadOrdenEnCurso' => $cantidadOrdenEnCurso,
            'promedioDuracion' => $promedioDuracion,
            'porcentajeRework' => $porcentajeRework,
            'reparacionesMasSolicitadas' => $reparacionesMasSolicitadas,
            'promedioDuracionServicios' => $promedioDuracionServicios,
            'cantidadAutosByMarca' => $cantidadAutosByMarca,
            'reparacionesExitosas' => $reparacionesExitosas,
            'promedioDuracion6Meses' => $promedioDuracion6Meses
        ];

        return response()->json($datos);
    }
	
    
    public function getBeneficiosData()
    {
        //beneficios del mes actual
        $fechaActual = Carbon::now();
        $mesActual = $fechaActual->format('m');
        $resultado = DB::table('factura')
            ->select(DB::raw('SUM(total) as beneficios'))
            ->whereRaw("EXTRACT(MONTH FROM fecha) = $mesActual")
            ->first();

        $beneficios = $resultado->beneficios;
        //return $beneficios;

        //porcentaje de clientes recurrentes
        $totalClientes = DB::table('clientes')->count();

        $clientesRecurrentes = DB::table('clientes')
            ->join('vehiculos', 'clientes.id', '=', 'vehiculos.cliente_id')
            ->join('ordens', 'vehiculos.id', '=', 'ordens.vehiculo_id')
            ->distinct('clientes.id')
            ->count();

        $porcentajeClientesRecurrentes = ($clientesRecurrentes / $totalClientes) * 100;
        //return $porcentajeClientesRecurrentes;

        // Ingresos en los últimos 3 meses
        $resultado = DB::table('factura')
            ->select(DB::raw('to_char(factura.fecha, \'YYYY-MM\') as mes, SUM(total) as ingreso_total'))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
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
        //return $ingresosPorMes;

        // promedio ARO (Dinero recibido por reparación) de los últimos 6 meses (Gráfico líneas)
        $fechaActual = Carbon::now();
        $fechaInicio = $fechaActual->subMonths(6);
        $resultados = DB::table('ordens')
            ->join('factura', 'ordens.id', '=', 'factura.orden_id')
            ->select(DB::raw('to_char(ordens.fechai, \'YYYY-MM\')  as mes, AVG(factura.total) as aro_promedio'))
            ->where('ordens.fechai', '>=', $fechaInicio)
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Crear arreglo de objetos con mes y ARO promedio
        $promediosAro = [];
        foreach ($resultados as $resultado) {
            $mes = $resultado->mes;
            $aroPromedio = $resultado->aro_promedio;
            $objeto = [
                'mes' => $mes,
                'aro_promedio' => $aroPromedio
            ];
            $promediosAro[] = (object)$objeto;
        }
        //return $promediosAro;


        // rentabilidad por tipo de reparación (servicio)
        $servicios = DB::table('servicios')->select('id', 'nombre', 'precio')->get();
        //return $servicios;

        $datos = [
            'beneficios' => $beneficios,
            'porcentajeClientesRecurrentes' => $porcentajeClientesRecurrentes,
            'ingresosPorMes' => $ingresosPorMes,
            'promediosAro' => $promediosAro,
            'servicios' => $servicios,
        ];
        return response()->json($datos);
    }
////////////////////////////////////////////////////////////////////////////////////////////////

public function getTecnicosData()
{
    //Promedio de ingreso por técnico en el último mes
    $fechaActual = Carbon::now();
    $fechaInicioMes = $fechaActual->startOfMonth();
    $fechaFinMes = $fechaActual->endOfMonth();

    $resultado = DB::table('mecanicos')
        ->join('ordens', 'mecanicos.id', '=', 'ordens.mecanico_id')
        ->join('factura', 'ordens.id', '=', 'factura.orden_id')
        ->select(DB::raw('AVG(factura.total) as ingreso_promedio'))
        //->whereBetween('factura.fecha', [$fechaInicioMes, $fechaFinMes])
        ->groupBy('mecanicos.id')
        ->get();

    $mediaIngresosPromedio = $resultado->avg('ingreso_promedio');
    //return $mediaIngresosPromedio;

    //tasa de eficiencia en el último mes
    $fechaActual = Carbon::now();
    $fechaInicioMes = $fechaActual->startOfMonth();
    $fechaFinMes = $fechaActual->endOfMonth();

    $resultado = DB::table('mecanicos')
        ->join('ordens', 'mecanicos.id', '=', 'ordens.mecanico_id')
        ->join('factura', 'ordens.id', '=', 'factura.orden_id')
        ->select('mecanicos.nombre', DB::raw('SUM(ordens.horas) as total_horas_trabajo'), DB::raw('COUNT(DISTINCT ordens.id) as total_ordenes'))
        //->whereBetween('factura.fecha', [$fechaInicioMes, $fechaFinMes])
        ->groupBy('mecanicos.nombre')
        ->get();

    $tasaEficiencia = [];

    foreach ($resultado as $row) {
        $nombreMecanico = $row->nombre;
        $totalHorasTrabajo = $row->total_horas_trabajo;
        $totalOrdenes = $row->total_ordenes;

        $horasTrabajoJornada = $totalOrdenes * 8; // Horas de trabajo considerando 8 horas por día
        $tasaEficienciaMecanico = ($totalHorasTrabajo / $horasTrabajoJornada) * 100;

        $tasaEficiencia[] = [
            'nombre' => $nombreMecanico,
            'tasa_eficiencia' => $tasaEficienciaMecanico,
        ];
    }
    $tasasEficiencia = collect($tasaEficiencia);
    $promedioTasaEficiencia = $tasasEficiencia->avg('tasa_eficiencia');

    //return $promedioTasaEficiencia;

    //tiempo promedio de reparación por cada técnico en los últimos 6 meses (gráfico multilínea)
    $fechaActual = Carbon::now();
    $fechaInicio = $fechaActual->subMonths(6)->startOfMonth();
    $fechaFin = Carbon::now()->endOfMonth();

    $resultado = DB::table('mecanicos')
        ->join('ordens', 'mecanicos.id', '=', 'ordens.mecanico_id')
        ->select(
            'mecanicos.nombre',
            DB::raw('to_char(ordens.fechai, \'YYYY-MM\') as mes'),
            DB::raw('AVG(ordens.horas) as tiempo_promedio')
        )
        ->whereBetween('ordens.fechai', [$fechaInicio, $fechaFin])
        ->groupBy('mecanicos.nombre', 'mes')
        ->orderBy('mes', 'asc')
        ->get();

    $tiempoPromedioReparacion = [];

    foreach ($resultado as $row) {
        $nombreMecanico = $row->nombre;
        $mes = $row->mes;
        $tiempoPromedio = $row->tiempo_promedio;

        $respuesta[] = [
            'mes' => $mes,
            'nombre' => $nombreMecanico,
            'tiempo_promedio' => $tiempoPromedio,
        ];
    }

    //return $tiempoPromedioReparacion;

    //Tasa de utilización por mecánico en el último mes (Gráfico de barras, utilizar la fórmula)
    $tasaUtilizacion = DB::table('mecanicos')->get();
    //return $tasaUtilizacion;

    //cantidad de reparaciones realizadas en el último mes por cada técnico (Barras)
    $cantidadReparaciones = DB::table('mecanicos')->get();
    //return $cantidadReparaciones;


    //Ingresos por técnico en los últimos 6 meses (gráfico multilínea)
    $ingresoPorTecnico = DB::table('mecanicos')->get();
    //return $ingresoPorTecnico;

    $datos = [
        'mediaIngresosPromedio' => $mediaIngresosPromedio,
        'promedioTasaEficiencia' => $promedioTasaEficiencia,
        'tiempoPromedioReparacion' => $tasaUtilizacion,
        'tasaUtilizacion' => $tasaUtilizacion,
        'cantidadReparaciones' => $cantidadReparaciones,
        'ingresoPorTecnico' => $ingresoPorTecnico,
    ];
    return response()->json($datos);
}

}
