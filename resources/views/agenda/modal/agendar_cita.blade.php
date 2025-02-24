<style>
    .icon-calendar {
        top: 6px !important;
    }
</style>
<div class="modal fade" id="modal_agendar_cita" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false"
    tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7" id="display_title_modal">REGISTRAR NUEVA CITA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_data_cita">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0" id="display_title_card"><i class="bi bi-person-vcard-fill"></i> AGENDAR NUEVA CITA</h4>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 mb-2">
                                    <div class="input-group">
                                        <label for="sucursal_emp" class="input-group-title1" title="sucursal">Sucursal*:
                                        </label>
                                        <select name="sucursal_emp" class="form-select border-radius i-validate-cita"
                                            data-toggle="tooltip" data-placement="bottom">
                                            @if(count($sucursales) == 1)
                                            @foreach($sucursales as $item)
                                            <option value="{{ $item['id'] }}" selected>{{ $item['nombre'].' -
                                                tel.:'.$item['telefono'] }}</option>
                                            @endforeach
                                            @else
                                            <option value="">Selecccionar</option>
                                            @foreach($sucursales as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nombre'].' -
                                                tel.:'.$item['telefono'] }}</option>
                                            @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="codigo_empleado" type="text"
                                            class="custom-input material i-validate-cita" value="" placeholder=" "
                                            style="margin-bottom: 0px;text-transform: uppercase">
                                        <label class="input-label" for="codigo_empleado"
                                            title="código colaborador">Código colaborador</label>
                                        <div class="valid-feedback m-0" id="display_loader_cita"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="nombre_empleado" type="text"
                                            class="custom-input material i-validate-cita" value="" placeholder=" "
                                            style="text-transform: uppercase">
                                        <label class="input-label" for="nombre_empleado" title="nombre">Nombre
                                            completo</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="telefono" type="text" class="custom-input material i-validate-cita"
                                            value="" placeholder=" ">
                                        <label class="input-label" for="telefono" title="teléfono">Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-6 mb-2">
                                    <div class="content-input mb-2">
                                        <input id="fecha_cita" name="fecha_cita" type="text"
                                            class="custom-input material i-validate-cita" value="" placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="fecha_cita" title="fecha cita">Fecha
                                            cita</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                    <div class="input-group">
                                        <label for="hora_cita" title="hora" class="input-group-title1">horarios
                                            disponibles </label>
                                        <select name="hora_cita" id="hora_cita" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar hora de cita">
                                            <option value="">Selecccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                    <div class="content-input mb-2">
                                        <textarea id="motivo" rows="3" name="motivo" type="text"
                                            class="form-control material i-validate-cita" value=""
                                            placeholder=" " style="text-transform: uppercase"></textarea>
                                        <label class="input-label-textarea" for="motivo" title="motivo">Motivo</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                    <div class="content-input">
                                        <input id="fecha_inicio_sintoma" name="fecha_inicio_sintoma" type="text"
                                            class="custom-input material i-validate-cita" value="" placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="fecha_inicio_sintoma" title="fecha de inicio de sintoma o enfermedad">Inicio de sintoma/enfermedad
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btnSaveCitaCalendar" id="btnSaveCitaCalendar"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>