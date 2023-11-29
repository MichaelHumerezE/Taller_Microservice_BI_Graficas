<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaServicioDetalle extends Model
{
    use HasFactory;
    protected $table= 'reserva_servicio_detalle';
    protected $fillable=[
        'reserva_id',
        'detalle_servicio_id',
        'precio',
    ];

    public function reserva(){
        return $this->belongsTo(Reserva::class,'reserva_id');
    }
    public function detalleServicio()
    {
        return $this->belongsTo(DetalleServicioTipo::class,'detalle_servicio_id');
    }

}
