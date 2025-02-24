<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class signoVitalesMedida extends Model
{
    use HasFactory;
    protected $fillable = [
        'fc_ipm',
        'fr_rpm',
        'pa_ps_pd',
        'temperatura',
        'saturacion',
        'peso_kg',
        'talla_cm',
        'imc',
        'empleado_id',
        'consulta_id',
        'empresa_id',
    ];
}
