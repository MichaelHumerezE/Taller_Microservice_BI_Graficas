<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{  public $timestamps = false; 
    use HasFactory;
   protected $table='tipo_material';
    protected $fillable=[
        'id',
        'descripción'
    ];
}
