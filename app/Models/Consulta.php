<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;
    protected $fillable = [
        'motivo',
        'diagnostico',
        'riesgo',
        'incapacidad',
        'fecha_inicio_sintoma',
        'observaciones',
        'estado',
        'fecha',
        'hora',
        'empleado_id',
        'empresa_id',
        'sucursal_id',
        'usuario_id'
    ];
}
