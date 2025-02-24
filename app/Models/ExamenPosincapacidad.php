<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenPosincapacidad extends Model
{
    use HasFactory;

    protected $table = 'examen_posincapacidad';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'fecha',
        'hora',
        'examen',
        'resultado',
        'url_file',
        'type_file',
        'empleado_id',
        'empresa_id',
        'usuario_id',
    ];

    // Relación con el modelo Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación con el modelo Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
