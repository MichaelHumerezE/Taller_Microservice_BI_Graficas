<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenRepuesto extends Model
{
    use HasFactory;
    protected $table = 'orden_repuestos';

    protected $fillable = [
        'estado',
        'ordenTrabajoId',
    ];
}
