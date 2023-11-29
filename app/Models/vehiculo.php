<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehiculo extends Model
{
    use HasFactory;
    protected $fillable=['matricula','marca','modelo','year','combustible','caja','color','tipo','cliente_id'];
    
}
