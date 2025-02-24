<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoIncapacidad extends Model
{
    use HasFactory;

    protected $table = 'motivo_incapacidad';

    protected $fillable = [
        'motivo',
        'empresa_id',
    ];

    // Relación con el modelo Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
