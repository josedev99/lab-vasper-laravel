<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    // Define the table name if it's different from the model name in plural form
    protected $table = 'sucursals';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'encargado',
        'fecha',
        'hora',
        'empresa_id',
        'usuario_id'
    ];

    /**
     * Relationship: A 'Sucursal' belongs to an 'Empresa'
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Define any additional relationships or methods as needed
     */
}
