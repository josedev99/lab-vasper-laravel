<?php

use App\Http\Controllers\Agenda;
use App\Http\Controllers\AreaDepartamentoController;
use App\Http\Controllers\CategoriaExamenController;
use App\Http\Controllers\Cie10Controller;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DetalleOrdenCreditoController;
use App\Http\Controllers\DeteccionTempranaController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;

use App\Http\Controllers\ExamenesCategoriaController;
use App\Http\Controllers\ExamenPreingresoController;
use App\Http\Controllers\ExamesJornadas;
use App\Http\Controllers\FactorRiesgoController;
use App\Http\Controllers\GruposEspecificosController;
use App\Http\Controllers\JornadaController;
use App\Http\Controllers\OrdenLabController;
use App\Http\Controllers\IncapacidadController;
use App\Http\Controllers\LaboratorioResultadoController;
use App\Http\Controllers\PageCuestionarioController;
use App\Http\Controllers\PerfilExamenController;
use App\Http\Controllers\PerfilRiesgoController;
use App\Http\Controllers\RegistroMedicoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ResultadoExamenImgController;
use App\Http\Controllers\ResultadoOptometriaController;
use App\Http\Controllers\ResultadosExamenController;
use App\Http\Controllers\SucursalController;

Route::get('/',[HomeController::class,'index'])->name('app.home')->middleware('auth');

Route::get('/login',[LoginController::class,'showlogin'])->name('app.login');
Route::post('/login', [loginController::class, 'login'])->name('login');
Route::post('/logout', [loginController::class, 'logout'])->name('logout');
Route::get('/obtener/sucursales/{usuario}', [loginController::class, 'getSucursalesByUsuario']);

//route para Usuarios
Route::get('/usuarios',[UsuarioController::class,'index'])->name('app.user');
Route::post('/usuarios/crear', [UsuarioController::class, 'CrearUsuario'])->name('usuarios.Crear');
Route::post('/Usuarios/show', [UsuarioController::class, 'UsuarioAll'])->name('usuarios.tabla');
Route::post('/obtener-usuario',[UsuarioController::class,'get_usuario_by_id'])->name('app.Usuarios.getUsuario');
Route::post('/modulo/save',[UsuarioController::class,'save_modulo'])->name('app.modulo.save');
Route::post('/modulos/show', [UsuarioController::class, 'modulosAll'])->name('modulos.tabla');
Route::post('/permisos/show', [UsuarioController::class, 'permisosAll'])->name('permisos.tabla');
Route::post('/permiso/save',[UsuarioController::class,'save_permiso'])->name('app.permiso.save');
Route::post('ruta/eliminar-sesion',[UsuarioController::class,'deletesession'])->name('app.session.deelte');
Route::post('/CambiarPassword', [UsuarioController::class, 'CambiarPassword'])->name('Cambiar.pasword');


//ROUTE PARA EMPRESAS
Route::get('/empresa',[EmpresaController::class,'index'])->name('app.empresa');
Route::post('/Empresas/show', [EmpresaController::class, 'EmpresasAll'])->name('empresas.tabla');
Route::post('/Sucursal/show/{empresa_id}', [EmpresaController::class, 'SucursalAll'])->name('sucursal.tabla');
Route::post('/empresa/guardar', [EmpresaController::class, 'guardarEmpresa'])->name('empresas.crear');
Route::post('/SaveHorario', [EmpresaController::class, 'guardarHorarios'])->name('horarios.guardar');
Route::get('/getHorarioEmpresa/{empresa_id}', [EmpresaController::class, 'getHorariosEmpresa'])->name('horarios.empresa');
Route::post('/obtener-emp',[EmpresaController::class,'getEmpresaEdit'])->name('app.empresa.getEmpresa');
Route::post('/actualizar-empresa', [EmpresaController::class, 'actualizarEmpresa']);
Route::get('/det_jerarquia/check', [EmpresaController::class, 'checkJerarquia'])->name('app.det_jerarquia.check');
//Route::get('/det_emp/check', [EmpresaController::class, 'dataEmp'])->name('app.det_emp.check');
Route::post('/det_jerarquia/save', [EmpresaController::class, 'saveJerarquia'])->name('app.det_jerarquia.save');
Route::post('/save_jerarquia',[EmpresaController::class,'save_jerarquias'])->name('jerarquia.save');
Route::post('/sucursal/guardar', [EmpresaController::class, 'guardarSucursal'])->name('sucursal.crear');
Route::post('/sucursal/edit',[EmpresaController::class,'getSucursalEdit'])->name('app.sucursal.edit');
Route::post('/sucursal/save_edit', [EmpresaController::class, 'guardarEditSucursal'])->name('sucursal.save.edit');
//Routa para obtener las sucursal por empresa
Route::post('obtener-sucursales-por-empresa',[SucursalController::class,'getSucursalesByEmpresa'])->name('empresa.sucursales.obtener')->middleware('auth');

Route::post('/obtenEmpresa', [EmpresaController::class, 'getEmpresas'])->name('empresas.get.api');
Route::post('/empresass/guardar', [EmpresaController::class, 'SavNewEmpresa'])->name('empresas.save.api');
Route::post('/datosUser', [EmpresaController::class, 'obtenerInformacion'])->name('empresas.datos.usuario');



//Routas para empleados
Route::prefix('empleados')->middleware('auth')->group(function(){
    Route::get('/',[EmpleadoController::class,'index'])->name('app.empleados.index');
    Route::post('/dt',[EmpleadoController::class,'listar_empleados'])->name('app.empleados.dt');
    Route::post('/dtDep',[EmpleadoController::class,'listar_dep'])->name('app.dep.dt');
    Route::post('/save',[EmpleadoController::class,'save_empleado'])->name('app.empleados.save');
    Route::post('/obtener-emp-ref',[EmpleadoController::class,'get_empleado_by_id'])->name('app.empleados.getEmpleado');
    Route::post('/eliminar-emp-ref',[EmpleadoController::class,'destroy_empleado'])->name('app.empleados.destroy');
    Route::post('/verificar',[EmpleadoController::class,'verifyEmp'])->name('app.empleado.verify');
    //routas para obtener la cantidad de empleados por area/departamentos
    Route::post('/obtener-empleados-departamentos',[EmpleadoController::class,'getDeptoCantEmp'])->name('app.empleado.departamentos');
    //habilitar y deshabilitar empleado
    Route::post('/habilitar-deshabilitar',[EmpleadoController::class,'toggleEmpleadoStatus'])->name('app.empleado.toggleStatus');
    //obtener datos del empleado por Id ===> columns(id, nombre,genero,edad)
    Route::post('/obtener-info-empleado',[EmpleadoController::class,'getInfoPacienteById'])->name('empleado.info.columns');
    //Obtener jornadas por coloaborador
    Route::post('obtener-jornadas',[JornadaController::class,'getJornadasByEmpresa'])->name('empresa.jornadas');
});

Route::prefix('examenes')->middleware('auth')->group(function(){
    Route::post('/obtener-examenes',[ExamenesCategoriaController::class,'get_examenes'])->name('examen.getExamenes');
    Route::post('/guardar-examen',[ExamenesCategoriaController::class,'save_examen'])->name('examen.save');
    Route::post('/guardar-especialidades',[ExamenesCategoriaController::class,'save_examenesespeciales'])->name('examenEspeciales.save');
    Route::post('/guardar-complementarios',[ExamenesCategoriaController::class,'save_examenescomplementarios'])->name('examenComplementarios.save');
    Route::post('/guardar-categoria',[CategoriaExamenController::class,'save_categoria'])->name('cat.examen.save');
    Route::post('/dt-categoria-examenes',[CategoriaExamenController::class,'dt_cat_examenes'])->name('examenes.dt');
    Route::post('/dt-categoria-examenes_edit',[CategoriaExamenController::class,'edit_cat_examenes'])->name('examenes.dt-edit');
    Route::post('/guardar-examen-perfil',[PerfilExamenController::class,'save_examen_perfil'])->name('examenes.perfil.save');
    Route::post('/udate-examen-perfil',[PerfilExamenController::class,'update_examen_perfil'])->name('examenes.perfil.update');
    Route::get('/perfil/{id}/examenes', [ExamenesCategoriaController::class, 'get_examenes_perfil_edit'])->name('perfil.obtener_examenes');
    Route::post('/obtener-perfiles',[PerfilExamenController::class,'get_perfiles'])->name('examen.perfil.save');
    
    //new routas para examen preingreso
    Route::post('/preingreso-save',[ExamenPreingresoController::class,'save'])->name('examen.preingreso.save');
    Route::post('/posincapacidad-save',[ExamenPreingresoController::class,'saveposincapacidad'])->name('examen.pposincapacidad.save');
    Route::post('/preingreso-get-data',[ExamenPreingresoController::class,'get_data'])->name('examen.preingreso.obtener');
    Route::post('/posinca-get-data',[ExamenPreingresoController::class,'get_datainca'])->name('examen.postinca.obtener');
    Route::post('/preingreso-eliminar-item',[ExamenPreingresoController::class,'deleteItem'])->name('examen.preingreso.deleteItem');
    Route::post('/postincapacidad-eliminar-item',[ExamenPreingresoController::class,'deleteItemPostIncapacidad'])->name('examen.postIncapacidad.deleteItem');
    Route::post('/exa-cat-jornadas',[ExamesJornadas::class,'getExamesCategorias'])->name('cat.exa.jornadas');

});

Route::prefix('jornadas')->middleware('auth')->group(function(){
    Route::post('/save',[JornadaController::class,'save_jornada'])->name('jornada.save');
    Route::post('/laboratorio/save',[JornadaController::class,'save_lab'])->name('jornada.lab.save');
    //new module
    Route::get("/s&so",[JornadaController::class,"index"])->name("jornada.index");
    //guardar jornada opacional
    Route::post('/seguridad-ocupacional/save',[JornadaController::class,'save_seg_ocupacional'])->name('jornada.ocupacional.save');
    Route::post('/obtener-cantidad-jornada-ocupacional',[JornadaController::class,'getCantRiesgoOcupacional'])->name('jornada.ocupacional.cantidad');
    Route::post('/obtener-deptos-jornada',[AreaDepartamentoController::class,'getDeptosParaJornadas'])->name('deptos.for.jorn');
    Route::post('/obtener-datadet-jornada',[JornadaController::class,'getDataDetJornada'])->name('get.data.jornada');
    Route::post('/exa-area-jornada',[JornadaController::class,'getAreaExaColab'])->name('colaboradores.area.examenes');
    Route::post('/regj',[JornadaController::class,'registrarJornada'])->name('jornada.reg');
    //listar jornadas
    Route::post('/listar-jornadas',[JornadaController::class,'listarJornadas'])->name('jornada.listar');
    //detalles de jornada
    Route::post('/listar-jornada-detalle',[JornadaController::class,'listarDetJornada'])->name('jornada.listar.detalle');
    //get_datos jornada
    Route::post('/obtener-datos-jornada',[JornadaController::class,'getDataJornada'])->name('jornada.obtener');
    //updateJornada
    Route::post('/actualiar-jornada',[JornadaController::class,'updateJornada'])->name('jornada.update');
    //obtener examen de colaboradores por riesgo o departamento
    Route::post('/obtener-examenes-area-colaboradores',[JornadaController::class,'getDataEmplRiesgoDepto'])->name('colaboradores.examenes.opcion');
});


Route::prefix('orden')->middleware('auth')->group(function(){
    Route::post('/lab/save',[OrdenLabController::class,'save_orden'])->name('orden.lab.save');
    //orden de examen pdf
    Route::post('/imprimir-examenes',[OrdenLabController::class,'imprimir_orden_pdf'])->name('orden.examenes.pdf');
});
///AGENDA MEDICA
Route::prefix('/agenda')->middleware('auth')->group(function(){
    Route::get('/',[Agenda::class,'index'])->name('app.agenda');
});

Route::prefix('cita')->middleware('auth')->group(function(){
    Route::post('/registrar/cita',[CitaController::class,'save_cita'])->name('cita.public.save');
    //Calendar citados
    Route::post('/calendar/citados',[CitaController::class,'get_citados_calendar'])->name('cita.calendarAll');
    Route::post('/citados',[CitaController::class,'get_citados'])->name('cita.citados.emp');
    Route::post('/nueva/cita',[CitaController::class,'nueva_cita_calendar'])->name('cita.save.calendar');
    Route::post('/obtener-datos-citado',[CitaController::class,'getDataCitado'])->name('cita.data.obtener');
    Route::post('/obtener-cita-por-id',[CitaController::class,'getDataCitaById'])->name('cita.data.obtener.id');
    //pdf de citados
    Route::post('/generar-pdf',[CitaController::class,'generar_pdf'])->name('cita.pdf.generar');
    //anular cita
    Route::post('/anular',[CitaController::class,'anular_cita'])->name('cita.anular');
});

Route::prefix('consulta')->middleware('auth')->group(function(){
    Route::post('/guardar-consulta',[ConsultaController::class,'save_consulta'])->name('consulta.save');
    Route::post('/obtener-consultas',[ConsultaController::class,'getConsultas'])->name('registroMedico.consulta.obtener');
    Route::post('/horarios/cita/disponibles',[CitaController::class,'get_horarios_citas_disp'])->name('cita.horarios');
    Route::post('/registrar-proxima-cita',[CitaController::class,'agendar_proxima_cita'])->name('cita.proxima_cita');
    //delete consulta
    Route::post('/eliminar-consulta',[ConsultaController::class,'deleteConsulta'])->name('consulta.delete');
    Route::post('/obtener-consulta-previa',[ConsultaController::class,'get_consulta_previa'])->name('consulta.obtener_consulta');
    Route::get('/cie10/json',[Cie10Controller::class,'get_json'])->name('cie10.json');
    Route::post('/insertar-cie-10',[Cie10Controller::class,'save_data'])->name('cie10.save');
    Route::post('/cie10/filtrar',[Cie10Controller::class,'get_code_descripcion'])->name('cie10.filtrar');
});


//perfiles y examenes
Route::get('/perfilesExamenes',[PerfilExamenController::class,'index'])->name('app.perfEx');
Route::post('/examenes_esp',[PerfilExamenController::class,'listar_exEspeciales'])->name('especiales.dt');
Route::post('/examenes_comp',[PerfilExamenController::class,'listar_exComplementarias'])->name('complementarios.dt');
//COMPLEMENTARIAS
Route::post('/obtener-ex',[PerfilExamenController::class,'get_examen'])->name('app.getExamenEsp');
Route::post('/actualizar-examen', [PerfilExamenController::class, 'update_examen'])->name('app.updateExamenEsp');
Route::post('/eliminar-examen',[PerfilExamenController::class,'destroy_examen'])->name('app.examen.destroy');
/**
 * ROUTAS PARA DEPARTAMENTOS
*/
Route::prefix('area-departamento')->middleware('auth')->group(function(){
    Route::get('/',[AreaDepartamentoController::class,'index'])->name('area.depto.index');
    Route::post('/save',[AreaDepartamentoController::class,'save'])->name('area.depto.save');
    Route::post('/obtener-departamentos-empleados',[AreaDepartamentoController::class,'getDeptoCantEmp'])->name('area.depto.obtener');
    Route::post('/save-detalle-riesgos',[AreaDepartamentoController::class,'saveDeptoRiesgo'])->name('area.depto.riesgo.save');
    Route::post('/remove-detalle-departamento-riesgos',[AreaDepartamentoController::class,'rmDetDeptopRiesgos'])->name('area.depto.riesgos.remove');
    Route::post('/remove-departamento',[AreaDepartamentoController::class,'removeDepartamento'])->name('area.depto.remove');
    //obtener los cargos por id area
    Route::post('/cargo-area',[AreaDepartamentoController::class,'getCargosArea'])->name('area.cargos.obtener');
});

//ROUTAS PARA RESULTADOS DE EXAMEN
Route::prefix('resultados')->middleware('auth')->group(function(){
    Route::get('/evaluacion-y-atencion',[ResultadosExamenController::class,'index'])->name('resultado.eval.atencion');
    Route::post('/examenes-colaboradores',[ResultadosExamenController::class,'listar_empleados_examen'])->name('result.evaluacion.listar');
    Route::post('/obtener-atencion-resultados',[ResultadosExamenController::class,'listar_atencion_resultados'])->name('result.atencion.listar');
    Route::get('/colaborador/examenes',[ResultadosExamenController::class,'detExamenesPaciente'])->name('result.paciente.examenes');
    Route::post('/obtener-examenes-paciente-jornada',[ResultadosExamenController::class,'getDetExamenesPaciente'])->name('result.examenes.detalle');

    Route::post('/guardar-resultado-examen',[ResultadosExamenController::class,'saveResultExamen'])->name('result.examen.save');
    //obtener resultados segun
    Route::post('/obtener',[ResultadosExamenController::class,'getResultadoByExamen'])->name('result.examen.obtener');
    //obtener examenes resultados por empleado
    Route::post('/obtener-resultados-empleado',[ResultadosExamenController::class,'getExamenesResultByEmp'])->name('result.examenes.obtener');
    
    //pdfs
    Route::post('/imprimir-atencion-por-resultados-pdf',[ResultadosExamenController::class,'impAtencionPDF'])->name('result.atencion.pdf');
    //estado evaluacion colaborador
    Route::post('/colaborador-establecer-evaluacion',[ResultadosExamenController::class,'setEvaluacionEmp'])->name('evaluar.resultados.examenes');
    //guardar imagen de examen
    Route::post('/examen-photo-save',[ResultadoExamenImgController::class,'save'])->name('examen.result.img.save');
    Route::post('/resultado-examenes-images-obtenre',[ResultadoExamenImgController::class,'get_data'])->name('examen.result.img.obtener');
    Route::post('/preingreso-eliminar-item',[ResultadoExamenImgController::class,'deleteItem'])->name('examen.result.img.deleteItem');
    //Resultados optometrias
    Route::get('/optometria',[ResultadoOptometriaController::class,'index'])->name('opto.index');
    Route::post('/listar-colaboradores-optometria',[ResultadoOptometriaController::class,'listar_empleados_examen'])->name('opto.result.listar');
    Route::post('consulta/optometria',[ResultadoOptometriaController::class,'resultConsultOpto'])->name('examen.opto.result');
    Route::post('/guardar-eval-opto',[ResultadoOptometriaController::class,'saveEvalOpto'])->name('examen.eval.opto.save');
});

Route::prefix('laboratorio-resultados')->middleware('auth')->group(function(){
    Route::get('/ordenes',[LaboratorioResultadoController::class,'index_listado'])->name('lab.resultado.index');
    Route::post('/listar-orden-examenes',[LaboratorioResultadoController::class,'listar_orden_examenes'])->name('lab.orden.listar');
    Route::get('/ingresar',[LaboratorioResultadoController::class,'detExamenesPaciente'])->name('lab.examenes.ingresar');
    //listar examenes de orden
    Route::post('/obtener-detalle-orden',[LaboratorioResultadoController::class,'getDetalleOrden'])->name('lab.orden.detalle');
    //save resultados
    Route::post('/registrar-resultado',[LaboratorioResultadoController::class,'saveResultado'])->name('lab.resultado.save');
    //obtener examen con resultado
    Route::post('/obtener-examenes-resultado',[LaboratorioResultadoController::class,'getExaResultado'])->name('lab.examen-resultado');
});

//RUTAS PARA REPORTES
Route::get('reportes',[ReportesController::class,'index'])->name('reportes.index');
Route::get('reportesVisuales',[ReportesController::class,'indexVisual'])->name('reportes.visuales.index');
Route::post('/datos-grafica_repor', [ReportesController::class, 'obtenerDatosGrafica'])->name('grafic.reporte');

Route::post('/datos-grafica_repor_vis', [ReportesController::class, 'obtenerDatosGraficaVisual'])->name('grafic.reporte.vis');

Route::post('/datos-grafica_analisis_res', [ReportesController::class, 'obtenerDatosAnalisisRes'])->name('analisis.reporte.res');

Route::post('/Detalles_NormaAlterados', [ReportesController::class, 'obtenerDetallesRes'])->name('dat.reporte.vis');

Route::post('/Detalles_NormaAlterados_Analisis', [ReportesController::class, 'obtenerDetallesAnalisis'])->name('dat.reporte.analisis');


Route::post('/datos-tabla', [ReportesController::class, 'obtenerDatosTabla'])->name('tabla.reporte');
Route::post('/ver-detalles-empleados', [ReportesController::class, 'obtenerDatosEmpleados'])->name('empleados.reporte');

//Routas para clientes
Route::prefix('pacientes')->middleware('auth')->group(function(){
    Route::get('/',[ClientesController::class,'index'])->name('cliente.index');
    //guardar la orden
    Route::post('guarda-orden',[OrdenLabController::class,'save_orden_cliente'])->name('cliente.save.orden');
    Route::post('listar',[ClientesController::class,'listar_clientes'])->name('cliente.listar');
    Route::post('boleta-examenes',[OrdenLabController::class,'boleta_examenes_cliente'])->name('cliente.boleta.examenes');
});