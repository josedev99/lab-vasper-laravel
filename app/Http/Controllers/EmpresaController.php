<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\DetJerarquia;
use App\Http\Services\Lucipher;
use App\Models\HorariosCita;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

use App\Models\AreaDepartamentoEmp;
use App\Models\AreaEmp;
use App\Models\CargoEmp;

use Illuminate\Support\Facades\Http;



class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return view('empresa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function guardarEmpresa(Request $request)
    {
        $request->validate([
            'nombreEmpresa' => 'required|string|max:255',
            'direccionEmpresa' => 'nullable|string|max:255',
            'telefonoEmpresa' => 'nullable|string|max:9',
            'celularEmpresa' => 'nullable|string|max:9',
            'nRegistroEmpresa' => 'nullable|string|max:20',
            'giro' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);
    
        $logoPath = null;
        if ($request->hasFile('image')) {
            $logoPath = $request->file('image')->store('FotosEmpresa', 'public');
        }
    
        $fechaActual = Carbon::now('America/El_Salvador')->toDateString();
        // Obtener el siguiente valor de correlativo para cod_clinica
        $correlativo = Empresa::count() + 1;
        $codClinica = 'AV' . $correlativo;
        // Crear la empresa
        $empresa = Empresa::create([
            'nombre' => $request->nombreEmpresa,
            'direccion' => $request->direccionEmpresa,
            'telefono' => $request->telefonoEmpresa,
            'celular' => $request->celularEmpresa,
            'no_registro' => $request->nRegistroEmpresa,
            'giro' => $request->giro,
            'cod_clinica' => $codClinica,
            'cargo' => $request->cargoEmpresa,
            'logo' => $logoPath,
            'usuario_id' => "-",
        ]);
        
        $tipo = $request->exist;
        $id_emp = $request->id;
        $encargado = $request->encargado || '';

        // Crear la sucursal con los mismos datos de la empresa
        $sucursal = Sucursal::create([
            'nombre' => $empresa->nombre,
            'direccion' => $empresa->direccion,
            'telefono' => $empresa->telefono,
            'email' => $empresa->email ?? '', 
            'encargado' => $encargado, 
            'fecha' => $fechaActual,
            'hora' => Carbon::now('America/El_Salvador')->toTimeString(),
            'empresa_id' => $empresa->id,
            'usuario_id' => "-", 
        ]);
    

        $nombreEmpresa = $request->nombreEmpresa;
        $usuario = strtolower(str_replace(' ', '', $nombreEmpresa)); // Convertir a minúsculas y eliminar espacios
        
        // Establecer la zona horaria a El Salvador y obtener la fecha y hora actual
        $fechaHoraLocal = Carbon::now('America/El_Salvador')->format('dmyHi'); // Año en dos dígitos

        // Hashear la contraseña
        $password = Hash::make($fechaHoraLocal);

        Usuario::create([
            'nombre' => $nombreEmpresa,
            'usuario' => $usuario, 
            'contr' => Lucipher::Cipher($fechaHoraLocal),
            'password' => $password, 
            'empresa_id' => $empresa->id,
            'estado' => 1,
            'categoria' => 2,
            'fecha_creacion' => $fechaActual,
            'sucursal_id' => $sucursal->id, 
            'cargo' => "Admin",
        ]); 
    
        return response()->json([
            'message' => 'Empresa y sucursal guardadas correctamente',
            'empresa' => $empresa, // Incluye los datos de la empresa creada
            'existencia' => $tipo,
            'emp_id' => $id_emp,
            'sucursal_id' => $sucursal->id
        ]);
        }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function getEmpresas()
    {
        try {
            // Aquí haces la consulta al otro sistema. 
            // Esto es un ejemplo si usas una API REST.
            $response = Http::get('https://tuopticasv.com/api/empresas/all');
          //  $response = Http::get('http://127.0.0.1:8001/api/empresas/all');

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }

            return response()->json(['message' => 'Error al obtener empresas'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en la conexión'], 500);
        }
    }



    public function SavNewEmpresa(Request $request)
    {
    
        try {
            $empresaData = $request->all();
           $response = Http::post('https://tuopticasv.com/api/empresa/save', $empresaData);
         //  $response = Http::post('http://127.0.0.1:8001/api/empresa/save', $empresaData);
    
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json(['message' => 'Error al enviar los datos al otro sistema'], 500);
            }
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en la conexión'], 500);
        }
    }
    
    
    
    
    /**
     * Display the specified resource.
     */
    public function EmpresasAll()
    {
        $userPermissions = Session::get('userPermissions', []); // Obtén los permisos desde la sesión

        $usuarios = Empresa::All();
        
        $data = array();
        foreach ($usuarios as $user) {
            $sub_array = array();
            $sub_array[] = $user->id;
            $sub_array[] = $user->nombre;
            $sub_array[] = $user->giro;
            $sub_array[] = $user->telefono;


            $botones = '';

                $botones .= '<button data-emp_ref="' . $user->id . '" title="Agregar Sucursales" class="btn btn-outline-primary btn-sm btn-add-sucursal" style="border:none;font-size:20px"><i class="bi bi-window-plus"></i></button>';
            // Agregar el botón "Editar" solo si el usuario tiene el permiso
            if (in_array('EDITAR_EMPRESA', $userPermissions)) {
                $botones .= '<button data-emp_id="' . $user->id . '" title="Editar" class="btn btn-outline-primary btn-sm btn-o-edit" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>';
            }
            if (in_array('VER REPORTES', $userPermissions)) {
                $botones .= '<button data-emp_id="' . $user->id . '" title="datos" class="btn btn-outline-primary btn-sm btn-o-data" style="border:none;font-size:18px"><i class="bi bi-card-list"></i></button>';
            }
            // Agregar los botones concatenados en un único elemento del array
            $sub_array[] = $botones;


            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data
        );

        echo json_encode($results);
    }

    public function obtenerInformacion(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
    
        // Obtener información de la base de datos
        $empresa = DB::select("SELECT usuario, contr FROM `usuarios` WHERE empresa_id = ?", [$empresa_id]);
    
        if ($empresa) {
            // Desencriptar las contraseñas
            $resultado = array_map(function ($item) {
                return [
                    'usuario' => $item->usuario,
                    'contr' => Lucipher::Descipher($item->contr),
                ];
            }, $empresa);
    
            return response()->json($resultado); // Enviar datos como JSON
        } else {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }
    }
    

    public function SucursalAll($empresa_id)
    {
        // Validar si el ID de la empresa viene vacío o es null
        if ($empresa_id == 0) {
            // Si está vacío, usar el valor de la sesión
            $empresa_id = session('epm_id');
        } else {
            // Si no está vacío, actualizar la sesión
            if (session()->has('epm_id')) {
                session()->forget('epm_id'); // Elimina la sesión existente
            }
            session(['epm_id' => $empresa_id]); // Crea una nueva sesión con el ID de empresa
        }
    
        // Consultar las sucursales para el ID de empresa
        $usuarios = DB::select("SELECT * FROM `sucursals` WHERE empresa_id = ?", [$empresa_id]);
        $data = array();
    
        foreach ($usuarios as $user) {
            $sub_array = array();
            $sub_array[] = $user->id;
            $sub_array[] = $user->nombre;
            $sub_array[] = $user->encargado;
            $sub_array[] = $user->telefono;
            $sub_array[] = '<button data-emp_ref="' . $user->id . '" title="Editar sucursal" class="btn btn-outline-primary btn-sm" Onclick="Nuevas(' . $user->id . ')" style="border:none;font-size:20px"><i class="bi bi-pencil-square"></i></button>';
            $data[] = $sub_array;
        }
    
        // Preparar los resultados para el DataTable
        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // Total registros
            "iTotalDisplayRecords" => count($data), // Total registros a visualizar
            "aaData" => $data
        );
    
        echo json_encode($results);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function guardarHorarios(Request $request)
    {
        // Recibir el array 'horariosSeleccionados' del request
        $horarios = $request->input('horariosSeleccionados');
        $obj = $request->input('obj');
    
        $empresa_id = Auth::user()->empresa_id;
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;
    
        // Eliminar los horarios existentes de la empresa en la tabla antes de guardar los nuevos
        HorariosCita::where('empresa_id', $empresa_id)->delete();
    
        // Recorremos cada día y sus horas correspondientes para guardar los nuevos horarios
        foreach ($horarios as $dia => $horas) {
            foreach ($horas as $hora) {
                // Guardar cada horario en la base de datos
                HorariosCita::create([
                    'rango' => $obj,
                    'hora' => $hora,
                    'estado' => 'disponible', // Ajusta según sea necesario
                    'dia' => $dia,
                    'empresa_id' => $empresa_id,
                    'sucursal_id' => $sucursal_id,
                    'usuario_id' => $usuario_id,
                ]);
            }
        }
    
        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Horarios guardados correctamente']);
    }
    
    public function getHorariosEmpresa($empresa_id) {
        // Suponiendo que tienes una relación para obtener los horarios de la empresa
        $horarios = HorariosCita::where('empresa_id', $empresa_id)->get();
    
        // Procesar los datos y devolverlos en el formato necesario
        $horariosPorDia = [
            'lunes' => $horarios->where('dia', 'lunes')->pluck('hora'),
            'martes' => $horarios->where('dia', 'martes')->pluck('hora'),
            'miercoles' => $horarios->where('dia', 'miercoles')->pluck('hora'),
            'jueves' => $horarios->where('dia', 'jueves')->pluck('hora'),
            'viernes' => $horarios->where('dia', 'viernes')->pluck('hora'),
            'sabado' => $horarios->where('dia', 'sabado')->pluck('hora'),
            'domingo' => $horarios->where('dia', 'domingo')->pluck('hora'),
        ];
    
        // Obtener el rango horario de la empresa
        $rango_horario = $horarios->first()->rango ?? null;
    
        return response()->json([
            'horarios' => $horariosPorDia,
            'rango_horario' => $rango_horario
        ]);
    }



    public function getEmpresaEdit(Request $request){
        $ref_emp = $request->input('ref_emp');
          // Verificar si ya existe 'empresa_id' en la sesión y eliminarlo si es necesario
            if ($request->session()->has('empresa_id')) {
                $request->session()->forget('empresa_id');
            }

            // Guardar el nuevo id de la empresa en la sesión
            $request->session()->put('empresa_id', $ref_emp);

        $data = Empresa::where('id',$ref_emp)->get();
    
        return response()
            ->json(($data))
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    public function actualizarEmpresa(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'nombreEmpresa' => 'required|string|max:255',
            'direccionEmpresa' => 'nullable|string|max:255',
            'telefonoEmpresa' => 'nullable|string|max:9',
            'celularEmpresa' => 'nullable|string|max:9',
            'nRegistroEmpresa' => 'nullable|string|max:20',
            'giro' => 'nullable|string|max:255',
            'cargoEmpresa' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);
    
        // Obtener la empresa desde la sesión
        $empresa = Empresa::find(session('empresa_id'));
    
        // Si la empresa no existe, retorna un error
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }
    
        // Verificar si se subió una nueva imagen
        if ($request->hasFile('image')) {
            // Guardar la nueva imagen
            $logoPath = $request->file('image')->store('FotosEmpresa', 'public');
    
            // Actualizar el logo de la empresa
            $empresa->logo = $logoPath;
        }
    
        // Actualizar otros campos de la empresa
        $empresa->nombre = $request->nombreEmpresa;
        $empresa->direccion = $request->direccionEmpresa;
        $empresa->telefono = $request->telefonoEmpresa;
        $empresa->celular = $request->celularEmpresa;
        $empresa->no_registro = $request->nRegistroEmpresa;
        $empresa->giro = $request->giro;
    
        // Guardar los cambios
        $empresa->save();
    
        return response()->json(['message' => 'Empresa actualizada correctamente']);
    }
    

    public function checkJerarquia() 
    {
        try {
            $empresaId = Auth::user()->empresa_id;
            $detJerarquia = DetJerarquia::where('id_empresa', $empresaId)->first();
    
            if ($detJerarquia) {
                // Obtener todos los datos
                $departamentos = AreaDepartamentoEmp::where('empresa_id', $empresaId)->get()
                    ->map(function($depto) {
                        return [
                            'id' => $depto->id,
                            'nombre' => Lucipher::Descipher($depto->departamento)
                        ];
                    })->toArray();
    
                $areas = AreaEmp::where('id_empresa', $empresaId)->get()
                    ->map(function($area) {
                        return [
                            'id' => $area->id,
                            'nombre' => $area->nombre,
                            'id_depto' => $area->id_depto
                        ];
                    })->toArray();
    
                $cargos = CargoEmp::where('id_empresa', $empresaId)->get()
                    ->map(function($cargo) {
                        return [
                            'id' => $cargo->id,
                            'nombre' => $cargo->nombre,
                            'id_area' => $cargo->id_area
                        ];
                    })->toArray();
    
                // Estructurar datos jerárquicamente
                $estructuraCompleta = [];
                
                // Para cada departamento, buscar sus áreas y cargos
                foreach ($departamentos as $depto) {
                    $areasDelDepto = array_filter($areas, function($area) use ($depto) {
                        return $area['id_depto'] == $depto['id'];
                    });
    
                    $areasEstructuradas = [];
                    foreach ($areasDelDepto as $area) {
                        // Buscar los cargos de esta área
                        $cargosDelArea = array_filter($cargos, function($cargo) use ($area) {
                            return $cargo['id_area'] == $area['id'];
                        });
    
                        $areasEstructuradas[] = [
                            'id' => $area['id'],
                            'nombre' => $area['nombre'],
                            'cargos' => array_values($cargosDelArea)
                        ];
                    }
    
                    $estructuraCompleta[] = [
                        'id' => $depto['id'],
                        'nombre' => $depto['nombre'],
                        'areas' => array_values($areasEstructuradas)
                    ];
                }
    
                // Estructura alternativa para tipo 2 (solo áreas con sus cargos)
                $estructuraAreas = [];
                foreach ($areas as $area) {
                    $cargosDelArea = array_filter($cargos, function($cargo) use ($area) {
                        return $cargo['id_area'] == $area['id'];
                    });
    
                    $estructuraAreas[] = [
                        'id' => $area['id'],
                        'nombre' => $area['nombre'],
                        'cargos' => array_values($cargosDelArea)
                    ];
                }
    
                return response()->json([
                    'exists' => true,
                    'tipo' => $detJerarquia->tipo,
                    'data' => [
                        'tipo1' => $estructuraCompleta,  // Estructura jerárquica completa
                        'tipo2' => $estructuraAreas,     // Solo áreas con sus cargos
                        // Datos planos por si se necesitan
                        'departamentos' => $departamentos,
                        'areas' => $areas,
                        'cargos' => $cargos
                    ]
                ]);
            }
    
            return response()->json(['exists' => false]);
    
        } catch (\Exception $e) {
            \Log::error('Error al verificar jerarquía: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'error' => 'Error al verificar la jerarquía: ' . $e->getMessage()
            ], 500);
        }
    }
/*  */

    public function saveJerarquia(Request $request) {
        $request->validate([
            'tipo' => 'required|in:1,2,3',  // Validar que el tipo sea 1, 2 o 3
        ]);
    
        $empresa_id = Auth::user()->empresa_id;

        // Buscar si ya existe una jerarquía para la empresa actual
        $detJerarquia = DetJerarquia::where('id_empresa', $empresa_id)->first();
    
        if ($detJerarquia) {
            // Si ya existe, actualiza el registro
            $detJerarquia->tipo = $request->tipo;
            $detJerarquia->save();
        } else {
            if( $request->tipo == '1'){
                $descripcion = 'depto-area-cargo';
                } else if ($request->tipo == '2') { 
                    $descripcion = 'area-cargo';
                   } else{ $descripcion = 'area'; }

            // Si no existe, crea un nuevo registro
            DetJerarquia::create([
                'id_empresa' => $empresa_id,
                'tipo' => $request->tipo,
                'descripcion' => $descripcion,  // Ajusta esto según sea necesario
            ]);
        }
    
        return response()->json(['success' => true, 'tipo' => $request->tipo]);
    }


    public function save_jerarquias(Request $request)
    {
        try {
            // Iniciar transacción para asegurar la integridad de los datos
          //  DB::beginTransaction();
            // Obtener el ID de la empresa actual (asumiendo que está disponible en la sesión o request)
            $empresa_id = Auth::user()->empresa_id; // Obtén la empresa del usuario autenticado

            $tipo = $request->tipo;
            $jerarquia = $request->jerarquia;

            $resultado = ['success' => true, 'message' => 'Jerarquía guardada correctamente'];

            foreach ($jerarquia as $departamento) {
                // Guardar departamento
                $newDepartamento = AreaDepartamentoEmp::create([
                    'departamento' =>  Lucipher::Cipher($departamento['nombre']) ,
                    'empresa_id' => $empresa_id
                ]);

                // Guardar áreas del departamento
                foreach ($departamento['areas'] as $area) {
                    $newArea = AreaEmp::create([
                        'nombre' => $area['nombre'],
                        'id_empresa' => $empresa_id,
                        'id_depto' => $newDepartamento->id
                    ]);

                    // Guardar cargos del área
                    foreach ($area['cargos'] as $cargo) {
                        CargoEmp::create([
                            'nombre' => $cargo['nombre'],
                            'id_empresa' => $empresa_id,
                            'id_area' => $newArea->id
                        ]);
                    }
                }
            }

            // Si todo salió bien, confirmar la transacción
            DB::commit();

            return response()->json($resultado);

        } catch (\Exception $e) {
            // Si algo salió mal, revertir todos los cambios
            DB::rollBack();

            \Log::error('Error al guardar jerarquía: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la jerarquía: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function guardarSucursal(Request $request)
    {
        // Obtener el ID de la empresa desde la sesión
        $empresa_id = session('epm_id');
    
        // Validate the request
        $request->validate([
            'nombreSucursal' => 'required|string|max:255',
            'direccionSucursal' => 'nullable|string|max:255',
            'telefonoSucursal' => 'nullable|string|max:9',
            'emailSucursal' => 'nullable|string|max:25',
            'encargadoSucursal' => 'nullable|string|max:25'
        ]);
        
        $fechaActual = Carbon::now('America/El_Salvador')->toDateString();
    
        // Create the branch (sucursal) with the data
        Sucursal::create([
            'nombre' => $request->nombreSucursal,
            'direccion' => $request->direccionSucursal,
            'telefono' => $request->telefonoSucursal,
            'email' => $request->emailSucursal,
            'encargado' => $request->encargadoSucursal,
            'fecha' => $fechaActual,
            'hora' => Carbon::now('America/El_Salvador')->toTimeString(),
            'empresa_id' => $empresa_id,
            'usuario_id' => "-", 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Sucursal guardada correctamente']);
    }
    

    public function getSucursalEdit(Request $request){
           $sucursal_id = $request->input('id');
            // Verificar si ya existe 'empresa_id' en la sesión y eliminarlo si es necesario
            if ($request->session()->has('suc_id')) {
                $request->session()->forget('suc_id');
            } 

            // Guardar el nuevo id de la empresa en la sesión
            $request->session()->put('suc_id', $sucursal_id);

        $data = Sucursal::where('id',$sucursal_id)->get();
    
        return response()
            ->json(($data))
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

public function guardarEditSucursal(Request $request)
{
    $empresa_id = $request->input('id_empr'); // Obtener el ID de la empresa del usuario autenticado
    //$sucursal_id = $request->session()->get('suc_id'); // Obtener el ID de la sucursal de la sesión
    $sucursal_d = $request->input('id_susc');
    // Validar la solicitud
    $request->validate([
        'nombreSucursal1' => 'required|string|max:255',
        'direccionSucursal1' => 'nullable|string|max:255',
        'telefonoSucursal1' => 'nullable|string|max:9',
        'emailSucursal1' => 'nullable|string|max:25',
        'encargadoSucursal1' => 'nullable|string|max:25'
    ]);
    
    $fechaActual = Carbon::now('America/El_Salvador')->toDateString();

    // Buscar y actualizar la sucursal con los datos proporcionados
    $sucursal = Sucursal::find($sucursal_d);

    if ($sucursal) {
        $sucursal->update([
            'nombre' => $request->nombreSucursal1,
            'direccion' => $request->direccionSucursal1,
            'telefono' => $request->telefonoSucursal1,
            'email' => $request->emailSucursal1,
            'encargado' => $request->encargadoSucursal1,
            'fecha' => $fechaActual,
            'hora' => Carbon::now('America/El_Salvador')->toTimeString(),
            'empresa_id' => $empresa_id,
            'usuario_id' => "-", 
        ]);

        return response()->json(['success' => true, 'message' => 'Sucursal actualizada correctamente']);
    } else {
        return response()->json(['success' => false, 'message' => 'Sucursal no encontrada'], 404);
    }
}

}
