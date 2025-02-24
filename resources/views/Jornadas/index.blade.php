@extends('layouts.app')

@section('title', 'Jornadas - App')

@section('section-title')
{{-- <h1 class="fs-5">Jornadas</h1>
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Jornadas</li>
    </ol>
</nav> --}}
@endsection

@section('content')

@include('Jornadas.modal.crear_jornada')
@include('Jornadas.modal.selected_factor_riesgo')
@include('Jornadas.modal.show_listado_jornadas')
@include('Jornadas.modal.areasjornadas')
@include('Jornadas.modal.listar_detalle_jornada')
@include('Jornadas.modal.editar_jornada')
@include('Jornadas.modal.optometria')

<div class="content-wrapper">
    <div class="card mb-0" style="border-top:4px solid #012970">
        <div class="card-header p-1 d-flex justify-content-between align-items-center">

            <h5 class="card-title p-0 m-0" style="font-size: 15px">JORNADAS DE SEGURIDAD Y SALUD EN EL TRABAJO</h5>
            <button class="btn btn-outline-info btn-sm" id="btn_new_jornada"><i class="bi bi-clipboard-plus"></i>Crear jornada</button>

        </div>
        <div class="card-body p-2">
            <div id="calendar-jornada-ocupacional"></div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ versioned_asset('assets/js/jornada/jornada.js') }}"></script>
<script>
  $("#exa_jornadas").selectize()
</script>
@endpush