<?php

namespace App\Http\Controllers;

use App\Models\CategoriaExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoriaExamenController extends Controller
{
    public function save_categoria()
    {

        $empresa_id = Auth::user()->empresa_id;
        $usuario_id = Auth::user()->id;
        $form_data = request()->validate([
            'categoria' => 'required|string|min:1|max:150'
        ]);
        $categoria_examen = trim(strtoupper($form_data['categoria']));
        //validacion para evitar duplicacion de examen
        $exists_cat = CategoriaExamen::where('nombre',$categoria_examen)->where('empresa_id', $empresa_id)->exists();
        if ($exists_cat) {
            return [
                'status' => 'exists',
                'message' => 'Esta categoria ya está registrado. Por favor, elige uno diferente.'
            ];
        }
        $save_cat = CategoriaExamen::create([
            'nombre' => $categoria_examen,
            'descripcion' => '',
            'empresa_id' => $empresa_id,
            'usuario_id' => $usuario_id
        ]);
        if ($save_cat) {
            return response()->json([
                'status' => 'success',
                'message' => 'La categoria se ha registrado exitosamente.',
                'results' => $save_cat
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar la categoria.'
        ]);
    }

    public function dt_cat_examenes(){
        $empresa_id = Auth::user()->empresa_id;
        //Data para mostrar en modal de perfil
        $categorias_examenes = DB::select("SELECT c.id as categoria_id,e.id as examen_id,c.nombre as categoria,e.nombre as examen FROM `categoria_examens` as c inner join examenes as e on c.id=e.categoria_id and c.empresa_id=e.empresa_id where c.empresa_id = ? order by e.id desc;",[$empresa_id]);

        $contador = 1;
        $data = [];
        foreach ($categorias_examenes as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row->categoria;
            $sub_array[] = $row->examen;
            $sub_array[] = '<div title="Seleccionar examen" class="checkbox icheck-success mt-0">
                                <input class="check_examen_perfil" onclick="selectedExamenPerfil(this)" type="checkbox" data-categoria="'.$row->categoria.'" data-examen="'.$row->examen.'" data-cat_id="'.$row->categoria_id.'" data-examen_id="'.$row->examen_id.'" id="'.$contador.'-cont" />
                                <label for="'.$contador.'-cont" style="font-size: 13px"></label>
                            </div>';

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


    public function edit_cat_examenes(){
        $empresa_id = Auth::user()->empresa_id;
        //Data para mostrar en modal de perfil
        $categorias_examenes = DB::select("SELECT c.id as categoria_id,e.id as examen_id,c.nombre as categoria,e.nombre as examen FROM `categoria_examens` as c inner join examenes as e on c.id=e.categoria_id and c.empresa_id=e.empresa_id where c.empresa_id = ? order by e.id desc;",[$empresa_id]);

        $contador = 1;
        $data = [];
        foreach ($categorias_examenes as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row->categoria;
            $sub_array[] = $row->examen;
            $sub_array[] = '<div title="Seleccionar examen" class="checkbox icheck-success mt-0">
                                <input class="check_examen_perfil" onclick="selectedExamenPerfilEdit(this)" type="checkbox" data-categoria="'.$row->categoria.'" data-examen="'.$row->examen.'" data-cat_id="'.$row->categoria_id.'" data-examen_id="'.$row->examen_id.'" id="'.$contador.'-cont" />
                                <label for="'.$contador.'-cont" style="font-size: 13px"></label>
                            </div>';

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
