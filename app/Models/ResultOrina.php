<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultOrina extends Model
{
    use HasFactory;

    protected $table = 'result_orina';

    protected $fillable = [
        'color', 'olor', 'aspecto', 'densidad', 'est_leuco', 'ph', 'proteinas', 'glucosa', 
        'cetonas', 'urobilinogeno', 'bilirrubina', 'sangre_oculta', 'cilindros', 'leucocitos', 
        'hematies', 'cel_epiteliales', 'filamentos_muco', 'bacterias', 'cristales', 
        'observaciones', 'nitritos_orina', 'estado', 'jornada_id', 
        'empleado_id', 'empresa_id'
    ];

    public function jornada()
    {
        return $this->belongsTo(Jornada::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
