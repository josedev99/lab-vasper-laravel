<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $fillable = [
        'estado',
        'codigo_empleado',
        'nombre',
        'telefono',
        'motivo',
        'fecha_inicio_sintoma',
        'fecha_cita',
        'hora_cita',
        'fecha',
        'hora',
        'empresa_id',
        'sucursal_id',
    ];
}
