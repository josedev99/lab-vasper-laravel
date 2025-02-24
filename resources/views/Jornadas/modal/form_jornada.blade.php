<style>
    .flatpickr-current-month{
        display: block !important;
    }
</style>
<div class="modal fade" id="modal_form_jornada" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7" id="modal_title_jornada">REGISTRAR NUEVA JORNADA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_data_jornada">
                    <div class="card p-1 mb-0">
                        <div class="card-header p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input name="fecha_jornada" id="fecha_jornada" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="fecha_jornada" title="Fecha jornada">Mes/Año</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="input-group mb-2">
                                        <label for="laboratorio" class="input-group-title1" title="Laboratorio">Laboratorio</label>
                                        <select name="laboratorio" id="laboratorio" class="form-select border-top-bottom-left"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar laboratorio">
                                            <option value="">Selecccionar</option>
                                            @foreach($laboratorios as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['laboratorio'] }}</option>
                                            @endforeach
                                        </select>
                                        <label class="input-group-label1 bg-info btnAdd btn-add-lab"><i
                                                class="bi bi-plus-lg" style="font-size: 16px"></i></label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="content-input mb-2">
                                        <input name="jornada" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="jornada" title="Nombre de la jornada">Nombre de la jornada</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button class="btn btn-outline-success btn-sm"><i class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>