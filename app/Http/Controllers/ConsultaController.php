<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\ConsultaIncapacidad;
use App\Models\Empleado;
use App\Models\Incapacidad;
use App\Models\Receta;
use App\Models\Signo;
use App\Models\signoVitalesMedida;
use App\Models\Sintoma;
use App\Models\Tratamiento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function save_consulta(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $data_form = request()->all();

        DB::beginTransaction();
        $empleado_id = Lucipher::Descipher($data_form['empleado_id']);
        //incapacidad
        $checkIncapaciadad = isset($data_form['incapacidad']) ? 'Si': 'No';
        //validate campos null
        $diagnostico = isset($data_form['diagnostico_consulta']) ? strtoupper(trim($data_form['diagnostico_consulta'])) : '';
        $observaciones = isset($data_form['observaciones']) ? $data_form['observaciones'] : '';
        //consulta id
        $consulta_id = isset($data_form['cita_id']) ? Lucipher::Descipher($data_form['cita_id']) : false;

        //validacion de consulta
        $consulta_id = isset($data_form['consulta_id']) ? Lucipher::Descipher($data_form['consulta_id']) : false;
        
        try{
            //format date inicio sintoma
            $fecha_inicio_sintoma = date('Y-m-d',strtotime(str_replace('/','-',$data_form['fecha_inicio_sintoma'])));

            $consulta_data = [
                'motivo' => $data_form['motivo_consulta'],
                'diagnostico' => $diagnostico,
                'riesgo' => '-',
                'incapacidad' => $checkIncapaciadad,
                'fecha_inicio_sintoma' => $fecha_inicio_sintoma,
                'observaciones' => $observaciones,
                'fecha' => $date,
                'hora' => $hora
            ];
            $data_signo_medidas = [
                'fc_ipm' => $data_form['signo_vital_fc'],
                'fr_rpm' => $data_form['signo_vital_fr'],
                'pa_ps_pd' => $data_form['signo_vital_pa'],
                'temperatura' => $data_form['medida_temp'],
                'saturacion' => $data_form['signo_vital_saturacion'],
                'peso_kg' => $data_form['medida_peso'],
                'talla_cm' => $data_form['medida_talla'],
                'imc' => $data_form['medida_imc']
            ];

            //validacion estado - segun creacion de consulta

            $estado = isset($data_form['is_data_preparado']) ? 'en proceso' : 'finalizada';

            if(!$consulta_id){
                $consulta_data = array_merge($consulta_data,[
                    'estado' => $estado,
                    'empleado_id' => $empleado_id,
                    'empresa_id' => $empresa_id,
                    'sucursal_id' => $sucursal_id,
                    'usuario_id' => $usuario_id
                ]);
                $save_consulta = Consulta::create($consulta_data);

                if($save_consulta){
                    $data_signo_medidas = array_merge($data_signo_medidas, [
                        'empleado_id' => $empleado_id,
                        'consulta_id' => $save_consulta->id,
                        'empresa_id' => $empresa_id
                    ]);

                    signoVitalesMedida::create($data_signo_medidas);
    
                    //save incapacidad si existe
                    $empleado_data = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->first();
                    if($checkIncapaciadad == "Si"){
                        //get_data_empleado
                        $array_rango_fechas = explode(' a ',$data_form['rango_fecha_incapacidad']);
                        if(count($array_rango_fechas) == 2){
                            $fecha_inicio = $array_rango_fechas[0];
                            $fecha_finaliz = $array_rango_fechas[1];
                            $data = [
                                'codigo_empleado' => $empleado_data['codigo_empleado'],
                                'colaborador' => $empleado_data['nombre'],
                                'cargo' => $empleado_data['cargo'],
                                'departamento' => $empleado_data['area_depto_id'],
                                'dui' => "-",
                                'categoria_incapacidad' => $data_form['cat_incapacidad'],
                                'diagnostico' => $data_form['diagnostico'],
                                'periodo' => $fecha_inicio,
                                'periodo_final' => $fecha_finaliz,
                                'motivo' => $data_form['motivo'],
                                'riesgo' => $data_form['riesgo'],
                                'tipo_incapacidad' => $data_form['tipo_incapacidad'],
                                'fecha_expedicion' => $data_form['fecha_expedicion'],
                                'sucursal_id' => $sucursal_id,
                                'empresa_id' => $empresa_id,
                                'empleado_id' => $empleado_data['id'],
                                'usuario_id' => $usuario_id,
                            ];
                            $save_incapacidad = Incapacidad::create($data);
                            //save relacion consulta == incapacidad
                            ConsultaIncapacidad::create([
                                'consulta_id' => $save_consulta->id,
                                'incapacidad_id' => $save_incapacidad->id,
                                'empresa_id' => $empresa_id
                            ]);
                        }
                    }
                    //update cita
                    if($consulta_id){
                        Cita::where('id',$consulta_id)->where('empresa_id',$empresa_id)->update([
                            'estado' => 1
                        ]);
                    }
                }
            } else {
                $consulta_data = array_merge($consulta_data,[
                    'estado' => $estado
                ]);
                $consulta_update = Consulta::where('id',$consulta_id)->where('empresa_id',$empresa_id)->update($consulta_data);

                if($consulta_update){

                    signoVitalesMedida::where('consulta_id',$consulta_id)->where('empresa_id',$empresa_id)->update($data_signo_medidas);
    
                    //save incapacidad si existe
                    $empleado_data = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->first();
                    if($checkIncapaciadad == "Si"){
                        //get_data_empleado
                        $array_rango_fechas = explode(' a ',$data_form['rango_fecha_incapacidad']);
                        if(count($array_rango_fechas) == 2){
                            $fecha_inicio = $array_rango_fechas[0];
                            $fecha_finaliz = $array_rango_fechas[1];
                            $data = [
                                'codigo_empleado' => $empleado_data['codigo_empleado'],
                                'colaborador' => $empleado_data['nombre'],
                                'cargo' => $empleado_data['cargo'],
                                'departamento' => $empleado_data['area_depto_id'],
                                'dui' => "-",
                                'categoria_incapacidad' => $data_form['cat_incapacidad'],
                                'diagnostico' => $data_form['diagnostico'],
                                'periodo' => $fecha_inicio,
                                'periodo_final' => $fecha_finaliz,
                                'motivo' => $data_form['motivo'],
                                'riesgo' => $data_form['riesgo'],
                                'tipo_incapacidad' => $data_form['tipo_incapacidad'],
                                'fecha_expedicion' => $data_form['fecha_expedicion'],
                                'sucursal_id' => $sucursal_id,
                                'empresa_id' => $empresa_id,
                                'empleado_id' => $empleado_data['id'],
                                'usuario_id' => $usuario_id,
                            ];
                            $save_incapacidad = Incapacidad::create($data);
                            //save relacion consulta == incapacidad
                            ConsultaIncapacidad::create([
                                'consulta_id' => $consulta_id,
                                'incapacidad_id' => $save_incapacidad->id,
                                'empresa_id' => $empresa_id
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return [
                'status' => 'success',
                'message' => 'La consulta se ha registrado exitosamente.',
                'results' => Lucipher::Cipher($empleado_id)
            ];
        }catch(Exception $err){
            DB::rollBack();
            return [
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de registrar la consulta.'
            ];
        }
    }

    //obtener las consultas por paciente
    public function getConsultas(){
        $empresa_id = Auth::user()->empresa_id;
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $consultas = DB::select('SELECT c.id as consulta_id,c.fecha_inicio_sintoma,c.motivo,c.diagnostico,c.riesgo,c.incapacidad,c.fecha,c.hora,s.*,COALESCE(DATEDIFF(i.periodo_final,i.periodo),"-") as days_incap,COALESCE(i.diagnostico,"-") as diag_incap,COALESCE(i.tipo_incapacidad,"-") as tipo_incapacidad FROM `consultas` as c INNER JOIN signo_vitales_medidas as s on c.id=s.consulta_id and c.empresa_id=s.empresa_id and c.empleado_id=s.empleado_id LEFT JOIN consulta_incapacidads as ci on c.id=ci.consulta_id and c.empresa_id=ci.empresa_id left join incapacidades as i on ci.incapacidad_id=i.id and ci.empresa_id=i.empresa_id where c.empleado_id = ? and c.empresa_id = ? order by c.id desc;',[$empleado_id,$empresa_id]);

        $data_final_consultas = [];
        foreach($consultas as $item){
            $array = [];
            $array['id'] = $item->consulta_id;
            $array['fecha'] = date('d-m-Y H:i:s A',strtotime($item->fecha." ".$item->hora));
            $array['motivo_consulta'] = $item->motivo;
            $array['diagnostico'] = $item->diagnostico;
            $array['riesgo'] = $item->riesgo;
            $array['incapacidad'] = $item->incapacidad;
            $array['fecha_inicio_sintoma'] = ($item->fecha_inicio_sintoma !== "") ? date('d/m/Y',strtotime($item->fecha_inicio_sintoma)) : '';

            $array['days_incap'] = ($item->days_incap != "-") ? $item->days_incap . " días de incapacidad." : '-';
            $array['diag_incap'] = $item->diag_incap;
            $array['tipo_incapacidad'] = $item->tipo_incapacidad;

            $array['fc_ipm'] = $item->fc_ipm;
            $array['fr_rpm'] = $item->fr_rpm;
            $array['pa_ps_pd'] = $item->pa_ps_pd;
            $array['temperatura'] = $item->temperatura;
            $array['saturacion'] = $item->saturacion;
            $array['peso_kg'] = $item->peso_kg;
            $array['talla_cm'] = $item->talla_cm;
            $array['imc'] = $item->imc;
            $data_final_consultas[] = $array;
        }
        return response()->json($data_final_consultas);
    }
    /**
     * Function para eliminar la consulta
     */
    public function deleteConsulta(){
        $empresa_id = Auth::user()->empresa_id;
        $consulta_id = request()->input('consulta_id');
        //validaciones pendiente
        //delete consulta y asociados
        DB::beginTransaction();
        try{
            $consulta = Consulta::where('id',$consulta_id)->where('empresa_id',$empresa_id)->first();
            if($consulta){
                $empleado_id = $consulta->empleado_id;
                signoVitalesMedida::where('consulta_id',$consulta->id)->where('empresa_id')->delete();
                $consulta->delete();
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'La consulta se ha removido exitosamente.',
                    'results' => [
                        'id' => Lucipher::Cipher($empleado_id)
                    ]
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error en remover la consulta.'
            ]);
        }catch(Exception $err){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de eliminar la consulta'
            ]);
        }
        return response()->json($consulta_id);
    }

    /**
     * Method para obtener una consulta mediante ID
     */
    public function get_consulta_previa(){
        $consulta_id = Lucipher::Descipher(request()->input('consulta_id'));
        $empresa_id = Auth::user()->empresa_id;
        if($consulta_id){
            $data = DB::select("SELECT c.id as consulta_id,e.id as empleado_id,e.nombre as colaborador,e.telefono,e.codigo_empleado,c.estado,c.motivo,c.diagnostico,c.fecha_inicio_sintoma,c.estado,c.empleado_id,svm.fc_ipm,svm.fr_rpm,svm.pa_ps_pd,svm.temperatura,svm.saturacion,svm.peso_kg,svm.talla_cm,svm.imc FROM `consultas` as c inner join signo_vitales_medidas as svm on c.id=svm.consulta_id and c.empleado_id=svm.empleado_id and c.empresa_id=svm.empresa_id INNER JOIN empleados as e on e.id=c.empleado_id and e.empresa_id=c.empresa_id where c.id = ? and c.empresa_id = ?;",[$consulta_id,$empresa_id]);
            $dataFinal = [];
            if($data){
                $dataFinal = $data[0];
                $dataFinal->colaborador = Lucipher::Descipher($dataFinal->colaborador);
                $dataFinal->fecha_inicio_sintoma = date('d/m/Y',strtotime($dataFinal->fecha_inicio_sintoma));
                $dataFinal->consulta_id = Lucipher::Cipher($dataFinal->consulta_id);
                $dataFinal->empleado_id = Lucipher::Cipher($dataFinal->empleado_id);
                $dataFinal->isData = 'Si';
                return response()->json($dataFinal);
            }
            return response()->json($dataFinal['isData'] = 'No');
        }else{
            return response()->json([]);
        }
    }
}
