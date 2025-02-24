<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetDeptoRiesgo extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'categoria',
        'cat_examen',
        'examen_id',
        'cargo_id',
        'factor_riesgo_id',
        'empresa_id',
        'usuario_id'
    ];
}
