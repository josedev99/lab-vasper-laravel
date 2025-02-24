<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOptoConsulta extends Model
{
    use HasFactory;

    protected $table = 'result_opto_consulta';

    protected $fillable = [
        'codigo_empleado',
        'motivo_consulta',
        'sintomas_consulta',
        'fecha_consulta',
        'hora',
        'patologias',
        'diag_optometra',
        'diag_oftalmologo',
        'diagnostico',
        'medicamento',
        'av_sc_oi',
        'av_sc_od',
        'ph_od',
        'ph_oi',
        'diag_preliminar_od',
        'diag_preliminar_oi',
        'observaciones',
        'consulta_externa',
        'empleado_id',
        'empresa_id',
        'sucursal_id',
        'jornada_id',
    ];
}
