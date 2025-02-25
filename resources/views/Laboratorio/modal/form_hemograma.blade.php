<style>
    .section-form{
        border: 1px solid #dbcfcf;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
</style>
<div class="modal fade" id="modal-form-hemograma" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: HEMOGRAMA COMPLETO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-exa-hemograma">
                    <div class="card p-1 m-1"
                        style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE: <span style="color: #3d3d3d;font-weight:700">HEMOGRAMA</span></h4>
                        </div>
                        <div class="card-body p-1 m-0" id="1content-form">
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;sborder-top-right-radius: 6px;"><strong>LINEA ROJA</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="gr_hemato" id="gr_hemato" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="gr_hemato">G.R. x mm3</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="ht_hemato" type="search" class="custom-input material" placeholder=" " >
                                            <label class="input-label" for="ht_hemato">Ht %</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="hb_hemato" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="hb_hemato">Hb g/dl</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="vcm_hemato" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="vcm_hemato">V.C.M fl</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="hcm_hemato" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="hcm_hemato">H.C.M Pg</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="content-input">
                                            <input name="cmhc_hemato" type="search" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="cmhc_hemato">C.M.H.C g/dl</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="content-input">
                                            <input name="gota_gruesa" type="search" class="custom-input material"
                                                value="" placeholder=" ">
                                            <label class="input-label" for="gota_gruesa">GOTA GRUESA</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                                    border-top-right-radius: 6px;"><strong>LINEA BLANCA</strong></div>
                                    <div class="section-form px-2 pt-2 mb-2">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="gb_hemato" id="gb_hemato" type="search" class="custom-input material" value=""
                                                        placeholder=" " >
                                                    <label class="input-label" for="gb_hemato">G.B. x mm3</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="linfocitos_hemato" type="search" class="custom-input material" placeholder=" " >
                                                    <label class="input-label" for="linfocitos_hemato">Linfocitos</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="monocitos_hemato" type="search" class="custom-input material" value="" placeholder=" " >
                                                    <label class="input-label" for="monocitos_hemato">Monocitos</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="eosinofilos_hemato" type="search" class="custom-input material"
                                                        value="" placeholder=" " >
                                                    <label class="input-label" for="eosinofilos_hemato">Eosinófilos</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="basinofilos_hemato" type="search" class="custom-input material"
                                                        value="" placeholder=" " >
                                                    <label class="input-label" for="basinofilos_hemato">Basinófilos</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                                    border-top-right-radius: 6px;"><strong>NEUTROFILOS</strong></div>
                                    <div class="section-form px-2 pt-2 mb-2">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="banda_hemato" type="search" class="custom-input material" value=""
                                                        placeholder=" " >
                                                    <label class="input-label" for="banda_hemato">En Banda</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="segmentado_hemato" type="search" class="custom-input material" placeholder=" " >
                                                    <label class="input-label" for="segmentado_hemato">Segmentados</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="metamielo_hemato" type="search" class="custom-input material" value="" placeholder=" " >
                                                    <label class="input-label" for="metamielo_hemato">Metamielo Neutro</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="mielocitos_hemato" type="search" class="custom-input material"
                                                        value="" placeholder=" " >
                                                    <label class="input-label" for="mielocitos_hemato">Mielocitos</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="content-input">
                                                    <input name="blasto_hemato" type="search" class="custom-input material"
                                                        value="" placeholder=" " >
                                                    <label class="input-label" for="blasto_hemato">Blastos</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="font-size: 13px;text-align: center;background:#17a2b8;color: white;border-top-left-radius: 6px;
                            border-top-right-radius: 6px;"><strong>VARIOS</strong></div>
                            <div class="section-form px-2 pt-2 mb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="content-input">
                                            <input name="plaquetas_hemato" id="plaquetas_hemato" type="search" class="custom-input material" value=""
                                                placeholder=" " >
                                            <label class="input-label" for="plaquetas_hemato">Plaquetas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="content-input">
                                            <input name="reticulocitos_hemato" type="search" class="custom-input material" placeholder=" " >
                                            <label class="input-label" for="reticulocitos_hemato">Reticulocitos</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="content-input">
                                            <input name="eritrosedimentacion_hemato" type="search" class="custom-input material" value="" placeholder=" " >
                                            <label class="input-label" for="eritrosedimentacion_hemato">Eritrosedimentación</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="content-input">
                                            <input name="otros_hemato" type="search" class="custom-input material"
                                                value="" placeholder=" " >
                                            <label class="input-label" for="otros_hemato">Otros</label>
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