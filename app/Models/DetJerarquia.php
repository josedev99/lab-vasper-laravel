<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetJerarquia extends Model
{
    use HasFactory;

    protected $table = 'det_jerarquia';

    protected $fillable = [
        'tipo',
        'id_empresa',
        'descripcion'
    ];

    // Si tienes relación con el modelo Empresa, puedes agregar la relación
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
}
