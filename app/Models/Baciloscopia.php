<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baciloscopia extends Model
{
    use HasFactory;

    protected $table = 'result_baciloscopia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'resultado',
        'jornada_id',
        'estado',
        'empleado_id',
        'observaciones'
    ];
}
