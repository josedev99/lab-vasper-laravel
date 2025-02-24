<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultGlucosa extends Model
{
    use HasFactory;
    protected $table = 'result_glucosas';
    protected $fillable = [
        'resultado',
        'estado',
        'observaciones',
        'jornada_id',
        'empleado_id',
        'empresa_id',
    ];
}
