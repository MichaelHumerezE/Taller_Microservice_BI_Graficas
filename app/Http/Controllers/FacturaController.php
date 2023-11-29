<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use App\Models\Factura;
use App\Models\orden;
use App\Models\Reserva;
use App\Models\ReservaServicioDetalle;
use Barryvdh\DomPDF\Facade as PDF;
// use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
// use App\Exports\VentasExport;

class FacturaController extends Controller
{

    public function filter(Request $request)
    {
        return 1;
    
        /* $request->validate([
            'mes'=>'required',
            'year'=>'required'
        ]);
        $mes=$request->mes;
        $year=$request->year;

        $meses = collect([
            ['nombre' => 'Enero', 'posicion' => '1'],
            ['nombre' => 'Febrero', 'posicion' => '2'],
            ['nombre' => 'Marzo', 'posicion' => '3'],
            ['nombre' => 'Abril', 'posicion' => '4'],
            ['nombre' => 'Mayo', 'posicion' => '5'],
            ['nombre' => 'Junio', 'posicion' => '6'],
            ['nombre' => 'Julio', 'posicion' => '7'],
            ['nombre' => 'Agosto', 'posicion' => '8'],
            ['nombre' => 'Septiembre', 'posicion' => '9'],
            ['nombre' => 'Octubre', 'posicion' => '10'],
            ['nombre' => 'Noviembre', 'posicion' => '11'],
            ['nombre' => 'Diciembre', 'posicion' => '12']
        ]);

    
        $informes=DB::table('factura')
            ->join('reservas','factura.reserva_id','=','reservas.id')
            ->join('reserva_servicio_detalle', 'reserva_servicio_detalle.reserva_id','=','reservas.id')
            ->join('detalles_servicio_tipo', 'detalles_servicio_tipo.id','=', 'reserva_servicio_detalle.detalle_servicio_id')
            ->join('servicios','servicios.id','=', 'detalles_servicio_tipo.servicio_id')
            ->join('tipo_servicios', 'tipo_servicios.id','=','detalles_servicio_tipo.tipo_servicio_id')
            ->whereMonth('factura.fecha',$mes)->whereYear('factura.fecha',$year)
            ->select(['servicios.id','tipo_servicios.nombre as nombre_tipo','servicios.nombre', DB::raw('SUM(reserva_servicio_detalle.precio) as subtotal'),DB::raw('COUNT(*) as cantidad')])->groupBy(['servicios.id', 'tipo_servicios.nombre', 'servicios.nombre'])
            ->get();

        return view('factura.report', ['informes' => $informes, 'meses' => $meses,'month'=>$mes,'years'=>$year]); */
    }
    public function indexreport()
    {
        /* $meses=collect([
            ['nombre'=>'Enero','posicion'=>'1'],
            ['nombre'=>'Febrero', 'posicion' => '2'],
            ['nombre'=>'Marzo', 'posicion' => '3'],
            ['nombre'=>'Abril','posicion'=>'4'],
            ['nombre'=>'Mayo','posicion'=>'5'],
            ['nombre'=>'Junio','posicion'=>'6'],
            ['nombre'=>'Julio','posicion'=>'7'],
            ['nombre'=>'Agosto','posicion'=>'8'],
            ['nombre'=>'Septiembre','posicion'=>'9'],
            ['nombre'=>'Octubre','posicion'=>'10'],
            ['nombre'=>'Noviembre','posicion'=>'11'],
            ['nombre'=>'Diciembre','posicion'=>'12']
        ]);

        $informes = DB::table('factura')
            ->join('reservas', 'factura.reserva_id', '=', 'reservas.id')
            ->join('reserva_servicio_detalle', 'reserva_servicio_detalle.reserva_id', '=', 'reservas.id')
            ->join('detalles_servicio_tipo', 'detalles_servicio_tipo.id', '=', 'reserva_servicio_detalle.detalle_servicio_id')
            ->join('servicios', 'servicios.id', '=', 'detalles_servicio_tipo.servicio_id')
            ->join('tipo_servicios', 'tipo_servicios.id', '=', 'detalles_servicio_tipo.tipo_servicio_id')
            ->whereMonth('factura.fecha', Carbon::now('America/La_paz')->format('m'))
            ->whereYear('factura.fecha', Carbon::now('America/La_paz')->format('Y'))
            ->select(['servicios.id', 'tipo_servicios.nombre as nombre_tipo', 'servicios.nombre', DB::raw('SUM(reserva_servicio_detalle.precio) as subtotal'), DB::raw('COUNT(*) as cantidad')])->groupBy(['servicios.id', 'tipo_servicios.nombre', 'servicios.nombre'])
            ->get();

        
        return view('factura.report', ['informes' => $informes,'meses'=>$meses,'month'=> Carbon::now('America/La_paz')->format('m'),'years'=> Carbon::now('America/La_paz')->format('Y')]); */
    }

    public function index()
    {
        /* $facturas=Factura::all();
        return view('factura.index',['facturas'=>$facturas]); */
    }
    public function create(orden $orden)
    {
        // return view('factura.create',['orden'=>$orden]);
    }

    public function store(Request $request,orden $orden)
    {

        return 1;

        /* $reserva=Reserva::findOrFail($orden->reserva_id);
        $total=$reserva->load('detalle')->detalle->sum('precio');
        
        $factura=new Factura();
        $factura->nit=$request->nit;
        $factura->total=$total;
        $factura->fecha=Carbon::now('America/La_paz')->format('Y-m-d');
        $factura->hora=Carbon::now('America/La_paz')->format('H:m:s');
        $factura->reserva_id=$reserva->id;
        $factura->save();

        $reserva = Reserva::findOrFail($factura->reserva_id);
        $detalle=$reserva->load('detalle')->detalle;

        $cliente = cliente::findOrFail($orden->reserva->cliente_id);
    

        Mail::send('mails.factura', ['factura' => $factura, 'detalle' => $detalle, 'cliente' => $cliente], function ($mail) use ($cliente) {
            $mail->to($cliente->email, $cliente->nombre)->subject('Facturacion');
        });

        return redirect()->route('facturas.show',['factura'=>$factura]); */
        
    }

    public function show(Factura $factura)
    {
        return 1;

        /* $reserva=Reserva::findOrFail($factura->reserva_id);
        $detalle=$reserva->load('detalle')->detalle;
        return view('factura.show',['factura'=>$factura,'detalle'=>$detalle]); */
    }
    public function dowloadPDF($month,$years)
    {
        return 1;

       /*  $informes = DB::table('factura')
        ->join('reservas', 'factura.reserva_id', '=', 'reservas.id')
        ->join('reserva_servicio_detalle', 'reserva_servicio_detalle.reserva_id', '=', 'reservas.id')
        ->join('detalles_servicio_tipo', 'detalles_servicio_tipo.id', '=', 'reserva_servicio_detalle.detalle_servicio_id')
        ->join('servicios', 'servicios.id', '=', 'detalles_servicio_tipo.servicio_id')
        ->join('tipo_servicios', 'tipo_servicios.id', '=', 'detalles_servicio_tipo.tipo_servicio_id')
        ->whereMonth('factura.fecha', $month)->whereYear('factura.fecha', $years)
        ->select(['servicios.id', 'tipo_servicios.nombre as nombre_tipo', 'servicios.nombre', DB::raw('SUM(reserva_servicio_detalle.precio) as subtotal'), DB::raw('COUNT(*) as cantidad')])->groupBy(['servicios.id', 'tipo_servicios.nombre', 'servicios.nombre'])
        ->get();

        
        $pdf = PDF::loadView('ventas', compact(['informes','month','years']));
        return $pdf->download('ventas.pdf'); */
    }
    public function downloadExcel($month, $years)
    {
        return 1;
        // return Excel::download(new VentasExport($month,$years),'ventas.xlsx');
    }
}
