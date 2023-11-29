<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';

    protected $fillable=['ci','nombre','genero','celular','email','fecha','user_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
