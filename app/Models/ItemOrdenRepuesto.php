<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrdenRepuesto extends Model
{
    use HasFactory; 
    protected $table = 'item_orden_repuesto';

    protected $fillable = [
        'nombre',
        'cantidad',
        'orden_id',
    ];
}
