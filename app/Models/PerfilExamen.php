<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilExamen extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora',
        'nombre',
        'empresa_id',
        'usuario_id'
    ];
}
