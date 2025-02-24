<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultCreatinina extends Model
{
    use HasFactory;
    protected $table = "result_creatininas";
    protected $fillable = [
        'resultado',
        'observaciones',
        'estado',
        'jornada_id',
        'empleado_id',
        'empresa_id',
    ];
}
