<style>
    .medida {
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translate(50%, -50%);
        color: #3d3d3d;
        font-size: 14px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Para Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    .section-form{
        border: 1px solid #dbcfcf;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
</style>
<div class="modal fade" id="modal-ingresar-resultado" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">INGRESAR RESULTADO DE: <span id="display_examen"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_resultado_exam">
                    <div class="card p-1 m-1"
                        style="border-radius: 6px;position:relative;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                        <div class="card-header p-1">
                            <h4 class="m-0" style="font-size: 14px">INGRESO DE VALORES QUIMICA</h4>
                        </div>
                        <div class="card-body p-1 m-0">
                            <div class="pt-2" id="content-form">

                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="content-input">
                                    <input name="observaciones_quimica" type="search"
                                        class="custom-input material" value="" placeholder=" " style="text-transform: uppercase">
                                    <label class="input-label" for="observaciones">Observaciones</label>
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