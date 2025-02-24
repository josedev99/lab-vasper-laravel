<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\AreaEmp;
use App\Models\DetJornada;
use App\Models\DetJornadaSeguridadOpacional;
use App\Models\DetResultEvaluacion;
use App\Models\Empleado;
use App\Models\Jornada;
use App\Models\JornadaSeguridadOpacional;
use App\Models\Laboratorio;
use App\Models\PruebaEspecial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JornadaController extends Controller
{
    public function index()
    {
        return view("Jornadas.index");
    }
    public function save_jornada()
    {
        date_default_timezone_set('America/El_Salvador');
        $fecha_actual = date('Y-m-d');
        $usuario_id = Auth::user()->id;
        $form_data = request()->validate([
            'fecha_jornada' => 'required|string|min:5',
            'jornada' => 'required|string|min:2|max:200',
        ]);
        $empresa_id = Lucipher::Descipher(request()->get('empresa_id'));
        //validar si no existe esa jornada
        $jornada = trim(strtoupper($form_data['jornada']));
        $laboratorio = !is_null(request()->get('laboratorio')) ? $form_data['laboratorio'] : 1;

        $exists_jornada = Jornada::where('nombre', $jornada)->where('laboratorio_id', $laboratorio)->where('empresa_id', $empresa_id)->exists();
        if ($exists_jornada) {
            return [
                'status' => 'warning',
                'message' => 'La jornada ya existe, por favor, elige otro nombre.'
            ];
        }
        $save_jornada = Jornada::create([
            'nombre' => $jornada,
            'cat_examenes' => 'laboratorio clinico',
            'fecha_jornada' => $form_data['fecha_jornada'],
            'hora' => $fecha_actual,
            'laboratorio_id' => $laboratorio,
            'empresa_id' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if ($save_jornada) {
            return [
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente la jornada.',
                'result' => [
                    'id' => $save_jornada->id,
                    'nombre' => $save_jornada->nombre,
                    'fecha_jornada' => date('d-m-Y'),
                ]
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar la jornada.'
        ];
    }

    public function save_lab()
    {
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;

        $form_data = request()->validate([
            'nombre_lab' => 'required|string|min:2|max:200',
            'telefono_lab' => 'required|string|min:8|max:15',
            'direccion_lab' => 'required|string|min:1|max:200',
        ]);
        //validar si no existe esa jornada
        $nombre = trim(strtoupper($form_data['nombre_lab']));

        $exists_jornada = Laboratorio::where('laboratorio', $nombre)->where('empresa_id', $empresa_id)->exists();
        if ($exists_jornada) {
            return [
                'status' => 'warning',
                'message' => 'El laboratorio ya existe, por favor, elige otro nombre.'
            ];
        }
        $save_lab = Laboratorio::create([
            'laboratorio' => $nombre,
            'direccion' => trim(strtoupper($form_data['direccion_lab'])),
            'telefono' => $form_data['telefono_lab'],
            'empresa_id' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if ($save_lab) {
            return [
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente el laboratorio.',
                'results' => $save_lab
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar el laboratorio.'
        ];
    }

    public function save_seg_ocupacional()
    {
        date_default_timezone_set('America/El_Salvador');
        $hora = date('H:i:s');

        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;

        $data_form = request()->all();
        //object
        $data_form['items_factor_riesgo'] = json_decode($data_form['items_factor_riesgo']);
        //return response()->json($data_form['items_factor_riesgo'][0]->examenes);

        $jornada = strtoupper(trim($data_form['nombre_jornada']));
        $fecha_jornada = $data_form['fecha_jornada'];
        //validacion
        if ($jornada == "" || $fecha_jornada == "") {
            return response()->json([
                'status' => 'error',
                'message' => 'Los campos no deben estar vacios.'
            ]);
        }
        $items_factor_riesgo = $data_form['items_factor_riesgo'];
        //save
        DB::beginTransaction();
        try {
            $save_jornadad = JornadaSeguridadOpacional::create([
                'nombre' => $jornada,
                'fecha' => $fecha_jornada,
                'hora' => $hora,
                'empresa_id' => $empresa_id,
                'usuario_id' => $usuario_id
            ]);
            foreach ($items_factor_riesgo as $item) {
                $departamento_id = $item->id;
                $data = $item->riesgos;
                foreach ($data as $value) {
                    DetJornadaSeguridadOpacional::create([
                        'departamento_id' => $departamento_id,
                        'factor_riesgo_id' => $value->factor_riesgo_id,
                        'jornada_id' => $save_jornadad->id,
                        'empresa_id' => $empresa_id
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'La jornada de riesgo ocupacional se ha registrado exitosamente.'
            ]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de registrar la jornada.'
            ]);
        }
    }
    //obtener la cantidad de registros de la jornada
    public function getCantRiesgoOcupacional()
    {
        $empresa_id = Auth::user()->empresa_id;
        $jornadasSegOcup = DB::select("SELECT so.id, CONCAT('Jornadas: ', COUNT(so.id)) AS title, so.fecha_jornada AS start FROM jornadas as so WHERE so.empresa_id = ? GROUP BY so.fecha_jornada;", [$empresa_id]);
        return response()->json($jornadasSegOcup);
    }

    public function  getDataDetJornada()
    {
        $empresa_id = Auth::user()->empresa_id;
        $id_depto = request()->input('id_depto');
        ///obtener areas
        $result_areas = [];
        $areas = AreaEmp::where('id_depto', $id_depto)->get();
        foreach ($areas as $area) {
            $result_areas[] = [
                'nombre' => $area->nombre,
                'id' => $area->id,
                'idp'=>$id_depto
            ];
        }

        return [
            'result_areas' => $result_areas
        ];
    }

    public function getAreaExaColab()
    {
        $empresa_id = Auth::user()->empresa_id;
        $id_area = request()->input('id_area');
        $idpto = request()->input('idpto');
        $area = request()->input('area');
        $colaboradores = Empleado::where('area_depto_id', $id_area)->get();
       
        $empExa = [];
        if (count($colaboradores) > 0) {
            foreach ($colaboradores as $colaborador) {
                $empleado = Lucipher::Descipher($colaborador->nombre);
                $cargo_id = $colaborador->cargo_id;

                $examenes = DB::select("select e.cargo_id,dr.factor_riesgo_id,e.nombre,ex.id as id_exa,ex.nombre as examen_nombre,dr.cat_examen from empleados as e inner join det_depto_riesgos as dr on dr.cargo_id=e.cargo_id inner join examenes as ex on ex.id=dr.examen_id where dr.cat_examen='laboratorio clinico' and e.cargo_id=? and e.id = ? and e.empresa_id=?;", [$cargo_id, $colaborador['id'], $empresa_id]);
                if (!empty($examenes)) {
                    $examenes_nombres = array_map(function ($examen) {
                        return [
                            'id_exa' => $examen->id_exa,
                            'examen_nombre' => $examen->examen_nombre,
                            'cat_examen' => 'laboratorio clinico'
                        ];
                    }, $examenes);

                    $empExa[] = [
                        "cargo" => $cargo_id,
                        "empleado" => $empleado,
                        "examenes" => $examenes_nombres,
                        'id_area' => $id_area,
                        'idpto'=>$idpto,
                        'area'=>$area,
                        'empleado_id' => $colaborador['id']
                    ];
                }
            }
        }
        return $empExa;
    }

    public function registrarJornada(Request $request){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        //return request()->all();
        try {
            DB::beginTransaction();
            $nombrejornada = strtoupper(trim($request->input('nombre_jornada')));
            $fecha_jornada = $request->input('fecha_jornada');
            $tipo_examenes = $request->input('exa_jornadas');
            $tipo_ex_jornada = $request->input('tipo_ex_jornada');

            $datadeptos = json_decode($request->input('detdeptos'), true);
            //validacion para el nombre de la jornada
            if($nombrejornada === ""){
                return response()->json([
                    'status' => 'warning',
                    'message' => 'El nombre de la jornada es obligatorio.'
                ]);
            }
            //validacion para evitar dos jornadas con el mismo nombre y la misma fecha
            $exists = Jornada::where('nombre',$nombrejornada)->where('fecha_jornada',$fecha_jornada)->where('empresa_id',$empresa_id)->exists();
            if($exists){
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Ya existe una jornada registrada para la fecha seleccionada.'
                ]);
            }
            //validacion
            if($tipo_examenes == "examenes_lab_j"){
                $cat_examenes = 'laboratorio clinico';
            }else if($tipo_examenes == "OPTOMETRIA"){
                $cat_examenes = 'OPTOMETRIA';
            }else{
                //obtener el nombre del examen especial
                $prueba_especial = PruebaEspecial::where('id',(int)$tipo_examenes)->where('id_empresa',$empresa_id)->select('id','nombre')->first();
                if($prueba_especial){
                    $cat_examenes = trim($prueba_especial['nombre']);
                    if($cat_examenes == "SALUD VISUAL (OPTOMETRIA)"){
                        $cat_examenes = "OPTOMETRIA";
                    }
                }else{
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Ha ocurrido un error al registrar la jornada.'
                    ]);
                }
            }

            $jornada = Jornada::create([
                'nombre' => $nombrejornada,
                'cat_examenes' => $cat_examenes,
                'fecha_jornada' => $fecha_jornada,
                'hora' => $hora,
                'laboratorio_id' => $tipo_ex_jornada,
                'empresa_id' => $empresa_id,
                'usuario_id' => $usuario_id
            ]);

            foreach($datadeptos as $item){
                $cargo = $item['cargo'];

                $examenes = $item['examenes'];
                $empleado_id = $item['empleado_id'];

                DetResultEvaluacion::create([
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'estado' =>  'Sin evaluar',
                    'jornada_id' => $jornada->id,
                    'empleado_id' => $empleado_id,
                    'empresa_id' => $empresa_id,
                    'usuario_id' => $usuario_id
                ]);

                foreach($examenes as $examen){
                    DetJornada::create([
                        'estado' => 'Pendiente',
                        'evaluacion' => '-',
                        'cat_examen' => $examen['cat_examen'],
                        'examen_id' => $examen['id_exa'],
                        'jornada_id' => $jornada->id,
                        'empleado_id' => $empleado_id,
                        'empresa_id' => $empresa_id,
                        'usuario_id' => $usuario_id
                    ]);
                }

            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'La jornada se ha registrado exitosamente.',
                'result' =>  [
                    'jornada_id' => $jornada->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar los datos',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function listarJornadas(){
        $empresa_id = Auth::user()->empresa_id;

        $fecha_jornada = request()->input('fecha_jornada');

        $datos = DB::select("SELECT j.id,j.nombre as jornada,j.cat_examenes,j.fecha_jornada,j.hora,lab.laboratorio,COUNT(DISTINCT dj.empleado_id) AS cant_empleado FROM `jornadas` as j inner join laboratorios as lab on j.laboratorio_id=lab.id and j.empresa_id=lab.empresa_id inner join det_jornadas as dj on j.id=dj.jornada_id and j.empresa_id=dj.empresa_id where j.fecha_jornada = ? and j.empresa_id = ? group by j.id,j.nombre",[$fecha_jornada,$empresa_id]);
        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();

            $sub_array[] = $contador;
            $sub_array[] = strtoupper($row->jornada);
            $sub_array[] = strtoupper($row->cat_examenes);
            $sub_array[] = date('d-m-Y H:i:s',strtotime($row->fecha_jornada . " ". $row->hora));
            $sub_array[] = strtoupper($row->laboratorio);
            $sub_array[] = $row->cant_empleado . ' COLABORADORES';

            $sub_array[] = '
            <button data-jornada_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. $row->jornada .'" title="Modificar la jornada" class="btn btn-outline-info btn-sm" onclick="editJornada(this)" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>

            <button data-jornada_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. $row->jornada .'" title="Mostrar detalles de la jornada" class="btn btn-outline-info btn-sm" onclick="showDetails(this)" style="border:none;font-size:18px"><i class="bi bi-eye"></i></button>
            ';

            $data[] = $sub_array;
            $contador ++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function listarDetJornada(){
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $datos = $this->getDetJornada($jornada_id);

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();

            $examenes = $row['examenes'];

            $examen = array_column($examenes, 'examen');

            // Une los nombres en una cadena de texto separada por comas
            $strExamenes = implode(', ', $examen);

            $sub_array[] = $contador;
            $sub_array[] = '<td style="text-align:center">' . strtoupper($row['depto']) . '</td>';
            $sub_array[] = '<td style="text-align:center">' . strtoupper($row['area']) . '</td>';
            $sub_array[] = strtoupper($row['cargo']);
            $sub_array[] = strtoupper($row['colaborador']);
            $sub_array[] = strtoupper($strExamenes);

            $data[] = $sub_array;
            $contador ++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function getDetJornada($jornada_id){
        $empresa_id = Auth::user()->empresa_id;
        //get data jornada
        $detJornada = DetJornada::where('jornada_id',$jornada_id)->where('empresa_id',$empresa_id)->get();
        $data_final = [];
        foreach($detJornada as $det){
            $colaborador = DB::select("SELECT depto.departamento,a.nombre as area,c.nombre as cargo, e.id as empleado_id, e.nombre as colaborador FROM `empleados` as e inner join area_emps as a on e.area_depto_id=a.id and e.empresa_id=a.id_empresa inner join cargo_emps as c on e.cargo_id=c.id and e.empresa_id=c.id_empresa inner join area_departamento_emps as depto on a.id_depto=depto.id and a.id_empresa=depto.empresa_id where e.id = ? and e.empresa_id = ?;",[$det['empleado_id'],$empresa_id]);

            $examenes = DB::select("select e.id as id_exa, e.nombre as examen from examenes as e WHERE e.id = ? and e.empresa_id = ? UNION select pe.id as id_exa, pe.nombre as examen from pruebas_especiales as pe WHERE pe.id = ? and pe.id_empresa = ?",[$det['examen_id'],$empresa_id,$det['examen_id'],$empresa_id]);

            $empleado = Lucipher::Descipher($colaborador[0]->colaborador);
            $depto = Lucipher::Descipher($colaborador[0]->departamento);

            if (!isset($data_final[$empleado])) {
                $data_final[$empleado] = [
                    'id_empleado' => $colaborador[0]->empleado_id,
                    'area' => $colaborador[0]->area,
                    'cargo' => $colaborador[0]->cargo,
                    'depto' => $depto,
                    'colaborador' => $empleado,
                    'examenes' => []
                ];
            }

            $data_final[$empleado]['examenes'][] = [
                'id_exa' => $examenes[0]->id_exa,
                'examen' => $examenes[0]->examen,
            ];
        }

        return array_values($data_final);
    }

    public function getDataJornada(){
        $empresa_id = Auth::user()->empresa_id;

        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));

        $data = Jornada::where('id',$jornada_id)->where('empresa_id', $empresa_id)->select('id','nombre','fecha_jornada')->first();
        session(['jornada_selected' => $data]);

        return response()->json($data);
    }

    public function updateJornada(){
        $empresa_id = Auth::user()->empresa_id;
        //request form
        $nombre_jornada = strtoupper(trim(request()->input('jornada_up_nombre')));
        $fecha_jornada = request()->input('fecha_up_jornada');

        $jornada = session()->get('jornada_selected');
        //validaciones
        if(strtoupper($jornada['nombre']) != $nombre_jornada){
            $exists = Jornada::where('nombre', $nombre_jornada)->where('fecha_jornada',$fecha_jornada)->where('empresa_id',$empresa_id)->exists();
            if($exists){
                return response()->json([
                    'status' => 'exists',
                    'message' => 'Ya existe una jornada para la fecha selecionada.'
                ]);
            }
        }

        $updJornada = Jornada::where('id',$jornada['id'])->where('empresa_id',$empresa_id)->update([
            'nombre' => $nombre_jornada,
            'fecha_jornada' => $fecha_jornada
        ]);
        if($updJornada){
            return response()->json([
                'status' => 'success',
                'message' => 'La jornada se actualizado exitosamente.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de actualizar.'
        ]);
    }
    //obtener empleados por riesgo o departamentos

    public function  getDataEmplRiesgoDepto()
    {
        $empresa_id = Auth::user()->empresa_id;

        $option = request()->input('option');
        $examen_id = request()->input('examen_id');
        $id_depto = request()->input('depto_id');

        //validacion para obtener segun opcion
        if($option == "riesgo"){
            $colaboradores = DB::select("SELECT ddr.cargo_id,a.nombre as area,ddr.cat_examen,ddr.examen_id,e.id,e.nombre,e.area_depto_id as area_id,depto.id as depto_id FROM `det_depto_riesgos` as ddr inner join pruebas_especiales as pr on ddr.examen_id=pr.id and ddr.cat_examen=pr.categoria and ddr.empresa_id=pr.id_empresa inner join empleados as e on ddr.cargo_id=e.cargo_id and ddr.empresa_id=e.empresa_id inner join area_emps as a on e.area_depto_id=a.id and e.empresa_id=a.id_empresa INNER join area_departamento_emps as depto on a.id_depto=depto.id and e.empresa_id=depto.empresa_id WHERE depto.id = ? and ddr.examen_id = ? and ddr.empresa_id = ?",[$id_depto,$examen_id,$empresa_id]);
            $empExa = [];
            foreach ($colaboradores as $colaborador) {
                $empleado = Lucipher::Descipher($colaborador->nombre);
                $cargo_id = $colaborador->cargo_id;
                $id_area = $colaborador->area_id;
                $idpto = $colaborador->depto_id;
                $area = $colaborador->area;

                $examenes = DB::select("select e.cargo_id,dr.factor_riesgo_id,e.nombre,pe.id as id_exa,pe.nombre as examen_nombre,dr.cat_examen from empleados as e inner join det_depto_riesgos as dr on dr.cargo_id=e.cargo_id inner join pruebas_especiales as pe on pe.id=dr.examen_id where dr.cat_examen=pe.categoria and e.cargo_id = ? and e.id = ? and pe.id = ? and e.empresa_id = ?", [$cargo_id, $colaborador->id,$colaborador->examen_id, $empresa_id]);
                if (!empty($examenes)) {
                    $examenes_nombres = array_map(function ($examen) {
                        return [
                            'id_exa' => $examen->id_exa,
                            'examen_nombre' => $examen->examen_nombre,
                            'cat_examen' => $examen->cat_examen
                        ];
                    }, $examenes);

                    $empExa[] = [
                        "cargo" => $cargo_id,
                        "empleado" => $empleado,
                        "examenes" => $examenes_nombres,
                        'id_area' => $id_area,
                        'idpto'=>$idpto,
                        'area'=>$area,
                        'empleado_id' => $colaborador->id
                    ];
                }
            }

            return $empExa;

        }else if($option == "depto"){
            $colaboradores = DB::select("select e.id,e.nombre,e.cargo_id,e.area_depto_id as area_id,a.id_depto as depto_id,a.nombre as area from area_emps as a inner join empleados as e on a.id=e.area_depto_id and a.id_empresa=e.empresa_id where a.id_depto = ?",[$id_depto]);
            
            $empExa = [];
            foreach ($colaboradores as $colaborador) {
                $empleado = Lucipher::Descipher($colaborador->nombre);
                $cargo_id = $colaborador->cargo_id;
                $id_area = $colaborador->area_id;
                $idpto = $colaborador->depto_id;
                $area = $colaborador->area;

                $examenes = DB::select("select pe.id as id_exa,pe.categoria,pe.nombre as examen_nombre from pruebas_especiales as pe where pe.id = ? and pe.id_empresa = ?", [$examen_id, $empresa_id]);
                if (!empty($examenes)) {
                    $examenes_nombres = array_map(function ($examen) {
                        return [
                            'id_exa' => $examen->id_exa,
                            'examen_nombre' => $examen->examen_nombre,
                            'cat_examen' => $examen->categoria
                        ];
                    }, $examenes);

                    $empExa[] = [
                        "cargo" => $cargo_id,
                        "empleado" => $empleado,
                        "examenes" => $examenes_nombres,
                        'id_area' => $id_area,
                        'idpto'=>$idpto,
                        'area'=>$area,
                        'empleado_id' => $colaborador->id
                    ];
                }
            }

            return $empExa;
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Valores alterados manualmente.'
            ]);
        }
    }

    public function getJornadasByEmpresa(){
        $empresa_id = Lucipher::Descipher(request()->get('empresa_id'));
        $validFiltrarFecha = !is_null(request()->get('filtrar_fecha')) ? request()->get('filtrar_fecha') : false;
        if($validFiltrarFecha){
            $rango_fechas = request()->input('fechas');
            $array_fechas = explode(' a ', $rango_fechas);
            $fecha_inicio = '';
            $fecha_fin = '';
            if(count($array_fechas) > 1){
                $fecha_inicio = date('Y-m-d',strtotime(str_replace('/','-',$array_fechas[0])));
                $fecha_fin = date('Y-m-d',strtotime(str_replace('/','-',$array_fechas[1])));
            }
            $jornadas = Jornada::where('empresa_id',$empresa_id)->whereBetween('fecha_jornada', [$fecha_inicio, $fecha_fin])->where('cat_examenes','!=','OPTOMETRIA')->select('id','nombre')->orderBy('id','desc')->get();
        }else{
            $jornadas = Jornada::where('empresa_id',$empresa_id)->where('cat_examenes','!=','OPTOMETRIA')->select('id','nombre')->orderBy('id','desc')->get();
        }
        return response()->json($jornadas);
    }
}
