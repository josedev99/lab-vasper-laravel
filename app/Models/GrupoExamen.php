<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoExamen extends Model
{
    use HasFactory;

    protected $table = 'grupo_examen';

    protected $fillable = [
        'nombre',
        'descripcion',
        'empresa_id',
        'usuario_id',
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
