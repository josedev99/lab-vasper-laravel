@extends('layouts.app')

@section('title', 'Empleados - App')

@section('section-title')
<h1 class="fs-5">Registro médico</h1>
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Registro médico</li>
    </ol>
</nav>
@endsection


@section('content')

@include('Empleado.modal.form_empleado')
@include('RegistroMedico.modal.registrar_consulta')
@include('Incapacidades.modal.agregar_motivo')

<div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12 col-sm-12">
        <div class="card info-card sales-card">
            <div class="card-header p-2">
                <div class="row">
                    <div class="col-sm-9 d-flex justify-content-start align-items-center"> 
                        <div class="radio icheck-turquoise d-inline">
                            <input type="radio" id="preparados" name="opcionConsulta" value="preparados">
                            <label for="preparados">Preparados</label>
                        </div>                     
                        <div class="radio icheck-turquoise d-inline mx-2">
                            <input type="radio" id="citas" name="opcionConsulta" value="citas">
                            <label for="citas">Citas</label>
                        </div>
                        <div class="radio icheck-turquoise d-inline mx-2">
                            <input type="radio" id="colaboradores" name="opcionConsulta" value="colaboradores">
                            <label for="colaboradores">Subsecuentes</label>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3 d-flex justify-content-center align-items-center" id="component-date-filter">
                            <div class="content-input">
                                <input id="fecha_filtro" name="fecha_filtro" type="text"
                                    class="custom-input material" value="{{ $fecha_actual }}" placeholder=" ">
                                <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                <label class="input-label" for="fecha_filtro">Fecha actual</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 d-flex justify-content-end align-items-center">
                        <button class="btn btn-outline-success btn-sm btn_new_empleado"><i class="bi bi-person-add"></i> Nuevo colaborador</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-1">
                <table id="dt_listados_emp" width="100%" data-order='[[ 0, "desc" ]]'
                    class="table-hover table-striped">
                    <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                        <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">FECHA</th>
                            <th style="text-align:center">HORA</th>
                            <th style="text-align:center">CÓDIGO EMP.</th>
                            <th style="text-align:center">NOMBRE</th>
                            <th style="text-align:center">TELÉFONO</th>
                            <th style="text-align:center">SUCURSAL</th>
                            <th style="text-align:center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- End Left side columns -->

</div>

@endsection

@push('js')

<script src="{{ versioned_asset('assets/js/empleado/empleado.js') }}"></script>
<script src="{{ versioned_asset('assets/js/registro/registro_medico.js') }}"></script>
<script src="{{ versioned_asset('assets/js/registro/consulta.js') }}"></script>
<script src="{{ versioned_asset('assets/js/registro/historial_consultas.js') }}"></script>
<script src="{{ versioned_asset('assets/js/registro/valid_signos_medidas.js') }}"></script>
<script src="{{ versioned_asset('assets/js/helpers/validation.js') }}"></script>

<script>
    validateNumberTel("telefono");
</script>
@endpush