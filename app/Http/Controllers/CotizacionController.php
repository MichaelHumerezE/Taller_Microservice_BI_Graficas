<?php

namespace App\Http\Controllers;

use App\Models\DetalleServicioTipo;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function quotation()
    {

        $cotizacionCollection=\Cart::getContent();
        return view('cotizacion_shop.show',['cotizacion'=>$cotizacionCollection]);
    }

    public function store(DetalleServicioTipo $servicio)
    {
        $service=$servicio->load('servicio')->load('tipoServicio');
          ;
        \Cart::add(array(
            'id'=>$service->id,
            'price'=>$service->precio,
            'quantity'=>1,
            'name'=>$service->servicio->nombre,
            'attributes'=>array('nameType'=>$service->tipoServicio->nombre)
        ));
        return redirect()->route('cotizacion.serticio.index')
      ;
    }
}
