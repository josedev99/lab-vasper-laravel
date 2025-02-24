<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOptoAltura extends Model
{
    use HasFactory;

    protected $table = 'result_opto_altura';

    protected $fillable = [
        'empleado_id',
        'codigo_empleado',
        'dip_od',
        'dip_oi',
        'ao_od',
        'ao_oi',
        'ap_od',
        'ap_oi',
        'result_consulta_id',
    ];
}
