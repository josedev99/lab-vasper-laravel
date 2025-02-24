<div class="modal fade" id="modal_form_laboratorio" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">REGISTRAR NUEVO LABORATORIO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_data_laboratorio">
                    <div class="card p-1 m-0">
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-8 col-lg-9">
                                    <div class="content-input mb-2">
                                        <input name="nombre_lab" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="nombre_lab" title="nombre de laboratorio">Nombre laboratorio</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input name="telefono_lab" data-valid-number="tel" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="telefono_lab" title="teléfono">Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="content-input mb-2">
                                        <input name="direccion_lab" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="direccion_lab" title="dirección">Dirección</label>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>