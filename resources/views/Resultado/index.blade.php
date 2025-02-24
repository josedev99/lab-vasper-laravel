@extends('layouts.app')

@section('title', 'Evaluación y atención - Clinica empresarial')

@section('section-title')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Analisis y resultados</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('Resultado.modal.show_resultados')
@include('Resultado.modal.ingresar_resultado')

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
                                aria-selected="true"><i class="bi bi-journal-text"></i> Evaluación de resultados</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-3 size-font-tabs" id="btnTabAtencion" data-bs-toggle="tab" data-bs-target="#tab_atencion"
                                type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="bi bi-journal-check"></i> Atención por resultados</button>
                        </li>
                        <li class="button-float-pdf" id="component_button_pdf" style="display: none">
                            <button type="button" id="btn_download_pdf" class="btn btn-outline-danger btn-sm" style="padding: 2px 10px;">Descargar <i class="bi bi-filetype-pdf"></i></button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active" id="tab_evaluacion" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="input-group">
                                        <label for="jornada_evaluacion" class="input-group-title1">Jornadas</label>
                                        <select name="jornada_evaluacion" id="jornada_evaluacion" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="">Selecccionar</option>
                                            @foreach ($jornadas as $jornada)
                                                <option value="{{ $jornada['id'] }}">
                                                    {{ $jornada['fecha'] . ' / ' . $jornada['nombre'] }}</option>
                                            @endforeach
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
                                        <th style="text-align:center">COLABORADOR</th>
                                        <th style="text-align:center">TELÉFONO</th>
                                        <th style="text-align:center">DEPTO/AREA</th>
                                        <th style="text-align:center">EVALUACIÓN</th>
                                        <th style="text-align:center">SUCURSAL</th>
                                        <th style="text-align:center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="tab_atencion" role="tabpanel">
                            <div class="row d-flex justify-content-between">
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="input-group">
                                        <label for="jornada_atencion" class="input-group-title1">Jornadas</label>
                                        <select name="jornada_atencion" id="jornada_atencion" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="">Selecccionar</option>
                                            @foreach ($jornadas as $jornada)
                                                <option value="{{ $jornada['id'] }}">
                                                    {{ $jornada['fecha'] . ' / ' . $jornada['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <label for="resultado_select" class="input-group-title1"> Resultados: </label>
                                        <select name="resultado_select" id="resultado_select" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar resultados">
                                            <option value="">Resumen</option>
                                            <option value="3">3 resultados alterados</option>
                                            <option value="2">2 resultados alterados</option>
                                            <option value="1">1 resultados alterados</option>
                                            <option value="Normales">Normales</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="dt_atencion_resultados" width="100%" data-order='[[ 0, "desc" ]]'
                                class="table-hover table-striped">
                                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                        <th style="text-align:center">#</th>
                                        <th style="text-align:center">CODIGO</th>
                                        <th style="text-align:center">COLABORADOR</th>
                                        <th style="text-align:center">TELÉFONO</th>
                                        <th style="text-align:center">DEPTO/AREA</th>
                                        <th style="text-align:center">EVALUACIÓN</th>
                                        <th style="text-align:center">SUCURSAL</th>
                                        <th style="text-align:center">ACCIONES</th>
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
    <script src="{{ versioned_asset('assets/js/resultados/evaluacion_atencion.js') }}"></script>
    <script>
        $("#jornada_evaluacion").selectize();
        $("#jornada_atencion").selectize();
    </script>
@endpush