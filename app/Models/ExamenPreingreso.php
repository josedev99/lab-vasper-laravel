<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenPreingreso extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'examen',
        'resultado',
        'url_file',
        'type_file',
        'empleado_id',
        'empresa_id',
        'usuario_id'
    ];
}
