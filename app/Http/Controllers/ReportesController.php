<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Lucipher;
use App\Models\Jornada;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportesController extends Controller
{
    protected $url_base = null;
    protected $token = null;
    public function __construct()
    {
      //  $this->url_base = "http://127.0.0.1:8001/api/empresas/obtDate"; //localhost
      //  $this->token = "1|sh819zxIQcYpye3HAXdzrDLqX1p44AAehkkklkOZ";//token de tuopticasv.com //localhost

        $this->url_base = "https://tuopticasv.com/api/empresas/obtDate"; //production
        $this->token = "1|sh819zxIQcYpye3HAXdzrDLqX1p44AAehkkklkOZ";//token de tuopticasv.com //localhost
    }

    public function index(){
        
        $empresa_id = Auth::user()->empresa_id;
        $jornadas = Jornada::where('empresa_id', $empresa_id)->get();
        $agencias = Sucursal::where('empresa_id', $empresa_id)->get();

        return view('reportes.index', compact('jornadas','agencias'));
    }
    
    public function indexVisual(){
        
        $empresa_id = Auth::user()->empresa_id;
        $jornadas = Jornada::where('empresa_id', $empresa_id)->get();
        $agencias = Sucursal::where('empresa_id', $empresa_id)->get();

        return view('reportesVisual.index', compact('jornadas','agencias'));
    }

    public function obtenerDatosGrafica(Request $request)
    {
        $idJornada = $request->input('id_jornada');
        //$idJornada = 1;
        // Ejecutamos la consulta filtrando por id_jornada
        $datos = DB::select('select cat_examenes from jornadas where id = ?', [$idJornada]);
        
        if ($datos[0]->cat_examenes === 'laboratorio clinico'){
            $datos = DB::select("SELECT 
            CASE 
                WHEN dj.evaluacion = 'Normal' THEN 'Normal'
                WHEN dj.evaluacion IN ('Alterado', 'Malo') THEN 'Alterado'
            END AS categoria, 
            COUNT(*) AS total
        FROM det_jornadas AS dj
        INNER JOIN jornadas AS j ON dj.jornada_id = j.id
        WHERE dj.jornada_id = ?
        AND dj.evaluacion != '-'
        GROUP BY categoria", [$idJornada]);
        }else{
                $datos = DB::select("SELECT 
                CASE 
                    WHEN dj.evaluacion = 'Normal' THEN 'Normal'
                    WHEN dj.evaluacion IN ('Alterado', 'Malo') THEN 'Alterado'
                END AS categoria, 
                COUNT(*) AS total
                FROM det_jornadas AS dj
                INNER JOIN jornadas AS j ON dj.jornada_id = j.id
                WHERE dj.jornada_id = ?
                AND dj.evaluacion != '-'
                GROUP BY categoria", [$idJornada]);
        }
        return response()->json($datos);
    }
/**
 * Store a newly created resource in storage.
 */
public function obtenerDatosGraficaVisual(Request $request)
{
    try {
        $empresa_id = Auth::user()->empresa_id;

        $datos = DB::select('SELECT cod_clinica FROM empresas WHERE id = ?', [$empresa_id]);
        if (empty($datos)) {
            return response()->json(['message' => 'Código de clínica no encontrado'], 404);
        }

        $cod_clinica = $datos[0]->cod_clinica;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $planta  = $request->input('planta');

        \Log::info('Datos enviados al segundo sistema:', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
            ])->get('https://tuopticasv.com/api/empresas/obtDate', [
       // ])->get('http://127.0.0.1:8001/api/empresas/obtDate', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
        ]);

        if ($response->successful()) {
            $datos = $response->json();

            // Obtén todos los sucursales una vez para evitar múltiples consultas
            $sucursales = DB::table('sucursals')
                ->whereIn('id', array_column($datos, 'sucursal'))
                ->pluck('nombre', 'id');

            // Sustituye sucursal_pac_id por su nombre
            $datosConNombre = array_map(function ($item) use ($sucursales) {
                $item['sucursal'] = $sucursales[$item['sucursal']] ?? 'Sucursal no encontrada';
                return $item;
            }, $datos);

            return response()->json($datosConNombre, 200);
        }

        return response()->json(['message' => 'Error al obtener empresas'], 500);
    } catch (\Exception $e) {
        \Log::error('Error en obtenerDatosGraficaVisual: ' . $e->getMessage());
        return response()->json(['message' => 'Error en la conexión'], 500);
    }
}
public function obtenerDetallesRes(Request $request)
{
    try {
        $empresa_id = Auth::user()->empresa_id;

        $datos = DB::select('SELECT cod_clinica FROM empresas WHERE id = ?', [$empresa_id]);
        if (empty($datos)) {
            return response()->json(['message' => 'Código de clínica no encontrado'], 404);
        }

        $cod_clinica = $datos[0]->cod_clinica;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $planta  = $request->input('planta');
        $evaluacion = $request->input('evaluacion');

        \Log::info('Datos enviados al segundo sistema:', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'evaluacion' => $evaluacion,
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
       ])->get('https://tuopticasv.com/api/datos/resumen', [
      //  ])->get('http://127.0.0.1:8001/api/datos/resumen', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'evaluacion' => $evaluacion,
        ]);

        if ($response->successful()) {
            $datos = $response->json();

            return response()->json($datos, 200);
        }

        return response()->json(['message' => 'Error al obtener empresas'], 500);
    } catch (\Exception $e) {
        \Log::error('Error en obtenerDatosGraficaVisual: ' . $e->getMessage());
        return response()->json(['message' => 'Error en la conexión'], 500);
    }
}


    public function obtenerDatosTabla(Request $request)
    {
        $idJornada = $request->input('id_jornada');
        $cat_ex = DB::select('select cat_examenes from jornadas where id = ?', [$idJornada]);
        $cat = $cat_ex[0]->cat_examenes;

        if($cat == 'laboratorio clinico'){
                $normales = DB::select("SELECT 
                dj.examen_id AS id_ex,
                ex.nombre AS nombre_examen,
                COUNT(*) AS total_normales,
                GROUP_CONCAT(dj.empleado_id) AS normales_ids
            FROM det_jornadas AS dj
            INNER JOIN examenes AS ex ON ex.id = dj.examen_id
            WHERE dj.jornada_id = ?
              AND dj.cat_examen = ?
              AND dj.evaluacion = 'Normal'
            GROUP BY dj.examen_id, ex.nombre
            ORDER BY total_normales DESC
            ", [$idJornada,$cat]);
            
            // Consulta para los exámenes alterados
            $alterados = DB::select("SELECT 
                dj.examen_id AS id_ex,
                ex.nombre AS nombre_examen,
                COUNT(*) AS total_alterados,
                GROUP_CONCAT(dj.empleado_id) AS alterados_ids
            FROM det_jornadas AS dj
            INNER JOIN examenes AS ex ON ex.id = dj.examen_id
            WHERE dj.jornada_id = ?
              AND dj.cat_examen = 'laboratorio clinico'
              AND dj.evaluacion IN ('Alterado', 'Malo')
            GROUP BY dj.examen_id, ex.nombre
            ORDER BY total_alterados DESC
            ", [$idJornada]);
            
            // Formateo de los resultados
            $resultados = [
            'normales' => array_map(function($item) {
                return [
                    'id_ex' => $item->id_ex,
                    'examen' => $item->nombre_examen,
                    'cantidad' => $item->total_normales,
                    'ids' => $item->normales_ids
                ];
            }, $normales),
            'alterados' => array_map(function($item) {
                return [
                    'id_ex' => $item->id_ex,
                    'examen' => $item->nombre_examen,
                    'cantidad' => $item->total_alterados,
                    'ids' => $item->alterados_ids
                ];
            }, $alterados)
            ];
            
            return response()->json($resultados); 
        }else{
               // Consulta para los exámenes normales
                $normales = DB::select("SELECT 
                dj.examen_id AS id_ex,
                ex.nombre AS nombre_examen,
                COUNT(*) AS total_normales,
                GROUP_CONCAT(dj.empleado_id) AS normales_ids
                FROM det_jornadas AS dj
                INNER JOIN pruebas_especiales AS ex ON ex.id = dj.examen_id
                WHERE dj.jornada_id = ?
                AND dj.cat_examen = 'complementarios'
                AND dj.evaluacion = 'Normal'
                GROUP BY dj.examen_id, ex.nombre
                ORDER BY total_normales DESC
            ", [$idJornada]);

            // Consulta para los exámenes alterados
            $alterados = DB::select("SELECT 
                    dj.examen_id AS id_ex,
                    ex.nombre AS nombre_examen,
                    COUNT(*) AS total_alterados,
                    GROUP_CONCAT(dj.empleado_id) AS alterados_ids
                FROM det_jornadas AS dj
                INNER JOIN pruebas_especiales AS ex ON ex.id = dj.examen_id
                WHERE dj.jornada_id = ?
                AND dj.cat_examen = 'complementarios'
                AND dj.evaluacion IN ('Alterado', 'Malo')
                GROUP BY dj.examen_id, ex.nombre
                ORDER BY total_alterados DESC
            ", [$idJornada]);

            // Formateo de los resultados
            $resultados = [
                'normales' => array_map(function($item) {
                    return [
                        'id_ex' => $item->id_ex,
                        'examen' => $item->nombre_examen,
                        'cantidad' => $item->total_normales,
                        'ids' => $item->normales_ids
                    ];
                }, $normales),
                'alterados' => array_map(function($item) {
                    return [
                        'id_ex' => $item->id_ex,
                        'examen' => $item->nombre_examen,
                        'cantidad' => $item->total_alterados,
                        'ids' => $item->alterados_ids
                    ];
                }, $alterados)
            ];

            return response()->json($resultados);       
        }

    }
    
    
    public function obtenerDatosEmpleados(Request $request)
    {
        $empleadosIds = $request->input('empleadosIds');
        $ex = $request->input('examen_id');
        $jornada_id = $request->input('id_jornada');

        $empleados = DB::table('empleados')->whereIn('id', $empleadosIds)->get();
    
        // Procesar y descifrar la información de los empleados
        $empleadosDescifrados = $empleados->map(function ($empleado) use ($jornada_id, $ex)  {
            $resultadosExamenes = [];
    
            // Obtener cargo desde la tabla cargo_emps  Obtener área desde la tabla area_departamento_emps y descifrarlo
            $cargo = DB::table('cargo_emps')->where('id', $empleado->cargo_id)->value('nombre');
            $areaDescifrada = DB::table('area_emps')->where('id', $empleado->area_depto_id)->value('nombre');
    
            // Buscar en det_jornadas con el jornada_id y empleado_id
            $detallesJornada = DB::table('det_jornadas')->where('jornada_id', $jornada_id)->where('empleado_id', $empleado->id)->where('examen_id', $ex)->get();
            // Iterar sobre los detalles de la jornada
            foreach ($detallesJornada as $detalle) {
                $examen_id = $detalle->examen_id;
                $evaluacion = $detalle->evaluacion;
                $cat_exa = $detalle->cat_examen;

                 if ($cat_exa == "laboratorio clinico"){
                                    // Buscar en la tabla examenes usando el examen_id
                                    $examen = DB::table('examenes')->where('id', $examen_id)->first();
                                    if ($examen) {
                                        $name_table = $examen->name_tabla;
                                        $resultado = DB::table($name_table)
                                            ->where('jornada_id', $jornada_id)
                                            ->where('empleado_id', $empleado->id)
                                            ->first();
                                        if ($resultado) {
                                            $resultadosExamenes[] = [
                                                'examen' => $examen->nombre,
                                                'resultado' => $resultado,
                                                'evaluacion' => $evaluacion,
                                            ];
                                        }
                                    }
                 } else if ($cat_exa == "complementarios"){
                    $examenEspecial = DB::table('pruebas_especiales')->where('id', $examen_id)->first();
                    if ($examenEspecial) {
                        $name_table = $examenEspecial->name_tabla;
                        
                        if($name_table == 'result_opto_consulta' ){

                            $resultado = DB::table('result_opto_consulta as roc')
                            ->join('result_opto_rx as ror', function($join) {
                                $join->on('ror.empleado_id', '=', 'roc.empleado_id')
                                     ->on('ror.jornada_id', '=', 'roc.jornada_id');
                            })
                            ->join('result_opto_altura as roa', function($join) {
                                $join->on('roa.empleado_id', '=', 'roc.empleado_id')
                                     ->on('roa.jornada_id', '=', 'roc.jornada_id');
                            })
                            ->where('roc.jornada_id', $jornada_id)
                            ->where('roc.empleado_id', $empleado->id)
                            ->select('roc.*', 'ror.*', 'roa.*')
                            ->first();
                        if ($resultado) {
                            $resultadosExamenes[] = [
                                'examen' => $examenEspecial->nombre,
                                'resultado' => $resultado,
                                'evaluacion' => $evaluacion,
                            ];
                        }
                        }else{
                            $resultado = DB::table($name_table)
                            ->where('jornada_id', $jornada_id)
                            ->where('empleado_id', $empleado->id)
                            ->first();

                        if ($resultado) {
                            $resultadosExamenes[] = [
                                'examen' => $examenEspecial->nombre,
                                'resultado' => $resultado,
                                'evaluacion' => $evaluacion,
                            ];
                        }
                        }


                    }
                 } else{

                    $resultadosExamenes[] = [
                        'examen' => "ccc",
                        'resultado' => "ccccc",
                        'evaluacion' => "cccc",
                    ];

                 }
             
            }
    
            // Devolver la información del empleado junto con sus resultados de exámenes y detalles adicionales
            return [
                'id' => $empleado->id,
                'codigo' => $empleado->codigo_empleado,
                'nombre' => Lucipher::Descipher($empleado->nombre),
                'telefono' => $empleado->telefono,
                'categoria' => $empleado->categoria,
                'cargo' => $cargo,
                'area' => $areaDescifrada,
                'resultados_examenes' => $resultadosExamenes
            ];
        });
    
        // Devolver los datos descifrados y resultados de exámenes en formato JSON
        return response()->json($empleadosDescifrados);
    }

    
    public function obtenerDatosAnalisisRes(Request $request)
{
    try {
        $empresa_id = Auth::user()->empresa_id;

        $datos = DB::select('SELECT cod_clinica FROM empresas WHERE id = ?', [$empresa_id]);
        if (empty($datos)) {
            return response()->json(['message' => 'Código de clínica no encontrado'], 404);
        }

        $cod_clinica = $datos[0]->cod_clinica;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $planta  = $request->input('planta');
        $etario = $request->input('etario');
        $genero = $request->input('genero');

        \Log::info('Datos enviados al segundo sistema:', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'etario' => $etario,
            'genero' => $genero,
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
            ])->get('https://tuopticasv.com/api/empresas/obtAnalisisResultados', [
       // ])->get('http://127.0.0.1:8001/api/empresas/obtAnalisisResultados', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'etario' => $etario,
            'genero' => $genero,
        ]);

        if ($response->successful()) {
            $datos = $response->json();

            // Obtén todos los sucursales una vez para evitar múltiples consultas
            $sucursales = DB::table('sucursals')
                ->whereIn('id', array_column($datos, 'sucursal'))
                ->pluck('nombre', 'id');

            // Sustituye sucursal_pac_id por su nombre
            $datosConNombre = array_map(function ($item) use ($sucursales) {
                $item['sucursal'] = $sucursales[$item['sucursal']] ?? 'Sucursal no encontrada';
                return $item;
            }, $datos);

            return response()->json($datosConNombre, 200);
        }

        return response()->json(['message' => 'Error al obtener empresas'], 500);
    } catch (\Exception $e) {
        \Log::error('Error en obtenerDatosGraficaVisual: ' . $e->getMessage());
        return response()->json(['message' => 'Error en la conexión'], 500);
    }
}
    
public function obtenerDetallesAnalisis(Request $request)
{
    try {
        $empresa_id = Auth::user()->empresa_id;
        $datos = DB::select('SELECT cod_clinica FROM empresas WHERE id = ?', [$empresa_id]);
        if (empty($datos)) {
            return response()->json(['message' => 'Código de clínica no encontrado'], 404);
        }

        $cod_clinica = $datos[0]->cod_clinica;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $planta  = $request->input('planta');
        $evaluacion = $request->input('evaluacion');
        $etario = $request->input('etario');
        $genero = $request->input('genero');

        \Log::info('Datos enviados al segundo sistema:', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'evaluacion' => $evaluacion,
            'etario' => $etario,
            'genero' => $genero,
        ]); 

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
         ])->get('https://tuopticasv.com/api/datos/resumen/Analisis', [
       // ])->get('http://127.0.0.1:8001/api/datos/resumen/Analisis', [
            'cod_clinica' => $cod_clinica,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'planta' => $planta,
            'evaluacion' => $evaluacion,
            'etario' => $etario,
            'genero' => $genero,
        ]);

        if ($response->successful()) {
            $datos = $response->json();

            return response()->json($datos, 200);
        }

        return response()->json(['message' => 'Error al obtener empresas'], 500);
    } catch (\Exception $e) {
        \Log::error('Error en obtenerDatosGraficaVisual: ' . $e->getMessage());
        return response()->json(['message' => 'Error en la conexión'], 500);
    }
}

}