<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incapacidad extends Model
{
    use HasFactory;

    protected $table = 'incapacidades';

    protected $fillable = [
        'dui',
        'codigo_empleado',
        'colaborador',
        'departamento',
        'cargo',
        'diagnostico',
        'periodo',
        'periodo_final',
        'motivo',
        'riesgo',
        'fecha_expedicion',
        'tipo_incapacidad',
        'empresa_id',
        'sucursal_id',
        'usuario_id',
        'empleado_id',
        'categoria_incapacidad',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
