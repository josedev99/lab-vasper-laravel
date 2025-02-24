<style>
    .section-form{
        border: 1px solid #dbcfcf;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
</style>
<div class="modal fade" id="modal-form-heces" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: HECES</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-exa-gen-heces">
                    <div class="card p-1 m-1"
                        style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE : <span style="color: #3d3d3d;font-weight:700">HECES</span></h4>
                        </div>
                        <div class="card-body p-1 m-0" id="1content-form">
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                            border-top-right-radius: 6px;"><strong>EXAMEN QUIMICO - HECES</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_color" id="egh_color" list="colores_default" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="egh_color">Color</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_consistencia" list="consistencia_default" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_consistencia">Consistencia</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_mucus" type="search" list="mucus_default" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="egh_mucus">Mucus</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                        <div class="content-input">
                                            <input name="egh_macroscopicos" type="search" list="macro_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_macroscopicos">Macroscopicos(R.
                                                Alim.)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                        <div class="content-input">
                                            <input name="egh_microscopicos" type="search" list="micro_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_microscopicos">Microscopicos(R.
                                                Alim.)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                            border-top-right-radius: 6px;"><strong>EXAMEN MICROSCOPICO</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_hematies" type="search" list="hematies_default" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="egh_hematies">Hematíes</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_leucocitos" type="search" list="leucocitos_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_leucocitos">Leucocitos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                        <div class="content-input">
                                            <input name="egh_protozoarios" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="egh_protozoarios">Protozoarios</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                        <div class="content-input">
                                            <input name="egh_activos" type="search" list="activos_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_activos">Activos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                        <div class="content-input">
                                            <input name="egh_quistes" type="search" list="quistes_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_quistes">Quistes</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="content-input">
                                            <input name="egh_metazoarios" type="search" list="metazoarios_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_metazoarios">Metazoarios</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8">
                                        <div class="content-input">
                                            <input name="egh_observaciones" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="egh_observaciones">Observaciones</label>
                                        </div>
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