<?php

namespace App\Http\Controllers;

use App\Models\PruebaEspecial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamesJornadas extends Controller
{
    public function getExamesCategorias(){
        
        $empresa_id = Auth::user()->empresa_id;
        $pruebas_esp = PruebaEspecial::where('id_empresa', $empresa_id)->get();
        return response()->json($pruebas_esp);
    }
}
