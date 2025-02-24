<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenesCategoria extends Model
{
    use HasFactory;
    protected $table = "examenes";
    protected $fillable = [
        'nombre',
        'descripcion',
        'name_tabla',
        'categoria_id',
        'empresa_id',
        'usuario_id',
    ];
}
