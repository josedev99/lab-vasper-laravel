<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'no_registro',
        'giro',
        'usuario_id',
        'celular',
        'logo',
        'rubro',
        'fecha_creacion',
        'cod_clinica',
    ];
    public function accesModulos()
    {
        return $this->hasMany(AccesModulo::class, 'id_empresa');
    }
    

}
