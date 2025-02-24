<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Lucipher;

class loginController extends Controller
{    
    public function showlogin(){
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        // Limpiar sesión anterior
        Session::flush();

        // Obtener credenciales del formulario
        $credentials = $request->only('usuario', 'pass');

        // Buscar usuario por 'usuario'
        $userAuth = Usuario::where('usuario', $credentials['usuario'])->first();

        // Verificar si se encontró el usuario y si la contraseña es correcta
        if ($userAuth && Hash::check($credentials['pass'], $userAuth->password)) {
            // Verificar si el usuario está habilitado
            if ($userAuth->estado == 1) {
                // Obtener la sucursal seleccionada
                $selectedSucursalId = $request->input('sucursal');

                if (!is_null($selectedSucursalId)) {
                    // Verificar si la sucursal existe y pertenece a la empresa del usuario
                    $empresaIdUsuario = $userAuth->empresa_id;
                    $sucursalExistente = Sucursal::where('id', $selectedSucursalId)
                        ->where('empresa_id', $empresaIdUsuario)
                        ->exists();
                    
                    if ($sucursalExistente) {
                        // Autenticar al usuario
                        Auth::login($userAuth);

                        // 1. Obtener los módulos asociados al usuario
                        $modulos = DB::table('acces_modulo')
                            ->join('modulos', 'acces_modulo.id_modulo', '=', 'modulos.id')
                            ->where('acces_modulo.id_usuario', $userAuth->id)
                            ->select('modulos.nombre as modulo_nombre')
                            ->get();

                        // Estructurar los módulos para guardarlos en la sesión
                        $userModules = [];
                        foreach ($modulos as $modulo) {
                            $userModules[] = Lucipher::Descipher($modulo->modulo_nombre); // Descifrar el nombre del módulo
                        }

                        // Guardar los módulos descifrados en la sesión
                        Session::put('userModules', $userModules);

                        // 2. Obtener los permisos asociados al usuario
                        $permisos = DB::table('acces_permisos')
                            ->join('permisos', 'acces_permisos.id_permiso', '=', 'permisos.id')
                            ->where('acces_permisos.id_usuario', $userAuth->id)
                            ->select('permisos.nombre as permiso_nombre')
                            ->get();

                        // Estructurar los permisos para guardarlos en la sesión
                        $userPermissions = [];
                        foreach ($permisos as $permiso) {
                            $userPermissions[] = Lucipher::Descipher($permiso->permiso_nombre); // Descifrar el nombre del permiso
                        }

                        // Guardar los permisos descifrados en la sesión
                        Session::put('userPermissions', $userPermissions);

                        return redirect()->route('app.home')->with('success', 'Bienvenido a tu plataforma');
                    } else {
                        return redirect()->back()->with('error', 'La sucursal seleccionada no existe en nuestros registros.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Debes seleccionar una sucursal.');
                }
            } else {
                return redirect()->back()->with('error', 'Usuario deshabilitado. Contacta al administrador.');
            }
        } else {
            return redirect()->back()->with('error', 'Usuario o contraseña incorrecta.');
        }
    }

    public function logout(){
        Auth::logout();
        Session::flush(); // Limpiar la sesión
        return redirect()->route('login');
    }

    public function getSucursalesByUsuario($usuario)
    {
        $sucursales = DB::select('SELECT s.id, s.nombre FROM usuarios AS u INNER JOIN sucursals AS s ON u.sucursal_id = s.id WHERE u.usuario = ? AND u.sucursal_id = s.id', [$usuario]);
        return response()->json($sucursales);
    }
}
