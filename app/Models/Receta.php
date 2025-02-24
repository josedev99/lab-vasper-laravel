<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_dispensar',
        'dosis',
        'tratamiento_id',
        'empleado_id',
        'consulta_id',
        'empresa_id',
    ];
}
