<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetOrdenLab extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero_orden',
        'estado',
        'empleado_id',
        'categoria_id',
        'examen_id',
        'orden_lab_id',
    ];
}
