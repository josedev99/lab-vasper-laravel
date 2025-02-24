<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaDepartamentoEmp extends Model
{
    use HasFactory;
    protected $fillable = [
        'departamento',
        'empresa_id'
    ];
}
