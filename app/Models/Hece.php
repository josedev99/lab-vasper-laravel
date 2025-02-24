<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hece extends Model
{
    use HasFactory;

    protected $table = 'result_heces';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jornada_id',
        'color',
        'consistencia',
        'mucus',
        'macroscopicos',
        'microscopicos',
        'hematies',
        'leucocitos',
        'activos',
        'quistes',
        'metazoarios',
        'protozoarios',
        'observaciones',
        'empleado_id',
        'estado'
    ];
}
