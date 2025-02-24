<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenLab extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero_orden',
        'fecha',
        'hora',
        'estado',
        'estado_eval',
        'empleado_id',
        'empresa_id',
        'sucursal_id',
        'usuario_id',
    ];
}
