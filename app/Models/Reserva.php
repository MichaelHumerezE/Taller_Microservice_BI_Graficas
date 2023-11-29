<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable= ['fecha','estado','hora','tipo','vehiculo_id','cliente_id'];
    //Relacion muchos a muchos
    public function servicios(){
        return $this->belongsToMany(Servicio::class);
    }
    public function orden(){
        return $this->hasOne(orden::class,'reserva_id');
    }
    public function detalle(){
        return $this->hasMany(ReservaServicioDetalle::class,'reserva_id');
    }
}
