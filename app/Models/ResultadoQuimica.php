<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoQuimica extends Model
{
    use HasFactory;
    protected $fillable = [
        'observaciones',
        'fecha',
        'hora',
        'jornada_id',
        'empleado_id',
        'empresa_id',
        'usuario_id'
    ];
}
