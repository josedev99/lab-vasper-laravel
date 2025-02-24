<style>
    .error {
        border-color: red;
    }
    .dropdown-empresas {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    z-index: 1000;
}

.dropdown-empresas .empresa-option {
    padding: 8px;
    cursor: pointer;
}

.dropdown-empresas .empresa-option:hover {
    background-color: #f0f0f0;
}

</style>
<div class="modal fade" id="CrearEmpresa" tabindex="-1" aria-labelledby="addEditEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light" style="padding: 3px 20px !important;">
                <h5 class="modal-title" id="addEditEmpresaLabel">Agregar Empresa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card p-2 mb-0">
                    <div class="row" enctype="multipart/form-data">
                        <div class="col-md-7">
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" placeholder=" " 
                                       id="nombreEmpresa" name="nombreEmpresa" oninput="filtrarEmpresas()" autocomplete="off">
                                <label class="input-label clear-input" for="nombreEmpresa">Nombre de la Empresa*</label>
                                <div id="dropdownEmpresas" class="dropdown-empresas">
                                    <!-- Aquí se añadirán las opciones dinámicamente desde JavaScript -->
                                </div>
                            </div>
                            <input id="existenciaValue" name="existenciaValue" type="hidden" />
                            <input id="idEmpresa" name="idEmpresa" type="hidden" />

                             <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value=""
                                    placeholder=" " id="direccionEmpresa" name="direccionEmpresa">
                                <label class="input-label" for="">Direccion</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear_input material" value=""
                                    placeholder=" " maxlength="9" name="telefonoEmpresa">
                                <label class="input-label" for="">Telefono</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value=""
                                    placeholder=" " maxlength="9" name="celularEmpresa">
                                <label class="input-label" for="">Celular</label>
                            </div>
                                <div class="content-input mb-2">
                                    <input type="text" class="custom-input material clear-input material" value=""
                                        placeholder=" " id="nRegistroEmpresa">
                                    <label class="input-label" for="">N· Registro</label>
                                </div>
                                <div class="content-input mb-2">
                                    <input type="text" class="custom-input material clear-input material" value=""
                                        placeholder=" " name="giro">
                                    <label class="input-label" for="">Giro</label>
                                </div>
                                <div class="content-input mb-2">
                                    <input type="text" class="custom-input material clear-input material" value=""
                                        placeholder=" " name="encargado">
                                    <label class="input-label" for="">Encargado</label>
                                </div>

                            </div>
                                <div class="col-sm-12 col-md-5">
                                    <div class="input-group mb-2">
                                        <input type="text" readonly onclick="loadImage()" class="form-control input-file" value=""
                                            placeholder="Imagen">
                                        <button class="btn-file" onclick="loadImage()" type="button"><i
                                                class="bi bi-card-image icon-input"></i></button>
                                    </div>
                                    <input name="image" type="file" id="file_logo" accept="logo/jpeg, logo/png"
                                        class="d-none d-flex align-items-center justify-content-center">
                                    <div class="card">
                                        <div class="card-body content-preview-img px-2 py-1">
                                            <div id="loading" class="d-none">Cargando...</div></span>
                                            <br>
                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                <img id="showImagePreview" src="" style="max-width: 100%; max-height: 175px;">
                                            </div>
                                        </div>
                                        <div id="errors" class="alert alert-danger d-none">
                                            <ul id="errorList">
                                                <!-- Error messages will appear here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                    </div>
                    <div class="btns d-flex justify-content-end my-2">
                        <button onclick="saveEmpresa()" class="btn btn-outline-primary btn-sm btn-inline">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", cargarEmpresas);
    let empresasData = [];
    let empresaSeleccionada = false;
    
    function cargarEmpresas() {
        const empresasApiUrl = "{{ route('empresas.get.api') }}";
    
        axios.post(empresasApiUrl)
            .then(response => {
                empresasData = response.data; // Guardamos las empresas en una variable global
            })
            .catch(error => {
                console.error("Error al cargar empresas:", error);
            });
    }
    
    function filtrarEmpresas() {
        const input = document.getElementById('nombreEmpresa');
        const dropdown = document.getElementById('dropdownEmpresas');
        const filtro = input.value.toLowerCase();
    
        dropdown.innerHTML = '';
        const empresasFiltradas = empresasData.filter(empresa => 
            empresa.nombre.toLowerCase().includes(filtro)
        );
    
        if (empresasFiltradas.length > 0) {
            dropdown.style.display = 'block';
    
            empresasFiltradas.forEach(empresa => {
                const option = document.createElement('div');
                option.className = 'empresa-option';
                option.textContent = empresa.nombre;
                option.onclick = () => seleccionarEmpresa(empresa.nombre);
                dropdown.appendChild(option);
            });
        } else {
            dropdown.style.display = 'none';
            empresaSeleccionada = false;
            document.getElementById('existenciaValue').value = 'nuevo'; 
            document.getElementById('idEmpresa').value = '';
        }
    }
    
    // Función para seleccionar una empresa y ocultar el dropdown
    function seleccionarEmpresa(nombre) {
        const input = document.getElementById('nombreEmpresa');
        const dropdown = document.getElementById('dropdownEmpresas');
    
        const empresaData = empresasData.find(empresa => empresa.nombre === nombre);
    
        if (empresaData) {
            input.value = empresaData.nombre;
            document.getElementById('direccionEmpresa').value = empresaData.direccion || '';
            document.getElementsByName('telefonoEmpresa')[0].value = empresaData.telefono || '';
            document.getElementsByName('celularEmpresa')[0].value = empresaData.celular || '';
            document.getElementById('nRegistroEmpresa').value = empresaData.nrc || '';
            document.getElementsByName('giro')[0].value = empresaData.giro || '';
            document.getElementById('idEmpresa').value = empresaData.id || '';
            document.getElementsByName('encargado')[0].value = empresaData.responsable || '';
            document.getElementById('existenciaValue').value = 'existencia';
            empresaSeleccionada = true;
            
            dropdown.style.display = 'none';
        }
    }
    
    document.addEventListener("click", function(event) {
        const dropdown = document.getElementById('dropdownEmpresas');
        const input = document.getElementById('nombreEmpresa');
        
        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });
    
    function saveEmpresa() {
        const existenciaValue = document.getElementById('existenciaValue').value;
        
        if (!empresaSeleccionada) {
            document.getElementById('existenciaValue').value = 'nuevo';
        }

    }


// Función para enviar los datos de la empresa a otro sistema
function enviarDatosAOtroSistema(empresaData) {
    const apiUrlOtroSistema = "{{ route('empresas.save.api') }}";
    console.log(empresaData)

    axios.post(apiUrlOtroSistema, empresaData)
    .then(response => {
        if(response.data.results.length > 0){
            listarDTColab(response.data.results);
            $("#modal-det-colab-import").modal('show');
        }
        console.log('Datos enviados correctamente al otro sistema:', response.data);
    })
    .catch(error => {
        console.error('Error al enviar los datos al otro sistema:', error);
    });
}
    </script>