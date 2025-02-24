<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_modulo',
    ];

    /**
     * Relación con el modelo Modulo
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }
}
