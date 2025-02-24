<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signo extends Model
{
    use HasFactory;
    protected $table = 'signos';
    protected $fillable = [
        'nombre',
        'empleado_id',
        'consulta_id',
        'empresa_id',
    ];
}
