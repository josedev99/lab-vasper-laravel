<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetGrupo extends Model
{
    use HasFactory;

    protected $table = 'det_grupo';

    protected $fillable = [
        'categoria',
        'riesgo_id',
        'examen_id',
        'grupo_id',
        'empresa_id'
    ];

    // Relaciones
    public function riesgo()
    {
        return $this->belongsTo(FactorRiesgo::class, 'riesgo_id');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoExamen::class, 'grupo_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
