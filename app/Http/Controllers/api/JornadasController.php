<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JornadasController extends Controller
{
    public function getJornadasAll($codigo_clinica = null){

        $empresa = Empresa::where('cod_clinica',$codigo_clinica)->first();
        if($empresa){
            $jornadas = DB::select("SELECT j.id,j.nombre as jornada,j.cat_examenes,DATE_FORMAT(j.fecha_jornada,'%d-%m-%Y') as fecha_jornada,j.hora FROM `jornadas` as j WHERE j.empresa_id = ? and j.cat_examenes = 'OPTOMETRIA' order by j.id desc",[$empresa['id']]);
            return response()->json([
                'results' => $jornadas,
                'status' => 'success'
            ],200);
        }

        return response()->json([
            'results' => [],
            'status' => 'success'
        ]);
    }
}
