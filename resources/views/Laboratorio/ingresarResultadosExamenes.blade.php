@extends('layouts.app')

@section('title', 'Ingresar resultados - Clinica empresarial')

@section('section-title')
<style>
    .pagetitle h1{
        font-size: 16px !important;
    }
</style>
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="btn btn-outline-info btn-sm" href="{{ route('lab.resultado.index') }}" style="font-size: 12px"><i class="bi bi-arrow-left-circle"></i> REGRESAR</a></a></li>
        <li class="breadcrumb-item"><i class="bi bi-journal-plus"></i> <span id="title_content_page"></span></li>
        <li class="breadcrumb-item active"><i class="bi bi-person-check"></i> COLABORADOR A: <span id="display_nombre_empleado"></span></li>
    </ol>
</nav>
@endsection

@section('content')

@include('Laboratorio.modal.form_examen_standar')
@include('Resultado.modal.agregar_examen_resultado_image')
@include('Laboratorio.modal.form_examen_g_heces')
@include('Laboratorio.modal.form_examen_g_orina')
@include('Laboratorio.modal.form_hemograma')
@include('Laboratorio.modal.form_examen_bacteriologia')
@include('Laboratorio.modal.form_examen_rpr')
<style>
    .badge{
        font-size: 10px !important;
    }
    .bg-success{
        background:#63d19e !important;color:#ffffff !important
    }
    .bg-warning{
        background: #ffe79e !important;color:#020202 !important
    }
    .bg-danger{
        background:#d5858d !important
    }
</style>

<div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12 col-sm-12">
        <div class="card info-card sales-card">
            <div class="card-body p-1">
                <table id="dt_examenes_empleado" width="100%" data-order='[[ 0, "desc" ]]'
                    class="table-hover table-striped">
                    <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                        <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">FECHA & HORA</th>
                            <th style="text-align:center">CODIGO</th>
                            <th style="text-align:center">COLABORADOR</th>
                            <th style="text-align:center">SUCURSAL</th>
                            <th style="text-align:center">CATEGORIA</th>
                            <th style="text-align:center">ESTADO</th>
                            <th style="text-align:center">INGRESAR</th>
                            {{-- <th style="text-align:center">IMAGEN</th> --}}
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
<script src="{{ versioned_asset('assets/js/resultados/detalle_orden_lab.js') }}"></script>
<script defer src="{{ versioned_asset('assets/js/helpers/nextInput.js') }}"></script>
@endpush