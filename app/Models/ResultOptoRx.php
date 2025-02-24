<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOptoRx extends Model
{
    use HasFactory;

    protected $table = 'result_opto_rx';

    protected $fillable = [
        'empleado_id',
        'codigo_empleado',
        'od_esfera_f',
        'od_cilindro_f',
        'od_eje_f',
        'od_prisma_f',
        'od_adicion_f',
        'oi_esfera_f',
        'oi_cilindro_f',
        'oi_eje_f',
        'oi_prisma_f',
        'oi_adicion_f',
        'oi_av_final_cc',
        'od_av_final_cc',
        'result_consulta_id',
        'jornada_id',
    ];
}
