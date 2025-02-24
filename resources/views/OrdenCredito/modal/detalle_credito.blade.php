<style>
    html {
        overflow: scroll;
        -webkit-overflow-scrolling: touch;
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

    .centered-cell {
        text-align: center;
    }

    /***
    */
    /**RESPONSIVE MODAL ***/
    /* DESTOK */
    @media screen and (min-width:1024px) {
        .modal-responsive-lg {
            max-width: 80%;
        }

        .modal-responsive-md {
            max-width: 70%;
        }

        .modal-responsive-sm {
            max-width: 60%;
        }
    }

    /* TABLE */
    @media screen and (min-width:767px) and (max-width:1023px) {
        .modal-responsive-lg {
            max-width: 90%;
        }

        .modal-responsive-md {
            max-width: 80%;
        }

        .modal-responsive-sm {
            max-width: 70%;
        }
    }

    /* MOVIL */
    @media screen and (max-width:767px) {
        .modal-responsive-lg {
            max-width: 100%;
        }

        .modal-responsive-md {
            max-width: 100%;
        }

        .modal-responsive-sm {
            max-width: 100%;
        }
    }
</style>
<div class="modal fade" id="modalListadoCreditos" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-responsive-md">
        <div class="modal-content">
            <div class="modal-header modal-move bg-dark modal-move" style='cursor:move;padding:5px 12px;'>
                <h4 class="modal-title text-light" style="font-size: 14px;" id="titleDetCredit">DETALLE ORDEN DESCUENTO
                    <span id="titular"></span>
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <form id="formListadoCredit" method="POST">
                @csrf
                <div class="modal-body card py-1 mb-0">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="card-header my-1 p-1">

                                <div class="card mb-1 px-1 py-2 ">
                                    <h5 style="font-size: 13px;text-align:center;font-weight:700">BENEFICIARIOS Y
                                        SERVICIOS
                                    </h5>
                                    <table class="table1">
                                        <thead>
                                            <tr
                                                style="background-color: #343a40;font-size:12px;color:white;text-align: center">
                                                <th style="width: 15%;border:1px solid #f2f2f2">CANTIDAD</th>
                                                <th style="width: 20%;border:1px solid #f2f2f2">CATEGORIA</th>
                                                <th style="width: 50%;border:1px solid #f2f2f2">PRODUCTOS</th>
                                                <th style="width: 15%;border:1px solid #f2f2f2">PRECIO VENTA</th>
                                            </tr>
                                        </thead>
                                        <tbody id="trowBeneficiosService" style="font-size: 13px !important">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--FIN MODAL ABONO--->
<!-- /.modal -->