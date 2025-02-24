<style>
    .container-icheck{
        border: 1px solid #dadce0;
        border-radius: 6px;
        position: relative;
    }
    .label-container{
        position: absolute;
        top: -10px;
        font-size: 12px;
        font-weight: 700;
        left: 10px;
        background: #fff;
    }
    .icheck-turquoise label{
        font-size: 13.5px !important;
    }
</style>
<div class="modal fade" id="modal-optometria-jornada" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">JORNADA VISUAL <i class="bi bi-eyeglasses"></i></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 m-0">
                    <div class="card-body p-1">
                        <div class="container-icheck d-flex justify-content-center">
                            <span class="label-container">AGENDAR POR: </span>
                            <div class="radio icheck-turquoise">
                                <input type="radio" id="iCheckRiesgo" name="checkAgendar" value="riesgo">
                                <label for="iCheckRiesgo">RIESGO</label>
                            </div>
                            <div class="radio icheck-turquoise" style="margin: 0px 10px">
                                <input type="radio" id="iCheckDepto" name="checkAgendar" value="depto">
                                <label for="iCheckDepto">DEPARTAMENTO</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-1 d-flex justify-content-end">
                        <button class="btn btn-outline-success btn-sm" id="btnAddJorVisual">AGREGAR <i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>