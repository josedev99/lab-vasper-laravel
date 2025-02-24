<style>
    .datos-generales tr th {
        text-align: center;
    }

    .datos-generales tr td {
        background: #f8f8f8;
    }

    .subtitle {
        color: #1890ff;
        text-align: center;
        font-size: 13px;
        text-transform: uppercase;
        margin: 10px 0px;
        font-weight: 500;
    }

    .table_observaciones {
        border: 1px solid #e1e8f2;
    }

    .table-diagn {
        border: 1px groove #e1e8f2;
    }

    .table-diagn tr td,
    th {
        padding: 5px;
    }
</style>
<div class="modal fade" id="modal_resultado_examen" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">EVALUAR RESULTADO DE EXAMEN: <span id="display_examen"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 m-0">
                    <div class="card-header p-1">
                        <div class="container_info_empl">
                            <table class="table-resultado" style="background: none">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center"
                                            style="width: 50%;padding:3px 8px;border: 1px solid #dbcfcf;">COLABORADOR
                                        </th>
                                        <th class="text-center"
                                            style="width: 30%;padding:3px 8px;border: 1px solid #dbcfcf;">GÉNERO</th>
                                        <th class="text-center"
                                            style="width: 20%;padding:3px 8px;border: 1px solid #dbcfcf;">EDAD</th>

                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr>
                                        <td class="text-center"
                                            style="width: 50%;padding:3px 8px;border: 1px solid #dbcfcf;"
                                            id="display_nombre"></td>
                                        <td class="text-center"
                                            style="width: 30%;padding:3px 8px;border: 1px solid #dbcfcf;"
                                            id="display_genero"></td>
                                        <td class="text-center"
                                            style="width: 20%;padding:3px 8px;border: 1px solid #dbcfcf;"
                                            id="display_edad"></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <div id="resultado_examen" class="container_resultado">

                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <div class="card-body d-flex justify-content-center p-2 my-2 mx-1" style="border: 1px solid #dbcfcf;border-radius: 6px;position:relative">
                                <span style="position: absolute;top: -10px;font-size: 14px;background: #ffffff;color:#1864c9"><i class="bi bi-bookmark-heart-fill"></i> Evaluación examen:</span>
                                <div class="checkbox icheck-success d-inline">
                                    <input type="radio" disabled name="optionResultado" id="checkNormal" value="Normal">
                                    <label for="checkNormal" style="font-size: 13px">Normal</label>
                                </div>
                                <div class="checkbox icheck-warning d-inline mx-2">
                                    <input type="radio" disabled name="optionResultado" id="checkAlterado" value="Alterado">
                                    <label for="checkAlterado" style="font-size: 13px">Alterado</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body d-flex justify-content-center p-2 my-2 mx-1" style="border: 1px solid #dbcfcf;border-radius: 6px;position:relative">
                                <span style="position: absolute;top: -10px;font-size: 14px;background: #ffffff;color:#1864c9"><i class="bi bi-sunglasses"></i> Adquirió el servicio:</span>
                                <div class="checkbox icheck-success d-inline">
                                    <input type="radio" disabled name="optionService" id="icheckSi" value="Normal">
                                    <label for="icheckSi" style="font-size: 13px">Si</label>
                                </div>
                                <div class="checkbox icheck-warning d-inline mx-2">
                                    <input type="radio" disabled name="optionService" id="icheckNo" value="Alterado">
                                    <label for="icheckNo" style="font-size: 13px">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>