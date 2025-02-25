<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\DetPerfilExamen;
use App\Models\ExamenesCategoria;
use App\Models\PerfilExamen;
use App\Models\PruebaEspecial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ExamenesCategoriaController extends Controller
{
    public function get_examenes(){
        $empresa_id = Auth::user()->empresa_id;
        $examenes = DB::select('SELECT c.id as categoria_id,c.nombre as categoria,e.id as examen_id,e.nombre as examen FROM `categoria_examens` AS c inner join examenes as e on c.id=e.categoria_id and c.empresa_id=e.empresa_id where e.empresa_id = ?',[$empresa_id]);
        //new array
        $cat_examenes = [];
        foreach($examenes as $item){
            $categoria = $item->categoria;

            if (!isset($cat_examenes[$categoria])) {
                $cat_examenes[$categoria] = [];
            }

            $cat_examenes[$categoria][] = [
                'categoria_id' => $item->categoria_id,
                'categoria' => $categoria,
                'examen_id' => $item->examen_id,
                'examen' => $item->examen,
                'check_examen' => false
            ];
        }
        $new_cat_examenes = array_map(function($key,$value) {
            return [
                'categoria' => $key,
                'examenes' => $value
            ];
        }, array_keys($cat_examenes),$cat_examenes);

        return response()->json($new_cat_examenes);
    }

    public function save_examen(){
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $form_data = request()->validate([
            'examen' => 'required|string|min:1|max:150',
            'categoria' => 'required|string|min:1'
        ]);

        $examen = trim(strtoupper($form_data['examen']));

        //validacion para evitar duplicacion de examen
        $exists_examen = ExamenesCategoria::where('nombre',$examen)->where('empresa_id',$empresa_id)->exists();
        if($exists_examen){
            return [
                'status' => 'exists',
                'message' => 'Este examen ya está registrado. Por favor, elige uno diferente.'
            ];
        }
        $save_examen = ExamenesCategoria::create([
            'nombre' => $examen,
            'descripcion' => '',
            'categoria_id' => $form_data['categoria'],
            'empresa_id' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if($save_examen){
            return response()->json([
                'status' => 'success',
                'message' => 'Examen registrado exitosamente.',
                'results' => $save_examen
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar el examen.'
        ]);
    }

    public function save_examenesespeciales(){
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $form_data = request()->validate([
            'examenEspecialidades' => 'required|string|min:1|max:150',
        ]);

        $examen = trim(strtoupper($form_data['examenEspecialidades']));

        //validacion para evitar duplicacion de examen
        $exists_examen = PruebaEspecial::where('nombre',$examen)->where('id_empresa',$empresa_id)->exists();
        if($exists_examen){
            return [
                'status' => 'exists',
                'message' => 'Este examen ya está registrado. Por favor, elige uno diferente.'
            ];
        }
        $save_examen = PruebaEspecial::create([
            'nombre' => $examen,
            'descripcion' => '',
            'categoria' => 'especialidades',
            'id_empresa' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if($save_examen){
            return response()->json([
                'status' => 'success',
                'message' => 'Examen registrado exitosamente.',
                'results' => $save_examen
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar el examen.'
        ]);
    }

    public function save_examenescomplementarios(){
        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $form_data = request()->validate([
            'examencomplementarios' => 'required|string|min:1|max:150',
        ]);

        $examen = trim(strtoupper($form_data['examencomplementarios']));

        //validacion para evitar duplicacion de examen
        $exists_examen = PruebaEspecial::where('nombre',$examen)->where('id_empresa',$empresa_id)->exists();
        if($exists_examen){
            return [
                'status' => 'exists',
                'message' => 'Este examen ya está registrado. Por favor, elige uno diferente.'
            ];
        }
        $save_examen = PruebaEspecial::create([
            'nombre' => $examen,
            'descripcion' => '',
            'categoria' => 'complementarios',
            'id_empresa' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if($save_examen){
            return response()->json([
                'status' => 'success',
                'message' => 'Examen registrado exitosamente.',
                'results' => $save_examen
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar el examen.'
        ]);
    }

    
    
    public function get_examenes_perfil_edit($nombre_perfil) {
        // Buscar el perfil en la tabla perfil_examens donde el nombre sea igual al nombre proporcionado
        $perfil = DB::select("SELECT * FROM perfil_examens WHERE nombre = ?", [$nombre_perfil]);
    
        // Verificar si se encontró el perfil
        if (empty($perfil)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perfil no encontrado'
            ], 404);
        }
    
        // Obtener el id del perfil
        $perfil_id = $perfil[0]->id;
    
        // Buscar los exámenes en la tabla det_perfil_examens 
        // y obtener también el nombre del examen y el nombre de la categoría
        $examenes = DB::select("
            SELECT dpe.*, e.nombre AS examen, ce.nombre AS categoria , ce.id AS categoria_id
            FROM det_perfil_examens dpe
            JOIN examenes e ON dpe.examen_id = e.id
            JOIN categoria_examens ce ON e.categoria_id = ce.id
            WHERE dpe.perfil_examen_id = ?", [$perfil_id]
        );
    
        // Retornar los datos del perfil y los exámenes obtenidos
        return response()->json([
            'status' => 'success',
            'perfil' => $perfil[0], // Se envía el primer (y único) perfil encontrado
            'examenes' => $examenes
        ]);
    }
    


}
