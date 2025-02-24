@extends('layouts.app')

@section('title', 'Pacientes - Lab Vasper')

@section('section-title')
<h1 class="fs-5">Gestionar pacientes</h1>
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Pacientes</li>
    </ol>
</nav>
@endsection

@section('content')

@include('Cliente.modal.form_cliente')
@include('Cliente.modal.orden_examen')
@include('Categoria_examen.modal.registrar_categoria')
<div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12 col-sm-12">
        <div class="card info-card sales-card">
            <div class="card-header p-1">
                <button class="btn btn-outline-success btn-sm" id="btnModalCliente"><i class="bi bi-person-add"></i> Nuevo cliente</button>
            </div>
            <div class="card-body p-1">
                <table id="dt_listado_clientes" width="100%" data-order='[[ 0, "desc" ]]'
                    class="table-hover table-striped">
                    <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                        <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">COD. ORDEN</th>
                            <th style="text-align:center">NOMBRE</th>
                            <th style="text-align:center">GENERO</th>
                            <th style="text-align:center">EDAD</th>
                            <th style="text-align:center">TELÉFONO</th>
                            <th style="text-align:center">DIRECCIÓN</th>
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
<script src="{{ versioned_asset('assets/js/empleado/cliente.js') }}"></script>
<script src="{{ versioned_asset('assets/js/categoria_examen/examenes.js') }}"></script>
<script src="{{ versioned_asset('assets/js/helpers/validation.js') }}"></script>
<script>
    validateNumberTel("telefono");
</script>
@endpush