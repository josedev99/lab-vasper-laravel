<style>
    #tamModal_abonosg {
        max-width: 150% !important;
    }

    #head_abonosg {
        background-color: #034f84;
        color: white;
        text-align: center;
    }

    .table1 {
        width: 100%;
        border-collapse: collapse;
    }

    .table1 th {
        text-align: center;
        border: 1px solid #034f84;
        font-size: 13px;
    }

    .table1 td {
        padding: 5px;
    }

    .table1 tr:last-child td {
        border-bottom: none;
    }
    .table-responsive {
    overflow-x: auto;
}
    .centered-cell {
    text-align: center;
    
}
    /**RESPONSIVE MODAL ***/
    @media screen and (min-width:1024px) {
        .modal-responsive {
            max-width: 90%;
        }

        #ui-datepicker-div {
            width: 350px !important;
        }
    }

    @media screen and (min-width:767px) and (max-width:1023px) {
        .modal-responsive {
            max-width: 100%;
        }

        #ui-datepicker-div {
            width: 280px !important;
        }
    }

    @media screen and (max-width:767px) {
        .modal-responsive {
            max-width: 100%;
        }

        #ui-datepicker-div {
            width: 280px !important;
        }
    }
</style><!-- The Modal -->
<div class="modal fade" id="historial_ab" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-responsive">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header py-2" style='background:#232629; color:white'>
                <h4 class="modal-title w-100 text-center" style="font-size: 16px;">HISTORIAL DE ABONOS &nbsp;&nbsp; <i style="cursor: pointer;" class="fas fa-id-card-alt" data-toggle="tooltip" data-placement="bottom" title="Botón para modificar los datos del paciente"></i></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="">

                <div class="card p-1 m-0">
                    <div class="card-header my-1 py-1">
                    
                        <div class="card px-1 py-2 shadow-lg">
                        <h5 style="font-size: 12px;text-align:center;font-weight:700">DETALLE CRÉDITO</h5>
                        <table class="table1">
                            <thead>
                                <tr style="background-color: #034f84;font-size:12px;color:white;text-align: center">
                                    <th style="width: 30%;border:1px solid #f2f2f2">Paciente</th>
                                    <th style="width: 15%;border:1px solid #f2f2f2">DUI</th>
                                    <th style="width: 15%;border:1px solid #f2f2f2">Monto crédito</th>
                                    <th style="width: 15%;border:1px solid #f2f2f2">Total abono</th>
                                    <th style="width: 15%;border:1px solid #f2f2f2">Saldo</th>
                                </tr>
                            </thead>
                            <tbody id="trow">
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 style="font-size: 12px;text-align:center;font-weight:700">DETALLES DE ABONOS</h5>
                        <table  style="border: 1px solid rgba(238, 238, 238, 0.5); width: 100%; text-align: center" data-order='[[ 0, "desc" ]]'    class="table-hover table-striped" id="datatable_historial_abonos">
                            <thead class="bg-dark" style="background-color: #034f84;font-size:12px;color:white;text-align: center">
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th style="text-align: center">Fecha abono</th>
                                    <th style="text-align: center">Gestor</th>
                                    <th style="text-align: center">Sucursal</th>
                                    <th style="text-align: center">No. recibo</th>
                                    <th style="text-align: center">Tipo pago</th>                                   
                                    <th style="text-align: center">Estado</th>
                                    <th style="text-align: center">Monto abono</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div><!-- Fin Modal body -->

    </div>

</div>
