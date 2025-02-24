<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultExofaringeo extends Model
{
    use HasFactory;
    protected $fillable = [
        'aisla',
        'sensible',
        'resiste',
        'refiere',
        'jornada_id',
        'empleado_id',
        'empresa_id',
    ];
}
