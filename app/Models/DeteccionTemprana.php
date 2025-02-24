<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeteccionTemprana extends Model
{
    use HasFactory;

    protected $table = 'deteccion_temprana';

    protected $fillable = [
        'nombre',
        'descripcion',
        'empresa_id',
        'usuario_id',
    ];

    // Relación con la empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
