<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'usuario',
        'correo',
        'contr',
        'password', // Este es el campo que debes usar para la contraseña
        'api_token',
        'empresa_id',
        'departamento',
        'telefono',
        'dui',
        'nit',
        'cargo',
        'estado',
        'direccion',
        'fecha_creacion',
        'sucursal_id',
        'categoria',
    ];

    protected $hidden = [
        'password', // Para ocultar la contraseña
    ];
    public function accesModulos()
    {
        return $this->hasMany(AccesModulo::class, 'id_usuario');
    }
    

}
