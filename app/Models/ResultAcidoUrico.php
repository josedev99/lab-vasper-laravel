<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultAcidoUrico extends Model
{
    use HasFactory;
    protected $fillable = [
        'resultado',
        'estado',
        'observaciones',
        'jornada_id',
        'empleado_id',
        'empresa_id',
    ];
}
