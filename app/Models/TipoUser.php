<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUser extends Model
{
    use HasFactory;
    protected $table = 'tipos_user';
    protected $fillable = [
        'descripcion',
    ];
}
