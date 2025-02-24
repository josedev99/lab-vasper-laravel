@extends('layouts.app')

@section('title', 'Resultado optometria - Clinica empresarial')

@section('section-title')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Analisis y resultados</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('Resultado.modal.resultado_optometria')

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
        .input-group-title1{
            font-weight: 600
        }
        #btn-reload-info{
            position: absolute;
            right: 6px;
            top: 1px;
            border: none;
            font-size: 16px;
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
                        <button title="Recargar información" class="btn btn-outline-info btn-sm" id="btn-reload-info"><i class="bi bi-arrow-clockwise"></i></button>
                    </ul>
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active" id="tab_evaluacion" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-3 mb-2">
                                    <div class="content-input">
                                        <input name="filtrar_fecha" id="filtrar_fecha" type="text"
                                            class="custom-input material" value="" placeholder=" ">
                                        <label class="input-label" for="filtrar_fecha" title="fecha">Filtrar por fecha</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 mb-2">
                                    <div class="input-group">
                                        <label for="filtrar_estado" class="input-group-title1">Filtrar por resultado</label>
                                        <select name="filtrar_estado" id="filtrar_estado" class="form-select border-radius" data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar estado">
                                            <option value="Resumen">Resumen</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Alterado">Alterado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mb-2">
                                    <div class="input-group">
                                        <label for="filtrar_agencia" class="input-group-title1">Filtrar por agencia</label>
                                        <select name="filtrar_agencia" id="filtrar_agencia" class="form-select border-radius" data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar estado">
                                            <option value="Resumen">Resumen</option>
                                            @foreach($data_sucursales as $sucursal)
                                                <option value="{{ $sucursal['id'] }}">{{ ucwords(strtolower($sucursal['nombre'])) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="dt_resultado_optometria" width="100%" data-order='[[ 0, "desc" ]]'
                                class="table-hover table-striped">
                                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                        <th style="text-align:center">#</th>
                                        <th style="text-align:center">FECHA ATENCIÓN</th>
                                        <th style="text-align:center">COD. EMP.</th>
                                        <th style="text-align:center">COLABORADOR</th>
                                        <th style="text-align:center">TELÉFONO</th>
                                        <th style="text-align:center">DEPTO/AREA</th>
                                        <th style="text-align:center">RESULTADO</th>
                                        <th style="text-align:center">SERVICIO ADQUIRIDO</th>
                                        <th style="text-align:center">PLANTA/AGENCIA</th>
                                        <th style="text-align:center">EXPEDIENTE</th>
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
    <script src="{{ versioned_asset('assets/js/resultados/optometria.js') }}"></script>
    <script>
        $("#filtrar_estado").selectize();
        $("#filtrar_agencia").selectize();
    </script>
@endpush