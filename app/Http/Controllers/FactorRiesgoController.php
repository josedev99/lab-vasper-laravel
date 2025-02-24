<?php

namespace App\Http\Controllers;

use App\Models\FactorRiesgo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactorRiesgoController extends Controller
{
    public function save(){
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $factor_riesgo = strtoupper(trim(request()->input('factor_riesgo')));
        //validaciones
        if(trim($factor_riesgo) == ""){
            return response()->json([
                'status' => 'errorForm',
                'message' => 'El campo factor riesgo es requerido.',
                'inputName' => 'factor_riesgo'
            ]);
        }
        //validar existencia
        if($this->validExisteFactor($factor_riesgo)){
            return response()->json([
                'status' => 'warning',
                'message' => 'El factor de riesgo ya esta registrado.'
            ]);
        }
        //inserted
        $result = FactorRiesgo::create([
            'nombre' => $factor_riesgo,
            'descripcion' => '-',
            'empresa_id' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if($result){
            return response()->json([
                'status' => 'success',
                'message' => 'Factor riesgo agregado exitosamente.',
                'data' => [
                    'factor_riesgo' => $result['nombre'],
                    'factor_riesgo_id' => $result['id'],
                    'removeStatus' => false,
                    'examenes' => []
                ]
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error, intente nuevamente.'
        ]);
    }

    public function validExisteFactor($stringFactorRiesgo){
        $empresa_id = Auth::user()->empresa_id;
        return FactorRiesgo::where('nombre',$stringFactorRiesgo)->where('empresa_id',$empresa_id)->exists();
    }  

   
}
