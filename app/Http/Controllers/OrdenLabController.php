<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\DetJornada;
use App\Models\DetOrdenLab;
use App\Models\DetResultEvaluacion;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\OrdenLab;
use App\Models\OrdenLabJornada;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class OrdenLabController extends Controller
{
    public function __construct(){
        date_default_timezone_set('America/El_Salvador');
    }
    public function save_orden(){
        date_default_timezone_set('America/El_Salvador');
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $data_form = request()->except('search_examen');
        $data_form['data_examenes'] = json_decode($data_form['data_examenes']);
        //validacion de cantidad de examenes
        if(count($data_form['data_examenes']) === 0){
            return [
                'status' => 'error',
                'message' => 'No ha seleccionado ningun examen para este paciente'
            ];
        }
        try{
            //obtener el colaborador
            $empleado_id = Lucipher::Descipher($data_form['emp_id']);
            $empleado = Empleado::where('id', $empleado_id)->first();
            $empresa_id = $empleado['empresa_id'];
            $sucursal_id = $empleado['sucursal_id'];
            //save orden lab
            DB::beginTransaction();
            //validar si existe jornada
            if($data_form['jornada_orden'] !== null){
                $jornada_id = (int)$data_form['jornada_orden'];

                $numero_orden = $this->get_correlativo_orden();
                $save_orden_lab = OrdenLab::create([
                    'numero_orden' => $numero_orden,
                    'fecha' => $date,
                    'hora' => $hora,
                    'estado' => '0',
                    'estado_eval' => 'Sin evaluar',
                    'empleado_id' => $empleado_id,
                    'empresa_id' => $empresa_id,
                    'sucursal_id' => $sucursal_id,
                    'usuario_id' => $usuario_id
                ]);
                DetResultEvaluacion::create([
                    'fecha' => $date,
                    'hora' => $hora,
                    'estado' =>  'Sin evaluar',
                    'jornada_id' => $jornada_id,
                    'empleado_id' => $empleado_id,
                    'empresa_id' => $empresa_id,
                    'usuario_id' => $usuario_id
                ]);
                foreach($data_form['data_examenes'] as $item){
                    DetOrdenLab::create([
                        'numero_orden' => $numero_orden,
                        'estado' => '0',
                        'empleado_id' => $empleado_id,
                        'categoria_id' => $item->categoria_id,
                        'examen_id' => $item->examen_id,
                        'orden_lab_id' => $save_orden_lab->id
                    ]);

                    DetJornada::create([
                        'estado' => 'Pendiente',
                        'evaluacion' => '-',
                        'cat_examen' => 'laboratorio clinico',
                        'examen_id' => $item->examen_id,
                        'jornada_id' => $jornada_id,
                        'empleado_id' => $empleado_id,
                        'empresa_id' => $empresa_id,
                        'usuario_id' => $usuario_id
                    ]);
                }
                $exists = OrdenLabJornada::where('jornada_id', $data_form['jornada_orden'])->where('empleado_id', $empleado_id)->where('empresa_id', $empresa_id)->first();
                if(!$exists){
                    OrdenLabJornada::create([
                        'empleado_id' => $empleado_id,
                        'orden_lab_id' => $save_orden_lab->id,
                        'jornada_id' => $data_form['jornada_orden'],
                        'empresa_id' => $empresa_id
                    ]);
                }else{
                    DB::rollBack();
                    return [
                        'status' => 'error',
                        'message' => 'Ya hay una orden registrada para la jornada seleccionada.'
                    ];
                }
            }
            DB::commit();
            return [
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente la orden de examenes.',
                'results' => [
                    'id' => Lucipher::Cipher($save_orden_lab->id),
                    'empresa_id' => Lucipher::Cipher($empresa_id)
                ]
            ];
        }catch(Exception $err){
            DB::rollBack();
            return [
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de registrar la orden.'
            ];
        }
    }

    public function save_orden_cliente(){
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        try{
            DB::beginTransaction();
            $formData = request()->except('search_examen');
            $data_examenes = json_decode($formData['data_examenes']);
            
            $numero_orden = $this->get_correlativo_orden();
            //datos formulario
            $fecha_formated = !is_null($_POST['fecha_nac']) ? str_replace('/','-',$_POST['fecha_nac']) : '';
            $fecha_nacimiento = date('Y-m-d',strtotime($fecha_formated));
            $array_data_paciente = [
                'categoria' => 'Cliente',
                'tipo' => 'Cliente',
                'nombre' => Lucipher::Cipher(trim(strtoupper($formData['nombre_empleado']))),
                'genero' => $formData['genero_emp'],
                'telefono' => trim($formData['telefono']),
                'codigo_empleado' => '-',
                'no_afiliacion' => '-',
                'fecha_ingreso' => '-',
                'area_depto_id' => 0,
                'cargo' => '-',
                'cargo_id' => '-',
                'fecha_nacimiento' => $fecha_nacimiento,
                'direccion' => strtoupper(trim($formData['direccion_cliente'])),
                'fecha' => $date,
                'hora' => $hora,
                'empresa_id' => $empresa_id,
                'sucursal_id' => $sucursal_id,
                'usuario_id' => $usuario_id,
            ];
    
            $paciente_save = Empleado::create($array_data_paciente);
    
            $save_orden_lab = OrdenLab::create([
                'numero_orden' => $numero_orden,
                'fecha' => $date,
                'hora' => $hora,
                'estado' => '0',
                'estado_eval' => 'Sin evaluar',
                'empleado_id' => $paciente_save->id,
                'empresa_id' => $empresa_id,
                'sucursal_id' => $sucursal_id,
                'usuario_id' => $usuario_id
            ]);
            foreach($data_examenes as $item){
                DetOrdenLab::create([
                    'numero_orden' => $numero_orden,
                    'estado' => '0',
                    'empleado_id' => $paciente_save->id,
                    'categoria_id' => $item->categoria_id,
                    'examen_id' => $item->examen_id,
                    'orden_lab_id' => $save_orden_lab->id
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente la orden de examenes.',
                'results' => [
                    'id' => Lucipher::Cipher($save_orden_lab->id)
                ]
            ]);
        }catch(Exception $err){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de registrar la orden.'
            ]);
        }
    }

    public function get_correlativo_orden(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
		$mes = date('m');
		$year = date('Y');
		$anio = substr($year, 2, 5);

        $month_year = "%" . $year . "-". $mes . "%";
        
        $data = DB::select("select numero_orden from orden_labs where fecha like ? and empresa_id = ? order by id desc limit 1", [$month_year,$empresa_id]);
        ////////OBTENEMOS EL CORRELATIVO //////
        if (is_array($data) and count($data) > 0) {
            foreach ($data as $row) {
                $numero_orden = substr($row->numero_orden, 4, 15) + 1;
                $codigo = $mes . $anio . $numero_orden;
            }
        } else {
            $codigo = $mes . $anio . '1';
        }
        return $codigo;
    }
    /**
     * PDF ORDEN EXAMENES
     */
    public function imprimir_orden_pdf(){
        $data_request = json_decode(request()->get('data'));

        $orden_id = Lucipher::Descipher($data_request->id);
        $empresa_id = Lucipher::Descipher($data_request->empresa_id);

        $empresa = Empresa::where('id',$empresa_id)->first();//data empresa

        if($orden_id){
            $data = DB::select('SELECT o.numero_orden,o.fecha,o.hora,o.estado,c.nombre as categoria,e.nombre as examen,emp.codigo_empleado,emp.nombre as colaborador,a.nombre as area_depto,empsa.nombre as empresa FROM `orden_labs` as o INNER JOIN det_orden_labs as d on o.id=d.orden_lab_id and o.numero_orden=d.numero_orden and o.empleado_id=d.empleado_id inner join examenes as e on d.examen_id=e.id inner join categoria_examens as c on d.categoria_id=c.id and e.categoria_id=c.id INNER JOIN empleados as emp on o.empleado_id=emp.id and o.empresa_id=emp.empresa_id INNER JOIN area_emps as a on emp.area_depto_id=a.id INNER JOIN empresas as empsa on emp.empresa_id=empsa.id WHERE o.id = ? and o.empresa_id = ?',[$orden_id,$empresa_id]);
            $data_orden = [];
            foreach($data as $row){
                $data_orden = [
                    'codigo_empleado' => $row->codigo_empleado,
                    'numero_orden' => $row->numero_orden,
                    'fecha' => date('d-m-Y',strtotime($row->fecha)),
                    'hora' => $row->hora,
                    'estado' => $row->estado,
                    'colaborador' => Lucipher::Descipher($row->colaborador),
                    'area_depto' => $row->area_depto,
                    'empresa' => $row->empresa,
                    'examenes' => []
                ];
                foreach($data as $item){
                    if (!empty($item->examen)) {
                        $data_orden['examenes'][] = ['examen' => $item->examen,'categoria' => $item->categoria];
                    }
                }
                break;
            }
    
            $pdf = PDF::loadView('Orden.pdf.imp_orden_examenes',compact('data_orden','empresa'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('examenes-orden-'.$data_orden['numero_orden'].".pdf");
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error inesperado.'
        ]);
    }

    public function boleta_examenes_cliente(){
        $empresa_id = Auth::user()->empresa_id;
        $empresa = Empresa::where('id',$empresa_id)->first();//data empresa

        $orden_id = Lucipher::Descipher(request()->input('id'));
        if($orden_id){
            $data = DB::select('SELECT o.numero_orden,o.fecha,o.hora,o.estado,c.nombre as categoria,e.nombre as examen,emp.nombre as colaborador,emp.genero,emp.fecha_nacimiento FROM `orden_labs` as o INNER JOIN det_orden_labs as d on o.id=d.orden_lab_id and o.numero_orden=d.numero_orden and o.empleado_id=d.empleado_id inner join examenes as e on d.examen_id=e.id and o.empresa_id=e.empresa_id inner join categoria_examens as c on d.categoria_id=c.id and e.categoria_id=c.id and c.empresa_id=o.empresa_id INNER JOIN empleados as emp on o.empleado_id=emp.id and o.empresa_id=emp.empresa_id WHERE o.id = ? and o.empresa_id = ?',[$orden_id,$empresa_id]);
            $data_orden = [];
            foreach($data as $row){
                $genero = '';
                if($row->genero == "M"){
                    $genero = "Masculino";
                }else if($row->genero == "F"){
                    $genero = "Femenino";
                }
                $data_orden = [
                    'numero_orden' => $row->numero_orden,
                    'genero' => $genero,
                    'edad' => $edad = $this->calcularEdad($row->fecha_nacimiento),
                    'fecha' => date('d-m-Y',strtotime($row->fecha)),
                    'hora' => $row->hora,
                    'estado' => $row->estado,
                    'cliente' => Lucipher::Descipher($row->colaborador),
                    'examenes' => []
                ];
                foreach($data as $item){
                    if (!empty($item->examen)) {
                        $data_orden['examenes'][] = ['examen' => $item->examen,'categoria' => $item->categoria];
                    }
                }
                break;
            }

            $pdf = PDF::loadView('Orden.pdf.boleta_examenes_cliente',compact('data_orden','empresa'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('examenes-orden-'.$data_orden['numero_orden'].".pdf");
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error inesperado.'
        ]);
    }

    public function calcularEdad($fechaNacimiento) {
        date_default_timezone_set('America/El_Salvador');
        $fechaNac = new DateTime($fechaNacimiento);
        $hoy = new DateTime(); // Fecha actual

        $edad = $hoy->diff($fechaNac);
        return $edad->y;
    }
}
