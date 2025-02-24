<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultHemograma extends Model
{
    use HasFactory;

    protected $table = 'result_hemograma';

    protected $fillable = [
        'gr_hemato', 'ht_hemato', 'hb_hemato', 'vcm_hemato', 'cmhc_hemato', 'gota_hema', 
        'gb_hemato', 'linfocitos_hemato', 'monocitos_hemato', 'eosinofilos_hemato', 
        'basinofilos_hemato', 'banda_hemato', 'segmentados_hemato', 'metamielo_hemato', 
        'mielocitos_hemato', 'blastos_hemato', 'plaquetas_hemato', 'reti_hemato', 
        'eritro_hemato', 'otros_hema', 'numero_orden', 'fecha', 'estado', 'hcm_hemato', 
        'jornada_id', 'empleado_id', 'empresa_id'
    ];

    // Relación con Jornada
    public function jornada()
    {
        return $this->belongsTo(Jornada::class);
    }

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
