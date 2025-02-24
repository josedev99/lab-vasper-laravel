<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetPerfilExamen extends Model
{
    use HasFactory;
    protected $fillable = [
        'examen_id',
        'perfil_examen_id',
        'empresa_id'
    ];
}
