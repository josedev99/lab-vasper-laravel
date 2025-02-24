

<div class="modal fade" id="CrearSucursal" tabindex="-1" aria-labelledby="addSucursalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light" style="padding: 3px 20px !important;">
                <h5 class="modal-title" id="addEditEmpresaLabel">Agregar Agencia/Planta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                                <div class="card-header p-1">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="CrearSucursal()"><i
                                            class="bi bi-person-add"></i> Nueva Agencia/planta</button>
                                </div>
                                <table class="table-responsive table-hover table-bordered table-custom table-td"
                                    style="width: 100%" id="sucursales" data-order='[[ 0, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">Id</th>
                                            <th style="text-align:center">Nombre</th>
                                            <th style="text-align:center">Encargado</th>
                                            <th style="text-align:center">Telefono</th>
                                            <th style="text-align:center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">

                                    </tbody>
                                </table>
            </div>
        </div>
    </div>
</div>