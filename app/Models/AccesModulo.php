<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesModulo extends Model
{
    use HasFactory;

    protected $table = 'acces_modulo';

    protected $fillable = [
        'id_modulo',
        'id_usuario',
        'id_empresa',
    ];

    /**
     * Relación con el modelo Modulo
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }

    /**
     * Relación con el modelo Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación con el modelo Empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }


    // Definir la relación con acces_permisos
    public function accesPermisos()
    {
        return $this->hasMany(AccesPermiso::class, 'id_modulo', 'id_modulo');
    }



}
