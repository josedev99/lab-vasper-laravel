<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\Baciloscopia;
use App\Models\Colesterol;
use App\Models\DetJornada;
use App\Models\DetResultadoQuimica;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\ExamenesCategoria;
use App\Models\Hece;
use App\Models\Jornada;
use App\Models\ResultAcidoUrico;
use App\Models\ResultadoQuimica;
use App\Models\ResultCreatinina;
use App\Models\ResultExofaringeo;
use App\Models\ResultGlucosa;
use App\Models\ResultHemograma;
use App\Models\ResultOrina;
use App\Models\ResultSgot;
use App\Models\ResultSgpt;
use App\Models\ResultTrigliceridos;
use App\Models\Rpr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaboratorioResultadoController extends Controller
{
    protected $array_exofaringeo = [];
    protected $array_vdrl = [];

    public function __construct(){
        $this->array_exofaringeo = ["CULTIVO FARINGEO", "EXOFARINGEO","EXOFARÍNGEO"];
        $this->array_vdrl = ['VDRL', 'R.P.R'];
    }
    public function index_listado(){
        $empresa_id = Auth::user()->empresa_id;
        $data_empresas = Empresa::orderBy('id','desc')->select('id','nombre')->get();
        $empresas = [];
        foreach($data_empresas as $item){
            $array = [];
            $array['id'] = Lucipher::Cipher($item['id']);
            $array['nombre'] = $item['nombre'];
            $empresas[] = $array;
        }
        return view('Laboratorio.indexOrdenExamenes',compact('empresas'));
    }

    public function listar_orden_examenes(){
        $empresa_id = Lucipher::Descipher(request()->input('empresa_id'));
        $jornada_id = request()->input('jornada_id');
        $data = [];
        if(!$jornada_id){
            return response()->json([
                "sEcho" => 1, // Información para el datatables
                "iTotalRecords" => count($data), // enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
                "aaData" => $data
            ]);
        }
        $datos = DB::select("SELECT e.id as empleado_id,dj.jornada_id, e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,emp.nombre as empresa,s.nombre as sucursal, ord.id as orden_id,ord.numero_orden,e.empresa_id, j.fecha_jornada FROM `det_jornadas` as dj INNER join jornadas as j on dj.jornada_id=j.id and j.empresa_id=dj.empresa_id INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN empresas as emp on e.empresa_id=emp.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN orden_labs as ord on e.id=ord.empleado_id and e.empresa_id=ord.empresa_id WHERE e.empresa_id = ? and j.id = ? GROUP by dj.empleado_id;",[$empresa_id,$jornada_id]);

        $contador = 1;
        foreach ($datos as $row) {
            $spanEstado = '';

            $strEstado = $this->getEstadoEvalExamen('',$row->empleado_id,$row->jornada_id);
            
            if($strEstado == "Analizada"){
                $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Analizada</span>';
            }else{
                $spanEstado = '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>Analizando</span>';
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = date('d/m/Y',strtotime($row->fecha_jornada));
            $sub_array[] = Lucipher::Descipher($row->nombre);
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->area;
            $sub_array[] = $spanEstado;
            $sub_array[] = $row->sucursal;
            $sub_array[] = '
            <button onclick="printOrdenExamenes(this)" data-nombre_empleado="'.Lucipher::Descipher($row->nombre).'" data-orden_id="'.Lucipher::Cipher($row->orden_id).'" data-empresa_id="'.Lucipher::Cipher($row->empresa_id).'" title="Reimprimir boleta de examen" class="btn btn-outline-success btn-sm" style="border:none;font-size:18px"><i class="bi bi-file-earmark-text"></i></button>
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

    //vista examenes
    public function detExamenesPaciente(){
        $empleado_id = Lucipher::Descipher(rawurldecode(request()->query('key1')));
        $jornada_id = Lucipher::Descipher(rawurldecode(request()->query('key2')));

        if($empleado_id && $jornada_id){
            return view('Laboratorio.ingresarResultadosExamenes');
        }else{
            return redirect()->route('lab.resultado.index');
        }
    }

    //comprobar estado modelo de laboratorio

    public function getEstadoEvalExamen($cat_id, $empleado_id, $jornada_id){
        $colaborador = Empleado::where('id',$empleado_id)->select('empresa_id')->first();
        $empresa_id = $colaborador['empresa_id'];

        if($cat_id != null || $cat_id != ''){
            $data = DB::select("SELECT dj.estado FROM `det_jornadas` as dj inner join examenes as e on dj.examen_id=e.id and dj.empresa_id=e.empresa_id where e.categoria_id = ? and dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? GROUP BY dj.estado;",[$cat_id, $jornada_id,$empleado_id,$empresa_id]);
        }else{
            $data = DB::select("SELECT dj.estado FROM `det_jornadas` as dj inner join examenes as e on dj.examen_id=e.id and dj.empresa_id=e.empresa_id where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? GROUP by dj.estado;",[$jornada_id,$empleado_id,$empresa_id]);
        }

        //validaciones
        if(count($data) === 1){
            return $data[0]->estado == "Finalizado" ? "Analizada" : "Analizando";
        }else{
            return "Analizando";
        }
    }

    public function getDetalleOrden(){
        
        $empleado_id = Lucipher::Descipher(rawurldecode(request()->input('empleado_id')));
        $jornada_id = Lucipher::Descipher(rawurldecode(request()->input('jornada_id')));
        $colaborador = Empleado::where('id',$empleado_id)->select('id','empresa_id')->first();
        $empresa_id = $colaborador['empresa_id'];

        $datos = DB::select("SELECT e.id as empleado_id,dj.jornada_id,j.fecha_jornada, e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,s.nombre as sucursal,dj.cat_examen,dj.evaluacion,c.nombre as categoria,c.id as categoria_id FROM `det_jornadas` as dj inner join jornadas as j on dj.jornada_id=j.id and dj.empresa_id=j.empresa_id INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN examenes as ex on dj.examen_id=ex.id and dj.empresa_id=ex.empresa_id inner join categoria_examens as c on ex.categoria_id=c.id and ex.empresa_id=c.empresa_id where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? and dj.cat_examen='laboratorio clinico' GROUP by c.nombre UNION SELECT e.id as empleado_id,dj.jornada_id,j.fecha_jornada,e.codigo_empleado,e.nombre,e.telefono,a.nombre as area,dj.estado,s.nombre as sucursal,dj.cat_examen,dj.evaluacion, pr.nombre as categoria, pr.id as categoria_id FROM `det_jornadas` as dj inner join jornadas as j on dj.jornada_id=j.id and dj.empresa_id=j.empresa_id INNER JOIN empleados as e on dj.empleado_id=e.id and dj.empresa_id=e.empresa_id INNER JOIN area_emps as a on e.area_depto_id=a.id INNER JOIN sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN pruebas_especiales as pr on dj.examen_id=pr.id and dj.empresa_id=pr.id_empresa where dj.jornada_id = ? and dj.empleado_id = ? and dj.empresa_id = ? and dj.cat_examen in ('especialidades','complementarios');",[$jornada_id,$empleado_id,$empresa_id,$jornada_id,$empleado_id,$empresa_id]);
        
        $data = [];
        $contador = 1;
        foreach ($datos as $row) {
            $spanEstado = '';

            $strEstado = $this->getEstadoEvalExamen($row->categoria_id,$row->empleado_id,$row->jornada_id);
            
            if($strEstado == "Analizada"){
                $spanEstado = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Analizada</span>';
            }else{
                $spanEstado = '<span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>Analizando</span>';
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d-m-Y',strtotime($row->fecha_jornada));
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = Lucipher::Descipher($row->nombre);
            $sub_array[] = $row->sucursal;
            $sub_array[] = $row->categoria;
            $sub_array[] = $spanEstado;
            $sub_array[] = '
            <button onclick="ingresarResultado(this)" data-categoria="'.$row->categoria.'" data-categoria_id="'.Lucipher::Cipher($row->categoria_id).'" data-cat_examen="'.Lucipher::Cipher($row->cat_examen).'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" title="Ingresar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-file-earmark-plus"></i></button>
            ';
            /* $sub_array[] = '
            <button onclick="addImageExamen(this)" data-cat_examen="'.Lucipher::Cipher($row->cat_examen).'" data-empleado_id="'.Lucipher::Cipher($row->empleado_id).'" data-jornada_id="'.Lucipher::Cipher($row->jornada_id).'" title="Ingresar resultados" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-image"></i></button>
            '; */
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

    //method para evaluar resultado::
    /**
     * @return string (Alterado or Normal)
     */
    public function getEvalResultQuimica($resultado,$examen,$genero){
        $evaluacion = '';
        if(strtoupper($examen) === "TRIGLICERIDOS"){
            if((int)$resultado  >= 0 and (int)$resultado <=200){
                $evaluacion = "Normal";
            }else{
                $evaluacion = "Alterado";
            }
        }else if(strtoupper($examen) == "ACIDO URICO"){
            if ($genero == "M" && ($resultado >= 3.4 && $resultado <= 7.0)) {
                $evaluacion = "Normal";
            } else if ($genero == "F" && ($resultado >= 2.4 && $resultado <= 5.7)) {
                $evaluacion = "Normal";
            } else if ($genero == "M" && ($resultado < 3.4 || $resultado > 7.0)) {
                $evaluacion = "Alterado";
            } else if ($genero == "F" && ($resultado < 2.4 || $resultado > 5.7)) {
                $evaluacion = "Alterado";
            }
        }else if(strtoupper($examen) == "CREATININA"){
            if ($genero == "F" && ($resultado>=0.50 && $resultado<=0.90)) {
                $evaluacion = "Normal";
            }elseif ($genero == "M" && ($resultado>=0.60 && $resultado<=1.0)) {
                $evaluacion = "Normal";
            }elseif ($genero == "F" && $resultado > 0.90){
                $evaluacion = "Alterado";
            }elseif ($genero == "M" && $resultado > 1.10){
                $evaluacion = "Alterado";
            }
        }else if(strtoupper($examen) == "COLESTEROL"){
            if ($resultado>=0 and $resultado<=190) {
                $evaluacion="Normal";
            }else{
                $evaluacion="Alterado";
            }
        }else if(strtoupper($examen) == "GLUCOSA"){
            if ($resultado >= 75 and $resultado <= 115) {
                $evaluacion = "Normal";
            }else{
                $evaluacion = "Alterado";
            }
        }else if(strtoupper($examen) == "SGOT"){
            if ($genero == "M" && ($resultado >= 8 && $resultado <= 37)) {
                $evaluacion = "Normal";
            }elseif ($genero == "F" && ($resultado >= 6 && $resultado <= 31)) {
                $evaluacion = "Normal";
            }else if ($genero == "M" && ($resultado < 8 || $resultado > 37)) {
                $evaluacion = "Alterado";
            }elseif ($genero == "F" && ($resultado < 6 || $resultado > 31)) {
                $evaluacion = "Alterado";
            }
        }else if(strtoupper($examen) == "SGPT"){
            if ($genero == "M" && ($resultado >= 7 && $resultado <= 42)) {
                $evaluacion = "Normal";
            }elseif ($genero == "F" && ($resultado >= 7 && $resultado <= 32)) {
                $evaluacion = "Normal";
            }elseif ($genero == "M" && ($resultado < 7 || $resultado > 42)) {
                $evaluacion = "Alterado";
            }elseif ($genero == "F" && ($resultado < 7 || $resultado > 32)) {
                $evaluacion = "Alterado";
            }
        }else{
            $evaluacion = '-';
        }

        return $evaluacion;
    }

    public function getEvalResultHemograma($hb_hemato,$gb_hemato,$plaquetas_hemato){
        if (($hb_hemato>=12.5 && $hb_hemato<=17) &&($gb_hemato>=4500 && $gb_hemato<=10500)&&($plaquetas_hemato>=150000 && $plaquetas_hemato<= 400000)) {
            $evaluacion="Normal";
        }else{
            $evaluacion="Alterado";
        }
        return $evaluacion;
    }

    public function getEvalResultHeces($hematies_heces,$leucocitos_heces,$activos_heces,$quistes_hecess,$metazoarios_hecess){
        $valores_normales = ["No se observan",'no se observan'];

        if (in_array($hematies_heces,$valores_normales) && in_array($leucocitos_heces,$valores_normales) && in_array($activos_heces,$valores_normales) && in_array($quistes_hecess,$valores_normales) && in_array($metazoarios_hecess,$valores_normales)) {
            $evaluacion="Normal";
         }else{
            $evaluacion="Alterado";
         }
         return $evaluacion;
    } 
    /**
     * Evaluacion para examen de orina
     */
    public function getEvalResultOrina($esterasas_orina,$nitritos_orina,$glucosa_orina,$sangre_oculta_orina,$bacterias_orina){
        $esterasas_orinass=preg_replace("/[[:space:]]/"," ",trim($esterasas_orina));
        $nitritos_orinas=preg_replace("/[[:space:]]/"," ",trim($nitritos_orina));
        $sangre_oculta_orinas=preg_replace("/[[:space:]]/"," ",trim($sangre_oculta_orina));
        $bacterias_orinas=preg_replace("/[[:space:]]/"," ",trim($bacterias_orina));

        $val_neg_normal = ["Negativo","negativo"];
        $val_normales = ["No se observan","no se observan"];

        if (in_array($esterasas_orinass,$val_neg_normal) && in_array($nitritos_orinas,$val_neg_normal) && in_array($glucosa_orina,$val_neg_normal) && in_array($sangre_oculta_orinas,$val_neg_normal) && in_array($bacterias_orinas,$val_normales)) {
            $evaluacion ="Normal";
        }else{
            $evaluacion="Alterado";
        }
        return $evaluacion;
    }
    /**
     * EVALUACION PARA EXAMEN DE BACILOSCOPIA
     */
    public function getEvalResultBaci($resultado){
        $valores_normales = ["positivo","Positivo"];
        if (in_array($resultado,$valores_normales)) {
            $evaluacion="Alterado";
        }else{
            $evaluacion="Normal";
        }
        return $evaluacion;
    }
    /**
     * Evaluacion para examen de EXOFARINGEO
     */
    public function getEvalResultExofaringeo($aisla){
        if (strpos("NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA NORMAL", $aisla) !== false) {
            $evaluacion = "Normal";
        } else {
            $evaluacion = "Alterado";
        }
        return $evaluacion;
    }

    /**
     * Evaluacion para examen de VDRL O RPR
     */
    public function getEvalResultRPR($resultado){
        $valores_normales = ["Reactivo","REACTIVO"];
        if(in_array($resultado,$valores_normales)){
            $evaluacion = "Alterado";  
        }else{
            $evaluacion = "Normal";
        }
        return $evaluacion;
    }

    public function saveResultado(){
        $usuario_id = Auth::user()->id;

        $data_form = request()->all();
        
        $empleado_id = Lucipher::Descipher($data_form['empleado_id']);
        $jornada_id = Lucipher::Descipher($data_form['jornada_id']);
        $cat_examen = Lucipher::Descipher($data_form['cat_examen']);
        $categoria = $data_form['categoria'];
        //empresa del colaborador
        $colaborador = Empleado::where('id',$empleado_id)->select('empresa_id')->first();
        $empresa_id = $colaborador['empresa_id'];

        if($cat_examen == "laboratorio clinico"){
            //validacion de la categoria del examen
            try{
                DB::beginTransaction();
                if($categoria == "QUIMICA"){
                        //observaciones
                        $observaciones = request()->input('observaciones_quimica');
                        $exists_result_quimica = ResultadoQuimica::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                        if($exists_result_quimica){
                            $exists_result_quimica->update([
                                'observaciones' => $observaciones,
                                'fecha' => date('Y-m-d'),
                            ]);
                            $exists_result_quimica->save();
                        }else{
                            $rQuimica = ResultadoQuimica::create([
                                'observaciones' => $observaciones,
                                'fecha' => date('Y-m-d'),
                                'hora' => date('H:i:s'),
                                'jornada_id' => $jornada_id,
                                'empleado_id' => $empleado_id,
                                'empresa_id' => $empresa_id,
                                'usuario_id' => $usuario_id
                            ]);
                        }
                        //data resultado
                        $examenes = json_decode(request()->input('data_resultado'));
                        foreach($examenes as $examen){
                            //obtener evaluacion de resultados automatizado
                            $empleado = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->select('genero')->first(); //datos empleado eval
                            if($empleado && $examen->resultado != ""){
                                $evaluacion = $this->getEvalResultQuimica($examen->resultado,$examen->examen,$empleado['genero']);
                            }else{
                                $evaluacion = '-';
                            }
                            //validacion para verificar existencia 
                            $exists_examen = DetResultadoQuimica::where('examen_id',$examen->id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();

                            if($exists_examen){
                                $exists_examen->update([
                                    'resultado' => $examen->resultado,
                                    'estado' => $evaluacion
                                ]);
                                $exists_examen->save();
                                $message = 'Los resultados se han actualizado exitosamente.';
                            }else{
                                DetResultadoQuimica::create([
                                    'resultado' => $examen->resultado,
                                    'estado' => $evaluacion,
                                    'result_quimica_id' => $rQuimica->id,
                                    'examen_id' => $examen->id,
                                    'jornada_id' => $jornada_id,
                                    'empleado_id' => $empleado_id,
                                    'empresa_id' => $empresa_id
                                ]);
                                //cambiar el estado de det_jornada
                                DetJornada::where('examen_id',$examen->id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                                    'estado' => 'Finalizado',
                                    'evaluacion' => $evaluacion
                                ]);
                                $message = 'Los resultados han registrado exitosamente.';
                            }
                        }
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => $message
                        ]);
                }else if($categoria == "UROLOGIA"){
                    $data = [
                        'color' => isset($data_form['ego_color']) ? trim($data_form['ego_color']) : '',
                        'olor' => isset($data_form['ego_olor']) ? trim($data_form['ego_olor']) : '',
                        'aspecto' => isset($data_form['ego_aspecto']) ? trim($data_form['ego_aspecto']) : '',
                        'densidad' => isset($data_form['ego_densidad']) ? trim($data_form['ego_densidad']) : '',
                        'est_leuco' => isset($data_form['ego_esterasas']) ? trim($data_form['ego_esterasas']) : '',
                        'ph' => isset($data_form['ego_ph']) ? trim($data_form['ego_ph']) : '',
                        'proteinas' => isset($data_form['ego_proteinas']) ? trim($data_form['ego_proteinas']) : '',
                        'glucosa' => isset($data_form['ego_glucosa']) ? trim($data_form['ego_glucosa']) : '',
                        'cetonas' => isset($data_form['ego_cetonas']) ? trim($data_form['ego_cetonas']) : '',
                        'urobilinogeno' => isset($data_form['ego_urobili']) ? trim($data_form['ego_urobili']) : '',
                        'bilirrubina' => isset($data_form['ego_bilirrubina']) ? trim($data_form['ego_bilirrubina']) : '',
                        'sangre_oculta' => isset($data_form['ego_sangre_ocul']) ? trim($data_form['ego_sangre_ocul']) : '',
                        'cilindros' => isset($data_form['ego_cilidros']) ? trim($data_form['ego_cilidros']) : '',
                        'leucocitos' => isset($data_form['ego_leucocitos']) ? trim($data_form['ego_leucocitos']) : '',
                        'hematies' => isset($data_form['ego_hematies']) ? trim($data_form['ego_hematies']) : '',
                        'cel_epiteliales' => isset($data_form['ego_cel_epiteliales']) ? trim($data_form['ego_cel_epiteliales']) : '',
                        'filamentos_muco' => isset($data_form['ego_filamentos']) ? trim($data_form['ego_filamentos']) : '',
                        'bacterias' => isset($data_form['ego_bacterias']) ? trim($data_form['ego_bacterias']) : '',
                        'cristales' => isset($data_form['ego_cristales']) ? trim($data_form['ego_cristales']) : '',
                        'observaciones' => isset($data_form['ego_observaciones']) ? trim($data_form['ego_observaciones']) : '',
                        'nitritos_orina' => isset($data_form['ego_nitritos']) ? trim($data_form['ego_nitritos']) : ''
                    ];

                    $exists = ResultOrina::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                    if($exists){
                        $exists->update($data);
                        $message = 'Los resultados se han actualizado exitosamente.';
                    }else{
                        ResultOrina::create(array_merge($data,[
                            'estado' => '',
                            'jornada_id' => $jornada_id,
                            'empleado_id' => $empleado_id,
                            'empresa_id' => $empresa_id
                        ]));
                        $message = 'Los resultados se ha ingresado exitosamente.';
                    }
                    //update estado en det_jornada
                    $prop_data_examen = json_decode(request()->get('prop_data_examen'));
                    foreach($prop_data_examen as $item){

                        if($data['est_leuco'] != '' && $data['nitritos_orina'] != '' && $data['glucosa'] != '' && $data['sangre_oculta'] != '' && $data['bacterias'] != ''){
                            $evaluacion = $this->getEvalResultOrina($data['est_leuco'],$data['nitritos_orina'],$data['glucosa'],$data['sangre_oculta'],$data['bacterias']);
                        }else{
                            $evaluacion = '-';
                        }
                        $examen_id = Lucipher::Descipher($item->examen_id);
                        $jornada_id = Lucipher::Descipher($item->jornada_id);
                        $empleado_id = Lucipher::Descipher($item->empleado_id);

                        DetJornada::where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 'Finalizado',
                            'evaluacion' => $evaluacion
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => $message,
                        'titleMsg' => 'Éxito'
                    ]);
                }else if($categoria == "COPROLOGIA"){
                    $data = [
                        'color' => isset($data_form['egh_color']) ? trim($data_form['egh_color']) : '',
                        'consistencia' => isset($data_form['egh_consistencia']) ? trim($data_form['egh_consistencia']) : '',
                        'mucus' => isset($data_form['egh_mucus']) ? trim($data_form['egh_mucus']) : '',
                        'macroscopicos' => isset($data_form['egh_macroscopicos']) ? trim($data_form['egh_macroscopicos']) : '',
                        'microscopicos' => isset($data_form['egh_microscopicos']) ? trim($data_form['egh_microscopicos']) : '',
                        'hematies' => isset($data_form['egh_hematies']) ? trim($data_form['egh_hematies']) : '',
                        'leucocitos' => isset($data_form['egh_leucocitos']) ? trim($data_form['egh_leucocitos']) : '',
                        'activos' => isset($data_form['egh_activos']) ? trim($data_form['egh_activos']) : '',
                        'quistes' => isset($data_form['egh_quistes']) ? trim($data_form['egh_quistes']) : '',
                        'metazoarios' => isset($data_form['egh_metazoarios']) ? trim($data_form['egh_metazoarios']) : '',
                        'protozoarios' => isset($data_form['egh_protozoarios']) ? trim($data_form['egh_protozoarios']) : '',
                        'observaciones' => isset($data_form['egh_observaciones']) ? trim($data_form['egh_observaciones']) : ''
                    ];

                    $exists = Hece::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                    if($exists){
                        $exists->update($data);
                        $message = 'Los resultados se han actualizado exitosamente.';
                    }else{
                        Hece::create(array_merge($data,[
                            'jornada_id' => $jornada_id,
                            'empleado_id' => $empleado_id,
                            'estado' => ''
                        ]));
                        $message = 'Los resultados se ha ingresado exitosamente.';
                    }
                    //update estado en det_jornada
                    $prop_data_examen = json_decode(request()->get('prop_data_examen'));
                    foreach($prop_data_examen as $item){
                        $examen_id = Lucipher::Descipher($item->examen_id);
                        $jornada_id = Lucipher::Descipher($item->jornada_id);
                        $empleado_id = Lucipher::Descipher($item->empleado_id);

                        if($data['hematies'] != "" && $data['leucocitos'] != "" && $data['activos'] != "" && $data['quistes'] != "" && $data['metazoarios'] != ""){
                            $evaluacion = $this->getEvalResultHeces($data['hematies'],$data['leucocitos'],$data['activos'],$data['quistes'],$data['metazoarios']);
                        }else{
                            $evaluacion = '-';
                        }

                        DetJornada::where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 'Finalizado',
                            'evaluacion' => $evaluacion
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => $message,
                        'titleMsg' => 'Éxito'
                    ]);
                }else if($categoria == "BACTERIOLOGIA"){
                    $exof = [];
                    $baciloscopia = [];

                    // Filtrar datos por prefijo
                    foreach ($data_form as $key => $value) {
                        if (strpos($key, 'exofaringeo') !== false) {
                            $exof[$key] = (trim($value) != "") ? $value : '';
                        } elseif (strpos($key, 'baciloscopia') !== false) {
                            $baciloscopia[$key] = $value;
                        }
                    }
                    //guardar
                    if(count($baciloscopia) > 0){
                        $resultado = trim($baciloscopia['resultado_baciloscopia']);
                        $observaciones = trim($baciloscopia['observaciones_baciloscopia']);
                        $exists = Baciloscopia::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                        if($exists){
                            $exists->resultado = $resultado;
                            $exists->observaciones = $observaciones;
                            $exists->save();
                            $message = 'Los resultados se han actualizado exitosamente.';
                        }else{
                            Baciloscopia::create([
                                'resultado' => $resultado,
                                'observaciones' => $observaciones,
                                'estado' => '',
                                'jornada_id' => $jornada_id,
                                'empleado_id' => $empleado_id
                            ]);
                        }
                    }
                    if(count($exof) > 0){
                        $aisla = $exof['aisla_exofaringeo'];
                        $sensible = $exof['sensible_exofaringeo'];
                        $resiste = $exof['resiste_exofaringeo'];
                        $refiere = $exof['refiere_exofaringeo'];
            
                        $exists = ResultExofaringeo::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                        if($exists){
                            $exists->aisla = $aisla;
                            $exists->sensible = $sensible;
                            $exists->resiste = $resiste;
                            $exists->refiere = $refiere;
                            $exists->save();
                        }else{
                            ResultExofaringeo::create([
                                'aisla' => $aisla,
                                'sensible' => $sensible,
                                'resiste' => $resiste,
                                'refiere' => $refiere,
                                'jornada_id' => $jornada_id,
                                'empleado_id' => $empleado_id,
                                'empresa_id' => $empresa_id
                            ]);
                        }
            
                    }
                    //update estado en det_jornada
                    $prop_data_examen = json_decode(request()->get('prop_data_examen'));
                    foreach($prop_data_examen as $item){
                        $examen_id = Lucipher::Descipher($item->examen_id);
                        $jornada_id = Lucipher::Descipher($item->jornada_id);
                        $empleado_id = Lucipher::Descipher($item->empleado_id);

                        $examen = ExamenesCategoria::where('id',$examen_id)->where('empresa_id',$empresa_id)->first();
                        if($examen){
                            if(strtoupper(trim($examen['nombre'])) == "BACILOSCOPIA"){
                                $resultado = trim($baciloscopia['resultado_baciloscopia']);
                                $evaluacion = $this->getEvalResultBaci($resultado);
                            }else if(in_array(strtoupper(trim($examen['nombre'])),$this->array_exofaringeo)){
                                $evaluacion = $this->getEvalResultExofaringeo($aisla);
                            }else{
                                $evaluacion = '-';    
                            }
                        }else{
                            $evaluacion = '-';
                        }

                        DetJornada::where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 'Finalizado',
                            'evaluacion' => $evaluacion
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Los resultados se ha ingresado exitosamente.',
                        'titleMsg' => 'Éxito'
                    ]);
                }else if($categoria === "INMUNOLOGIA"){
                    $resultado = trim($data_form['resultado_rpr']);
                    $observaciones = trim($data_form['observaciones_rpr']);
                    $exists = Rpr::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                    if($exists){
                        $exists->resultado = $resultado;
                        $exists->observaciones = $observaciones;
                        $exists->save();
                        $message = 'Los resultados se han actualizado exitosamente.';
                    }else{
                        Rpr::create([
                            'resultado' => $resultado,
                            'observaciones' => $observaciones,
                            'estado' => '',
                            'jornada_id' => $jornada_id,
                            'empleado_id' => $empleado_id
                        ]);
                        $message = 'Los resultados se ha ingresado exitosamente.';
                    }
                    //update estado en det_jornada
                    $prop_data_examen = json_decode(request()->get('prop_data_examen'));
                    foreach($prop_data_examen as $item){
                        $examen_id = Lucipher::Descipher($item->examen_id);
                        $jornada_id = Lucipher::Descipher($item->jornada_id);
                        $empleado_id = Lucipher::Descipher($item->empleado_id);
                        //get string examen
                        $examen = ExamenesCategoria::where('id',$examen_id)->where('empresa_id',$empresa_id)->first();
                        if($examen){
                            if(in_array(strtoupper(trim($examen['nombre'])),$this->array_vdrl) && $resultado != ''){
                                $evaluacion = $this->getEvalResultRPR($resultado);
                            }else{
                                $evaluacion = '-';
                            }
                        }else{
                            $evaluacion = '-';
                        }

                        DetJornada::where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 'Finalizado',
                            'evaluacion' => $evaluacion
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => $message,
                        'titleMsg' => 'Éxito'
                    ]);
                }else if($categoria == "HEMATOLOGIA"){
                    $data = [
                        'gr_hemato' => isset($data_form['gr_hemato']) ? trim($data_form['gr_hemato']) : '',
                        'ht_hemato' => isset($data_form['ht_hemato']) ? trim($data_form['ht_hemato']) : '',
                        'hb_hemato' => isset($data_form['hb_hemato']) ? trim($data_form['hb_hemato']) : '',
                        'vcm_hemato' => isset($data_form['vcm_hemato']) ? trim($data_form['vcm_hemato']) : '',
                        'cmhc_hemato' => isset($data_form['cmhc_hemato']) ? trim($data_form['cmhc_hemato']) : '',
                        'gota_hema' => isset($data_form['gota_gruesa']) ? trim($data_form['gota_gruesa']) : '',
                        'gb_hemato' => isset($data_form['gb_hemato']) ? trim($data_form['gb_hemato']) : '',
                        'linfocitos_hemato' => isset($data_form['linfocitos_hemato']) ? trim($data_form['linfocitos_hemato']) : '',
                        'monocitos_hemato' => isset($data_form['monocitos_hemato']) ? trim($data_form['monocitos_hemato']) : '',
                        'eosinofilos_hemato' => isset($data_form['eosinofilos_hemato']) ? trim($data_form['eosinofilos_hemato']) : '',
                        'basinofilos_hemato' => isset($data_form['basinofilos_hemato']) ? trim($data_form['basinofilos_hemato']) : '',
                        'banda_hemato' => isset($data_form['banda_hemato']) ? trim($data_form['banda_hemato']) : '',
                        'segmentados_hemato' => isset($data_form['segmentado_hemato']) ? trim($data_form['segmentado_hemato']) : '',
                        'metamielo_hemato' => isset($data_form['metamielo_hemato']) ? trim($data_form['metamielo_hemato']) : '',
                        'mielocitos_hemato' => isset($data_form['mielocitos_hemato']) ? trim($data_form['mielocitos_hemato']) : '',
                        'blastos_hemato' => isset($data_form['blasto_hemato']) ? trim($data_form['blasto_hemato']) : '',
                        'plaquetas_hemato' => isset($data_form['plaquetas_hemato']) ? trim($data_form['plaquetas_hemato']) : '',
                        'reti_hemato' => isset($data_form['reticulocitos_hemato']) ? trim($data_form['reticulocitos_hemato']) : '',
                        'eritro_hemato' => isset($data_form['eritrosedimentacion_hemato']) ? trim($data_form['eritrosedimentacion_hemato']) : '',
                        'otros_hema' => isset($data_form['otros_hemato']) ? trim($data_form['otros_hemato']) : '',
                        'numero_orden' => '',
                        'fecha' => date('Y-m-d'),
                        'estado' => '',
                        'hcm_hemato' => isset($data_form['hcm_hemato']) ? trim($data_form['hcm_hemato']) : ''
                    ];
                    $exists = ResultHemograma::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->first();
                    if($exists){
                        $exists->update($data);
                        $message = 'Los resultados se han actualizado exitosamente.';
                    }else{
                        ResultHemograma::create(array_merge($data,[
                            'jornada_id' => $jornada_id,
                            'empleado_id' => $empleado_id,
                            'empresa_id' => $empresa_id
                        ]));
                        $message = 'Los resultados se ha ingresado exitosamente.';
                    }
                    //update estado en det_jornada
                    $prop_data_examen = json_decode(request()->get('prop_data_examen'));
                    foreach($prop_data_examen as $item){
                        $examen_id = Lucipher::Descipher($item->examen_id);
                        $jornada_id = Lucipher::Descipher($item->jornada_id);
                        $empleado_id = Lucipher::Descipher($item->empleado_id);

                        if($data['hb_hemato'] != '' && $data['gb_hemato'] != '' && $data['plaquetas_hemato'] != ''){
                            $evaluacion = $this->getEvalResultHemograma($data['hb_hemato'],$data['gb_hemato'],$data['plaquetas_hemato']);
                        }else{
                            $evaluacion = '-';
                        }

                        DetJornada::where('examen_id',$examen_id)->where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 'Finalizado',
                            'evaluacion' => $evaluacion
                        ]);
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => $message,
                        'titleMsg' => 'Éxito'
                    ]);
                }
            }catch(Exception $e){
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }
        }else if($cat_examen == "especialidades"){

        }else if($cat_examen == "complementarios"){

        }

        return;
    }
    //obtener examen por categoria y resultado
    public function getExaResultado(){
        $empresa_id = Auth::user()->empresa_id;
        //validacion para el tipo de examen
        $cat_examen = Lucipher::Descipher(request()->input('cat_examen'));
        $categoria_id = Lucipher::Descipher(request()->input('categoria_id'));
        $categoria = request()->input('categoria');

        $jornada_id = Lucipher::Descipher(request()->input('jornada_id'));
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));

        if($cat_examen == "laboratorio clinico"){
            if($categoria === "QUIMICA"){
                $examenes = DB::select("SELECT e.id,e.nombre as examen,c.nombre as categoria,dj.cat_examen,dj.jornada_id,dj.empleado_id,COALESCE(drq.resultado,'') as resultado,COALESCE(drq.estado,'') as estado FROM `det_jornadas` as dj inner join examenes as e on dj.cat_examen='laboratorio clinico' and dj.examen_id=e.id and dj.empresa_id=e.empresa_id inner join categoria_examens as c on e.categoria_id=c.id LEFT JOIN det_resultado_quimicas as drq on drq.examen_id=e.id and dj.jornada_id=drq.jornada_id and dj.empleado_id=drq.empleado_id where c.id = ? and dj.jornada_id = ? and dj.empleado_id = ?", [$categoria_id,$jornada_id,$empleado_id]);
                $observaciones = ResultadoQuimica::where('jornada_id',$jornada_id)->where('empleado_id',$empleado_id)->where('empresa_id',$empresa_id)->select('observaciones')->first();
                if($observaciones){
                    $observaciones = $observaciones['observaciones'];
                }else{
                    $observaciones = '';
                }
                return response()->json([
                    'observaciones' => $observaciones,
                    'examenes' => $examenes
                ]);

            }else{
                $data = DB::select("SELECT e.id,e.nombre as examen,c.nombre as categoria,dj.cat_examen,dj.jornada_id,dj.empleado_id FROM `det_jornadas` as dj inner join examenes as e on dj.cat_examen='laboratorio clinico' and dj.examen_id=e.id and dj.empresa_id=e.empresa_id inner join categoria_examens as c on e.categoria_id=c.id where c.id = ? and dj.jornada_id = ? and dj.empleado_id = ?",[$categoria_id,$jornada_id,$empleado_id]);
                $examenes = [];
                foreach($data as $item){
                    $array = [
                        'id' => Lucipher::Cipher($item->id),
                        'examen' => $item->examen,
                        'categoria' => $item->categoria,
                        'cat_examen' => $item->cat_examen,
                        'jornada_id' => Lucipher::Cipher($item->jornada_id),
                        'empleado_id' => Lucipher::Cipher($item->empleado_id)
                    ];
                    $array['resultado'] = $this->getResultExamenById($item->id,$empleado_id,$jornada_id,$item->cat_examen);
                    $examenes[] = $array;
                }
                return response()->json($examenes);
            }
        }else if($cat_examen == "especialidades"){

        }else if($cat_examen == "complementarios"){

        }
        return $cat_examen;
    }
    //function copy de ResultadosExamen
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
}
