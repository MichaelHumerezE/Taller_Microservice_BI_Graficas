<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class VentasExport implements FromView
{
    protected $month;
    protected $year;

    public function __construct($month,$year)
    {
        $this->month=$month;
        $this->year=$year;
    }
    
    public function view(): View
    {
       
        $informes = DB::table('factura')
        ->join('reservas', 'factura.reserva_id', '=', 'reservas.id')
        ->join('reserva_servicio_detalle', 'reserva_servicio_detalle.reserva_id', '=', 'reservas.id')
        ->join('detalles_servicio_tipo', 'detalles_servicio_tipo.id', '=', 'reserva_servicio_detalle.detalle_servicio_id')
        ->join('servicios', 'servicios.id', '=', 'detalles_servicio_tipo.servicio_id')
        ->join('tipo_servicios', 'tipo_servicios.id', '=', 'detalles_servicio_tipo.tipo_servicio_id')
        ->whereMonth('factura.fecha', $this->month)->whereYear('factura.fecha', $this->year)
        ->select(['servicios.id', 'tipo_servicios.nombre as nombre_tipo', 'servicios.nombre', DB::raw('SUM(reserva_servicio_detalle.precio) as subtotal'), DB::raw('COUNT(*) as cantidad')])->groupBy(['servicios.id', 'tipo_servicios.nombre', 'servicios.nombre'])
        ->get();

        return view('ventas', [
            'informes' =>$informes,
            'month'=>$this->month,
            'years'=>$this->year
        ]);
    }
}
