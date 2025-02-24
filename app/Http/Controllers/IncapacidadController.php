<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\AreaDepartamentoEmp;
use App\Models\Incapacidad;
use App\Models\MotivoIncapacidad;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class IncapacidadController extends Controller
{
    // Display a listing of incapacidades
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;

        $deptos = AreaDepartamentoEmp::where('empresa_id', $empresa_id)->get();
        $areas_depto = [];
        foreach ($deptos as $item) {
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $sucursales = Sucursal::where('empresa_id', $empresa_id)->get();

        $mot = MotivoIncapacidad::where('empresa_id', $empresa_id)->get();
        $motivos = [];
        foreach ($mot as $item) {
            $array = [];
            $array['id'] = $item['id'];
            $array['motivo'] = Lucipher::Descipher($item['motivo']);
            $motivos[] = $array;
        }
        return view('Incapacidades.index', compact('sucursales', 'areas_depto', 'motivos'));
    }

    public function verificarEmpleado(Request $request)
    {
        $id_empresa = Auth::user()->empresa_id;
        $codigo_empleado = $request->codigo_empleado;

        // Realizar la consulta
        $colaborador1 = DB::select('SELECT * from empleados where empresa_id = ? AND codigo_empleado = ?', [$id_empresa, $codigo_empleado]);
        if ($request->session()->has('colaborador_update')) {
            $request->session()->forget('colaborador_update');
        }

        if (empty($colaborador1)) {
            // Si no se encuentra el empleado, destruir cualquier sesión previa si existe
            $request->session()->forget('colaborador_id');
            return response()->json(['success' => false, 'message' => 'Empleado no encontrado'], 404);
        }

        $colaborador = [];
        foreach ($colaborador1 as $item) {
            $array = [];
            $array['id'] = $item->id;
            $array['nombre'] = Lucipher::Descipher($item->nombre);
            $array['area_depto_id'] = $item->area_depto_id;
            $array['cargo'] = Lucipher::Descipher($item->cargo);
            $colaborador[] = $array;

            // Destruir la sesión anterior si existe
            if ($request->session()->has('colaborador_id')) {
                $request->session()->forget('colaborador_id');
            }

            // Almacenar el ID del colaborador en una nueva sesión
            $request->session()->put('colaborador_id', $item->id);
        }

        return response()->json([
            'success' => true,
            'empleado' => $colaborador
        ]);
    }


    public function save_motivo()
    {
        $empresa_id = Auth::user()->empresa_id;
        $motivo = strtoupper(trim(request()->input('motivo_crear')));
        $exists_ = MotivoIncapacidad::where('motivo', $motivo)->where('empresa_id', $empresa_id)->exists();
        if ($exists_) {
            return [
                'status' => 'warning',
                'message' => 'Este motivo ya está registrado. Por favor, elige otro.',
            ];
        }
        $saveResult = MotivoIncapacidad::create([
            'motivo' => Lucipher::Cipher($motivo),
            'empresa_id' => $empresa_id
        ]);
        if ($saveResult) {
            $saveResult['motivo'] = Lucipher::Descipher($saveResult['motivo']);
            return [
                'status' => 'success',
                'message' => 'motivo creado exitosamente.',
                'results' => $saveResult
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de crear el departamento.',
            'results' => []
        ];
    }
    public function save_incapacidad(Request $request)
    {
        date_default_timezone_set('America/El_Salvador');
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;
        $date = date('Y-m-d');
        $hora = date('H:i:s');
        $colaboradorId = $request->session()->get('colaborador_id');
        $colaboradorUpdate = $request->session()->get('colaborador_update');

        $validateData = request()->validate([
            'codigo_empleado_dui' => 'required|string|max:25',
            'categoria_incapacidad' => 'required',
            'colaborador' => 'required|string|max:200',
            'cargo_col' => 'required|string|max:150',
            'departamento_col' => 'required|string|max:150',
            'diagnostico' => 'required|string|max:255',
            'fecha_inicio' => 'required|string',
            'fecha_fin' => 'required|string',
            'motivo' => 'required|string',
            'riesgo' => 'required|string',
            'tipo_incapacidad' => 'required|string',
            'fecha_expedicion' => 'string',
        ]);

        // Preparación de datos
        $data = [
            'codigo_empleado' => $validateData['codigo_empleado_dui'],
            'colaborador' => Lucipher::Cipher(trim($validateData['colaborador'])),
            'cargo' => Lucipher::Cipher(trim($validateData['cargo_col'])),
            'departamento' => $validateData['departamento_col'],
            'dui' => "-",
            'categoria_incapacidad' => $validateData['categoria_incapacidad'],
            'diagnostico' => $validateData['diagnostico'],
            'periodo' => $validateData['fecha_inicio'],
            'periodo_final' => $validateData['fecha_fin'],
            'motivo' => $validateData['motivo'],
            'riesgo' => $validateData['riesgo'],
            'tipo_incapacidad' => $validateData['tipo_incapacidad'],
            'fecha_expedicion' => $validateData['fecha_expedicion'],
            'sucursal_id' => $sucursal_id,
            'empresa_id' => $empresa_id,
        ];

        if ($colaboradorUpdate) {
            // Si existe la sesión colaborador_update, actualiza el registro correspondiente
            $incapacidad_id = $colaboradorUpdate;
            $result_save = Incapacidad::where('id', $incapacidad_id)
                ->where('empresa_id', $empresa_id)
                ->update($data);
            $message = 'La incapacidad se ha actualizado exitosamente.';
        } else {
            // Si no existe la sesión colaborador_update, crea un nuevo registro
            $merge_data = array_merge($data, [
                'empleado_id' => $colaboradorId,
                'usuario_id' => $usuario_id,
            ]);
            $result_save = Incapacidad::create($merge_data);
            $message = 'La incapacidad se ha registrado exitosamente.';

            // Establecer la sesión colaborador_update para la próxima actualización
            $request->session()->put('colaborador_update', Lucipher::Cipher($result_save->id));
        }

        if ($result_save) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'results' => []
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al momento de registrar la incapacidad.',
            'results' => []
        ]);
    }



    public function listar_incapacidades()
    {
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id =  Auth::user()->sucursal_id;
        $categoria =  Auth::user()->categoria;
        if ($categoria == 3) {
            $datos = DB::select("SELECT * FROM `incapacidades`");
        } else {
            $datos = DB::select("SELECT * FROM `incapacidades` as inc  WHERE inc.empresa_id = ? AND inc.sucursal_id = ?", [$empresa_id,  $sucursal_id]);
        }
        $contador = 1;
        $data = [];
        foreach ($datos as $row) { 
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row->codigo_empleado;
            $sub_array[] = Lucipher::Descipher($row->colaborador);
            $sub_array[] = $row->periodo;
            $sub_array[] = $row->periodo_final;
            $sub_array[] = $row->tipo_incapacidad;
            $sub_array[] = $row->riesgo;
            $sub_array[] = '<button data-ref="' . ($row->id) . '" title="Actualizar información del empleado" class="btn btn-outline-info btn-sm btn-incp" style="border:none;font-size:18px"><i class="bi bi-ticket-detailed"></i></button>';
            $data[] = $sub_array;
            $contador++;
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

    public function get_empleado_by_id(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = $request->input('ref_emp');

        // Obtener los datos del empleado y hacer el join con motivo_incapacidad
        $data = Incapacidad::where('incapacidades.id', $ref_emp)
            ->where('incapacidades.empresa_id', $empresa_id)
            ->join('motivo_incapacidad', 'incapacidades.motivo', '=', 'motivo_incapacidad.id')
            ->select(
                'incapacidades.*',
                'motivo_incapacidad.motivo as motivo_descifrado')
            ->get();

        $arrayData = [];
        foreach ($data as $item) {
            $arrayData['cargo'] = Lucipher::Descipher($item['cargo']);
            $arrayData['codigo_empleado'] = $item['codigo_empleado'];
            $arrayData['colaborador'] = Lucipher::Descipher($item['colaborador']);
            $arrayData['departamento'] = $item['departamento'];
            $arrayData['diagnostico'] = $item['diagnostico'];
            $arrayData['fecha_expedicion'] = $item['fecha_expedicion'];
            $arrayData['categoria_incapacidad'] = $item['categoria_incapacidad'];

            $fechaInicio = new \DateTime($item['periodo']);
            $fechaFin = new \DateTime($item['periodo_final']);
            $diferencia = $fechaInicio->diff($fechaFin);
            $cantidadDias = $diferencia->days + 1;
            $arrayData['rango_incapacidad'] = date('d/m/Y', strtotime($item['periodo'])) . " AL " . date('d/m/Y', strtotime($item['periodo_final']));
            $arrayData['dias_incapacidad'] = $cantidadDias . " Días";
            $arrayData['periodo'] = $item['periodo'];
            $arrayData['periodo_final'] = $item['periodo_final'];
            // Desencripta el motivo
            $arrayData['motivo'] = Lucipher::Descipher($item['motivo_descifrado']);
            $arrayData['motivo1'] =$item['motivo'];
            $arrayData['riesgo'] = $item['riesgo'];
            $arrayData['tipo_incapacidad'] = $item['tipo_incapacidad'];
        }

        // Guardar el id del primer elemento de la colección en la sesión
        if ($data->isNotEmpty()) {
            // Destruir la sesión anterior si existe
            if ($request->session()->has('colaborador_update')) {
                $request->session()->forget('colaborador_update');
            }

            // Guardar el id del primer registro
            $request->session()->put('colaborador_update', $data->first()->id);
        }

        return response()
            ->json($arrayData)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }



    //Resumen incapacidades 
    public function render()
    {
        $empresa_id = Auth::user()->empresa_id;

        $deptos = AreaDepartamentoEmp::where('empresa_id', $empresa_id)->get();
        $areas_depto = [];
        foreach ($deptos as $item) {
            $array = [];
            $array['id'] = $item['id'];
            $array['departamento'] = Lucipher::Descipher($item['departamento']);
            $areas_depto[] = $array;
        }
        $sucursales = Sucursal::where('empresa_id', $empresa_id)->get();

        $mot = MotivoIncapacidad::where('empresa_id', $empresa_id)->get();
        $motivos = [];
        foreach ($mot as $item) {
            $array = [];
            $array['id'] = $item['id'];
            $array['motivo'] = Lucipher::Descipher($item['motivo']);
            $motivos[] = $array;
        }
        $response = response()->view('incapacidades_resumen.index', compact('sucursales', 'areas_depto', 'motivos'));
        $response->header('Cache-Control', 'public, max-age=604800');
        return $response;
    }

    public function get_Datosrango()
    {
        // Obtener el ID de la empresa actual desde el usuario autenticado
        $empresa_id = Auth::user()->empresa_id;
    
        // Obtener los datos enviados desde la solicitud
        $fechaStart = request()->input('fechastart');
        $fechaEnd = request()->input('fechaEnd');
    
        // Iniciar la consulta de incapacidades para la empresa actual
        $query = Incapacidad::where('empresa_id', $empresa_id);
    
        // Si se ha proporcionado un rango de fechas, agregar la condición de búsqueda con BETWEEN
        if ($fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }
    
        // Ejecutar la consulta y obtener los resultados
        $data = $query->get();
        $departamentos = [];
    
        foreach ($data as $item) {
            // Calcular la duración de la incapacidad en días (incluyendo ambos días)
            $inicio = new \DateTime($item->periodo);
            $fin = new \DateTime($item->periodo_final);
            
            // Aumentar en 1 para contar ambos días
            $diferencia = $fin->diff($inicio)->days + 1; 
    
            // Clasificar por departamento
            $departamento_id = $item->departamento;
    
            if (!isset($departamentos[$departamento_id])) {
                $departamentos[$departamento_id] = [
                    'menor_igual_3' => 0,
                    'mayor_3' => 0,
                ];
            }
    
            // Incrementar el contador basado en la duración de la incapacidad
            if ($diferencia <= 3) {
                $departamentos[$departamento_id]['menor_igual_3']++;
            } else {
                $departamentos[$departamento_id]['mayor_3']++;
            }
        }
    
        // Obtener los nombres de los departamentos desde la tabla area_departamento_emps
    $departamento_ids = array_keys($departamentos);
    $nombresDepartamentos = AreaDepartamentoEmp::whereIn('id', $departamento_ids)->pluck('departamento', 'id');

    // Crear un nuevo arreglo con el nombre del departamento y aplicar el descifrado
    $departamentosConNombres = [];
    foreach ($departamentos as $id => $counts) {
        // Descifrar el nombre del departamento
        $nombreDepartamento = Lucipher::Descipher($nombresDepartamentos[$id]); // Asegúrate de que el método funcione correctamente
        $departamentosConNombres[$nombreDepartamento] = $counts; // Usar el nombre del departamento descifrado como clave
    }
        // Retornar los datos procesados y la cuenta de incapacidades por riesgo como respuesta JSON
        return response()
            ->json([
                'departamentos' => $departamentosConNombres // Aquí se envía la información agrupada por nombre del departamento
            ])
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    

    public function listar_incapacidadesRang(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id =  Auth::user()->sucursal_id;
        $categoria =  Auth::user()->categoria;

        // Obtener los datos enviados desde la solicitud
        $fechaStart = request()->input('fechastart');
        $fechaEnd = request()->input('fechaEnd');
        $departamento = request()->input('departamento');
        $query = Incapacidad::where('empresa_id', $empresa_id);

        // Si se ha proporcionado un rango de fechas, agregar la condición de búsqueda con BETWEEN
        if ($fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }

        // Si se ha proporcionado el departamento, agregar la condición de búsqueda para el departamento
        if ($departamento) {
            $query->where('departamento', $departamento);
        }


        if ($categoria == 3) {
            if ($empresa_id) {
                $query->where('empresa_id', $empresa_id);
            }
        } else {
            if ($empresa_id) {
                $query->where('empresa_id', $empresa_id);
            }
            if ($sucursal_id) {
                $query->where('sucursal_id', $sucursal_id);
            }
        }
        $datos = $query->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row->id;
            $sub_array[] = Lucipher::Descipher($row->colaborador);

            try {
                $periodo = $row->periodo ? new \DateTime($row->periodo) : null;
                $periodo_final = $row->periodo_final ? new \DateTime($row->periodo_final) : null;

                if ($periodo && $periodo_final) {
                    // Calcular la diferencia en días e incluir ambos días
                    $dias_incapacidad = $periodo->diff($periodo_final)->days + 1; // Incluye el primer y el último día
                } else {
                    $dias_incapacidad = '-'; // Valor por defecto si las fechas no son válidas
                }

                $fecha_rango = $periodo && $periodo_final ? date('d/m/Y', strtotime($row->periodo)) . " AL " . date('d/m/Y', strtotime($row->periodo_final)) : 'Fecha inválida';
            } catch (\Exception $e) {
                // Manejo de excepciones para fechas inválidas
                $dias_incapacidad = '-';
                $fecha_rango = 'Fecha inválida';
            }

            $sub_array[] = $fecha_rango;
            $sub_array[] = $dias_incapacidad;
            $sub_array[] = $row->tipo_incapacidad;
            $sub_array[] = $row->riesgo;
            $sub_array[] = '<button data-emp_ref="' . $row->id . '" title="Ver detalles" class="btn btn-outline-secondary btn-sm btn-o-detall" style="border:none;font-size:18px"><i class="bi bi-archive"></i></button>';
            $data[] = $sub_array;
            $contador++;
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
    public function get_DatosActivos()
    {
        // Obtener el ID de la empresa actual desde el usuario autenticado
        $empresa_id = Auth::user()->empresa_id;

        // Obtener la fecha actual
        $fechaHoy = now(); // También puedes usar Carbon::now() si prefieres trabajar con Carbon

        // Iniciar la consulta de incapacidades activas para la empresa actual
        $query = Incapacidad::where('empresa_id', $empresa_id)
            ->whereDate('periodo', '<=', $fechaHoy) // La incapacidad debe haber comenzado antes o el mismo día que hoy
            ->whereDate('periodo_final', '>=', $fechaHoy); // La incapacidad debe no haber terminado aún

        // Ejecutar la consulta y obtener los resultados
        $data = $query->get();

        // Procesar los datos obtenidos y desencriptar los valores necesarios
        $arrayData = [];
        $riesgosContador = [];

        foreach ($data as $item) {
            // Desencriptar y agregar al array principal
            $arrayData[] = [
                'cargo' => Lucipher::Descipher($item->cargo),
                'codigo_empleado' => $item->codigo_empleado,
                'colaborador' => Lucipher::Descipher($item->colaborador),
                'fecha_ingreso' => date('d/m/Y', strtotime($item->fecha_ingreso)),
                'departamento' => $item->departamento,
                'diagnostico' => $item->diagnostico,
                'inicio' => $item->periodo,
                'fin' => $item->periodo_final,
                'fecha_expedicion' => date('d/m/Y', strtotime($item->fecha_expedicion)),
                'rango_incapacidad' => date('d/m/Y', strtotime($item->periodo)) . " AL " . date('d/m/Y', strtotime($item->periodo_final)),
                'motivo' => $item->motivo,
                'riesgo' => $item->riesgo,
                'tipo_incapacidad' => $item->tipo_incapacidad
            ];

            // Contar incapacidades por riesgo
            if (isset($riesgosContador[$item->riesgo])) {
                $riesgosContador[$item->riesgo]++;
            } else {
                $riesgosContador[$item->riesgo] = 1;
            }
        }

        // Retornar los datos procesados y la cuenta de incapacidades por riesgo como respuesta JSON
        return response()
            ->json([
                'incapacidades' => $arrayData, // Aquí se envían los datos procesados
                'riesgos_contador' => $riesgosContador // Aquí se envía la información agrupada por riesgo
            ])
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function listar_incapacidadesActivas(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id =  Auth::user()->sucursal_id;
        $categoria =  Auth::user()->categoria;

        // Obtener el ID de la empresa actual desde el usuario autenticado
        $empresa_id = Auth::user()->empresa_id;

        // Obtener la fecha actual
        $fechaHoy = now(); // También puedes usar Carbon::now() si prefieres trabajar con Carbon

        // Iniciar la consulta de incapacidades activas para la empresa actual
        $query = Incapacidad::where('empresa_id', $empresa_id)
            ->whereDate('periodo', '<=', $fechaHoy) // La incapacidad debe haber comenzado antes o el mismo día que hoy
            ->whereDate('periodo_final', '>=', $fechaHoy); // La incapacidad debe no haber terminado aún

        if ($categoria == 3) {
            if ($empresa_id) {
                $query->where('empresa_id', $empresa_id);
            }
        } else {
            if ($empresa_id) {
                $query->where('empresa_id', $empresa_id);
            }
            if ($sucursal_id) {
                $query->where('sucursal_id', $sucursal_id);
            }
        }
        $datos = $query->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row->id;
            $sub_array[] = Lucipher::Descipher($row->colaborador);

            try {
                $periodo = $row->periodo ? new \DateTime($row->periodo) : null;
                $periodo_final = $row->periodo_final ? new \DateTime($row->periodo_final) : null;

                if ($periodo && $periodo_final) {
                    // Calcular la diferencia en días e incluir ambos días
                    $dias_incapacidad = $periodo->diff($periodo_final)->days + 1; // Incluye el primer y el último día
                } else {
                    $dias_incapacidad = '-'; // Valor por defecto si las fechas no son válidas
                }

                $fecha_rango = $periodo && $periodo_final ? date('d/m/Y', strtotime($row->periodo)) . " AL " . date('d/m/Y', strtotime($row->periodo_final)) : 'Fecha inválida';
            } catch (\Exception $e) {
                // Manejo de excepciones para fechas inválidas
                $dias_incapacidad = '-';
                $fecha_rango = 'Fecha inválida';
            }

            $sub_array[] = $fecha_rango;
            $sub_array[] = $dias_incapacidad;
            $sub_array[] = $row->tipo_incapacidad;
            $sub_array[] = $row->riesgo;
            $sub_array[] = '<button data-emp_ref="' . $row->id . '" title="Ver detalles" class="btn btn-outline-secondary btn-sm btn-o-detall" style="border:none;font-size:18px"><i class="bi bi-archive"></i></button>';
            $data[] = $sub_array;
            $contador++;
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

    public function listar_incapacidadesRanking(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $filtro = $request->input('filtro');
        $fechaStart = $request->input('fechaStart');
        $fechaEnd = $request->input('fechaEnd');

        // Obtener la fecha actual
        $fechaHoy = now();

        // Iniciar la consulta de incapacidades activas para la empresa actual
        $query = Incapacidad::selectRaw('incapacidades.empleado_id, COUNT(*) as total_incapacidades, MAX(incapacidades.colaborador) as colaborador, MAX(incapacidades.cargo) as cargo, MAX(area_departamento_emps.departamento) as departamento')
            ->join('area_departamento_emps', 'area_departamento_emps.id', '=', 'incapacidades.departamento')
            ->where('incapacidades.empresa_id', $empresa_id)
            ->where('incapacidades.sucursal_id', $sucursal_id)
            ->groupBy('incapacidades.empleado_id');

        // Aplicar el filtro de rango de fechas si es necesario
        if ($filtro === 'rango' && $fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }

        // Ejecutar la consulta y obtener los resultados
        $datos = $query->get();

        $data = [];
        foreach ($datos as $row) {
            $sub_array = [];
            $sub_array[] = $row->empleado_id; // ID del colaborador
            $sub_array[] = Lucipher::Descipher($row->colaborador); // Nombre del colaborador
            $sub_array[] = Lucipher::Descipher($row->cargo); // Cargo
            $sub_array[] = Lucipher::Descipher($row->departamento); // Departamento
            $sub_array[] = $row->total_incapacidades; // Total de incapacidades
            // Agregar las fechas como atributos en el botón cuando el filtro es "rango"
            if ($filtro === 'rango' && $fechaStart && $fechaEnd) {
                $sub_array[] = '<button data-emp_ref="' . $row->empleado_id . '" data-fecha_start="' . $fechaStart . '" data-fecha_end="' . $fechaEnd . '" title="Ver detalles" class="btn btn-outline-secondary btn-sm btn-o-detEmpl" style="border:none;font-size:18px"><i class="bi bi-archive"></i></button>';
            } else {
                $sub_array[] = '<button data-emp_ref="' . $row->empleado_id . '" title="Ver detalles" class="btn btn-outline-secondary btn-sm btn-o-detEmpl" style="border:none;font-size:18px"><i class="bi bi-archive"></i></button>';
            }

            $data[] = $sub_array;
        }

        $results = [
            "sEcho" => 1, // Información para datatables
            "iTotalRecords" => count($data), // Total de registros
            "iTotalDisplayRecords" => count($data), // Total de registros a mostrar
            "aaData" => $data // Los datos
        ];

        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function get_ArchivosEmpleados(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $ref_emp = $request->input('ref_emp');

        // Obtener fechas del request
        $fecha_start = $request->input('fecha_start');
        $fecha_end = $request->input('fecha_end');

        // Construir la consulta base
        $query = Incapacidad::where('incapacidades.empleado_id', $ref_emp)
            ->where('incapacidades.empresa_id', $empresa_id)
            ->join('motivo_incapacidad', 'incapacidades.motivo', '=', 'motivo_incapacidad.id')
            ->select(
                'incapacidades.*',
                'motivo_incapacidad.motivo as motivo_descifrado' // Selecciona el motivo descifrado
            );

        // Si las fechas están presentes en el request, agregamos la condición de rango de fechas
        if (!empty($fecha_start) && !empty($fecha_end)) {
            $query->whereBetween('incapacidades.fecha_expedicion', [$fecha_start, $fecha_end]);
        }

        // Ejecutar la consulta y obtener los datos
        $DatosVarios = $query->get();

        $arrayDatosVarios = [];
        foreach ($DatosVarios as $item) {
            $arrayDatosVarios[] = [
                'cargo' => Lucipher::Descipher($item['cargo']),
                'codigo_empleado' => $item['codigo_empleado'],
                'colaborador' => Lucipher::Descipher($item['colaborador']),
                'departamento' => $item['departamento'],
                'diagnostico' => $item['diagnostico'],
                'fecha_expedicion' => $item['fecha_expedicion'],

                // Manejo del periodo de incapacidad
                'periodo' => $item['periodo'],
                'periodo_final' => $item['periodo_final'],
                'rango_incapacidad' => date('d/m/Y', strtotime($item['periodo'])) . " AL " . date('d/m/Y', strtotime($item['periodo_final'])),
                'dias_incapacidad' => (new \DateTime($item['periodo']))->diff(new \DateTime($item['periodo_final']))->days + 1 . " Días",

                // Motivo descifrado
                'motivo' => Lucipher::Descipher($item['motivo_descifrado']),
                'riesgo' => $item['riesgo'],
                'tipo_incapacidad' => $item['tipo_incapacidad']
            ];
        }

        // Guardar el id del primer elemento de la colección en la sesión
        if ($DatosVarios->isNotEmpty()) {
            // Destruir la sesión anterior si existe
            if ($request->session()->has('colaborador_update')) {
                $request->session()->forget('colaborador_update');
            }

            // Guardar el id del primer registro
            $request->session()->put('colaborador_update', $DatosVarios->first()->id);
        }

        // Responder con los datos en formato JSON
        return response()
            ->json($arrayDatosVarios)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    //FUNCIONES PARA CATEGORIA//PAGO
    public function listar_incapacidadesMes(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id =  Auth::user()->sucursal_id;
        $fechaStart = request()->input('fechastart');
        $fechaEnd = request()->input('fechaEnd');

        $query = Incapacidad::where('empresa_id', $empresa_id);
        if ($fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }
        if ($sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        }
        $datos = $query->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row->id;
            $sub_array[] = Lucipher::Descipher($row->colaborador);

            try {
                $periodo = $row->periodo ? new \DateTime($row->periodo) : null;
                $periodo_final = $row->periodo_final ? new \DateTime($row->periodo_final) : null;
                if ($periodo && $periodo_final) {
                    $dias_incapacidad = $periodo->diff($periodo_final)->days + 1; // Incluye el primer y el último día
                } else {
                    $dias_incapacidad = '-';
                }
                $fecha_rango = $periodo && $periodo_final ? date('d/m/Y', strtotime($row->periodo)) . " AL " . date('d/m/Y', strtotime($row->periodo_final)) : 'Fecha inválida';
            } catch (\Exception $e) {
                $dias_incapacidad = '-';
                $fecha_rango = 'Fecha inválida';
            }
            $sub_array[] = $fecha_rango;
            $sub_array[] = $dias_incapacidad;
            $sub_array[] = $row->tipo_incapacidad;
            $sub_array[] = $row->categoria_incapacidad;
            $sub_array[] = '<button data-emp_ref="' . $row->id . '" title="Ver detalles" class="btn btn-outline-secondary btn-sm btn-o-detall" style="border:none;font-size:18px"><i class="bi bi-archive"></i></button>';
            $data[] = $sub_array;
            $contador++;
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

    public function get_Datosmes()
    {
        $empresa_id = Auth::user()->empresa_id;
        $fechaStart = request()->input('fechastart');
        $fechaEnd = request()->input('fechaEnd');
        $query = Incapacidad::where('empresa_id', $empresa_id);
    
        if ($fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }
        $data = $query->get();
        $arrayData = [];
        $categoriaContador = [];  // Cambiar a categoría de incapacidad
        foreach ($data as $item) {
            $arrayData[] = [
                'cargo' => Lucipher::Descipher($item->cargo),
                'codigo_empleado' => $item->codigo_empleado,
                'colaborador' => Lucipher::Descipher($item->colaborador),
                'fecha_ingreso' => date('d/m/Y', strtotime($item->fecha_ingreso)),
                'departamento' => $item->departamento,
                'diagnostico' => $item->diagnostico,
                'inicio' => $item->periodo,
                'fin' => $item->periodo_final,
                'fecha_expedicion' => date('d/m/Y', strtotime($item->fecha_expedicion)),
                'rango_incapacidad' => date('d/m/Y', strtotime($item->periodo)) . " AL " . date('d/m/Y', strtotime($item->periodo_final)),
                'motivo' => $item->categoria_incapacidad,
                'riesgo' => $item->riesgo,
                'tipo_incapacidad' => $item->tipo_incapacidad
            ];
    
            if (isset($categoriaContador[$item->categoria_incapacidad])) {
                $categoriaContador[$item->categoria_incapacidad]++;
            } else {
                $categoriaContador[$item->categoria_incapacidad] = 1;
            }
        }
    
        return response()
            ->json([
                'incapacidades' => $data,
                'categoria_contador' => $categoriaContador // Devolver el nuevo contador
            ])
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function get_Datosriesgo() {
        // Obtener el ID de la empresa actual desde el usuario autenticado
        $empresa_id = Auth::user()->empresa_id;
    
        // Obtener los datos enviados desde la solicitud
        $fechaStart = request()->input('fechastart');
        $fechaEnd = request()->input('fechaEnd');
        $departamento = request()->input('departamento');
    
        // Iniciar la consulta de incapacidades para la empresa actual
        $query = Incapacidad::where('empresa_id', $empresa_id);
    
        // Si se ha proporcionado un rango de fechas, agregar la condición de búsqueda con BETWEEN
        if ($fechaStart && $fechaEnd) {
            $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
        }
    
        // Si se ha proporcionado el departamento, agregar la condición de búsqueda para el departamento
        if ($departamento) {
            $query->where('departamento', $departamento);
        }
        
        // Ejecutar la consulta y obtener los resultados
        $data = $query->get();
    
        // Procesar los datos obtenidos y desencriptar los valores necesarios
        $arrayData = [];
        $riesgosContador = [];
    
        foreach ($data as $item) {
            // Desencriptar y agregar al array principal
            $arrayData[] = [
                'cargo' => Lucipher::Descipher($item->cargo),
                'codigo_empleado' => $item->codigo_empleado,
                'colaborador' => Lucipher::Descipher($item->colaborador),
                'fecha_ingreso' => date('d/m/Y', strtotime($item->fecha_ingreso)),
                'departamento' => $item->departamento,
                'diagnostico' => $item->diagnostico,
                'inicio' => $item->periodo,
                'fin' => $item->periodo_final,

                'fecha_expedicion' => date('d/m/Y', strtotime($item->fecha_expedicion)),
                'rango_incapacidad' => date('d/m/Y', strtotime($item->periodo)) . " AL " . date('d/m/Y', strtotime($item->periodo_final)),
                'motivo' => $item->motivo,
                'riesgo' => $item->riesgo,
                'tipo_incapacidad' => $item->tipo_incapacidad
            ];
    
            // Contar incapacidades por riesgo
            if (isset($riesgosContador[$item->riesgo])) {
                $riesgosContador[$item->riesgo]++;
            } else {
                $riesgosContador[$item->riesgo] = 1;
            }
        }
    
        // Retornar los datos procesados y la cuenta de incapacidades por riesgo como respuesta JSON
        return response()
            ->json([
                'incapacidades' => $data,
                'riesgos_contador' => $riesgosContador // Aquí se envía la información agrupada por riesgo
            ])
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function get_Datosdepartamento(){
            // Obtener el ID de la empresa actual desde el usuario autenticado
            $empresa_id = Auth::user()->empresa_id;
        
            // Obtener los datos enviados desde la solicitud
            $fechaStart = request()->input('fechastart');
            $fechaEnd = request()->input('fechaEnd');
        
            // Iniciar la consulta de incapacidades para la empresa actual
            $query = Incapacidad::where('empresa_id', $empresa_id);
        
            // Si se ha proporcionado un rango de fechas, agregar la condición de búsqueda con BETWEEN
            if ($fechaStart && $fechaEnd) {
                $query->whereBetween('fecha_expedicion', [$fechaStart, $fechaEnd]);
            }
        
            // Ejecutar la consulta y obtener los resultados
            $data = $query->get();
            $departamentos = [];
        
            foreach ($data as $item) {
                // Obtener el ID del departamento
                $departamento_id = $item->departamento;
        
                // Inicializar si no existe aún en el arreglo
                if (!isset($departamentos[$departamento_id])) {
                    $departamentos[$departamento_id] = 0; // Solo se cuenta la cantidad
                }
        
                // Incrementar el contador por departamento
                $departamentos[$departamento_id]++;
            }
        
            // Obtener los nombres de los departamentos desde la tabla area_departamento_emps
            $departamento_ids = array_keys($departamentos);
            $nombresDepartamentos = AreaDepartamentoEmp::whereIn('id', $departamento_ids)->pluck('departamento', 'id');
        
            // Crear un nuevo arreglo con el nombre del departamento y aplicar el descifrado
            $departamentosConNombres = [];
            foreach ($departamentos as $id => $count) {
                // Descifrar el nombre del departamento
                $nombreDepartamento = Lucipher::Descipher($nombresDepartamentos[$id]);
                $departamentosConNombres[$nombreDepartamento] = $count; // Usar el nombre del departamento descifrado como clave
            }
        
            // Retornar los datos procesados y la cuenta de incapacidades por departamento como respuesta JSON
            return response()
                ->json([
                    'departamentos' => $departamentosConNombres // Aquí se envía la información agrupada por nombre del departamento
                ])
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'max-age=86400');
        }
        
}
