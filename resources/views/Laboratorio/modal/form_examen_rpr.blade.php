<div class="modal fade" id="modal-resultado-rpr" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: RPR</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_result-rpr">
                    <div class="card p-1 m-1"
                        style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE : <span style="color: #3d3d3d;font-weight:700">RPR</span></h4>
                        </div>
                        <div class="card-body p-1 m-0">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="content-input">
                                        <input name="resultado_rpr" type="search" list="rpr_default" class="custom-input material" value="" placeholder=" ">
                                        <label class="input-label" for="resultado_rpr">Resultado</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="content-input">
                                        <input name="observaciones_rpr" type="search"
                                            class="custom-input material" value="" placeholder=" ">
                                        <label class="input-label" for="observaciones_rpr">Observaciones</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i>
                                Guardar resultado</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>