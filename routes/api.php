<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\JornadasController;
use App\Http\Controllers\api\SucursalEmpresaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * ROUTAS PARA OBTENER TOKEN DE API
*/
Route::post('/login',[AuthController::class,'login']);
/* 
ROUTE API - JORNADAS
*/
Route::prefix('jornadas')->middleware('auth:sanctum')->group(function(){
    Route::get('/{codigo_clinica?}',[JornadasController::class,'getJornadasAll']);
});

Route::prefix('empresa')->middleware('auth:sanctum')->group(function(){
    Route::post('/obtener-sucursales',[SucursalEmpresaController::class,'getSucursalesEmpresa']);
});