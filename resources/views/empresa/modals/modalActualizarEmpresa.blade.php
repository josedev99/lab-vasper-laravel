<style>
    .error {
        border-color: red;
    }
</style>
<div class="modal fade" id="ActualizarEmpresa" tabindex="-1" aria-labelledby="addEditEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light" style="padding: 3px 20px !important;">
                <h5 class="modal-title" id="addEditEmpresaLabel">Actualizar Empresa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card p-2 mb-0">
                    <div class="row" enctype="multipart/form-data">
                        <div class="col-md-7">
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value=""
                                    placeholder=" " id="ActualizarEmpresaa" name="ActualizarEmpresaa">
                                <label class="input-label clear-input" for="">Nombre de la Empresa*</label>
                             </div>
                             <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value=""
                                    placeholder=" " id="ActdireccionEmpresa" name="ActdireccionEmpresa">
                                <label class="input-label" for="">Direccion</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear_input material" value=""
                                    placeholder=" " maxlength="9" name="ActtelefonoEmpresa" id="ActtelefonoEmpresa">
                                <label class="input-label" for="">Telefono</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value=""
                                    placeholder=" " maxlength="9" name="ActcelularEmpresa" id="ActCelularEmpresa">
                                <label class="input-label" for="">Celular</label>
                            </div>
                                <div class="content-input mb-2">
                                    <input type="text" class="custom-input material clear-input material" value=""
                                        placeholder=" " id="ActnRegistroEmpresa" name="ActRegistroEmpresa">
                                    <label class="input-label" for="">N· Registro</label>
                                </div>
                                <div class="content-input mb-2">
                                    <input type="text" class="custom-input material clear-input material" value=""
                                        placeholder=" " name="Actgiro" id="Actgiro">
                                    <label class="input-label" for="">Giro</label>
                                </div>

                                    </div>
                    <div class="col-sm-12 col-md-5">
                        <div class="input-group mb-2">
                            <input type="text" readonly onclick="triggerImageUpload()" class="form-control input-file" value=""
                                placeholder="Imagen">
                            <button class="btn-file" onclick="triggerImageUpload()" type="button"><i
                                    class="bi bi-card-image icon-input"></i></button>
                        </div>
                        <input name="image" type="file" id="file_image" accept="image/jpeg, image/png" class="d-none d-flex align-items-center justify-content-center">
                        
                        <div class="card">
                            <div class="card-body content-preview-img px-2 py-1">
                                <div id="loadingMessage" class="d-none">Cargando...</div>
                                <br>
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <img id="previewImage" src="" style="max-width: 100%; max-height: 175px;">
                                </div>
                            </div>
                            <div id="imageErrors" class="alert alert-danger d-none">
                                <ul id="errorMessages">
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="btns d-flex justify-content-end my-2">
                        <button onclick="ActualizarEmpresa()" class="btn btn-outline-primary btn-sm btn-inline">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
