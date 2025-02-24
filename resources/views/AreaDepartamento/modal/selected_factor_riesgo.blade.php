<div class="modal fade" id="modal_selected_factor_riesgo" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">DEPARTAMENTO/AREA SELECCIONADO: <span id="display_depto"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 m-0">
                    <div class="card-header p-2 my-2 mx-1" style="border: 1px solid #dbcfcf;">
                        <span style="position: absolute;top: 0px;font-size: 14px;background: #ffffff;color:#1864c9">Aplicar factor de riesgo a:</span>
                        <div class="checkbox icheck-info d-inline">
                            <input type="radio" name="optionCategoria" id="checkDepto" onclick="checkJerarquia(this)" value="departamentos">
                            <label for="checkDepto" style="font-size: 13px">DEPARTAMENTO</label>
                        </div>
                        <div class="checkbox icheck-info d-inline">
                            <input type="radio" name="optionCategoria" id="checkArea" onclick="checkJerarquia(this)" value="areas">
                            <label for="checkArea" style="font-size: 13px">AREAS</label>
                        </div>
                        <div class="checkbox icheck-turquoise d-inline">
                            <input type="radio" name="optionCategoria" id="checkCargo" onclick="checkJerarquia(this)" value="cargos">
                            <label for="checkCargo" style="font-size: 13px">CARGOS</label>
                        </div>
                    </div>
                    <div class="card-body py-2 px-1">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-2" id="component_select_cargo" style="display:none">
                                <div class="input-group">
                                    <label class="input-group-title1" id="d-label-select-nivel">Cargos*: </label>
                                    <select name="select_cargos" id="select_cargos" class="form-select border-radius" title="Seleccionar cargo">
                                        <option value="">Selecccionar</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="input-group">
                                    <label class="input-group-title1">Factor riesgo*: </label>
                                    <select name="factor_riesgo" id="select_factor_riesgo" class="form-select border-radius" title="Seleccionar factor de riesgo">
                                        <option value="">Selecccionar</option>
                                    </select>
                                </div>
                            </div>                             
                        </div>
                        <table style="width: 100%;border-collapse:collapse;font-size: 12px">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th style="width: 45%">AREA/DEPARTAMENTO</th>
                                    <th style="width: 45%">FACTOR RIESGO</th>
                                    <th style="width: 10%">ACC</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" id="rows_factor_riesgo">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer p-1 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addItemsFactorDepartamento()"><i class="bi bi-floppy"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>