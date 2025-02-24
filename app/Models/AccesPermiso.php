<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesPermiso extends Model
{
    use HasFactory;

    protected $table = 'acces_permisos';

    protected $fillable = [
        'id_modulo',
        'id_permiso',
        'id_empresa',
        'id_usuario',
    ];


    // Relación con el modelo Permiso
    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso');
    }

    // Relación con el modelo Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }



    // Relación con el modelo Modulo
    public function modulo()
    {
        return $this->belongsTo(AccesModulo::class, 'id_modulo', 'id_modulo');
    }


}
