<style>
 
</style>
<div class="modal fade" id="CrearUsuarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-move bg-dark modal-move" style='cursor:move;padding:5px 12px;'>
                <h4 class="modal-title text-light" style="font-size: 14px;" id="titleModal">REGISTRAR USUARIO</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <form id="CrearUsuario"">
                @csrf
                <div class="modal-body">
                    <div class="modal-body card py-1 mb-0">
                        <div class="form-group row ">
                            <div class="col-sm-12 col-md-8 col-lg-4">
                                <div class="content-input mb-2">
                                    <input id="nombreUsuario" name="nombreUsuario" type="text"
                                        class="custom-input material" value="" placeholder=" " placeholder=" ">
                                    <label class="input-label" for="">Nombre*</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-2">
                                <div class="content-input mb-2">
                                    <input id="telefonoUsuario" name="telefonoUsuario" type="number" class="custom-input material" value="" placeholder=" ">
                                    <label class="input-label" for="">Telefono</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                <div class="content-input mb-2">
                                    <input id="CorreoUsuario" name="CorreoUsuario" type="text" class="custom-input material" value="" placeholder=" ">
                                    <label class="input-label" for="">Correo</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <div class="content-input mb-2">
                                    <input id="duiUsuario" name="duiUsuario" type="search" class="custom-input material" value="" placeholder=" ">
                                    <label class="input-label" for="">DUI</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
                                <div class="content-input mb-2">
                                    <input id="nitUsuario" name="nitUsuario" type="number" class="custom-input material" value="" placeholder=" ">
                                    <label class="input-label" for="">NIT</label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <div class="content-input mb-2">
                                    <input id="direccionUsuario" name="direccionUsuario" type="text" class="custom-input material" value="" placeholder=" ">
                                    <label class="input-label" for="">Direccion</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-2">
                                <div class="input-group">
                                    <label for="SucursalUsuario" class="input-group-title1">Sucursal*: </label>
                                    <select id="SucursalUsuario" name="SucursalUsuario"
                                        class="form-select" data-toggle="tooltip" data-placement="bottom" title="Selec. cuotas">
                                        <option value="" selected>Selec...</option>
                                         @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endforeach                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-2">
                                <div class="input-group">
                                    <label for="cuotas_desc" class="input-group-title1">Cargo*: </label>
                                    <select id="cargoUsuario" name="cargoUsuario"  class="form-select" data-toggle="tooltip" data-placement="bottom" title="Seleccionar">
                                        <option value="" selected>Selec...</option>
                                        <option value="medico">MEDICO</option>
                                        <option value="enfermera">ENFERMERA/O</option>
                                        <option value="rrhh">RRHH</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-2">
                                <div class="input-group">
                                    <label for="estadoUsuario" class="input-group-title1">Estado*: </label>
                                    <select id="estadoUsuario" name="estadoUsuario"
                                        class="custom-select form-select" data-toggle="tooltip" data-placement="bottom" title="Seleccionar">
                                        <option value="" selected>Selec...</option>
                                        <option value="1">Habilitado</option>
                                        <option value="2">Deshabilitado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="content-input mb-2">
                                    <input id="Usuario" name="Usuario" type="text" class="custom-input" value="" placeholder=" ">
                                    <label class="input-label" for="">Usuario*</i></label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="content-input mb-2">
                                    <input id="Contraseña" name="Contraseña" type="password" class="custom-input" value="" placeholder=" ">
                                    <label class="input-label" for="">Contraseña*</label>
                                </div>
                            </div>

                                        <div class="row g-3"> <!-- Reduced gutter for closer columns -->
                                            @foreach($modul as $index => $modulo)
                                                <div class="col-md-6">
                                                    <div class="accordion-item shadow-sm" style="border-radius: 10px; border: 1px solid #ddd;">
                                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}" style="background-color: #fdfdfd; border-radius: 10px;">
                                                                <div class="form-check m-0">
                                                                    <label class="form-check-label" for="moduloCheck{{ $modulo['id'] }}" style="font-weight: bold;">
                                                                        {{ $modulo['nombre'] }}
                                                                    </label>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionPermisos">
                                                            <div class="accordion-body p-2" style="background-color: #ffffff; border-radius: 0 0 10px 10px;">
                                                                @if($modulo['permisos']->isNotEmpty())
                                                                    <table class="table table-sm table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">*</th>
                                                                                <th scope="col">Permiso</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($modulo['permisos'] as $permiso)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="checkbox" id="permiso{{ $permiso['id'] }}" name="permisos[]" onclick="gestionarSeleccionPermiso('{{ $permiso['id'] }}', '{{ $permiso['nombre'] }}', '{{ $modulo['id'] }}', '{{ $modulo['nombre'] }}', this)" value="{{ $permiso['id'] }}">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>{{ $permiso['nombre'] }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @else
                                                                    <p>No hay permisos disponibles para este módulo.</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($loop->iteration % 2 == 0)
                                                    </div><div class="row g-3"> <!-- Start a new row every two modules -->
                                                @endif
                                            @endforeach
                                        </div>
                                    
 
                        </div>
            
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer p-0">
                    <button type="button" id="btnRegisOD" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-save"></i> REGISTRAR USUARIO
                    </button>
                    <button type="button" id="btnActualizar" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-save"></i> ACTUALIZAR USUARIO
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>