<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $fillable = [
        'categoria',
        'tipo',
        'nombre',
        'genero',
        'telefono',
        'codigo_empleado',
        'no_afiliacion',
        'fecha_ingreso',
        'area_depto_id',
        'cargo_id',
        'cargo',
        'fecha_nacimiento',
        'direccion',
        'fecha',
        'hora',
        'empresa_id',
        'sucursal_id',
        'usuario_id',
    ];
}
