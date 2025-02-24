<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetResultEvaluacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'estado',
        'jornada_id',
        'empleado_id',
        'empresa_id',
        'usuario_id',
    ];
}
