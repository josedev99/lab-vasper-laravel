<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JornadaSeguridadOpacional extends Model
{
    use HasFactory;
    protected $table = "jornada_seg_opacional";
    protected $fillable = [
        'nombre',
        'fecha',
        'hora',
        'empresa_id',
        'usuario_id'
    ];
}
