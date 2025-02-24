<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function getSucursalesByEmpresa(){
        $empresa_id = request()->get('empresa_id');
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->select('id','nombre')->get();
        return response()->json($sucursales);
    }
}
