<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoEmp extends Model
{
    use HasFactory;

    protected $table = 'cargo_emps';

    protected $fillable = [
        'nombre',
        'id_empresa',
        'id_area',
    ];

    // Relación opcional con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
}
