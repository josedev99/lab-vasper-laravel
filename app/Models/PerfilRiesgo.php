<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilRiesgo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'fecha',
        'hora',
        'empresa_id',
        'usuario_id'
    ];
}
