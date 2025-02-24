@extends('layouts.app')

@section('title', 'Agenda | clinica')

@section('section-title')
<!--   <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Agenda </li>
    </ol>
    </nav> -->
@endsection
@section('content')

@include('agenda.modal.citas_agendadas')
@include('agenda.modal.agendar_cita')
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
</style>
<div class="content-wrapper">
    <div class="card mb-0" style="border-top:4px solid #012970">
        <div class="card-header p-1 d-flex justify-content-between align-items-center">
            <h5 class="card-title p-0 m-0">AGENDA MEDICA </h5>
            <button class="btn btn-outline-info btn-sm btnShowCitasAll" title="Ver todas las citas"> <i class="bi bi-calendar-check"></i> Citas</button>
        </div>
        <div class="card-body p-2">
            <div id="calendar-agenda-cita"></div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ versioned_asset('assets/js/cita/calendar_cita.js') }}"></script>
<script src="{{ versioned_asset('assets/js/helpers/validation.js') }}"></script>
<script>
    validateNumberTel("telefono");
</script>
@endpush