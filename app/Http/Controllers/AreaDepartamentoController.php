<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\AreaDepartamentoEmp;
use App\Models\AreaEmp;
use App\Models\CargoEmp;
use App\Models\DetDeptoRiesgo;
use App\Models\DetJerarquia;
use App\Models\Empleado;
use App\Models\FactorRiesgo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaDepartamentoController extends Controller
{
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $det_jerarquia = DetJerarquia::where('id_empresa', $empresa_id)->first();
        return view('AreaDepartamento.index', compact('det_jerarquia'));
    }

    public function save()
    {
        $empresa_id = Auth::user()->empresa_id;
        $departamento = strtoupper(trim(request()->input('area_depto')));
        $data_depto = AreaDepartamentoEmp::where('empresa_id', $empresa_id)->get();
        $exists_depto = false;
        foreach ($data_depto as $item) {
            if (Lucipher::Descipher($item['departamento']) == $departamento) {
                $exists_depto = true;
            }
        }
        if ($exists_depto) {
            return [
                'status' => 'warning',
                'message' => 'Este nombre ya está registrado. Por favor, elige otro.',
            ];
        }

        $saveResult = AreaDepartamentoEmp::create([
            'departamento' => Lucipher::Cipher($departamento),
            'empresa_id' => $empresa_id
        ]);
        if ($saveResult) {
            $saveResult['departamento'] = Lucipher::Descipher($saveResult['departamento']);
            return [
                'status' => 'success',
                'message' => 'Departamento o división creado exitosamente.',
                'results' => $saveResult
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de crear el departamento.',
            'results' => []
        ];
    }

    public function get_factores_riesgos($cargo_id)
    {
        $empresa_id = Auth::user()->empresa_id;

        $data = DB::select("SELECT ddr.factor_riesgo_id, ddr.cargo_id, ddr.categoria, fr.nombre FROM det_depto_riesgos AS ddr INNER JOIN factor_riesgos AS fr ON ddr.factor_riesgo_id = fr.id AND ddr.empresa_id = fr.empresa_id WHERE ddr.categoria = 'cargos' and ddr.cargo_id = ? AND ddr.empresa_id = ? GROUP BY fr.nombre;", [$cargo_id, $empresa_id]);
        return $data;
    }

    public function get_examenes($cargo_id)
    {
        $empresa_id = Auth::user()->empresa_id;

        $data = DB::select("select e.id,ddr.cargo_id,ddr.factor_riesgo_id,e.nombre as examen,ddr.cat_examen,ddr.categoria from det_depto_riesgos as ddr INNER JOIN examenes as e on ddr.examen_id=e.id and ddr.empresa_id=e.empresa_id and ddr.cat_examen='laboratorio clinico' WHERE ddr.categoria='cargos' and ddr.cargo_id = ? and ddr.empresa_id = ? UNION select pe.id,ddr.cargo_id,ddr.factor_riesgo_id,pe.nombre as examen,ddr.cat_examen,ddr.categoria from det_depto_riesgos as ddr INNER JOIN pruebas_especiales as pe on ddr.examen_id=pe.id and ddr.empresa_id=pe.id_empresa and ddr.cat_examen in ('especialidades','complementarios') WHERE ddr.categoria='cargos' and ddr.cargo_id= ? and ddr.empresa_id = ?;", [$cargo_id, $empresa_id, $cargo_id, $empresa_id]);
        return $data;
    }

    public function getDeptoCantEmp()
    {
        $empresa_id = Auth::user()->empresa_id;

        $data_final = [];
        $data_deptos = DB::select("select d.id,d.departamento as nombre from area_departamento_emps as d where d.empresa_id = ?", [$empresa_id]);
        foreach ($data_deptos as $depto) {
            $array['id'] = $depto->id;
            $array['nombre'] = Lucipher::Descipher($depto->nombre);
            $data_area = DB::select("select a.id,a.nombre from area_emps as a where a.id_depto = ? and a.id_empresa=?", [$depto->id, $empresa_id]);
            $data_areas_cargos = [];

            foreach ($data_area as $item) {
                $sub_array = [];
                $sub_array['id'] = $item->id;
                $sub_array['nombre'] = $item->nombre;

                $data_cargos = DB::select("select c.id from cargo_emps as c where c.id_area=? and c.id_empresa=?", [$item->id, $empresa_id]);

                $riesgos_array = [];
                $examenes_array = [];

                foreach ($data_cargos as $cargo) {
                    // Obtener factores de riesgo para el área y cargo
                    $riesgos = $this->get_factores_riesgos($cargo->id);
                    // Obtener exámenes para el área y cargo
                    $examenes = $this->get_examenes($cargo->id);

                    // Guardar los riesgos y exámenes en los arrays acumulativos
                    $riesgos_array = array_merge($riesgos_array, $riesgos);
                    $examenes_array = array_merge($examenes_array, $examenes);
                }

                // Guardar riesgos y exámenes en sub_array
                $sub_array['riesgos'] = $riesgos_array;
                $sub_array['examenes'] = $examenes_array;
                //obtener todos los cargos
                $detalles_cargos = CargoEmp::where('id_area', $item->id)->where('id_empresa', $empresa_id)->get();
                $sub_array['cargos'] = $detalles_cargos;
                $data_areas_cargos[] = $sub_array;
            }
            $array['areas'] = $data_areas_cargos;
            $data_final[] = $array;
        }
        return response()->json($data_final);
    }

    public function saveDeptoRiesgo()
    {
        $empresa_id = Auth::user()->empresa_id;

        $form = request()->all();
        $items_riesgos_examenes = json_decode($form['items_riesgos_examenes']);
        //return response()->json($items_riesgos_examenes);
        try {
            DB::beginTransaction();
            foreach ($items_riesgos_examenes as $item) {
                $area_depto_id = $item->area_depto_id;
                $categoria = $item->categoria;

                $riesgo_examenes = $item->riesgo_examenes;
                if ($categoria == "departamentos") {
                    $data = DB::select("SELECT a.id as area_id,c.id as cargo_id FROM area_emps as a inner join cargo_emps as c on a.id=c.id_area and a.id_empresa=c.id_empresa where a.id_depto = ? and a.id_empresa = ?;", [$area_depto_id, $empresa_id]);
                    foreach ($data as $cargo) {
                        $cargo_id = $cargo->cargo_id;
                        $this->save_det_depto_riesgo($riesgo_examenes, $cargo_id);
                    }
                } else if ($categoria == "areas") {
                    $data = DB::select("SELECT a.id as area_id,c.id as cargo_id FROM area_emps as a inner join cargo_emps as c on a.id=c.id_area and a.id_empresa=c.id_empresa where a.id = ? and a.id_empresa = ?", [$area_depto_id, $empresa_id]);
                    foreach ($data as $cargo) {
                        $cargo_id = $cargo->cargo_id;
                        $this->save_det_depto_riesgo($riesgo_examenes, $cargo_id);
                    }
                } else if ($categoria == "cargos") {
                    $this->save_det_depto_riesgo($riesgo_examenes, $area_depto_id);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Se han registrados nuevos factor de riesgos al departamentos.'
            ]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de registrar los factores de riesgo.',
                'messageError' => $err->getMessage()
            ]);
        }
    }

    public function save_det_depto_riesgo($riesgo_examenes, $cargo_id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;

        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $hora = date('H:i:s');


        foreach ($riesgo_examenes as $examen) {
            //validar que no existe el factor riesgo ya agregado

          /*   $det_depto_riesgos = DB::select("select * from det_depto_riesgos as ddr where ddr.examen_id = ? and ddr.cargo_id = ? and ddr.factor_riesgo_id = ?", [$examen->examen_id, $cargo_id, $examen->riesgo_id]); */

            $det_depto_riesgos = DB::select("select * from det_depto_riesgos as ddr where ddr.cat_examen = ? and ddr.examen_id = ? and ddr.cargo_id = ? and ddr.empresa_id = ?", [$examen->categoria,$examen->examen_id, $cargo_id,$empresa_id]);


            if (count($det_depto_riesgos) === 0) {
                DetDeptoRiesgo::create([
                    'fecha' => $date,
                    'hora' => $hora,
                    'categoria' => 'cargos',
                    'cat_examen' => $examen->categoria,
                    'examen_id' => $examen->examen_id,
                    'cargo_id' => $cargo_id,
                    'factor_riesgo_id' => $examen->riesgo_id,
                    'empresa_id' => $empresa_id,
                    'usuario_id' => $usuario_id
                ]);
            }
        }
    }
    public function rmDetDeptopRiesgos()
    {
        $empresa_id = Auth::user()->empresa_id;
        $cargo_id = request()->input('cargo_id');
        $factor_riesgo_id = request()->input('factor_riesgo_id');
        $categoria = request()->input('categoria');
        try {
            $result = DetDeptoRiesgo::where('categoria', $categoria)->where('cargo_id', $cargo_id)->where('factor_riesgo_id', $factor_riesgo_id)->where('empresa_id', $empresa_id)->delete();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'El factor riesgo se ha removido exitosamente.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ha ocurrido un error al momento de eliminar el factor riesgo.'
                ]);
            }
        } catch (Exception $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de eliminar el factor riesgo.',
                'messageError' => $err->getMessage()
            ]);
        }
    }
    //remover departamento
    public function removeDepartamento()
    {
        $empresa_id = Auth::user()->empresa_id;
        try {
            DB::beginTransaction();
            $depto_id = base64_decode(request()->input('id'));
            //validar si no tiene empleados
            $validExists = Empleado::where('area_depto_id', $depto_id)->where('empresa_id', $empresa_id)->exists();
            if ($validExists) {
                return response()->json([
                    'status' => 'no-delete',
                    'message' => 'El departamento tiene empleados asignados.'
                ]);
            }

            DetDeptoRiesgo::where('area_depto_id', $depto_id)->where('empresa_id', $empresa_id)->delete();

            AreaDepartamentoEmp::where('id', $depto_id)->where('empresa_id', $empresa_id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha eliminado exitosamente el departamento.'
            ]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado al momento de eliminar',
                'messageError' => $err->getMessage()
            ]);
        }
    }

    public function getCargosArea()
    {
        $empresa_id = request()->input('empresa_id');

        $area_id = request()->input('area_id');
        $datos = CargoEmp::where('id_area', $area_id)->where('id_empresa', $empresa_id)->get();
        return response()->json($datos);
    }


    public function getDeptosParaJornadas(){

        $empresa_id = Auth::user()->empresa_id;
        $tipo_exa = request()->input('categoriaSelect');
        
        if ($tipo_exa == "examenes_lab_j") {
            $resultados = DB::select("SELECT dp.id as iddp,dp.departamento, COUNT(c.id) AS total_cargos FROM det_depto_riesgos AS d INNER JOIN examenes AS e ON e.id = d.examen_id INNER JOIN cargo_emps AS c ON c.id = d.cargo_id
            INNER JOIN area_emps AS ca ON c.id_area = ca.id INNER JOIN area_departamento_emps AS dp ON ca.id_depto = dp.id WHERE d.cat_examen = 'laboratorio clinico' AND d.empresa_id = ? GROUP BY dp.departamento", [$empresa_id]);

            $departamentos = [];

            foreach ($resultados as $item) {
                $departamentos[] = [
                    'iddp'=>$item->iddp,
                    'departamento' => Lucipher::Descipher($item->departamento),
                    'total_cargos' => $item->total_cargos
                ];
            }
            return response()->json($departamentos);
        }else{
            //Obtener todos los departamentos, para pruebas especiales
            $resultados = DB::select("SELECT dp.id as iddp,dp.departamento, COUNT(c.id) AS total_cargos FROM area_departamento_emps AS dp inner join area_emps as a on a.id_depto=dp.id and a.id_empresa=dp.empresa_id inner join cargo_emps as c on c.id_area=a.id and c.id_empresa=a.id_empresa WHERE dp.empresa_id = ? GROUP BY dp.departamento;", [$empresa_id]);

            $departamentos = [];

            foreach ($resultados as $item) {
                $departamentos[] = [
                    'iddp'=>$item->iddp,
                    'departamento' => Lucipher::Descipher($item->departamento),
                    'total_cargos' => $item->total_cargos
                ];
            }
            return response()->json($departamentos);
        }
    }
    public function getDeptosByEmpresa(){
        $empresa_id = request()->input('empresa_id');

        $deptos = DB::select("SELECT a.id as area_id,depto.departamento,a.nombre as area FROM `area_emps` as a INNER JOIN area_departamento_emps as depto on a.id_depto=depto.id and a.id_empresa=depto.empresa_id where a.id_empresa = ?",[$empresa_id]);
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item->area_id;
            $array['area'] = Lucipher::Descipher($item->departamento) ."/".$item->area;
            $areas_depto[] = $array;
        }
        return response()->json($areas_depto);
    }
}
