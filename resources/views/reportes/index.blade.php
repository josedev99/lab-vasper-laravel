@extends('layouts.app')

@section('title', 'Reportes - Clinica empresarial')

@section('section-title')
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Jornadas</li>
    </ol>
</nav>
@endsection
@section('content')
@include('reportes.modal.det_reporte')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Reportes Laboratorio</h5>
                <div class="card">
                    <div class="card-body">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="jornadas-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-jornadas" type="button" role="tab"
                                    aria-controls="jornadas" aria-selected="true">Jornadas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-home" type="button" role="tab" aria-controls="home"
                                    aria-selected="false">General</button>
                            </li>
                    {{--         <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-profile" type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Agencia/planta</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-contact" type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Grupo etario</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="genero-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-genero" type="button" role="tab" aria-controls="genero"
                                    aria-selected="false">Género</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="afectaciones-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-afectaciones" type="button" role="tab"
                                    aria-controls="afectaciones" aria-selected="false">Afectaciones alteradas</button>
                            </li> --}}
                        </ul>
                        <div class="tab-content pt-2" id="borderedTabContent">
                            <div class="tab-pane fade show active" id="bordered-jornadas" role="tabpanel"
                                aria-labelledby="jornadas-tab">
                                <div class="col-sm-12 col-md-12 col-lg-3 mb-2 p-2" >
                                    <div class="input-group mb-2">
                                        <label for="selecc_jornada" class="input-group-title1">Jornada </label>
                                        <select name="selecc_jornada" id="selecc_jornada" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom" title="Seleccionar jornada"
                                            onchange="enviarSeleccion()">
                                            <option value="">Selecccionar</option>
                                            @foreach($jornadas as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <canvas id="jornadasGraf"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <th colspan="3" style="background-color: rgb(186, 234, 253)">
                                                        Examenes Normales</th>
                                                </tr>
                                                <tr>
                                                    <th>Examen</th>
                                                    <th>Normales</th>
                                                    <th>Detalles</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaResumenNormales" class="text-center">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <th colspan="3" style="background-color: rgb(186, 234, 253)">
                                                        Examenes Alterados</th>
                                                </tr>
                                                <tr>
                                                    <th>Examen</th>
                                                    <th>Alterados</th>
                                                    <th>Detalles</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaResumenAlterados" class="text-center">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">

                                <div class="row">

                                    <div class="col-sm-12 col-md-12 col-lg-2 mb-2 p-2">
                                        <div class="input-group mb-2">
                                            <label for="range_calendar" class="input-group-title1">Periodo de Jornada</label>
                                            <input type="text" id="range_calendar" name="range_calendar" 
                                                   class="form-control border-radius" 
                                                   placeholder="Seleccionar rango de fechas" 
                                                   data-toggle="tooltip" data-placement="bottom" 
                                                   title="Seleccionar periodo de jornada">
                                            <button class="btn btn-primary" type="button" id="open_calendar">
                                                <i class="bi bi-calendar-range"></i>                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-12 col-lg-2 mb-2 p-2">
                                        <div class="input-group mb-2">
                                            <label for="selecc_jornada" class="input-group-title1">Agencia/Planta </label>
                                            <select name="selecc_jornada" id="selecc_jornada"
                                                class="form-select border-radius" data-toggle="tooltip"
                                                data-placement="bottom" title="Seleccionar jornada"
                                                onchange="enviarSeleccion()">
                                                <option value="">Selecccionar</option>
                                                @foreach($agencias as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-2 mb-2 p-2">
                                        <div class="input-group mb-2">
                                            <label for="selecc_jornada" class="input-group-title1">Grupo Etario </label>
                                            <select name="selecc_jornada" id="selecc_jornada"
                                                class="form-select border-radius" data-toggle="tooltip"
                                                data-placement="bottom" title="Seleccionar jornada"
                                                onchange="enviarSeleccion()">
                                                <option value="">Selecccionar</option>
                                                <option value="18-20">18 a 20 Años</option>
                                                <option value="21-23">21 a 23 Años</option>
                                                <option value="24-26">24 a 26 Años</option>
                                                <option value="27-29">27 a 29 Años</option>
                                                <option value="30-32">30 a 32 Años</option>
                                                <option value="33-35">33 a 35 Años</option>
                                                <option value="36-38">36 a 38 Años</option>
                                                <option value="39-41">39 a 41 Años</option>
                                                <option value="42-44">42 a 44 Años</option>s
                                                <option value="45-47">45 a 47 Años</option>
                                                <option value="48-50">48 a 50 Años</option>
                                                <option value="51-53">51 a 53 Años</option>
                                                <option value="54-56">54 a 56 Años</option>
                                                <option value="57-59">57 a 59 Años</option>
                                                <option value="60-62">60 a 62 Años</option>
                                                <option value="63-65">63 a 65 Años</option>
                                                <option value="66-68">66 a 68 Años</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-2 mb-2 p-2">
                                        <div class="input-group mb-2">
                                            <label for="selecc_jornada" class="input-group-title1">Genero </label>
                                            <select name="selecc_jornada" id="selecc_jornada"
                                                class="form-select border-radius" data-toggle="tooltip"
                                                data-placement="bottom" title="Seleccionar jornada"
                                                onchange="enviarSeleccion()">
                                                <option value="">Selecccionar</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-2 mb-2 p-2">
                                        <div class="input-group mb-2">
                                            <label for="selecc_jornada" class="input-group-title1">Afectaciones Alteradas </label>
                                            <select name="selecc_jornada" id="selecc_jornada"
                                                class="form-select border-radius" data-toggle="tooltip"
                                                data-placement="bottom" title="Seleccionar jornada"
                                                onchange="enviarSeleccion()">
                                                <option value="">Selecccionar</option>
                                                @foreach($jornadas as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bordered-profile" role="tabpanel"
                                aria-labelledby="profile-tab">
                                Nesciunt totam et. Consequuntur magnam aliquid eos nulla dolor iure eos quia.
                                Accusantium distinctio omnis et atque fugiat. Itaque doloremque aliquid sint quasi quia
                                distinctio similique. Voluptate nihil recusandae mollitia dolores. Ut laboriosam
                                voluptatum dicta.
                            </div>
                            <div class="tab-pane fade" id="bordered-contact" role="tabpanel"
                                aria-labelledby="contact-tab">
                                Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque.
                                Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit
                                molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
                            </div>
                            <div class="tab-pane fade" id="bordered-genero" role="tabpanel"
                                aria-labelledby="genero-tab">
                                Contenido para Género.
                            </div>
                            <div class="tab-pane fade" id="bordered-afectaciones" role="tabpanel"
                                aria-labelledby="afectaciones-tab">
                                Contenido para Afectaciones alteradas.
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><!-- End Left side columns -->

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="{{ versioned_asset('assets/js/resultados/reportes.js') }}"></script>
@endpush


