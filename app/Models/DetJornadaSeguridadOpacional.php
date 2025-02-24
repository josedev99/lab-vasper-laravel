<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetJornadaSeguridadOpacional extends Model
{
    use HasFactory;
    protected $table = "det_jornada_seg_opacional";
    protected $fillable = [
        'departamento_id',
        'factor_riesgo_id',
        'jornada_id',
        'empresa_id'
    ];
}
