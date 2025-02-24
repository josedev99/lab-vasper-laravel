<div class="modal fade" id="ModalCrearHorario" tabindex="-1" aria-labelledby="addEditEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light" style="padding: 1px 10px !important;">
                <h6 class="modal-title" id="addEditEmpresaLabel">CREACION DE HORARIOS DE CITAS</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-header p-1">
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                            <div class="input-group">
                                <label for="rango_horario" class="input-group-title1">Rango*: </label>
                                <select name="rango_horario" id="rango_horario" class="form-select border-radius" data-toggle="tooltip" data-placement="bottom" title="">
                                    <option value="">Seleccionar</option>
                                    <option value="60">1 HORA</option>
                                    <option value="45">45 MINUTOS</option>
                                    <option value="30">30 MINUTOS</option>
                                    <option value="15">15 MINUTOS</option>
                                    <option value="10">10 MINUTOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-8 mb-10">

                        <div class="alert alert-info  alert-dismissible fade show" role="alert">
                            <h4 class="alert-heading">HORARIO PARA DISPONIBILIDAD DE CITAS</h4>
                            <hr>
                            <p>Seleccione los dias y horas, que podra recibir citas</p>
                          </div>
                          </div>
                    </div>
                    <hr>
                </div>
                <table class="table-responsive table-hover table-bordered table1 table-custom table-td" style="width: 100%">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" class="select-all" data-day="lunes"> Lunes</th>
                            <th><input type="checkbox" class="select-all" data-day="martes"> Martes</th>
                            <th><input type="checkbox" class="select-all" data-day="miercoles"> Miércoles</th>
                            <th><input type="checkbox" class="select-all" data-day="jueves"> Jueves</th>
                            <th><input type="checkbox" class="select-all" data-day="viernes"> Viernes</th>
                            <th><input type="checkbox" class="select-all" data-day="sabado"> Sábado</th>
                            <th><input type="checkbox" class="select-all" data-day="domingo"> Domingo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="lunes-slots" class="slot-container"></td>
                            <td id="martes-slots" class="slot-container"></td>
                            <td id="miercoles-slots" class="slot-container"></td>
                            <td id="jueves-slots" class="slot-container"></td>
                            <td id="viernes-slots" class="slot-container"></td>
                            <td id="sabado-slots" class="slot-container"></td>
                            <td id="domingo-slots" class="slot-container"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal-footer p-0">
                    <button type="button" id="BtnRegHor" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-save"></i> REGISTRAR HORARIO
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Styling */
    .modal-header {
        background-color: #2c3e50;
    }

    .modal-body {
        font-size: 0.95rem;
    }


    /* Checkbox styling */
    input[type="checkbox"] {
        accent-color: #007bff; /* Color azul */
        width: 1.5em;
        height: 1.5em;
    }

    /* Checkbox al lado izquierdo del día */
    th .select-all {
        margin-bottom: left; /* Separar el checkbox del texto del día */
    }

    /* Estilo para el select de rango */
    .form-select {
        margin-top: 10px;
        text-align: center;
    }

    /* Select All Styling */
    .select-all {
        accent-color: #0056b3;
    }

    /* Time Slot Labels */
    .slot-label {
        font-weight: bold;
        margin-left: 8px;
        color: #34495e;
    }
</style>



