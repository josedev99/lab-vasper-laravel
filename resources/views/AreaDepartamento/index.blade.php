@extends('layouts.app')

@section('title', 'Area/Departamentos | clinica')

@section('section-title')
<!--   <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Agenda </li>
    </ol>
    </nav> -->
@endsection
@section('content')

@include('Empleado.modal.area_departamentoCrear')
@include('AreaDepartamento.modal.selected_factor_riesgo')
@include('empresa.modals.modalCrearDetJerarquia')

<style>
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        font-size: 13px;
        margin-bottom: 12px;
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 15px;
        color: #012970;
    }

    .fc-event {
        cursor: pointer;
        /* Cambiar el cursor a puntero (manito) */
    }

    .swal2-html-container {
        font-size: 1rem !important;
    }

    .btn-close-span {
        position: absolute;
        top: -12px;
        right: -8px;
        font-size: 15px;
        color: #020202;
        cursor: pointer;
        background: #fff;
        border-radius: 50%;
        padding: 1px;
    }

    .badge {
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 11px;
        background: #e7f3e9 !important;
        color: #181515;
    }

    .bg-custom-color {
        background: #3788d8;
        color: #fff;
    }

    .left-th {
        border-top-left-radius: 4px;
    }

    .rigth-th {
        border-top-right-radius: 4px;
    }
    
    .custom-td {
        padding: 2px;
        border-bottom: 1px solid #dadce0;
        border-right: 1px solid #dadce0;
    }

    .custom-badge {
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 13px;
        color: #181515;
    }
</style>
<div class="content-wrapper">
    <div class="card mb-0" style="border-top:4px solid #012970">
        <div class="card-header p-1 d-flex justify-content-between align-items-center">
            <h5 class="card-title p-0 m-0">AREA/DEPARTAMENTOS</h5>
        </div>
        <div class="card-body p-2">
            <div id="component_perfil">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card m-0 p-0" style="border: 1px solid #dadce0;">

                        <div class="card-header p-1 d-flex ">
                            <button class="btn btn-success btn-sm " onclick="btn_jerarquia()" title="Guarda cambios realizados en el perfil"> 
                                <i class="bi bi-file-earmark-plus"></i> Agregar departamento</button>
                        </div>

                        {{-- Validaciones --}}
                        <table style="width: 100%;border-collapse:collapse">
                            <thead style="font-size: 13px;text-align: center;">
                                <tr>
                                    <th class="bg-custom-color" style="width: 10%">DEPARTAMENTOS </th>
                                    <th class="bg-custom-color" style="width: 10%">AREA</th>
                                    <th class="bg-custom-color" style="width: 15%">CARGO</th>
                                    <th class="bg-custom-color" style="width: 20%">RIESGOS</th>
                                    <th class="bg-custom-color" style="width: 45%">EXAMENES</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 13px;text-align: center;" id="component_body_departamentos">                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ versioned_asset('assets/js/area_departamento/area_departamento.js') }}"></script>
<script src="{{ versioned_asset('assets/js/empresa.js') }}"></script>
<script>
    let factor_riesgo_selectize = $("#select_factor_riesgo").selectize()[0].selectize;
    factor_riesgo_selectize.on('change', function(value) {
        addItemRiesgo();
        factor_riesgo_selectize.clear();
    });
</script>
@endpush