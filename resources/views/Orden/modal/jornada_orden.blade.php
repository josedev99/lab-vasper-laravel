<div class="modal fade" id="modal-new-jornada" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1" style="background: rgba(0, 0, 0, 0.4);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">REGISTRAR NUEVA JORNADA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-jornada-orden">
                    <div class="card p-1 m-0">
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="content-input mb-2">
                                        <input name="jornada" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="jornada">Nombre de la jornada</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input name="fecha_jornada" type="date"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" ">
                                        <label class="input-label" for="fecha_jornada">Fecha</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" id="btnSaveJornadaOrden" class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>