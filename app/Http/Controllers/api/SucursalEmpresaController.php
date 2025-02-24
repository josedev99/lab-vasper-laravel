<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SucursalEmpresaController extends Controller
{
    public function getSucursalesEmpresa(){
        $codigo_clinica = !is_null(request()->get('codigo_clinica')) ? trim(request()->get('codigo_clinica')) : '';
        if($codigo_clinica == ''){
            return response()->json([
                'status' => 'warning',
                'message' => 'El código de la clínica es obligatorio y no se ha proporcionado.'
            ]);
        }
        $sucursales = DB::select("SELECT s.id as sucursal_id,e.id as empresa_id,s.nombre as sucursal,s.direccion,s.telefono,e.nombre as empresa FROM `empresas` as e inner join sucursals as s on e.id=s.empresa_id WHERE e.cod_clinica = ?;",[$codigo_clinica]);
        return response()->json([
            'status' => 'success',
            'message' => 'Sucursales obtenidas exitosamente',
            'results' => $sucursales
        ]);
    }
}
