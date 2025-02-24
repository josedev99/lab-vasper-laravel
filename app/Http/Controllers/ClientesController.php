<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public function index(){
        $empresa_id = Auth::user()->empresa_id;
        return  view('Cliente.index');
    }

    public function listar_clientes(){
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select("SELECT e.id,o.numero_orden,o.estado_eval,e.categoria,e.tipo,e.estado,e.nombre,e.genero,e.telefono,e.fecha_nacimiento,e.direccion,e.fecha,e.hora FROM `empleados` as e inner join orden_labs as o on e.id=o.empleado_id and e.empresa_id=o.empresa_id WHERE e.empresa_id = ? and e.tipo = 'Cliente' ORDER BY e.id asc;",[$empresa_id]);
        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $genero = '';
            if($row->genero == "M"){
                $genero = "Masculino";
            }else if($row->genero == "F"){
                $genero = "Femenino";
            }else{
                $genero = $row->genero;
            }
            $edad = $this->calcularEdad($row->fecha_nacimiento);

            $iconInhabilitar = ($row->estado == '1') ? '<i class="bi bi-person-slash text-danger"></i>' : '<i class="bi bi-person-check text-success"></i>';

            $labelBtnStatusEmp = ($row->estado == '1') ? 'Deshabilitar cliente' : 'Habilitar cliente';
            //MODULE EXAMEN
            $sub_array[] = $contador;
            $sub_array[] = $row->numero_orden;
            $sub_array[] = ucwords(strtolower(Lucipher::Descipher($row->nombre)));
            $sub_array[] = $genero;
            $sub_array[] = $edad;
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->direccion;
            $sub_array[] = '<button data-ref="'. Lucipher::Cipher($row->id) .'" title="Actualizar información del empleado" class="btn btn-outline-info btn-sm" onclick="editEmpleado(this)" style="border:none;font-size:18px"><i class="bi bi-person-gear"></i></button>
            <button data-ref="'. Lucipher::Cipher($row->id) .'" title="Eliminar empleado" class="btn btn-outline-danger btn-sm btn-destroy" onclick="destroyEmp(this)" style="border:none;font-size:18px"><i class="bi bi-person-fill-x"></i></button>
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

    public function calcularEdad($fechaNacimiento) {
        date_default_timezone_set('America/El_Salvador');
        $fechaNac = new DateTime($fechaNacimiento);
        $hoy = new DateTime(); // Fecha actual

        $edad = $hoy->diff($fechaNac);
        return $edad->y;
    }
}
