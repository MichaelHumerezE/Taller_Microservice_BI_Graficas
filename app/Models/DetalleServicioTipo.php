<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleServicioTipo extends Model
{
    protected $table='detalles_servicio_tipo';
    protected $fillable=[
        'precio',
        'servicio_id',
        'tipo_servicio_id'
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class,'servicio_id');
    }

    public function tipoServicio(){
        return $this->belongsTo(TipoServicio::class,'tipo_servicio_id');
    }

    public function reserva_detalles(){
        return $this->hasMany(ReservaServicioDetalle::class, 'detalle_servicio_id');
    }

}
