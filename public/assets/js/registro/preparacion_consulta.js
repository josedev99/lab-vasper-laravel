//Section colaborador
var formColaborador = document.getElementById('register_colaborador');
var formColaboradorChild = document.getElementById('register_colaborador_child');

var showInfoColaborador = document.getElementById('show_info_colaborador');
var showInfoColaboradorChild = document.getElementById('show_info_colaborador_child');

//Section Consulta y historial
var contentConsultHistorial = document.getElementById('content_consult_historial');
var contentConsultHistorialChild = document.getElementById('content_consult_historial_child');

var quill_motivo = null;

document.addEventListener('DOMContentLoaded', App);

//loader app
function App(){
    //fecha de inicio de sintoma
    flatpickr("#fecha_inicio_sintoma", {
        locale: "es",
        maxDate: "2050",
        minDate: "2022",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });
    //quill editors
    const toolbarOptions = [
        [{ 'size': ['small', false, 'large', 'huge'] }],
        ['bold', 'italic', 'underline'],        // toggled buttons
        ['blockquote'],
        ['link'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'align': [] }],

        ['clean']                                     // remove formatting button
    ];
    quill_motivo = new Quill('#motivo-editor', {
        theme: 'snow',
        placeholder: '...',
        modules: {
            toolbar: toolbarOptions
        }
    });
    //section Buttons datatable
    let opcion = document.querySelectorAll('input[name="opcionCitaColab"]');
    let fecha_filtro = document.getElementById('fecha_filtro');
    if (opcion && fecha_filtro) {
        opcion.forEach((input) => {
            input.addEventListener('click', (e) => {
                let component_date_filter = document.getElementById('component-date-filter');
                if(input.value === "citas"){
                    if(component_date_filter.classList.contains('d-none')){
                        component_date_filter.classList.remove('d-none');
                    }
                    if (fecha_filtro.value !== "") {
                        dataTable('dt_listados_emp', route('registroMedico.listado'), { opcion: e.target.value, fecha_filtro: fecha_filtro.value });
                    } else {
                        Toast.fire({
                            icon: "warning",
                            title: `La fecha para filtrar los datos es requeridad.`
                        });
                    }
                }else{
                    if(!component_date_filter.classList.contains('d-none')){
                        component_date_filter.classList.add('d-none');
                    }
                    dataTable('dt_listados_emp', route('registroMedico.listado'), { opcion: e.target.value, fecha_filtro: '' });
                }
                
            })
        })
        fecha_filtro.addEventListener('change', (e) => {
            let opcion = document.querySelector('input[name="opcionCitaColab"]:checked');
            
            if(opcion.value === "citas"){
                dataTable('dt_listados_emp', route('registroMedico.listado'), { opcion: opcion.value, fecha_filtro: e.target.value });  
            }else if(opcion.value === "colaboradores"){
                dataTable('dt_listados_emp', route('registroMedico.listado'), { opcion: opcion.value, fecha_filtro: '' });  
            } else {
                Toast.fire({
                    icon: "warning",
                    title: `Por favor seleccionar una opción.`
                });
            }
        })
    }
    let table = new DataTable('#dt_listados_emp');
    table.on('draw.dt', attachEventsDT);
    /**
     * GUARDAR CONSULTA PREVIA
     * @VERSION: 1.1.0
     */
    let formConsulta = document.getElementById('register_consulta');
    if (formConsulta) {
        formConsulta.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(formConsulta);

            if (sessionStorage.getItem('is_consulta') === "Si") {

                //validacion de motivo de consulta
                let motivo_consulta = quill_motivo.root.innerHTML;
                if(quill_motivo === null){
                    Toast.fire({
                        icon: "warning",
                        title: `El motivo de la consulta es obligatorio.`
                      });return;
                }
                //validacion de input de form consulta
                let inputs = document.querySelectorAll('.input-valid-consult');
                for (let index = 0; index < inputs.length; index++) {
                    let input = inputs[index];
                    if (!input.value.trim()) {    
                        input.classList.add('border-valid');               
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${input.title} es requerido.` 
                          });
                        return;
                    }else{
                        input.classList.remove('border-valid');
                    }
                }

                formData.append('empleado_id', sessionStorage.getItem('empleado_id'));
                formData.append('motivo_consulta',motivo_consulta);
                //validacion segun opcion seleccionado en la Interfaz

                if(sessionStorage.getItem('opcionSelected') === "citas"){
                    formData.append('cita_id',sessionStorage.getItem('cita_id'));
                }

                axios.post(route('consulta.save'), formData)
                    .then((result) => {
                        if (result.data.status === "success") {
                            //RESET EDITOR
                            quill_motivo.root.innerHTML = '';

                            $("#modal_reg_consulta").modal('hide');
                            Swal.fire({
                                title: "Éxito",
                                text: result.data.message,
                                icon: "success"
                            });
                            formConsulta.reset();
                            //destroy cita_id
                            if(sessionStorage.getItem('opcionSelected') === "citas"){
                                sessionStorage.removeItem('cita_id');
                            }
                            //remove empleado_id
                            sessionStorage.removeItem('empleado_id');
                            //reload datatable
                            $("#dt_listados_emp").DataTable().ajax.reload(null,false);

                            //reset border valid signos vitales y medidas
                            let inputs = document.querySelectorAll('.reset_valid');
                            inputs.forEach((input) => input.style.border = '1px solid #dadce0');
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: result.data.message,
                                icon: "error"
                            });
                        }
                    }).catch((err) => {
                        console.log(err)
                    });

            } else {
                //validacion de inputs
                let inputs = document.querySelectorAll('.c_valid_emp');
                for (let index = 0; index < inputs.length; index++) {
                    let input = inputs[index];
                    let labelTextContent = document.querySelector('label[for="' + input.name + '"]').textContent;
                    if (input.value.trim() === "" || input.value === "0") {
                        input.classList.add('border-valid');
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${labelTextContent} es requerido.`
                        });
                        return;
                    }
                    input.classList.remove('border-valid');
                }
                //validacion para departamento campo con selectize
                let depto_emp = document.getElementById('area_departamento_emp');
                if (depto_emp.value === "") {
                    Toast.fire({
                        icon: "warning",
                        title: `El campo área/departamento es requerido.`
                    });
                    return;
                }

                //loader
                document.getElementById('loading_full_screen').style.display = 'flex';

                axios.post(route('app.empleados.save'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((result) => {

                    if (result.data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: 'Colaborador agregado exitosamente. Puede llenar su registro médico.',
                            icon: "success"
                        });

                        formConsulta.reset();
                        $("#area_departamento_emp").selectize()[0].selectize.clear();
                        //TIEMPO de carga de la otra GUI
                        setTimeout(() => {
                            sessionStorage.setItem('empleado_id', result.data.results.id);
                            showHistorialConsultas(result.data.results.id); //Listar consultas

                            document.getElementById('titleSectionColaborador').innerHTML = `<i class="bi bi-person-vcard-fill" ></i> Información del colaborador.`;
                            sessionStorage.setItem('is_consulta', 'Si');//update para agregar consulta de la misma modal
                            if (formColaborador.contains(formColaboradorChild)) {
                                formColaborador.removeChild(formColaboradorChild);
                            }
                            if (!showInfoColaborador.contains(showInfoColaboradorChild)) {
                                showInfoColaborador.appendChild(showInfoColaboradorChild);
                            }

                            if (!contentConsultHistorial.contains(contentConsultHistorialChild)) {
                                contentConsultHistorial.appendChild(contentConsultHistorialChild);
                            }
                            //Set info colaborador
                            document.querySelector('.cod_colaborador').value = result.data.results.codigo_empleado;
                            document.querySelector('.nombre_colaborador').value = result.data.results.nombre;
                            document.querySelector('.telefono_colaborador').value = result.data.results.telefono;
                        }, 1500);

                    } else if (result.data.status === "exists") {
                        Toast.fire({
                            icon: "warning",
                            title: result.data.message
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                        });
                    }
                    document.getElementById('loading_full_screen').style.display = 'none';
                }).catch((err) => {
                    document.getElementById('loading_full_screen').style.display = 'none';
                    console.log(err);
                });
            }
        });
    }
}

function attachEventsDT() {
    let buttonsAddConsults = document.querySelectorAll('.btn-consulta');
    if (buttonsAddConsults) {
        buttonsAddConsults.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                let cita_id = btn.dataset.ref;
                let codigo_empleado = btn.dataset.codigo_emp;
                let opcion = btn.dataset.opcion;
                
                sessionStorage.setItem('opcionSelected',opcion);

                if(opcion === "citas"){
                    sessionStorage.setItem('cita_id',cita_id);
                }else if(opcion === "colaboradores"){
                    sessionStorage.removeItem('cita_id');
                }

                verifyEmpleado(codigo_empleado,cita_id);
            })
        })
    }
}

function verifyEmpleado(codigo_empleado,cita_id) {
    axios.post(route('app.empleado.verify'), { codigo_empleado: codigo_empleado,cita_id: cita_id }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {

        let component_check_cosulta = document.getElementById('component-check-historial-consult');
        if (result.data.status === "success") {
            //checke false incapacidad
            if(document.getElementById('checkIncapacidad')){
                document.getElementById('checkIncapacidad').checked = false;
            }

            //mostrar check de historial consulta
            component_check_cosulta.classList.replace('d-none','d-inline');

            showHistorialConsultas(result.data.results.id);

            sessionStorage.setItem('empleado_id', result.data.results.id);

            sessionStorage.setItem('is_consulta', 'Si');
            document.getElementById('titleSectionColaborador').innerHTML = `<i class="bi bi-person-vcard-fill" ></i> Información del colaborador.`;
            //Validacion
            if (formColaborador.contains(formColaboradorChild)) {
                formColaborador.removeChild(formColaboradorChild);
            }

            if (!showInfoColaborador.contains(showInfoColaboradorChild)) {
                showInfoColaborador.appendChild(showInfoColaboradorChild);
            }

            if (!contentConsultHistorial.contains(contentConsultHistorialChild)) {
                contentConsultHistorial.appendChild(contentConsultHistorialChild);
            }

            document.querySelector('.cod_colaborador').value = result.data.results.codigo_empleado;
            document.querySelector('.nombre_colaborador').value = result.data.results.nombre;
            document.querySelector('.telefono_colaborador').value = result.data.results.telefono;
            //set fecha inicio sintoma
            if(result.data.results.fecha_inicio_sintoma !== ""){
                document.getElementById('fecha_inicio_sintoma').value = result.data.results.fecha_inicio_sintoma;
                document.getElementById('fecha_inicio_sintoma').dispatchEvent(new Event('change'));
            }else{
                document.getElementById('fecha_inicio_sintoma').value = ''; //set empty
            }
            //cargar motivo si es cita
            quill_motivo.root.innerHTML = `<p>${result.data.results.motivo}</p>`;
            $("#modal_reg_consulta").modal('show');
        } else if (result.data.status === "not-data") {
            //configuraciones para evitar mostrar el historial
            if(!document.getElementById('component_historial').classList.contains('d-none')){
                document.getElementById('checkHisConsult').checked = false; //checked false
                document.getElementById('component_form_consulta').classList.replace('col-md-8','col-md-12');
                document.getElementById('component_historial').classList.add('d-none');
            }

            //ocultar check de historial consulta
            component_check_cosulta.classList.replace('d-inline','d-none');

            sessionStorage.setItem('is_consulta', 'No');

            document.getElementById('titleSectionColaborador').innerHTML = `<i class="bi bi-person-vcard-fill" ></i> El colaborador aún no está registrado. Complete el formulario.`;
            if (!formColaborador.contains(formColaboradorChild)) {
                formColaborador.appendChild(formColaboradorChild);
            }

            if (showInfoColaborador.contains(showInfoColaboradorChild)) {
                showInfoColaborador.removeChild(showInfoColaboradorChild);
            }

            if (contentConsultHistorial.contains(contentConsultHistorialChild)) {
                contentConsultHistorial.removeChild(contentConsultHistorialChild);
            }

            //add info form
            document.querySelector('.nombre_colaborador').value = result.data.results.nombre;
            document.querySelector('.telefono_colaborador').value = result.data.results.telefono;
            $("#modal_reg_consulta").modal('show');
        }else if(result.data.status === "exists_consult_proceso"){
            let opcion = sessionStorage.getItem('opcionSelected');
            if(opcion === "citas"){
                sessionStorage.removeItem('cita_id',cita_id);
            }

            Swal.fire({
                title: "Aviso",
                text: result.data.message,
                icon: "warning"
            });
        } else {
            Toast.fire({
                icon: "error",
                title: response.data.message
            });
        }
    }).catch((err) => {
        console.log(err);
    });
}