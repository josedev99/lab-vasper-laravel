<div class="modal fade" id="modal_new_permisos" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">CREAR PERMISOS</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0"><i class="bi bi-bookmarks"></i>Crear nuevos permisos</h4>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-4 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="nombrePermiso" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " id="nombrePermiso">
                                        <label class="input-label" for="nombrePermiso">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="descripcionPermiso" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase" id="descripcionPermiso">
                                        <label class="input-label" for="descripcionPermiso">Descripción</label>
                                    </div>
                                </div>
                                <input type="hidden" id="id_n" value="">
                                <div class="col-sm-12 col-md-12 col-lg-2 mb-2">
                                    <div class="content-input mb-2">
                                        <button id="btn-save-permiso" class="btn btn-outline-success btn-xl btn-save-permiso"><i class="bi bi-floppy"></i> Crear Permiso </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body py-2 px-1">
                            <table class="table-responsive table-hover table-bordered table1 table-custom table-td"
                            style="width: 100%" id="permisos" data-order='[[ 0, "desc" ]]'>
                            <thead>
                                <tr>
                                    <th style="padding: 0px; text-align: center;">ID</th>
                                    <th style="padding: 0px; text-align: center;">Nombre</th>
                                    <th style="padding: 0px; text-align: center;">Descripcion</th>
                                    <th style="padding: 0px; text-align: center;">Accion</th>
                                </tr>
                            </thead>                
                            <tbody style="text-align: center;">
                            </tbody>
                        </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>