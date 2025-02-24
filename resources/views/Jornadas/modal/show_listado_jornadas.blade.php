<div class="modal fade" id="modal_listar_jornadas" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">LISTADO DE JORNADAS</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 m-0">
                    <table id="dt_listado_jornadas" width="100%" data-order='[[ 0, "desc" ]]'
                            class="table-hover table-striped">
                            <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                    <th style="text-align:center">#</th>
                                    <th style="text-align:center">NOMBRE</th>
                                    <th style="text-align:center">CATEGORIA</th>
                                    <th style="text-align:center">FECHA & HORA</th>
                                    <th style="text-align:center">EVALUADOR</th>
                                    <th style="text-align:center">CANTIDAD COLABORADORES</th>
                                    <th style="text-align:center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider" style="font-size: 12px;text-align:center;color:#232121">

                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>