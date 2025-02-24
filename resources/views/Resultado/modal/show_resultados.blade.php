<style>
    .accordion-item{
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }
</style>
<div class="modal fade" id="modal_result_ex_empleado" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">RESULTADOS DE EXAMENES EVALUADOS</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1 d-flex justify-content-end">
                            <div class="checkbox icheck-success" style="margin: 0px !important">
                                <input type="checkbox" id="icheckEstado" onclick="toggleEstadoEval(this)">
                                <label for="icheckEstado">Evaluado</label>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            <h4 style="font-size: 14px">RESULTADOS DEL COLABORADOR: <span id="display_empleado_nombre"></span></h4>
                            <div id="list_items_resultado">

                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>