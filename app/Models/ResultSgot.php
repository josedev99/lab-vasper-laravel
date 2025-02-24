<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultSgot extends Model
{
    use HasFactory;
    protected $fillable = [
        'resultado',
        'observaciones',
        'estado',
        'jornada_id',
        'empleado_id',
        'empresa_id',
    ];
}
