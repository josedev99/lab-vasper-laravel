<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetDeteccion extends Model
{
    use HasFactory;

    protected $table = 'det_deteccion';

    protected $fillable = [
        'categoria',
        'riesgo_id',
        'examen_id',
        'detecciontemprana_id',
        'empresa_id',
    ];

    // Relación con Examen
    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relación con Detección Temprana
    public function deteccionTemprana()
    {
        return $this->belongsTo(DeteccionTemprana::class, 'detecciontemprana_id');
    }
}
