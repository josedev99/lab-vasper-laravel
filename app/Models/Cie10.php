<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cie10 extends Model
{
    protected $table = "cie10";
    use HasFactory;
    protected $fillable = [
        'codigo_capitulo',
        'codigo_bloque',
        'codigo',
        'descripcion',
        'empresa_id'
    ];
}
