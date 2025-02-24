<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosCita extends Model
{
    use HasFactory;
    protected $fillable = [
        'hora',
        'estado',
        'rango',
        'dia',
        'empresa_id',
        'sucursal_id',
        'usuario_id'
    ];

    protected $table = 'horarios_citas';

}
