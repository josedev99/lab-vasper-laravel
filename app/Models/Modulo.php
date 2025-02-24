<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

       /**
     * Relación con el modelo Permiso
     */
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_modulo');
    }

    public function accesModulos()
{
    return $this->hasMany(AccesModulo::class, 'id_modulo');
}

}
