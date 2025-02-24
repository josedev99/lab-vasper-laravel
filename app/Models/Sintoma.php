<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sintoma extends Model
{
    use HasFactory;
    protected $fillable = [
        'sintoma',
        'empleado_id',
        'consulta_id',
        'empresa_id',
        'sucursal_id',
        'usuario_id'
    ];
}
