<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mecanico extends Model
{
    use HasFactory;
     protected $fillable=['ci','nombre','email','fecha','especialidad','genero','celular'];

    public function ordens(){
        return $this->belongsToMany(orden::class);
    }
}
