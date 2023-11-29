<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;
    
    protected $table = 'detalle_ordenes';

    protected $fillable=[
        'cantidad',
        'orden_trabajo_id',
        'material_id'
    ];

    public function ordenTrabajo()
    {
        return $this->belongsTo(OrdenTrajo::class,'orden_trabajo_id');
    }

    public function materiales()
    {
        return $this->belongsTo(Material::class,'material_id');
    }
}
