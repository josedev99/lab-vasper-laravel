<div class="modal fade" id="modal-form-cliente" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">NUEVO CLIENTE</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_data_orden">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> Agregar información del cliente</h4>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="nombre_empleado" type="text"
                                            class="custom-input material validInput" value="" placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="nombre_empleado">Nombre completo</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="fecha_nac" id="fecha_nac" type="text" class="custom-input material validInput" value="" placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="fecha_nac">Fecha de nacimiento</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <label for="genero_emp" class="input-group-title1">Género*: </label>
                                        <select name="genero_emp" class="form-select validInput border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="0">Selecccionar</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="telefono" type="text"
                                            class="custom-input material validInput" value="" placeholder=" ">
                                        <label class="input-label" for="telefono">Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-9 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="direccion_cliente" type="search"
                                            class="custom-input material validInput" value="" placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="direccion_cliente">Dirección</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0"><i class="bi bi-file-medical"></i> Examenes clinicos</h4>
                        </div>
                        <div class="card-body p-1">
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
                            <div class="row p-1">
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
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveCliente"><i class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>