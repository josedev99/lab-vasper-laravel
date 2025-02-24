<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetJornada extends Model
{
    use HasFactory;
    protected $fillable = [
        'estado',
        'evaluacion',
        'cat_examen',
        'examen_id',
        'jornada_id',
        'empleado_id',
        'empresa_id',
        'usuario_id'
    ];
}
