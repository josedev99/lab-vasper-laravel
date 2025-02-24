<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenLabJornada extends Model
{
    use HasFactory;
    protected $fillable = [
        'empleado_id',
        'orden_lab_id',
        'jornada_id',
        'empresa_id'
    ];
}
