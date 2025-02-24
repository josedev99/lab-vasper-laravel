<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        // Obtener credenciales del formulario
        $credentials = request()->only('user', 'passwd');

        // Buscar usuario por 'usuario'
        $user = Usuario::where('usuario', $credentials['user'])->first();
        
        if ($user && Hash::check($credentials['passwd'], $user->password)) {
            // Verificar si el usuario está habilitado
            if ($user->estado == 1) {

                //Comprobar si no existe token
                if ($user->api_token == "") {
                    $token = $user->createToken('appEmpSystem2024')->plainTextToken;
                    $user->api_token = $token;
                    $user->save();
                } else {
                    $token = $user->api_token;
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Usuario autenticado exitosamente.',
                    'data' => [
                        "nombre" => $user->nombre,
                        "token_type" => "Bearer",
                        "access_token" => "Bearer " . $token
                    ]
                ]);
            } else {
                return response()->json([
                    'error' => 'Unauthorised'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'Error credenciales'
            ]);
        }
    }
}
