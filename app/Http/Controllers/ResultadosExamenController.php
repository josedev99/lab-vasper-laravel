<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\DetJornada;
use App\Models\DetResultEvaluacion;
use App\Models\Empresa;
use App\Models\ExamenesCategoria;
use App\Models\Jornada;
use App\Models\ResultAcidoUrico;
use App\Models\ResultGlucosa;
use App\Models\Sucursal;
use FontLib\Table\Type\post;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ResultadosExamenController extends Controller
{
    public function index(){
        $empresa_id = Auth::user()->empresa_id;
        $data_jornadas = Jornada::where('empresa_id',$empresa_id)->where('cat_examenes','!=','OPTOMETRIA')->select('id','nombre','fecha_jornada')->orderBy('id','DESC')->get();
        $jornadas = [];
        foreach($data_jornadas as $item){
            $array = [];
            $array['id'] = Lucipher::Cipher($item['id']);
            $array['nombre'] = $item['nombre'];
            $array['fecha'] = date('d-m-Y',strtotime($item['fecha_jornada']));
            $jornadas[] = $array;
        }
        return view('Resultado.index',compact('jornadas'));
    }

    public function listar_empleados_examen(){
        $empresa_id = Auth::user()->empresa_id;
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $data = [];

        if(!$jornada_id){
            return response()->json([
                "sEcho" => 1, // Información para el datatables
                "iTotalRecords" => count($data), // enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
                "aaData" => $data
            ]);
        }
        //validacion para optometria
        $jornada = Jornada::where('id',$jornada_id)->where('empresa_id',$empresa_id)->first();

        if($jornada['cat_examenes'] == "OPTOMETRIA"){
            $empresa = Empresa::where('id',$empresa_id)->first();

            $codigo_clinica = $empresa['cod_clinica'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token
            ])->get($this->url_base ."resultados/" . $jornada_id . "/" .$codigo_clinica);

            $response = json_decode($response);
            $datos = $response->results;
            $contador = 1;
            foreach ($datos as $row) {
                $spanEstado = '';
                $result = $this->getEstadoEvalOpto($row->empleado_id,$row->jornada_id,$row->consulta_id,"estado");
                if($result['estado'] == "Pendiente"){
                    $spanEstado = '<span class="badge bg-danger"><i class="bi bi-check-circle me-1"></i>Sin evaluar</span>';
                }else if($result['estado'] == "Proceso"){
                    $spanEstado = '<span class="badge bg-warning"><i class="bi bi-check-circle me-1"></i> En proceso</span>';
                }else if($result['estado'] === "Finalizado"){
                    $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Evaluado</span>';
                }
                $sub_array = array();
                $sub_array[] = $contador;
                $sub_array[] = $row->codigo_empleado;
                $sub_array[] = $row->colaborador;
                $sub_array[] = $row->telefono;
                $sub_array[] = $row->depto_area;
                $sub_array[] = $spanEstado;
                $sub_array[] = $row->empresa;
                $sub_array[] = '<button class="btn btn-outline-info btn-sm" data-genero="'.$row->genero.'" data-nombre_empleado="'.$row->colaborador.'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" data-consulta_id="'.Lucipher::Cipher($row->consulta_id).'" title="Visualizar resultados" onclick="showResultOptoConsult(this)" style="border:none"><i class="bi bi-eye"></i>';

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

        }else{
            $datos = DB::select("SELECT e.id as empleado_id,dj.jornada_id, e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,s.nombre as sucursal FROM `det_jornadas` as dj INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id where dj.jornada_id = ? GROUP by dj.empleado_id",[$jornada_id]);
            $contador = 1;
            foreach ($datos as $row) {
                $spanEstado = '';
                $estado = $this->getEstadoPacienteExamens($row->jornada_id,$row->empleado_id);
                if($estado == "Pendiente"){
                    $spanEstado = '<span class="badge bg-danger"><i class="bi bi-check-circle me-1"></i>Sin evaluar</span>';
                }else if($estado == "Proceso"){
                    $spanEstado = '<span class="badge bg-warning"><i class="bi bi-check-circle me-1"></i> En proceso</span>';
                }else if($estado === "Finalizado"){
                    $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Evaluado</span>';
                }
                $sub_array = array();
                $sub_array[] = $contador;
                $sub_array[] = $row->codigo_empleado;
                $sub_array[] = Lucipher::Descipher($row->nombre);
                $sub_array[] = $row->telefono;
                $sub_array[] = $row->area;
                $sub_array[] = $spanEstado;
                $sub_array[] = $row->sucursal;
                $sub_array[] = '
                <button onclick="redirectToExamenes(this)" data-nombre_empleado="'.Lucipher::Descipher($row->nombre).'" data-empleado_id="'.rawurlencode(Lucipher::Cipher($row->empleado_id)).'" data-jornada_id="'.rawurlencode(Lucipher::Cipher($row->jornada_id)).'" title="Ingresar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-file-earmark-plus"></i></button>';

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
    }
    public function resultConsultOpto(){
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $consulta_id = Lucipher::Descipher(request()->input('consulta_id'));

        if($empleado_id && $jornada_id && $consulta_id){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])->post($this->url_base ."consulta-por-jornada",[
                'empleado_id' => $empleado_id,
                'jornada_id' => $jornada_id,
                'consulta_id' => $consulta_id
            ]);
            return $response;
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Se ha detectado valores alterados manualmente.'
        ]);

    }
    //obtener estado del conjunto de examenes
    public function getEstadoPacienteExamens($jornada_id,$empleado_id){
        $empresa_id = Auth::user()->empresa_id;
        $estados = DetJornada::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->groupBy('estado')->get();
        if(count($estados) == 1){
            return $estados[0]['estado'];
        }else if(count($estados) == 2){
            return 'Proceso';
        }
    }
    //obtener estado de examen de optometria
    public function getEstadoEvalOpto($empleado_id,$jornada_id,$consulta_id,$buscar){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->post($this->url_base ."obtener-estado-evaluacion",[
            'empleado_id' => $empleado_id,
            'jornada_id' => $jornada_id,
            'consulta_id' => $consulta_id,
            'buscar' => $buscar
        ]);
        return json_decode($response,true);
    }
    //vista examenes
    public function detExamenesPaciente(){
        $empleado_id = Lucipher::Descipher(rawurldecode(request()->query('key1')));
        $jornada_id = Lucipher::Descipher(rawurldecode(request()->query('key2')));
        if($empleado_id && $jornada_id){
            return view('Resultado.detExamenesEmp');
        }else{
            return redirect()->route('resultado.index');
        }
    }

    public function getDetExamenesPaciente(){
        $empresa_id = Auth::user()->empresa_id;

        $empleado_id = Lucipher::Descipher(rawurldecode(request()->input('empleado_id')));
        $jornada_id = Lucipher::Descipher(rawurldecode(request()->input('jornada_id')));

        $datos = DB::select("SELECT e.id as empleado_id,dj.jornada_id,j.fecha_jornada, e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,s.nombre as sucursal,dj.examen_id,ex.nombre as examen,dj.cat_examen,dj.evaluacion FROM `det_jornadas` as dj inner join jornadas as j on dj.jornada_id=j.id and dj.empresa_id=j.empresa_id INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN examenes as ex on dj.examen_id=ex.id and dj.empresa_id=ex.empresa_id where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? and dj.cat_examen='laboratorio clinico' UNION SELECT e.id as empleado_id,dj.jornada_id,j.fecha_jornada,e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,s.nombre as sucursal,dj.examen_id,pr.nombre as examen,dj.cat_examen,dj.evaluacion FROM `det_jornadas` as dj inner join jornadas as j on dj.jornada_id=j.id and dj.empresa_id=j.empresa_id INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN pruebas_especiales as pr on dj.examen_id=pr.id and dj.empresa_id=pr.id_empresa where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? and dj.cat_examen in ('especialidades','complementarios');",[$jornada_id,$empleado_id,$empresa_id,$jornada_id,$empleado_id,$empresa_id]);
        
        $data = [];
        $contador = 1;
        foreach ($datos as $row) {
            $spanEstado = '';
            $spanEvaluacion = '';
            if($row->estado == "Pendiente"){
                $spanEstado = '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i> Sin evaluar</span>';
            }else if($row->estado === "Finalizado"){
                $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Evaluado</span>';
            }
            if($row->evaluacion == "Normal"){
                $spanEvaluacion = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Normal</span>';
            }else if($row->evaluacion === "Alterado"){
                $spanEvaluacion = '<span class="badge bg-warning"><i class="bi bi-exclamation-octagon me-1"></i> Alterado</span>';
            }else if($row->evaluacion === "-"){
                $spanEvaluacion = '<span class="badge bg-info"><i class="bi bi-exclamation-octagon me-1"></i> Sin evaluar</span>';
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d-m-Y',strtotime($row->fecha_jornada));
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = Lucipher::Descipher($row->nombre);
            $sub_array[] = $row->sucursal;
            $sub_array[] = $row->examen;
            $sub_array[] = $spanEvaluacion;
            $sub_array[] = $spanEstado;
            $sub_array[] = '
            <button onclick="ingresarResultado(this)" data-examen="'.$row->examen.'" data-cat_examen="'.Lucipher::Cipher($row->cat_examen).'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" data-examen_id="'.Lucipher::Cipher($row->examen_id).'" title="Ingresar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-file-earmark-plus"></i></button>
            ';
            $sub_array[] = '
            <button onclick="addImageExamen(this)" data-examen="'.$row->examen.'" data-cat_examen="'.Lucipher::Cipher($row->cat_examen).'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" data-examen_id="'.Lucipher::Cipher($row->examen_id).'" title="Ingresar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-image"></i></button>
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

    public function saveResultExamen(){
        $empresa_id = Auth::user()->empresa_id;

        $optionResultado = request()->input('optionResultado');
        $cat_examen = Lucipher::Descipher(request()->input('cat_examen'));
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $examen_id = Lucipher::Descipher(request()->input('examen_id'));
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));

        $result_save = DetJornada::where('cat_examen',$cat_examen)->where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
            'evaluacion' => $optionResultado,
            'estado' => 'Finalizado'
        ]);

        if ($result_save) {
            return response()->json([
                'status' => 'success',
                'message' => 'El resultado del examen se ingresó correctamente.'
            ]);
        }
        return response()->json([
            'status' => 'warning',
            'message' => 'Ha ocurrido un error al ingresar el resultado del examen.'
        ]);
    }

    public function getResultadoByExamen(){
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $examen_id = Lucipher::Descipher(request()->input('examen_id'));
        $cat_examen = Lucipher::Descipher(request()->input('cat_examen'));

        $detJornada = DetJornada::where('cat_examen',$cat_examen)->where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->select('evaluacion')->first();

        $datos = $this->getResultExamenById($examen_id,$empleado_id,$jornada_id,$cat_examen);

        if($datos && $detJornada){
            return response()->json([
                'evaluacion' => $detJornada['evaluacion'],
                'resultado' => $datos
            ]);
        }
        return response()->json([
            'status' => 'warning',
            'message' => 'No se encontraron resultados para el examen'
        ]);
    }
    //Resultados para atencion de resultados
    public function listar_atencion_resultados(){
        $empresa_id = Auth::user()->empresa_id;

        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $resultado = request()->input('resultado');
        $data = [];

        if(!$jornada_id){
            return response()->json([
                "sEcho" => 1, // Información para el datatables
                "iTotalRecords" => count($data), // enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
                "aaData" => $data
            ]);
        }

        $jornada = Jornada::where('id',$jornada_id)->where('empresa_id',$empresa_id)->first();

        if($jornada['cat_examenes'] == "SALUD VISUAL (OPTOMETRIA)"){
            $empresa = Empresa::where('id',$empresa_id)->first();

            $codigo_clinica = $empresa['cod_clinica'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token
            ])->get($this->url_base ."resultados/" . $jornada_id . "/" .$codigo_clinica);

            $response = json_decode($response);
            $datos = $response->results;
            $contador = 1;
            foreach ($datos as $row) {
                $spanEstado = '';
                $result = $this->getEstadoEvalOpto($row->empleado_id,$row->jornada_id,$row->consulta_id,"eval");
                if($result['eval'] != "-"){
                    $colorBadge = ($result['eval'] == "Normal") ? 'bg-success' : 'bg-danger';
                    $spanEstado = '<span class="badge '.$colorBadge.'"><i class="bi bi-check-circle me-1"></i>'.$result['eval'].' </span>';
    
                    $sub_array = array();
                    $sub_array[] = $contador;
                    $sub_array[] = $row->codigo_empleado;
                    $sub_array[] = $row->colaborador;
                    $sub_array[] = $row->telefono;
                    $sub_array[] = $row->depto_area;
                    $sub_array[] = $spanEstado;
                    $sub_array[] = $row->empresa;
                    $sub_array[] = '<button class="btn btn-outline-info btn-sm" data-genero="'.$row->genero.'" data-nombre_empleado="'.$row->colaborador.'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" data-consulta_id="'.Lucipher::Cipher($row->consulta_id).'" title="Visualizar resultados" onclick="showResultOpto(this)" style="border:none"><i class="bi bi-eye"></i>';
    
                    $data[] = $sub_array;
                    $contador ++;
                }
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

        }else{
            //validacion de resultados alterados
            $datos = $this->getAtencionEmpResult($resultado,$jornada_id);

            $contador = 1;
            foreach ($datos as $row) {
                $spanEstado = '';
                $estado = $row->estado_evaluacion;
                if($estado == "Sin evaluar"){
                    $spanEstado = '<span class="badge bg-danger"><i class="bi bi-check-circle me-1"></i>Sin evaluar</span>';
                }else if($estado === "Evaluado"){
                    $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Evaluado</span>';
                }
                $sub_array = array();
                $sub_array[] = $contador;
                $sub_array[] = $row->codigo_empleado;
                $sub_array[] = Lucipher::Descipher($row->colaborador);
                $sub_array[] = $row->telefono;
                $sub_array[] = $row->area;
                $sub_array[] = $spanEstado;
                $sub_array[] = $row->sucursal;
                $sub_array[] = '
                <button type="button" onclick="showResultadosPac(this)" data-nombre_empleado="'.Lucipher::Descipher($row->colaborador).'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" title="Visualizar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-clipboard-pulse"></i></button>';

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
    }

    public function getAtencionEmpResult($resultado,$jornada_id){
        $empresa_id = Auth::user()->empresa_id;

        $sql_base = "SELECT dj.id,dj.jornada_id,dj.empleado_id,e.codigo_empleado,e.nombre as colaborador,e.telefono,a.nombre as area,c.nombre as cargo,COALESCE(dre.estado,'Sin evaluar') as estado_evaluacion,dj.estado,dj.evaluacion,s.nombre as sucursal FROM `det_jornadas`  as dj inner join empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id inner join area_emps as a on e.area_depto_id=a.id and e.empresa_id=a.id_empresa INNER join cargo_emps as c on e.cargo_id=c.id and e.empresa_id=c.id_empresa left join det_result_evaluacions as dre on dj.jornada_id=dre.jornada_id and dj.empleado_id=dre.empleado_id and dj.empresa_id=dre.empresa_id inner join sucursals as s on e.sucursal_id=s.id and e.empresa_id = s.empresa_id";

        $datos = [];
        switch($resultado){
            case "1":
                $datos = DB::select($sql_base . " where dj.jornada_id = ? and dj.evaluacion = 'Alterado' and dj.empresa_id = ? GROUP by dj.empleado_id,dj.evaluacion HAVING count(dj.evaluacion) = 1 and SUM(CASE WHEN dj.estado != 'Finalizado' THEN 1 ELSE 0 END) = 0;",[$jornada_id,$empresa_id]);
                break;
            case "2":
                $datos = DB::select($sql_base ." where dj.jornada_id = ? and dj.evaluacion = 'Alterado' and dj.empresa_id = ? GROUP by dj.empleado_id,dj.evaluacion HAVING count(dj.evaluacion) = 2 and SUM(CASE WHEN dj.estado != 'Finalizado' THEN 1 ELSE 0 END) = 0;",[$jornada_id,$empresa_id]);
                break;
            case "3":
                $datos = DB::select($sql_base . " where dj.jornada_id = ? and dj.evaluacion = 'Alterado' and dj.empresa_id = ? GROUP by dj.empleado_id,dj.evaluacion HAVING count(dj.evaluacion) >= 3 and SUM(CASE WHEN dj.estado != 'Finalizado' THEN 1 ELSE 0 END) = 0;",[$jornada_id,$empresa_id]);
                break;
            case "Normales":
                $datos = DB::select($sql_base . " where dj.jornada_id = ? and dj.empresa_id = ? GROUP by dj.empleado_id HAVING  SUM(CASE WHEN dj.evaluacion != 'Normal' THEN 1 ELSE 0 END) = 0 and SUM(CASE WHEN dj.estado != 'Finalizado' THEN 1 ELSE 0 END) = 0;",[$jornada_id,$empresa_id]);
                break;
            default:
                $datos = DB::select($sql_base . " where dj.jornada_id = ? and dj.empresa_id = ? GROUP by dj.empleado_id HAVING SUM(CASE WHEN dj.estado != 'Finalizado' THEN 1 ELSE 0 END) = 0;",[$jornada_id,$empresa_id]);
            break;
        }

        return $datos;
    }

    //function para obtener resultados para pdf

    public function impAtencionPDF(){
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;

        $dataRequest = json_decode(base64_decode(request()->input('data')));
        $jornada_id = Lucipher::Descipher($dataRequest->jornada_id);
        $resultado = $dataRequest->resultado;
        //title
        //Datos empresa
        $empresa = Empresa::where('id',$empresa_id)->first();
        $sucursal = Sucursal::where('id',$sucursal_id)->where('empresa_id',$empresa_id)->first();

        //validacion por tipo de jornada
        $jornada = Jornada::where('id',$jornada_id)->where('empresa_id',$empresa_id)->first();

        if($jornada['cat_examenes'] == "SALUD VISUAL (OPTOMETRIA)"){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])->post($this->url_base ."obtener-datos-atencion-resultados",[
                'jornada_id' => $jornada_id,
                'codigo_clinica' => $empresa['cod_clinica']
            ]);
            $datos = json_decode($response);
            $datos = $datos->results;
        }else{
            $datos = $this->getAtencionEmpResult($resultado,$jornada_id);
        }

        $data_final = [];
        foreach ($datos as $item) {
            $area = $item->area;
            $colaborador = Lucipher::Descipher($item->colaborador);
            if (!isset($data_final[$area])) {
                $data_final[$area] = [
                    'area' => $area,
                    'items' => []
                ];
            }
            $data_final[$area]['items'][] = [
                'cargo' => $item->cargo,
                'colaborador' => $colaborador,
                'estado_evaluacion' => $item->estado_evaluacion
            ];
        }
        
        $data_final = array_values($data_final);

        $pdf = PDF::loadView('Resultado.pdf.listado_empleados_estado',compact('data_final','empresa','sucursal'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('listado_empleados.pdf');
    }

    //obtener resultados por jornada y colaborador
    public function getExamenesResultByEmp(){
        $empresa_id = Auth::user()->empresa_id;

        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));

        if($jornada_id && $empleado_id){
            $categoria_exam_lab = DB::select("SELECT c.id,c.nombre as categoria,dj.jornada_id,dj.empleado_id FROM `det_jornadas` as dj inner join examenes as e on dj.cat_examen='laboratorio clinico' and dj.examen_id=e.id and dj.empresa_id=e.empresa_id inner join categoria_examens as c on e.categoria_id=c.id where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? GROUP By c.id,c.nombre;",[$jornada_id,$empleado_id,$empresa_id]);
            
            $data_final = [];
            foreach($categoria_exam_lab as $categoria){
                $array = [];
                $datos = DB::select("SELECT e.id as examen_id,dj.jornada_id,dj.empleado_id,e.nombre as examen,dj.evaluacion,dj.cat_examen FROM `det_jornadas` as dj inner join examenes as e on dj.examen_id=e.id and dj.empresa_id=e.empresa_id and dj.cat_examen = 'laboratorio clinico' where dj.jornada_id = ? and dj.empleado_id = ? and e.categoria_id = ? and dj.empresa_id = ?;",[$jornada_id,$empleado_id,$categoria->id,$empresa_id]);
                //obtener evaluacion
                $detalleResultEvaluacion = DetResultEvaluacion::where('jornada_id', $jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->first();
                $estadoEvaluacionExamnes = ($detalleResultEvaluacion) ? $detalleResultEvaluacion['estado'] : 'Sin evaluar';
                $examenes_resultado = [];
                foreach($datos as $item){
                    $sub_array = [];
                    $sub_array['jornada_id'] = $item->jornada_id;
                    $sub_array['empleado_id'] = $item->empleado_id;
                    $sub_array['examen'] = $item->examen;
                    $sub_array['evaluacion'] = $item->evaluacion;
                    $sub_array['resultado'] = $this->getResultExamenById($item->examen_id,$empleado_id,$jornada_id,$item->cat_examen);
                    $examenes_resultado[] = $sub_array;
                }
                $array['categoria'] = $categoria->categoria;
                $array['examenes'] = $examenes_resultado;
                $data_final[] = $array;
            }
            return response()->json([
                'estado' => $estadoEvaluacionExamnes,
                'examenes_data' => $data_final
            ]);
        }
        return response()->json([]);
    }

    public function getResultExamenById($examen_id,$empleado_id,$jornada_id,$cat_examen = null){
        $empresa_id = Auth::user()->empresa_id;
        //get examene data
        if($cat_examen == "complementarios"){
            $examenEspecial = DB::table('pruebas_especiales')->where('id', $examen_id)->first();
            if ($examenEspecial) {
                $name_table = $examenEspecial->name_tabla;
                $data_examen = [];
                if($name_table == 'result_opto_consulta' ){

                    $resultado = DB::table('result_opto_consulta as roc')
                    ->join('result_opto_rx as ror', function($join) {
                        $join->on('ror.empleado_id', '=', 'roc.empleado_id')
                                ->on('ror.jornada_id', '=', 'roc.jornada_id');
                    })
                    ->join('result_opto_altura as roa', function($join) {
                        $join->on('roa.empleado_id', '=', 'roc.empleado_id')
                                ->on('roa.jornada_id', '=', 'roc.jornada_id');
                    })
                    ->where('roc.jornada_id', $jornada_id)
                    ->where('roc.empleado_id', $empleado_id)
                    ->select('roc.*', 'ror.*', 'roa.*')
                    ->first();
                    if ($resultado) {

                        $data_examen = $resultado;
                        $data_examen->examen = $examenEspecial->nombre;
                        $data_examen->categoria = $cat_examen;
                    }
                    return $data_examen;
                }/* else{
                    $resultado = DB::table($name_table)
                    ->where('jornada_id', $jornada_id)
                    ->where('empleado_id', $empleado->id)
                    ->first();

                    if ($resultado) {
                        $resultadosExamenes[] = [
                            'examen' => $examenEspecial->nombre,
                            'resultado' => $resultado,
                            'evaluacion' => $evaluacion,
                        ];
                    }
                } */
            }

        }else if($cat_examen == "laboratorio clinico"){
            $examen = DB::select("SELECT e.id,e.nombre,e.name_tabla,c.nombre as categoria FROM `examenes` AS e INNER JOIN categoria_examens as c on e.categoria_id=c.id and e.empresa_id=c.empresa_id where e.id = ? and e.empresa_id = ?",[$examen_id,$empresa_id]);
            
            if($examen){
                $examen = $examen[0];
                $name_tabla = ($examen->name_tabla !== '') ? $examen->name_tabla : false;

                $data_examen = [];
                if(strtoupper($examen->categoria) == "QUIMICA"){
                    $resultado = DB::select("select r.id,r.resultado,rq.observaciones from det_resultado_quimicas as r inner join resultado_quimicas as rq on r.result_quimica_id=rq.id and r.empresa_id=rq.empresa_id where r.examen_id =? and r.empleado_id = ? and r.jornada_id = ?",[$examen_id,$empleado_id,$jornada_id]);
                    foreach($resultado as $item){
                        $array = [];
                        $array['id'] = $item->id;
                        $array['resultado'] = $item->resultado;
                        $array['observaciones'] = $item->observaciones;
                        $array['examen'] = $examen->nombre;
                        $array['categoria'] = $examen->categoria;
                        $data_examen = $array;
                    }
                }else if(strtoupper($examen->categoria) != "QUIMICA" && $name_tabla){
                    $resultado = DB::select("select * from $name_tabla as r where r.empleado_id = ? and r.jornada_id = ?",[$empleado_id,$jornada_id]);
                    if($resultado){
                        $data_examen = $resultado[0];
                        $data_examen->examen = $examen->nombre;
                        $data_examen->categoria = $examen->categoria;
                    }
                }
                return $data_examen;
            }else {
                return [];
            }
        }
    }

    public function setEvaluacionEmp(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $datos_empleado = json_decode(base64_decode(request()->input('data')));
        $estado = request()->input('estado');
        $empleado_id = Lucipher::Descipher($datos_empleado->empleado_id);
        $jornada_id = Lucipher::Descipher($datos_empleado->jornada_id);

        if($empleado_id && $jornada_id){
            $estadoAllow = ['Evaluado','Sin evaluar'];
            //validacion de estado
            if(in_array($estado,$estadoAllow)){
                //validar segun jornada
                $jornada = Jornada::where('id',$jornada_id)->where('empresa_id',$empresa_id)->first();
                if($jornada['cat_examenes'] == "SALUD VISUAL (OPTOMETRIA)"){
                    $consulta_id = Lucipher::Descipher($datos_empleado->consulta_id);
                    $responseJson = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token
                    ])->post($this->url_base ."guardar-estado-atencion",[
                        'empleado_id' => $empleado_id,
                        'jornada_id' => $jornada_id,
                        'consulta_id' => $consulta_id,
                        'estadoEval' => $estado
                    ]);
                    return $responseJson;
                }else{
                    //validacion si ya existe un registro solo actualizar sino crear registro
                    $detalleResultEvaluacion = DetResultEvaluacion::where('jornada_id', $jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->first();
                    if($detalleResultEvaluacion){
                        $detalleResultEvaluacion->estado = $estado;
                        $detalleResultEvaluacion->save();
                        $message = 'Se ha actualizado el estado a: ' . $estado;
                    }else{
                        DetResultEvaluacion::create([
                            'fecha' => $date,
                            'hora' => $hora,
                            'estado' => $estado,
                            'jornada_id' => $jornada_id,
                            'empleado_id' => $empleado_id,
                            'empresa_id' => $empresa_id,
                            'usuario_id' => $usuario_id
                        ]);
                        $message = 'Se ha actualizado el estado a: ' . $estado;
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => $message
                    ]);
                }
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Se ha detectado datos alterados manualmente.'
            ]);

        }
    }

    //guardar evaluacion de examen de optometria

    public function saveEvalOpto(){
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $consulta_id = Lucipher::Descipher(request()->input('consulta_id'));
        $eval = request()->input('optionResultado');

        if($empleado_id && $jornada_id && $consulta_id && $eval){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])->post($this->url_base ."evaluar-resultado-optometria",[
                'empleado_id' => $empleado_id,
                'jornada_id' => $jornada_id,
                'consulta_id' => $consulta_id,
                'evaluacion' => $eval
            ]);
            if($response['status'] == "success"){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Los resultados se han evaluado exitosamente.'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ha ocurrido un error interno.'
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Se ha detectado valores alterados manualmente.'
        ]);
    }
}
