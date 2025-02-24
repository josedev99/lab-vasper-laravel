@extends('layouts.app')

@section('title', 'Ingresar resultados - Clinica empresarial')

@section('section-title')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Laboratorio - ingreso</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('Resultado.modal.show_resultados')
@include('Empleado.modal.orden_examen')
@include('Orden.modal.jornada_orden')
    <style>
        .badge {
            font-size: 10px !important;
        }

        .bg-success {
            background: #63d19e !important;
            color: #ffffff !important
        }

        .bg-warning {
            background: #ffe79e !important;
            color: #020202 !important
        }

        .bg-danger {
            background: #d5858d !important
        }
        .size-font-tabs{
            font-size: 14px;
        }
        .button-float-pdf{
            position: absolute;
            right: 4px;
        }
    </style>

    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12 col-sm-12">
            <div class="card info-card sales-card">
                <div class="card-body p-1">
                    <ul class="nav nav-tabs" id="borderedTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-3 size-font-tabs active" id="btnTabEvaluacion" data-bs-toggle="tab"
                                data-bs-target="#tab_evaluacion" type="button" role="tab" aria-controls="home"
                                aria-selected="true"><i class="bi bi-journal-text"></i> Ingresar resultados</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active" id="tab_evaluacion" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                                    <div class="input-group">
                                        <label for="empresas_select" class="input-group-title1">Empresas</label>
                                        <select name="empresas_select" id="empresas_select" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar empresas">
                                            <option value="">Selecccionar</option>
                                            @foreach ($empresas as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="filtro_rango_fechas" id="filtro_rango_fechas" type="text"
                                            class="custom-input material" value="" placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="filtro_rango_fechas">Filtrar por fecha</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                                    <div class="input-group">
                                        <label for="jornada_lab" class="input-group-title1">Jornadas</label>
                                        <select name="jornada_lab" id="jornada_lab" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar jornadas">
                                            <option value="">Selecccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="dt_evaluacion_resultados" width="100%" data-order='[[ 0, "desc" ]]'
                                class="table-hover table-striped">
                                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                        <th style="text-align:center">#</th>
                                        <th style="text-align:center">CODIGO</th>
                                        <th style="text-align:center">FECHA JORNADA</th>
                                        <th style="text-align:center">COLABORADOR</th>
                                        <th style="text-align:center">TELÉFONO</th>
                                        <th style="text-align:center">DEPTO/AREA</th>
                                        <th style="text-align:center">ESTADO</th>
                                        <th style="text-align:center">SUCURSAL</th>
                                        <th style="text-align:center">INGRESAR</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

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
    <script src="{{ versioned_asset('assets/js/resultados/orden_lab.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/categoria_examen/examenes.js') }}"></script>
    <script>
        $("#jornada_lab").selectize();
    </script>
@endpush
