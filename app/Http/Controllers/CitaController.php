<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\HorariosCita;
use App\Models\Sucursal;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class CitaController extends Controller
{
    public function form_cita_public($empresa_id = ''){

        $empresa_id = Lucipher::Descipher($empresa_id);

        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        
        session(['empresa_id' => $empresa_id]);

        $response = response()->view('Cita.public_form_cita',compact('sucursales'));
        return $response;
    }

    public function save_cita(){
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $hora = date('H:i:s');
        $data_form = request()->validate([
            'codigo_empleado' => 'required|min:1|max:200|string',
            'nombre_empleado' => 'required||min:1|max:200|string',
            'telefono_emp' => 'required||min:1|max:15|string',
            'fecha_cita' => 'required||min:10|max:20|string',
            'hora_cita' => 'required||min:2|max:200|string',
            'sucursal_id' => 'required|string',
            'motivo' => 'required|string|max:500',
            'fecha_inicio_sintoma' => 'required|string|max:15'
        ]);

        $empresa_id = session('empresa_id');
        //validacion para reenvio de datos ==>> genera error message
        if($empresa_id){
            if(request()->has('_token')){
                //Validacion para evitar ingresas empleados duplicados
                $fecha_cita = str_replace('/','-',$data_form['fecha_cita']);
                $fecha_cita = date('Y-m-d',strtotime($fecha_cita));
    
                $codigo_empleado = strtoupper(trim($data_form['codigo_empleado']));
                
                $motivo_cita = strtoupper(trim($data_form['motivo']));

                $save_cita = Cita::create([
                    'estado' => '0',
                    'codigo_empleado' => $codigo_empleado,
                    'nombre' => Lucipher::Cipher(trim(strtoupper($data_form['nombre_empleado']))),
                    'telefono' => $data_form['telefono_emp'],
                    'motivo' => $motivo_cita,
                    'fecha_inicio_sintoma' => $data_form['fecha_inicio_sintoma'],
                    'fecha_cita' => $fecha_cita,
                    'hora_cita' => $data_form['hora_cita'],
                    'fecha' => $date,
                    'hora' => $hora,
                    'empresa_id' => $empresa_id,
                    'sucursal_id' => $data_form['sucursal_id']
                ]);
                if($save_cita){
                    $save_cita->nombre = Lucipher::Descipher($save_cita->nombre);
                    $save_cita->fecha_cita = date('d/m/Y',strtotime($save_cita->fecha_cita));
                    return redirect()->route('cita.public.form')->with('success','Su cita ha sido registrada con éxito.')->with('data', $save_cita);
                }
                return redirect()->route('cita.public.form',Lucipher::Cipher($empresa_id))->with('error','Ha ocurrido un error al momento de registrar su cita.');
            }
            return redirect()->route('cita.public.form',Lucipher::Cipher($empresa_id))->with('error','Ha ocurrido un error inesperado.');
        }else{
            return redirect()->route('cita.public.form')->with('error','Su cita ya ha sido registrada previamente.');
        }
    }

    public function get_horarios_disp(){
        date_default_timezone_set('America/El_Salvador');
        $currentDay = date('Y-m-d');

        $empresa_id = (session()->has('empresa_id') && !empty(session('empresa_id'))) ? session('empresa_id') : Auth::user()->empresa_id;

        $fecha_cita = date('Y-m-d',strtotime(str_replace('/','-',request()->input('fecha_cita'))));
        $sucursal_id = request()->input('sucursal_id');

        $fecha1 = new DateTime($currentDay);
        $fecha2 = new DateTime($fecha_cita);

        if($fecha2 >= $fecha1){
            $dayString = strtolower($this->getDayString($fecha_cita));

            $horarios = $this->getHorarios($dayString,$fecha_cita,$empresa_id,$sucursal_id);

            return response()->json($horarios);
        }
        return [];
    }

    public function get_citados_calendar(){
        $empresa_id = Auth::user()->empresa_id;

        $citados = DB::select("SELECT c.id,concat('Citas: ',count(c.id)) as title,c.fecha_cita as start FROM `citas` as c WHERE c.empresa_id = ? GROUP by c.fecha_cita;",[$empresa_id]);

        return response()->json($citados);
    }

    public function get_citados(){
        $empresa_id = Auth::user()->empresa_id;

        $fecha = request()->input('fecha');
        if($fecha != ''){
            $citas_cifrado = DB:: select("SELECT c.id,c.estado,c.codigo_empleado,c.nombre,c.telefono,c.motivo,c.fecha_cita,c.hora_cita,s.nombre as sucursal FROM `citas` as c INNER JOIN sucursals as s on c.sucursal_id=s.id and c.empresa_id=s.empresa_id where c.empresa_id = ? and c.fecha_cita = ?",[$empresa_id,$fecha]);
        }else{
            $citas_cifrado = DB:: select("SELECT c.id,c.estado,c.codigo_empleado,c.nombre,c.telefono,c.motivo,c.fecha_cita,c.hora_cita,s.nombre as sucursal FROM `citas` as c INNER JOIN sucursals as s on c.sucursal_id=s.id and c.empresa_id=s.empresa_id where c.empresa_id = ?",[$empresa_id]);
        }
        
        $contador = 1;
        $data = [];
        foreach ($citas_cifrado as $row) {
            $sub_array = array();
            $estado = '';
            if($row->estado == "0"){
                $estado = '<span class="badge text-dark" style="background: #f3da8f;font-size:10px"><i class="bi bi-exclamation-triangle me-1"></i>Sin atender</span>';
            }else if($row->estado == "1"){
                $estado = '<span class="badge" style="background: #29b373;font-size:10px"><i class="bi bi-check-circle me-1"></i> Atendido</span>';
            }else if($row->estado == "-1"){
                $estado = '<span class="badge bg-danger" style="background: #f3da8f;font-size:10px"><i class="bi bi-exclamation-octagon me-1"></i> Anulado</span>';
            }

            //validaciones para button de edicion
            $btnEdicion = '';
            $btnAnularCita = '';

            if($row->estado == "0"){
                $btnAnularCita = '<button data-cita_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. Lucipher::Descipher($row->nombre) .'" data-cod_empleado="'. $row->codigo_empleado .'" title="Anular cita" class="btn btn-outline-danger btn-sm btn-event-anular" style="border:none;font-size:18px"><i class="bi bi-x-square"></i></button>';

                $btnEdicion = '<button data-cita_id="'. Lucipher::Cipher($row->id) .'" data-nombre="'. Lucipher::Descipher($row->nombre) .'" data-cod_empleado="'. $row->codigo_empleado .'" data-telefono="'. $row->telefono .'" data-sucursal="'. $row->sucursal .'" title="Editar la información de la cita" class="btn btn-outline-secondary btn-sm btn-event-edit" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>';
            }

            $sub_array[] = $contador;
            $sub_array[] = $estado;
            $sub_array[] = date('d-m-Y',strtotime($row->fecha_cita));
            $sub_array[] = $row->hora_cita;
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = Lucipher::Descipher($row->nombre);
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->motivo;
            $sub_array[] = $row->sucursal;
            $sub_array[] = $btnEdicion . $btnAnularCita;
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

    public function nueva_cita_calendar(){
        $empresa_id = Auth::user()->empresa_id;
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $hora = date('H:i:s');
        $data_form = request()->all();
        //Validacion para evitar ingresas empleados duplicados
        $fecha_cita = str_replace('/','-',$data_form['fecha_cita']);
        $fecha_cita = date('Y-m-d',strtotime($fecha_cita));
        //validar si la fecha se modifico o la hora
        $fecha_old = new DateTime(session('fecha_cita'));
        $fecha_cita_form = new DateTime($fecha_cita);
        $hora_old = session('hora_cita');
        if($fecha_old != $fecha_cita_form && $hora_old != $data_form['hora_cita']){
            //validar si la hora ya fue ocupada
            $validated_hora = Cita::where('fecha_cita',$fecha_cita)->where('hora_cita',$data_form['hora_cita'])->where('sucursal_id',$data_form['sucursal_emp'])->where('empresa_id',$empresa_id)->exists();
            if($validated_hora){
                return response()->json([
                    'status' => 'warning',
                    'message' => 'La hora de la cita no esta disponible.'
                ]);
            }
        }

        $codigo_empleado = strtoupper(trim($data_form['codigo_empleado']));

        $motivo_cita = strtoupper(trim($data_form['motivo']));
        $fecha_inicio_sintoma = date('Y-m-d',strtotime(str_replace('/','-',$data_form['fecha_inicio_sintoma'])));
        //validar si es una ediccion
        $data_cita = [
            'estado' => '0',
            'codigo_empleado' => $codigo_empleado,
            'nombre' => Lucipher::Cipher(trim(strtoupper($data_form['nombre_empleado']))),
            'telefono' => $data_form['telefono'],
            'motivo' => $motivo_cita,
            'fecha_inicio_sintoma' => $fecha_inicio_sintoma,
            'fecha_cita' => $fecha_cita, 
            'hora_cita' => $data_form['hora_cita'],
            'fecha' => $date,
            'hora' => $hora,
            'sucursal_id' => $data_form['sucursal_emp']
        ];

        if(!isset($data_form['cita_id'])){
            $data_cita = array_merge($data_cita,[
                'empresa_id' => $empresa_id
            ]);
            $save_cita = Cita::create($data_cita);
            $message = 'La cita se ha registrado exitosamente.';
        }else{
            $cita_id = Lucipher::Descipher($data_form['cita_id']);
            $save_cita = Cita::where('id',$cita_id)->where('empresa_id',$empresa_id)->update($data_cita);
            $message = 'La cita se ha actualizado exitosamente.';
        }

        if($save_cita){
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar la cita.'
        ]);
    }

    /**
     * Obtener fechas disponibles para agendar cita
     */
    public function get_horarios_citas_disp(){
        date_default_timezone_set('America/El_Salvador');

        $empresa_id = Auth::user()->empresa_id;
        $fecha_cita = date('Y-m-d',strtotime(str_replace('/','-',request()->input('fecha_cita'))));
        $empleado_id = Lucipher::Descipher(request()->input('empleado_id'));
        $empleado_data = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->first();
        if($empleado_data){
            $sucursal_id = $empleado_data['sucursal_id'];
            //validar fechas
            $currentDays = date('Y-m-d');
            $fecha1 = new DateTime($fecha_cita);
            $fecha2 = new DateTime($currentDays);
            if($fecha1 >= $fecha2){
                $dayString = strtolower($this->getDayString($fecha_cita));

                $horarios = $this->getHorarios($dayString,$fecha_cita,$empresa_id,$sucursal_id);
                return response()->json($horarios);
            }
            return [];
        }
        return [];
    }

    //registrar nueva cita desde consulta
    public function agendar_proxima_cita(){
        $empresa_id = Auth::user()->empresa_id;
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $data_form = request()->all();
        //Validacion para evitar ingresas empleados duplicados
        $fecha_cita = str_replace('/','-',$data_form['fecha_cita']);
        $fecha_cita = date('Y-m-d',strtotime($fecha_cita));

        $empleado_id = Lucipher::Descipher($data_form['empleado_id']);

        if($empleado_id){
            $empleado_data = Empleado::where('id',$empleado_id)->where('empresa_id',$empresa_id)->first();
    
            $save_cita = Cita::create([
                'estado' => '0',
                'codigo_empleado' => $empleado_data['codigo_empleado'],
                'nombre' => $empleado_data['nombre'],
                'telefono' => $empleado_data['telefono'],
                'motivo' => 'Nueva cita programada desde la consulta.',
                'fecha_cita' => $fecha_cita,
                'hora_cita' => $data_form['hora_cita'],
                'fecha' => $date,
                'hora' => $hora,
                'empresa_id' => $empresa_id,
                'sucursal_id' => $empleado_data['sucursal_id']
            ]);
            if($save_cita){
                return response()->json([
                    'status' => 'success',
                    'message' => 'La cita se ha registrado exitosamente.'
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar la cita.'
        ]);
    }

    //OBTENER DATOS DE LA CITA
    public function getDataCitado(){
        $empresa_id = Auth::user()->empresa_id;
        $codigo_empleado = request()->input('codigo_empleado');

        $dataCitado = Cita::where('codigo_empleado',$codigo_empleado)->where('empresa_id',$empresa_id)->select('codigo_empleado','nombre','telefono')->first();
        if($dataCitado){
            $dataCitado->nombre = Lucipher::Descipher($dataCitado->nombre);
        }else{
            $dataCitado = 'not-data';
        }

        return response()->json($dataCitado);
    }
    //OBTENER DATOS DE LA CITA MEDIANTE ID
    public function getDataCitaById(){
        $empresa_id = Auth::user()->empresa_id;
        $cita_id = Lucipher::Descipher(request()->input('cita_id'));

        $dataCitado = Cita::where('id',$cita_id)->where('empresa_id',$empresa_id)->first();

        if($dataCitado){
            //guardar datos en session para comparar y validar que sean iguales para no actualizar registro de fecha cita y hora cita
            session([
                "fecha_cita" => $dataCitado->fecha_cita,
                "hora_cita" => $dataCitado->hora_cita
            ]);

            $dataCitado->nombre = Lucipher::Descipher($dataCitado->nombre);
            $dataCitado->fecha_cita = date('d/m/Y',strtotime(str_replace('-','/',$dataCitado->fecha_cita)));
            $dataCitado->fecha_inicio_sintoma = ($dataCitado->fecha_inicio_sintoma != "") ? date('d/m/Y',strtotime(str_replace('-','/',$dataCitado->fecha_inicio_sintoma))) : '';
        }else{
            $dataCitado = 'not-data';
        }

        return response()->json($dataCitado);
    }

    public function generar_pdf(){
        $empresa_id = Auth::user()->empresa_id;
        $empresa = Empresa::where('id',$empresa_id)->first();//data empresa

        $form = request()->all();


        $fecha_cita = explode('_',base64_decode($form['fecha_cita']));
        $fecha_cita = $fecha_cita[1];

        $citas_cifrado = DB:: select("SELECT c.id,c.estado,c.codigo_empleado,c.nombre,c.telefono,c.motivo,c.fecha_cita,c.hora_cita,s.nombre as sucursal FROM `citas` as c INNER JOIN sucursals as s on c.sucursal_id=s.id and c.empresa_id=s.empresa_id where c.empresa_id = ? and c.fecha_cita = ? and c.estado != '-1' order by c.hora_cita asc",[$empresa_id,$fecha_cita]);

        $data = [];
        $contador = 1;
        foreach ($citas_cifrado as $row) {
            $sub_array = array();
            if($row->estado == "0"){
                $estado = 'Sin atender';
            }else if($row->estado == "1"){
                $estado = 'Atendido';
            }else if($row->estado == "2"){
                $estado = 'Anulado';
            }

            $sub_array['id'] = $contador;
            $sub_array['estado'] = $estado;
            $sub_array['fecha'] = date('d-m-Y',strtotime($row->fecha_cita));
            $sub_array['hora'] = $row->hora_cita;
            $sub_array['codigo'] = $row->codigo_empleado;
            $sub_array['nombre'] = Lucipher::Descipher($row->nombre);
            $sub_array['telefono'] = $row->telefono;
            $sub_array['motivo'] = $row->motivo;
            $sub_array['sucursal'] = $row->sucursal;
            $data[] = $sub_array;
            $contador ++;
        }

        $pdf = PDF::loadView('agenda.pdf.imprimir_citas',compact('data','fecha_cita','empresa'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('citas-'.$fecha_cita.".pdf");
    }

    public function anular_cita(){
        $empresa_id = Auth::user()->empresa_id;
        $cita_id = Lucipher::Descipher(request()->input('cita_id'));
        if($cita_id){
            $result = Cita::where('id',$cita_id)->where('empresa_id',$empresa_id)->update([
                'estado' => '-1'
            ]);

            if($result){
                return response()->json([
                    'status' => 'success',
                    'message' => 'La cita se ha anulado exitosamente.'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de anular la cita.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error inesperado.'
        ]);
    }

    /**
     * Method public para obtener el dia en string apartir de una fecha
     */
    public function getDayString($date){
        date_default_timezone_set('America/El_Salvador');
        
        $arrayDayString = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
        $indexDate = date('w',strtotime($date));
        return $arrayDayString[$indexDate];
    }
    /**
     * public function obtener horarios
     */
    public function getHorarios($dayString,$fecha_cita,$empresa_id,$sucursal_id){
        $horarios = DB::select('select hc.hora,hc.estado from horarios_citas as hc WHERE hc.hora not in (select c.hora_cita from citas as c WHERE hc.hora=c.hora_cita and hc.empresa_id=c.empresa_id and c.estado != "-1" and c.fecha_cita = ?) and hc.dia = ? and hc.empresa_id = ? and hc.sucursal_id = ? and hc.estado = "disponible";', [$fecha_cita,$dayString,$empresa_id,$sucursal_id]);
        return $horarios;
    }
}
