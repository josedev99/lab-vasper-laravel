<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaIncapacidad extends Model
{
    use HasFactory;
    protected $fillable = [
        'consulta_id',
        'incapacidad_id',
        'empresa_id'
    ];
}
