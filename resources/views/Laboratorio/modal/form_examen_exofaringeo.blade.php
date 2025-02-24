<style>
    .item{
        font-size: 14px !important;
    }
</style>
<div class="modal fade" id="modal-resultado-exofaringeo" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: EXOFARINGEO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_result-exofaringeo">
                    <div class="card p-1 m-1" style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE : <span
                                    style="color: #3d3d3d;font-weight:700">EXOFARINGEO</span></h4>
                        </div>
                        <div class="card-body p-1 m-0">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="aisla_exof" class="input-group-title1">Se aisla: </label>
                                        <select name="aisla_exof" id="aisla_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option
                                                value="NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA NORMAL">
                                                NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA
                                                NORMAL
                                            </option>
                                            <option value="KLEBSIELLA PNEUMONAE">KLEBSIELLA PNEUMONAE
                                            </option>
                                            <option value="STREPTOCOCCUS BETA HEMOLITICO DEL GRUPO A">STREPTOCOCCUS BETA
                                                HEMOLITICO DEL GRUPO A
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="sensible_exof" class="input-group-title1">Sensible a: </label>
                                        <select name="sensible_exof" id="sensible_exof"
                                            class="form-select border-radius" data-toggle="tooltip"
                                            data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="AMOXICILINA/ACIDO CLAVULANICO">AMOXICILINA/ACIDO CLAVULANICO
                                            </option>
                                            <option value="IMIPENEM">IMIPENEM</option>
                                            <option value="TRIMETROPRIM/SULFAMETOXALE">TRIMETROPRIM/SULFAMETOXALE
                                            </option>
                                            <option value="CIPROFLOXACINA">CIPROFLOXACINA</option>
                                            <option value="CEFIXIME">CEFIXIME</option>
                                            <option value="FOSFOMICINA">FOSFOMICINA</option>
                                            <option value="CEFADROXIL">CEFADROXIL</option>
                                            <option value="CEFTAZIDIMA">CEFTAZIDIMA</option>
                                            <option value="CEFOTAXIMA">CEFOTAXIMA</option>
                                            <option value="CEFOXITIN">CEFOXITIN</option>
                                            <option value="CEFEPIME">CEFEPIME</option>
                                            <option value="CEFTRIAXONA">CEFTRIAXONA</option>
                                            <option value="LEVOFLAXACINA">LEVOFLAXACINA</option>
                                            <option value="CEFOTAXIN">CEFOTAXIN</option>
                                            <option value="CEFUROXIME">CEFUROXIME</option>
                                            <option value="AUGMENTIN">AUGMENTIN</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMIKIN">AMIKIN</option>
                                            <option value="CEFTRIAXONE">CEFTRIAXONE</option>
                                            <option value="CLARITROMICINA">CLARITROMICINA</option>
                                            <option value="AMOXICICILINA">AMOXICICILINA</option>
                                            <option value="TMP+SMT">TMP+SMT</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMPICILINA">AMPICILINA</option>
                                            <option value="PIPERACILINA/TAZOBACTAM">PIPERACILINA/TAZOBACTAM</option>
                                            <option value="AMIKACINA">AMIKACINA</option>
                                            <option value="CLINDAMICINA">CLINDAMICINA</option>
                                            <option value="VANCOMICINA">VANCOMICINA</option>
                                            <option value="NORFLOXACINA">NORFLOXACINA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="resiste_exof" class="input-group-title1">Resiste a: </label>
                                        <select name="resiste_exof" id="resiste_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="AMOXICILINA/ACIDO CLAVULANICO">AMOXICILINA/ACIDO CLAVULANICO
                                            </option>
                                            <option value="IMIPENEM">IMIPENEM</option>
                                            <option value="TRIMETROPRIM/SULFAMETOXALE">TRIMETROPRIM/SULFAMETOXALE
                                            </option>
                                            <option value="CIPROFLOXACINA">CIPROFLOXACINA</option>
                                            <option value="CEFIXIME">CEFIXIME</option>
                                            <option value="FOSFOMICINA">FOSFOMICINA</option>
                                            <option value="CEFADROXIL">CEFADROXIL</option>
                                            <option value="CEFTAZIDIMA">CEFTAZIDIMA</option>
                                            <option value="CEFOTAXIMA">CEFOTAXIMA</option>
                                            <option value="CEFOXITIN">CEFOXITIN</option>
                                            <option value="CEFEPIME">CEFEPIME</option>
                                            <option value="CEFTRIAXONA">CEFTRIAXONA</option>
                                            <option value="LEVOFLAXACINA">LEVOFLAXACINA</option>
                                            <option value="CEFOTAXIN">CEFOTAXIN</option>
                                            <option value="CEFUROXIME">CEFUROXIME</option>
                                            <option value="AUGMENTIN">AUGMENTIN</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMIKIN">AMIKIN</option>
                                            <option value="CEFTRIAXONE">CEFTRIAXONE</option>
                                            <option value="CLARITROMICINA">CLARITROMICINA</option>
                                            <option value="AMOXICICILINA">AMOXICICILINA</option>
                                            <option value="TMP+SMT">TMP+SMT</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMPICILINA">AMPICILINA</option>
                                            <option value="PIPERACILINA/TAZOBACTAM">PIPERACILINA/TAZOBACTAM</option>
                                            <option value="AMIKACINA">AMIKACINA</option>
                                            <option value="CLINDAMICINA">CLINDAMICINA</option>
                                            <option value="VANCOMICINA">VANCOMICINA</option>
                                            <option value="NORFLOXACINA">NORFLOXACINA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="refiere_exof" class="input-group-title1">Referido a: </label>
                                        <select name="refiere_exof" id="refiere_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="LABORATORIO VIDLAB">LABORATORIO VIDLAB</option>
                                            <option value="LABORATORIO SOUNDY CALL">LABORATORIO SOUNDY CALL</option>
                                        </select>
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