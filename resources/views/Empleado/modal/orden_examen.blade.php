<style>
    [class*=icheck-]>input:first-child+label::before {
        border-radius: 50% !important;
    }

    [class*=icheck-] {
        margin-top: 0px !important;
    }

    .card-item-examen {
        transition: 0.3s ease-in-out;
    }

    .card-item-examen:hover {
        transform: scale(1.1, 1.1);
    }
    .list-group-item{
        padding: 6px 8px;
    }
    .rm-item{
        position: absolute;
        top: -8px;
        right: -4px;
        height: 10px;
        width: 10px;
        background: #dee2e6;
        color: #000000;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        padding: 7px;
        cursor: pointer;
        visibility: hidden;
    }
    .active{
        visibility: visible;
    }
</style>

<div class="modal fade" id="modal_nueva_orden_examen" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7" id="modal_orden_examen">REGISTRAR NUEVA ORDEN DE EXAMENES</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_orden_lab">
                    <div class="card p-1 m-1 shadow-lg">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-7">
                                    <p class="p-0 m-0" style="display: inline-block"><span class="badge bg-light"
                                            style="font-size: 13px !important; color:#23272a"
                                            id="nombre_empleado_html"></span></p>
                                    <p class="p-0 m-0" style="display: inline-block"><span class="badge bg-light"
                                            style="font-size: 13px !important; color:#23272a"
                                            id="nombre_empresa_html"></span></p>
                                    <p class="p-0 m-0" style="display: inline-block"><span class="badge bg-light"
                                            style="font-size: 13px !important; color:#23272a"
                                            id="nombre_sucursal_html"></span></p>
                                </div>
                                <div class="col-sm-5 d-flex justify-content-end">
                                    <button type="button" title="Registrar nuevo examen"
                                        class="btn btn-outline-success btn-sm btnAddExamen"><i
                                            class="bi bi-plus-lg"></i>
                                        Examen</button>
                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2" id="contents_inputs_jornada">
                                        <div class="input-group">
                                            <label for="jornada_orden" title="jornada"
                                                class="input-group-title1">Jornadas
                                            </label>
                                            <select name="jornada_orden" id="jornada_orden"
                                                class="form-select border-radius" data-toggle="tooltip"
                                                data-placement="bottom" title="Seleccionar jornada">
                                                <option value="">Selecccionar</option>
                                            </select>
                                            <label class="input-group-label1 bg-dark btnAdd" id="btnAddJornada"><i class="bi bi-plus-lg" style="font-size: 16px"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-1 mb-0">

                        <div class="card-header p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-8 d-flex align-items-center mb-2">
                                    <h4 class="m-0 text-dark" style="font-size: 14px;">EXÁMENES: <span id="display_examenes">NO HAY EXÁMENES SELECCIONADOS.</span></h4>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <input type="search" class="input-search" id="input-search" name="search_examen"
                                        placeholder="Buscar examen" title="Buscar examen">
                                    <button class="icon-input-search" type="button" title="Search"><i
                                            class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row p-1" {{-- id="rows_examenes_orden" --}}>
                            <div class="col-sm-12 col-md-4">
                                <div class="card m-0 p-1">
                                    <div class="card-header p-1">
                                        <h5 class="card-title m-0 px-1 py-0" style="font-size: 15px;">CATEGORIA</h5>
                                    </div>
                                    <div class="card-body p-1">
                                        <!-- List group With Icons -->
                                        <div class="list-group" id="list-items-categoria">
                                        </div><!-- End List group With Icons -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <div class="card m-0 p-1">
                                    <div class="card-header p-1">
                                        <h5 class="card-title m-0 px-1 py-0 text-dark" style="font-size: 15px;"><span id="display_cat_selected" class="text-dark"></span>EXAMENES</h5>
                                    </div>
                                    <div class="card-body p-1" id="list-items-examenes">
                                        <p class="m-0 p-0 text-danger">Categoria no seleccionada.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 m-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btnSaveOrden"><i
                                    class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>