<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Lucipher;
use App\Models\AccesModulo;
use App\Models\AccesPermiso;
use App\Models\Modulo;
use App\Models\Permiso;

class UsuarioController extends Controller
{
    public function index(){
        $empresa_id = Auth::user()->empresa_id;
        $sucursales = DB::select('SELECT * from sucursals where empresa_id = ?',[$empresa_id]);
        $modulos = Modulo::with('permisos')->get();
        $modul = [];
        foreach($modulos as $item){
            $array = [];
            $array['id'] = $item['id'];
            $array['nombre'] = Lucipher::Descipher($item['nombre']);
            $array['permisos'] = $item['permisos']->map(function($permiso) {
                return [
                    'id' => $permiso['id'],
                    'nombre' => Lucipher::Descipher($permiso['nombre']),
                ];
            });
            $modul[] = $array;
        }
    
        return view('usuarios.index', compact('sucursales', 'modul'));
    }


    public function CrearUsuario(Request $request)
    {
        $fechaActual = Carbon::now('America/El_Salvador')->toDateString();
        $id_empresa = Auth::user()->empresa_id;
        $usuarioId = session('actualizarDuser');
        $actualizar = $request->input('actualizar');
    
        if ($actualizar) {
            $usuario = Usuario::find($usuarioId);
            if (!$usuario) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
            $usuario->nombre = $request->nombreUsuario ?: $usuario->nombre;
            $usuario->telefono = $request->telefonoUsuario ?: $usuario->telefono;
            $usuario->dui = $request->duiUsuario ?: $usuario->dui;
            $usuario->nit = $request->nitUsuario ?: $usuario->nit;
            $usuario->direccion = $request->direccionUsuario ?: $usuario->direccion;
            $usuario->sucursal_id = $request->SucursalUsuario ?: $usuario->sucursal_id;
            $usuario->cargo = $request->cargoUsuario ?: $usuario->cargo;
            $usuario->estado = $request->estadoUsuario ?: $usuario->estado;
            $usuario->usuario = $request->Usuario ?: $usuario->usuario;
            if ($request->Contraseña) {
                $usuario->password = Hash::make($request->Contraseña);
            }
            $usuario->save();
            AccesModulo::where('id_usuario', $usuario->id)->delete();
            AccesPermiso::where('id_usuario', $usuario->id)->delete();
        } else {
            $usuario = new Usuario();
            $usuario->nombre = $request->nombreUsuario;
            $usuario->telefono = $request->telefonoUsuario ?: '-';
            $usuario->dui = $request->duiUsuario ?: '-'; 
            $usuario->nit = $request->nitUsuario ?: '-'; 
            $usuario->direccion = $request->direccionUsuario ?: '-'; 
            $usuario->sucursal_id = $request->SucursalUsuario ?: '1'; 
            $usuario->cargo = $request->cargoUsuario;
            $usuario->estado = $request->estadoUsuario;
            $usuario->usuario = $request->Usuario;
            $usuario->password = Hash::make($request->Contraseña);
            $usuario->contr = "-";
            $usuario->empresa_id = $id_empresa; 
            $usuario->fecha_creacion = $fechaActual;
            $usuario->categoria = "1";
            $usuario->save();
        }
        $permisosModulos = json_decode($request->input('permisos_modulos'), true);
        foreach ($permisosModulos as $moduloId => $moduloData) {
            $accesModulo = new AccesModulo();
            $accesModulo->id_modulo = $moduloId;
            $accesModulo->id_usuario = $usuario->id;
            $accesModulo->id_empresa = $id_empresa;
            $accesModulo->save();
                foreach ($moduloData['permisos'] as $permisoId) {
                $accesPermiso = new AccesPermiso();
                $accesPermiso->id_modulo = $moduloId;
                $accesPermiso->id_permiso = $permisoId;
                $accesPermiso->id_usuario = $usuario->id;
                $accesPermiso->id_empresa = $id_empresa;
                $accesPermiso->save();
            }
        }
    
        return response()->json(['message' => 'Usuario y permisos guardados con éxito']);
    }
  
     public function UsuarioAll()
     {
         $categoria = Auth::user()->categoria;
         $id_empresa = Auth::user()->empresa_id;     
             $usuarios = DB::select(
                 "SELECT usuarios.*, sucursals.nombre as sucursal_nombre 
                  FROM usuarios 
                  INNER JOIN sucursals ON usuarios.sucursal_id = sucursals.id 
                  WHERE usuarios.empresa_id = ? AND usuarios.estado = 1", 
                  [$id_empresa]
             );
         $data = array();
         foreach ($usuarios as $user) {
         $sub_array = array();
         $sub_array[] = $user->id;
             $sub_array[] = $user->nombre;
             $sub_array[] = $user->usuario;
             $sub_array[] = $user->estado == 1 ? 'Activo' : 'Desactivado';
             $sub_array[] = $user->cargo;
             $sub_array[] = $user->sucursal_nombre; 
             $sub_array[] = '<button data-emp_ref="'. $user->id .'" title="Ver detalles" class="btn btn-outline-secondary 
             btn-sm btn-o-user" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>'.'<button data-emp_ref="'. $user->id .'" title="Ver detalles" class="btn btn-outline-primary 
             btn-sm btn-o-pass" style="border:none;font-size:18px"><i class="bi bi-key"></i></button>';
             $data[] = $sub_array;
         }
         $results = array(
             "sEcho" => 1, 
             "iTotalRecords" => count($data), 
             "iTotalDisplayRecords" => count($data), 
             "aaData" => $data
         );
 
         echo json_encode($results);
     }
     public function get_usuario_by_id(Request $request) {
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = $request->input('ref_emp');
    
        // Obtener los datos del usuario
        $usuario = Usuario::where('id', $ref_emp)
                          ->where('empresa_id', $empresa_id)
                          ->first();
    
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        // Preparar los datos del usuario
        $arrayData = [
            'cargo' => $usuario->cargo,
            'categoria' => $usuario->categoria,
            'departamento' => $usuario->departamento,
            'direccion' => $usuario->direccion,
            'dui' => $usuario->dui,
            'correo' => $usuario->correo,
            'estado' => $usuario->estado,
            'nit' => $usuario->nit,
            'telefono' => $usuario->telefono,
            'nombre' => $usuario->nombre,
            'sucursal_id' => $usuario->sucursal_id,
            'usuario' => $usuario->usuario,
            'id' => $usuario->id,
        ];
    
        session()->forget('actualizarDuser');
        session()->put('actualizarDuser', $usuario->id);
    
        // Obtener módulos y permisos
        $modulosPermisos = DB::table('acces_modulo')
            ->where('id_usuario', $ref_emp)
            ->where('id_empresa', $empresa_id)
            ->get();
    
        $modulosData = [];
        foreach ($modulosPermisos as $modulo) {
            $permisos = DB::table('acces_permisos')
                ->where('id_modulo', $modulo->id_modulo)
                ->where('id_usuario', $ref_emp)
                ->pluck('id_permiso');
    
            $modulosData[] = [
                'modulo_id' => $modulo->id_modulo,
                'permisos' => $permisos,
            ];
        }
    
        $arrayData['modulos_permisos'] = $modulosData;
    
        return response()
            ->json($arrayData)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    
    
    
    

    public function save_modulo(){
        $empresa_id = Auth::user()->empresa_id;
        $nombreModulo = strtoupper(trim(request()->input('nombreModulo')));
        $descripcionModulo = strtoupper(trim(request()->input('descripcionModulo')));
        $exists_Modulo = Modulo::where('nombre',$nombreModulo)->exists();
        if($exists_Modulo){
            return [
                'status' => 'warning',
                'message' => 'Este nombre ya está registrado. Por favor, elige otro.',
            ];
        }
        $saveResult = Modulo::create([
            'nombre' => Lucipher::Cipher($nombreModulo),
            'descripcion' => $descripcionModulo
        ]);
        if($saveResult){
            $saveResult['nombre'] = Lucipher::Descipher($saveResult['nombre']);
            return [
                'status' => 'success',
                'message' => 'Modulo creado exitosamente.',
                'results' => $saveResult
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de crear el modulo.',
            'results' => []
        ];
    }

    
    public function modulosAll(){
        $datos = DB::select("SELECT * FROM `modulos`");
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row->id;
            $sub_array[] = Lucipher::Descipher($row->nombre);
            $sub_array[] = $row->descripcion;
            $sub_array[] = '<button data-ref="'. ($row->id) .'" title="Agregar permisos" class="btn btn-outline-info btn-sm btn-verModulo" style="border:none;font-size:18px"><i class="bi bi-node-plus-fill"></i></button>';
            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1, 
            "iTotalRecords" => count($data), 
            "iTotalDisplayRecords" => count($data), 
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function permisosAll(Request $request){
    $id_modulo = $request->input('permiso_id');
    $datos = DB::select("SELECT * FROM `permisos` WHERE id_modulo = ?", [$id_modulo]);
    
    $data = [];
    foreach ($datos as $row) {
        $sub_array = array();
        $sub_array[] = $row->id;
        $sub_array[] = Lucipher::Descipher($row->nombre);
        $sub_array[] = $row->descripcion;
        $sub_array[] = '<button data-ref="'. ($row->id) .'" title="Agregar permisos" class="btn btn-outline-info btn-sm btn-verModulo" style="border:none;font-size:18px"><i class="bi bi-node-plus-fill"></i></button>';
        $data[] = $sub_array;
    }
    $results = array(
        "sEcho" => 1, 
        "iTotalRecords" => count($data), 
        "iTotalDisplayRecords" => count($data), 
        "aaData" => $data
    );
    return response()
        ->json($results)
        ->header('Content-Type', 'application/json')
        ->header('Cache-Control', 'max-age=86400');
}


public function save_permiso(){
    $empresa_id = Auth::user()->empresa_id;
    $nombrePermiso = strtoupper(trim(request()->input('nombrePermiso')));
    $descripcionPermiso = strtoupper(trim(request()->input('descripcionPermiso')));
    $id_modulo = strtoupper(trim(request()->input('id_modulo')));
    $saveResult = Permiso::create([
        'nombre' => Lucipher::Cipher($nombrePermiso),
        'descripcion' => $descripcionPermiso,
        'id_modulo' => $id_modulo
    ]);

    if($saveResult){
        $saveResult['nombre'] = Lucipher::Descipher($saveResult['nombre']);
        return [
            'status' => 'success',
            'message' => 'Modulo creado exitosamente.',
            'results' => $saveResult
        ];
    }
    return [
        'status' => 'error',
        'message' => 'Ha ocurrido un error al momento de crear el modulo.',
        'results' => []
    ];
}
public function CambiarPassword(Request $request)
{  
    $empresa_id = Auth::user()->empresa_id;   
    $usuario_id = $request->input('id_user');
    $new_password = $request->input('password');

    $usuario = usuario::where('empresa_id', $empresa_id)
                    ->where('id', $usuario_id)
                    ->first();
    if ($usuario) {
        $usuario->password = Hash::make($new_password);
        $usuario->save();
        return response()->json(['mensaje' => 'Contraseña actualizada correctamente']);
    } else {
        return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
    }
}

}
