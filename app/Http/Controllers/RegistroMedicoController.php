<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\AreaDepartamentoEmp;
use App\Models\Consulta;
use App\Models\MotivoIncapacidad;
use App\Models\Sintoma;
use App\Models\Sucursal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroMedicoController extends Controller
{
    public function index_medico(){
        date_default_timezone_set('America/El_Salvador');

        $fecha_actual = date('d/m/Y');
        $data = $this->get_data_index();

        $sucursales = $data['sucursales'];
        $motivos = $data['motivos'];
        $areas_depto = $data['area_depto'];

        return view('RegistroMedico.index_medico',compact('sucursales','areas_depto','fecha_actual','motivos'));
    }

    function get_data_index(){
        $empresa_id = Auth::user()->empresa_id;

        $deptos = AreaDepartamentoEmp::where('empresa_id',$empresa_id)->get();
        $areas_depto = [];
        foreach($deptos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }

        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();

        $mot = MotivoIncapacidad::where('empresa_id',$empresa_id)->get();
        $motivos = [];
        foreach($mot as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['motivo'] = Lucipher::Descipher($item['motivo']);
            $motivos[] = $array;
        }
        return [
            'area_depto' => $areas_depto,
            'sucursales' => $sucursales,
            'motivos' => $motivos
        ];
    }

    public function index_enfermeria(){
        date_default_timezone_set('America/El_Salvador');

        $fecha_actual = date('d/m/Y');

        $data = $this->get_data_index();

        $sucursales = $data['sucursales'];
        $motivos = $data['motivos'];
        $areas_depto = $data['area_depto'];

        return view('RegistroMedico.index_enfermeria',compact('sucursales','areas_depto','fecha_actual','motivos'));
    }

    //Filtrar datos
    public function listar_empleados(){
        $empresa_id = Auth::user()->empresa_id;
        $opcionCitasOrLab = request()->input('opcion');
        if($opcionCitasOrLab == "citas"){
            $fecha_filtro = date('Y-m-d',strtotime(str_replace('/','-',request()->input('fecha_filtro'))));
            $datos = DB::table('citas as c')
                ->join('sucursals as s','c.sucursal_id','=','s.id')
                ->select('c.id','c.estado','c.codigo_empleado','c.nombre as colaborador','c.telefono','c.fecha_cita','c.hora_cita','s.nombre as sucursal')
                ->where('c.empresa_id',$empresa_id)
                ->where('c.fecha_cita',$fecha_filtro)
                ->where('c.estado','0')
                ->orderBy('c.id','asc')
                ->get();
        }else if($opcionCitasOrLab == "colaboradores"){
            $datos = DB::select("SELECT e.id,e.nombre as colaborador,e.telefono,e.codigo_empleado,s.nombre as sucursal,e.fecha as fecha_cita,e.hora as hora_cita FROM `empleados` as e INNER join sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id where e.empresa_id = ? order by e.id asc",[$empresa_id]);
        }else if($opcionCitasOrLab == "preparados"){
            $datos = DB::select("SELECT c.id,e.nombre as colaborador,e.telefono,e.codigo_empleado,s.nombre as sucursal,c.fecha as fecha_cita,c.hora as hora_cita FROM `empleados` as e INNER join sucursals as s on e.sucursal_id=s.id and e.empresa_id=s.empresa_id INNER JOIN consultas AS c on e.id=c.empleado_id and e.empresa_id=c.empresa_id where e.empresa_id = ? and c.estado = 'en proceso' order by e.id asc;",[$empresa_id]);
        }else {
            $datos = [];
        }

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = ($row->fecha_cita != '-') ? date('d-m-Y',strtotime($row->fecha_cita)) : $row->fecha_cita;
            $sub_array[] = $row->hora_cita;
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = Lucipher::Descipher($row->colaborador);
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->sucursal;
            $sub_array[] = '
            <button type="button" data-opcion="'.$opcionCitasOrLab.'" data-ref="'. Lucipher::Cipher($row->id) .'" data-codigo_emp="'. Lucipher::Cipher($row->codigo_empleado) .'" title="Registrar consulta" class="btn btn-outline-info btn-sm btn-consulta" style="border:none;font-size:18px"><i class="bi bi-clipboard2-plus"></i></button>
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
}
