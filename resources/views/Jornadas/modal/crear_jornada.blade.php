<style>
    .flatpickr-current-month {
        display: block !important;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        font-size: 13px;
        margin-bottom: 12px;
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 15px;
        color: #012970;
    }

    .fc-event {
        cursor: pointer;
    }

    .swal2-html-container {
        font-size: 1rem !important;
    }

    .btn-close-span {
        position: absolute;
        top: -12px;
        right: -8px;
        font-size: 15px;
        color: #020202;
        cursor: pointer;
        background: #fff;
        border-radius: 50%;
        padding: 1px;
    }

    .badge {
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 11px;
        background: #e7f3e9 !important;
        color: #181515;
    }

    .bg-custom-color {
        background: #3788d8;
        color: #fff;
    }

    .left-th {
        border-top-left-radius: 4px;
    }

    .rigth-th {
        border-top-right-radius: 4px;
    }

    .custom-td {
        padding: 2px;
        border-bottom: 1px solid #dadce0;
        border-right: 1px solid #dadce0;
    }

    .custom-badge {
        margin: 2px;
        padding: 4px;
        position: relative;
        font-size: 13px;
        color: #181515;
    }
</style>
<div class="modal fade" id="modal_crear_jornada" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7" id="modal_title_jornada">REGISTRAR NUEVA JORNADA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_jornada_ocupacional">
                    <div class="card p-1 mb-0">
                        <div class="card-header p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="content-input mb-2">
                                        <input name="nombre_jornada" type="text"
                                            class="custom-input material oblig_input" value="" placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="nombre_jornada" title="nombre de la jornada">Nombre de la jornada</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="tipo_compra" class="input-group-text">Evaluador </label>
                                        <select id="tipo_ex_jornada" name="tipo_ex_jornada"
                                            class="custom-select form-select clear-select oblig"
                                            data-toggle="tooltip" data-placement="bottom" title="Selec. tipo de compra">
                                            <option value="2">OPTICA AV PLUS</option>
                                            <option value="2">Laboratorio Clinico Vasper</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input name="fecha_jornada" id="fecha_jornada" type="date"
                                            class="custom-input material oblig_input" value="" placeholder=" ">
                                        <label class="input-label" for="fecha_jornada" title="fecha">Fecha</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                    <div class="input-group">
                                        <label class="input-group-title1">Examenes*: </label>
                                        <select name="exa_jornadas" id="exa_jornadas" class="form-select border-radius" title="Seleccionar examenes">                                                                                    
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row" id="component_body_departamentos"></div>
                        <div class="card-body m-0 p-0" style="border: 1px solid #dadce0;">
<!--                             <table style="width: 100%;text-transform:uppercase" class="table table-hover">
                                <thead style="font-size: 13px;text-align: center;">
                                    <tr>
                                        <th class="left-th bg-custom-color" style="width: 10%"></th>
                                        <th class="left-th bg-custom-color" style="width: 15%">Depto.</th>
                                        <th class="bg-custom-color" style="width: 20%">Area</th>
                                        <th class="bg-custom-color" style="width: 20%">Colaboradores</th>
                                        <th class="bg-custom-color" style="width: 25%">Examenes</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;text-align: center;" id="component_body_departamentos">
                                </tbody>
                            </table> -->
                        </div>
                    </div>
                    <div class="card-footer p-1 d-flex justify-content-between">
                        <span id="total_colaboradores"><i class="bi bi-people-fill"></i> 0 COLABORADORES</span>
                        <button class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i> Registrar</button>
                    </div>
                </form>
                <div id="contenedor-tabla"></div>
            </div>
        </div>
    </div>
</div>