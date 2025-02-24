<div class="modal fade" id="modal-det-colab-import" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">DETALLE DE COLABORADORES IMPORTADOS</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 m-0">
                    <div class="card-header p-1">
                        <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> Colaboradores importados</h4>
                    </div>
                    <div class="card-body py-2 px-1">
                        <table width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                            <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                    <th style="text-align:center">#</th>
                                    <th style="text-align:center">FECHA DE ATENCIÓN</th>
                                    <th style="text-align:center">NOMBRE</th>
                                    <th style="text-align:center">COD. EMP.</th>
                                    <th style="text-align:center">DEPTO/AREA</th>
                                    <th style="text-align:center">TELÉFONO</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider" style="font-size: 12px;text-align:center" id="listados_colab_dt">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>