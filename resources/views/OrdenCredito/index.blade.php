@extends('layouts.app')
@section('title', 'Detalle orden - Clinica empresarial')
@section('section-title')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Detalle de créditos y abonos</li>
        </ol>
    </nav>
@endsection
@section('content')
@include('OrdenCredito.modal.detalle_credito')
@include('OrdenCredito.modal.detalle_abonos')


    <div class="card-header p-0 m-0">

        <div class="content-wrapper">
            <div class="card" style="border-top:4px solid #012970">

                <div class="card">
                    <div class="card-body px-2 py-1">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" option="credito" data-tipo="c-planilla" data-bs-toggle="tab" aria-selected="true" data-bs-target="#credito-planilla"
                                    style="padding: 3px 12px;"><i class="bi bi-cash"></i> Descuento en planilla</button>
                            </li>
                        </ul>
                        @include('OrdenCredito.helpers._opcionesFiltro')
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade credito-planilla pt-3 active show" id="credito-planilla">
                                <table id="tabla_credit_planilla" width="100%"
                                    style="text-align: center;text-align:center ; padding:20px;"
                                    data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                    <thead style="color:white;min-height:10px;border-radius: 2px;"
                                        class="bg-dark">
                                        <tr
                                            style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                            <th style="text-align:center">#</th>
                                            <th style="text-align:center">N. Venta</th>
                                            <th style="text-align:center">Titular</th>
                                            <th style="text-align:center">Empresa</th>
                                            <th style="text-align:center">Plazo</th>
                                            <th style="text-align:center">Monto</th>
                                            <th style="text-align:center">Cuota</th>
                                            <th style="text-align:center">Saldo</th>
                                            <th style="text-align:center">Credit.</th>
                                            <th style="text-align:center">Hist. Abonos</th>
                                            <th style="text-align:center">Info.</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;"></tbody>
                                    <tfoot style="font-size: 12px;">
                                        <tr>
                                            <th colspan="5" style="text-align:right; font-weight:bold"></th>
                                            <th style="text-align:center"></th> <!-- Aquí irá el total de Monto -->
                                            <th style="text-align:center"></th> <!-- Aquí irá el total de Saldo -->
                                            <th style="text-align:center"></th> <!-- Aquí irá el total de Saldo -->
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ versioned_asset('assets/js/ordenCredito/detalle_orden_credito.js') }}"></script>
@endpush