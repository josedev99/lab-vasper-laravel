<style>
    .section-form{
        border: 1px solid #dbcfcf;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
</style>
<div class="modal fade" id="modal-form-orina" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: EXAMEN GENERAL DE ORINA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-exa-gen-orina">
                    <div class="card p-1 m-1"
                        style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE: <span style="color: #3d3d3d;font-weight:700">EXAMEN GENERAL DE ORINA</span></h4>
                        </div>
                        <div class="card-body p-1 m-0" id="1content-form">
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                            border-top-right-radius: 6px;"><strong>EXAMEN QUIMICO - ORINA</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_color" list="color_default" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="ego_color">Color</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_olor" type="search" class="custom-input material" value="suigeneris" placeholder=" " >
                                            <label class="input-label" for="ego_olor">Olor</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_aspecto" type="search" list="aspecto_default" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="ego_aspecto">Aspecto</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_densidad" type="search" list="densidad_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_densidad">Densidad</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_esterasas" type="search" list="esterasas_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_esterasas">Esterasas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_nitritos" type="search" list="nitrito_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_nitritos">Nitrito</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_ph" type="search" list="ph_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_ph">PH</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_proteinas" type="search" list="proteinas_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_proteinas">Proteinas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_glucosa" type="search" list="glucosa_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_glucosa">Glucosa</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_cetonas" type="search" list="cetona_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_cetonas">Cetonas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_urobili" type="search" list="urobilinogeno_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_urobili">Urobilinógeno</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_bilirrubina" type="search" list="bilirrubina_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_bilirrubina">Bilirrubina</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_sangre_ocul" type="search" list="sangre_ocult_default" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="ego_sangre_ocul">Sangre oculta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                            border-top-right-radius: 6px;"><strong>EXAMEN MICROSCOPICO</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_cilidros" type="search" list="cilindros_default" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="ego_cilidros">Cilindros</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_leucocitos" type="search" list="leucocitos_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_leucocitos">Leucocitos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_hematies" type="search" list="hematiies_default" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="ego_hematies">Hematíes</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_cel_epiteliales" type="search" list="cel_epiteliales_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_cel_epiteliales">Cel. Epiteliales</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_filamentos" type="search" list="filamento_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_filamentos">Filamentos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_bacterias" type="search" list="bacterias_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_bacterias">Bacterias</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ego_cristales" type="search" list="cristales_default" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_cristales">Cristales</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-12 col-xl-10">
                                        <div class="content-input">
                                            <input name="ego_observaciones" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="ego_observaciones">Observaciones</label>
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