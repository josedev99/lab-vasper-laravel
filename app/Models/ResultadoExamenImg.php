<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoExamenImg extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'cat_examen',
        'resultado',
        'url_file',
        'type_file',
        'examen_id',
        'empleado_id',
        'jornada_id',
        'empresa_id',
        'usuario_id',
    ];
}
