<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table='servicios';
    protected $fillable=[
        'nombre',
        'descripcion',
        'precio'
    ];

    public function detallesServicioTipo(){
        return $this->hasMany(DetalleServicioTipo::class,'servicio_id');
    }

    public function ordens(){
        return $this->belongsToMany(orden::class);
    }
    //Relacion muchos a muchos
    public function reservas(){
        return $this->belongsToMany(Reserva::class);
    }
}
