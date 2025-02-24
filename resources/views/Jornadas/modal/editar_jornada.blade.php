<div class="modal fade" id="modal_jornada_editar" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light" style="background: #034f84 !important;">
                <h1 class="modal-title fs-7">ACTUALIZAR INFORMACIÓN DE LA JORNADA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_jornada_upd">
                    <div class="card p-1 m-0">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8">
                                    <div class="content-input mb-2">
                                        <input name="jornada_up_nombre" id="jornada_up_nombre" type="search"
                                            class="custom-input material" value="" placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="jornada_up_nombre">Nombre de la jornada</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input name="fecha_up_jornada" id="fecha_up_jornada" type="date"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="fecha_up_jornada" title="Fecha jornada">Fecha jornada</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-info btn-sm">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>