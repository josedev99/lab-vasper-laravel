<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'cat_examenes',
        'fecha_jornada',
        'hora',
        'laboratorio_id',
        'empresa_id',
        'usuario_id',
    ];
}
