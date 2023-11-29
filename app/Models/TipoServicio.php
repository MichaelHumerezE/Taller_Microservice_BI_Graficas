<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table='tipo_servicios';
    protected $fillable=[
        'nombre',
        'descripcion',
        'domicilio'=>'boolean'
    ];

    public function detallesServicioTipo(){
        return $this->hasMany(DetalleServicioTipo::class,'tipo_servicio_id');
    }
}
