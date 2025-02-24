<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetResultadoQuimica extends Model
{
    use HasFactory;
    protected $fillable = [
        'resultado',
        'estado',
        'result_quimica_id',
        'examen_id',
        'jornada_id',
        'empleado_id',
        'empresa_id'
    ];
}
