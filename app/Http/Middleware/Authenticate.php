<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Verifica si la solicitud proviene de Sanctum
        /* if ($request->routeIs('api.*')) {
            return $request->expectsJson()
                ? null
                : response()->json(["Mensaje"=>"No autorizado, No se proporcionó Token o es invalido"],401);
        } */
        
        return $request->expectsJson() ? null : route('login');
    }
}
