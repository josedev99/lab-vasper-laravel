@extends('layouts.app')

@section('title', 'Empresa')
@section('section-title')
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Empresa</li>
    </ol>
    </nav>
@endsection
@section('content')
@include('empresa.modals.modalCrearEmpresa')
@include('empresa.modals.modalCrearHorario')
@include('empresa.modals.modalActualizarEmpresa')
@include('Empleado.modal.area_departamento')
@include('Empleado.modal.area_departamentoCrear')
@include('empresa.modals.modalCrearDetJerarquia')
@include('empresa.modals.modalCrearSucursal')
@include('empresa.modals.modalCrearSuc')
@include('empresa.modals.modalEditSuc')

@include('empresa.modals.det_colaboradores_sincronizacion') {{-- New modal::para mostrar colaboradores sincronizados --}}
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
    .swal2-html-container{
        font-size: 1rem !important;
    }
    .btn-close-span{
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
    .badge{
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 11px;
        background: #e7f3e9 !important;
        color: #181515;
    }
    .bg-custom-color{
        background: #3788d8;
        color: #fff;
    }
    .left-th{
        border-top-left-radius: 4px;
        border-right: 1px solid #dadce0;
    }
    .rigth-th{
        border-top-right-radius: 4px;
        border-left: 1px solid #dadce0;
    }
    .custom-td{
        padding: 4px 4px;
        border-bottom: 1px solid #dadce0;
        border-right: 1px solid #dadce0;
    }
    .custom-badge{
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 13px;
        color: #181515;
    }
</style>
<div class="content-wrapper">
    <div class="col-lg-12 col-sm-12">
        <div class="card info-card sales-card">
            @if(Auth::check() && in_array('VER REPORTES', Session::get('userPermissions', [])))
            <div class="card-header p-1">
                <button class="btn btn-outline-secondary btn-sm" onclick="CrearEmpresa()"><i class="bi bi-person-add"></i> Nueva Empresa</button>
            </div>
            @endif
            <div class="card-body p-1">
                <table class="table-responsive table-hover table-bordered table1 table-custom table-td" 
                style="width: 100%"  id="empresas" data-order='[[ 0, "desc" ]]'>
                <thead style="color:white;min-height:10px;border-radius: 2px;"
                    class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">Id</th>
                        <th style="text-align:center">Nombre</th>
                        <th style="text-align:center">Giro</th>
                        <th style="text-align:center">Telefono</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">

                </tbody>
            </table>
            </div>
        </div>
    </div><!-- End Left side columns -->
</div>

<script src="{{ versioned_asset('assets/js/empresa/empresa.js') }}"></script>
<script src="{{ versioned_asset('assets/js/helpers/validation.js') }}"></script>

@endsection



