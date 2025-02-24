<?php

namespace App\Http\Controllers;

use App\Models\CategoriaExamen;
use App\Models\DetPerfilExamen;
use App\Models\PerfilExamen;
use App\Models\PruebaEspecial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerfilExamenController extends Controller
{

    public function index(){
        $empresa_id = Auth::user()->empresa_id;
        $sucursales = DB::select('SELECT * from sucursals where empresa_id = ?',[$empresa_id]);
        $cat_examenes = CategoriaExamen::where('empresa_id',$empresa_id)->get();

        return view('perfilesExamenes.index', compact('sucursales','cat_examenes'));
    }




    public function get_perfiles(){
        $empresa_id = Auth::user()->empresa_id;
        $data_perfiles = DB::select("SELECT p.id as perfil_id,p.nombre as perfil,e.nombre as examen,e.categoria_id,e.id as examen_id FROM `perfil_examens` as p INNER JOIN det_perfil_examens as dp on p.id=dp.perfil_examen_id and p.empresa_id=dp.empresa_id INNER JOIN examenes as e on dp.examen_id=e.id and dp.empresa_id=e.empresa_id where p.empresa_id = ?",[$empresa_id]);

        //new array
        $perfil_examenes = [];
        foreach($data_perfiles as $item){
            $perfil = $item->perfil;

            if (!isset($perfil_examenes[$perfil])) {
                $perfil_examenes[$perfil] = [];
            }

            $perfil_examenes[$perfil][] = [
                'perfil_id' => $item->perfil_id,
                'categoria_id' => $item->categoria_id,
                'examen_id' => $item->examen_id,
                'examen' => $item->examen
            ];
        }
        $new_perfil_examenes = array_map(function($key,$value) {
            return [
                'perfil' => $key,
                'check_perfil' => false,
                'examenes' => $value
            ];
        }, array_keys($perfil_examenes),$perfil_examenes);

        return response()->json($new_perfil_examenes)->header('Content-Type','application/json');
    }

    //Save perfiles examenes
    public function save_examen_perfil(){
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $data_form = request()->all();
        $nombre_perfil = trim(strtoupper($data_form['nombre_perfil']));

        $data_form['examenes_perfil'] = json_decode($data_form['examenes_perfil']);
        //validacion para evitar duplicacion de examen
        $exists_perfil = PerfilExamen::where('nombre',$nombre_perfil)->where('empresa_id',$empresa_id)->exists();
        if($exists_perfil){
            return [
                'status' => 'exists',
                'message' => 'Este perfil ya está registrado. Por favor, elige uno diferente.'
            ];
        }
        DB::beginTransaction();
        try{
            $save_perfil = PerfilExamen::create([
                'fecha' => $date,
                'hora' => $hora,
                'nombre' => $nombre_perfil,
                'empresa_id' => $empresa_id,
                'usuario_id' => $usuario_id
            ]);
            if($save_perfil){
                foreach($data_form['examenes_perfil'] as $item){
                    DetPerfilExamen::create([
                        'examen_id' => $item->examen_id,
                        'perfil_examen_id' => $save_perfil->id,
                        'empresa_id' => $empresa_id
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha registrado exitosamente el perfil.'
            ]);
        }catch(Exception $err){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message_error' => $err->getMessage(),
                'message' => 'Ha ocurrido un error al momento de registrar el perfil.'
            ]);
        }
    }
    public function obtener_examenes($id_perfil) {
        $examenes = DetPerfilExamen::where('perfil_examen_id', $id_perfil)
            ->with('examen') // Asocia los datos de los exámenes
            ->get();
    
        return response()->json([
            'status' => 'success',
            'examenes' => $examenes
        ]);
    }
    


    public function listar_exEspeciales(){
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select("SELECT * FROM `pruebas_especiales` WHERE id_empresa = ? AND categoria = 'especialidades' ORDER BY id asc;",[$empresa_id]);
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();

            $sub_array[] = $row->id;
            $sub_array[] = $row->nombre;
            $sub_array[] = '<button data-emp_ref="'. $row->id .'" title="Editar"  
            class="btn btn-outline-primary btn-sm btn-o-EditExaes" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i>
            '.'<button data-emp_ref="'. $row->id .'" title="Eliminar"  
            class="btn btn-outline-danger btn-sm btn-o-delExaEs" style="border:none;font-size:18px"><i class="bi bi-x-square"></i>';
            $data[] = $sub_array;
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

    public function listar_exComplementarias(){
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select("SELECT * FROM `pruebas_especiales` WHERE id_empresa = ? AND categoria = 'complementarios' ORDER BY id asc;",[$empresa_id]);
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();

            $sub_array[] = $row->id;
            $sub_array[] = $row->nombre;
            $sub_array[] = '<button data-emp_ref="'. $row->id .'" title="Editar"  
            class="btn btn-outline-primary btn-sm btn-o-EditExa" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i>
            '.'<button data-emp_ref="'. $row->id .'" title="Eliminar"  
            class="btn btn-outline-danger btn-sm btn-o-delExa" style="border:none;font-size:18px"><i class="bi bi-x-square"></i>';
            $data[] = $sub_array;
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
    public function get_examen(Request $request){
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = $request->input('ref_emp');

        $data = PruebaEspecial::where('id',$ref_emp)->where('id_empresa',$empresa_id)->get();

        return response()
            ->json(($data))
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function update_examen(Request $request)
{
    $empresa_id = Auth::user()->empresa_id;  // Obtiene el id de la empresa actual
    $ref_emp = $request->input('id');  // El ID del examen que deseas actualizar
    $nuevo_nombre = $request->input('nombre');  // El nuevo nombre enviado desde SweetAlert

    // Busca el examen que pertenece a la empresa
    $examen = PruebaEspecial::where('id', $ref_emp)
                            ->where('id_empresa', $empresa_id)
                            ->first();

    if ($examen) {
        // Actualiza el campo nombre
        $examen->nombre = $nuevo_nombre;
        $examen->save();  // Guarda los cambios

        return response()->json([
            'status' => 'success',
            'message' => 'El nombre ha sido actualizado correctamente.'
        ], 200);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Examen no encontrado o no pertenece a la empresa.'
        ], 404);
    }
}


function destroy_examen(Request $request){
    $empresa_id = Auth::user()->empresa_id;
    $ref_emp = $request->input('examen');
    $resultado = PruebaEspecial::where('id',$ref_emp)->where('id_empresa',$empresa_id)->delete();

    if($resultado){
        return response()->json([
            'status' => 'success',
            'message' => 'Examen eliminado exitosamente.'
        ]);
    }
    return response()->json([
        'status' => 'error',
        'message' => 'Ha ocurrido un error al momento de eliminar el examen.'
        ])
        ->header('Content-Type', 'application/json')
        ->header('Cache-Control', 'max-age=86400');
}
//examenes especiales 
// Update perfiles examenes
public function update_examen_perfil() {
    date_default_timezone_set('America/El_Salvador');
    $empresa_id = Auth::user()->empresa_id;
    $usuario_id = Auth::user()->id;
    $date = date('Y-m-d');
    $hora = date('H:i:s');

    $data_form = request()->all();
    $perfil_id = $data_form['id_erfil']; // ID del perfil a actualizar
    $nombre_perfil = trim(strtoupper($data_form['nombre_perfiledit']));

    $data_form['examenes_perfil'] = json_decode($data_form['examenes_perfil']);

    // Validar si el nombre del perfil ya existe (excepto el actual)
    $exists_perfil = PerfilExamen::where('nombre', $nombre_perfil)
                                ->where('empresa_id', $empresa_id)
                                ->where('id', '!=', $perfil_id) // Excluir el perfil actual
                                ->exists();
    if ($exists_perfil) {
        return [
            'status' => 'exists',
            'message' => 'Este perfil ya está registrado. Por favor, elige uno diferente.'
        ];
    }

    DB::beginTransaction();
    try {
        // Actualizar el perfil
        $perfil = PerfilExamen::find($perfil_id);
        if ($perfil) {
            $perfil->update([
                'nombre' => $nombre_perfil,
                'fecha' => $date,
                'hora' => $hora,
                'usuario_id' => $usuario_id
            ]);

            // Borrar los exámenes anteriores asociados al perfil
            DetPerfilExamen::where('perfil_examen_id', $perfil_id)
                            ->where('empresa_id', $empresa_id)
                            ->delete();

            // Guardar los nuevos exámenes seleccionados
            foreach ($data_form['examenes_perfil'] as $item) {
                DetPerfilExamen::create([
                    'examen_id' => $item->examen_id,
                    'perfil_examen_id' => $perfil_id,
                    'empresa_id' => $empresa_id
                ]);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Perfil actualizado exitosamente.'
        ]);
    } catch (Exception $err) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message_error' => $err->getMessage(),
            'message' => 'Ha ocurrido un error al actualizar el perfil.'
        ]);
    }
}

}
