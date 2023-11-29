<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orden extends Model
{
    use HasFactory;
    protected $table='ordens';
    protected $fillable=['estado','descripcion','fechai','fechaf','mecanico_id','reserva_id'];

    public function mecanicos(){
        return $this->belongsToMany(mecanico::class);
    }

    public function servicios(){
        return $this->belongsToMany(Servicio::class);
    }

    public function reserva(){
        return $this->belongsTo(Reserva::class,'reserva_id');
    }

}
