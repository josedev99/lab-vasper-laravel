//Section colaborador
var formColaborador = document.getElementById('register_colaborador');
var formColaboradorChild = document.getElementById('register_colaborador_child');

var showInfoColaborador = document.getElementById('show_info_colaborador');
var showInfoColaboradorChild = document.getElementById('show_info_colaborador_child');

//Section Consulta y historial
var contentConsultHistorial = document.getElementById('content_consult_historial');
var contentConsultHistorialChild = document.getElementById('content_consult_historial_child');
//componenente de incapacidades
var component_incapacidad = document.getElementById('component_incapacidad');
var component_incapacidad_child = document.getElementById('component_incapacidad_child');

document.addEventListener('DOMContentLoaded', () => {
    try {
        dataTable('dt_listados_emp', route('registroMedico.listado'), { opcion: '', fecha_filtro: '' });
        //selectize area departamento
        $("#area_departamento_emp").selectize();
        //set fecha flatpick
        flatpickr("#fecha_filtro", {
            locale: "es",
            maxDate: "2050",
            minDate: "2024",
            dateFormat: "d/m/Y",
            disableMobile: "true",
        });
        //fechas incapacidades
        flatpickr("#fecha_expedicion", {
            locale: "es",
            maxDate: "2050",
            minDate: "2024",
            dateFormat: "d/m/Y",
            disableMobile: "true",
        });
        flatpickr("#rango_fecha_incapacidad", {
            locale: 'es',
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            locale: "es",
            altFormat: "d/m/Y",
            allowInput: true,
            onChange: function (array_fechas) {

                if (array_fechas.length === 2) {
                    let fecha_desde = array_fechas[0];
                    let fecha_hasta = array_fechas[1];
                    calc_diff_days(fecha_desde, fecha_hasta, 'dias_incapacitado');
                }
            }
        });
        //variables

        let opcion = document.querySelectorAll('input[name="opcionConsulta"]');
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
                let opcion = document.querySelector('input[name="opcionConsulta"]:checked');
                
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
        //button edicion
        let table = new DataTable('#dt_listados_emp');
        table.on('draw.dt', attachEventsDT);
        /**
         * Incacidades helpers
         * checkbox input funcionalidad
         */
        let checkIncapacidad = document.getElementById('checkIncapacidad');
        if (checkIncapacidad) {
            checkIncapacidad.addEventListener('click', (event) => {
                if (event.target.checked) {
                    if (!component_incapacidad.contains(component_incapacidad_child)) {
                        component_incapacidad.appendChild(component_incapacidad_child);
                    }
                } else {
                    if (component_incapacidad.contains(component_incapacidad_child)) {
                        component_incapacidad.removeChild(component_incapacidad_child);
                    }
                }
            })
        }
        /**
         * Codigo para agregar incapacidad desde consulta
         */
        //modal departamento
        let btn_add_motivo = document.querySelector('.btn-add-motivo');
        if (btn_add_motivo) {
            btn_add_motivo.addEventListener('click', (e) => {
                e.preventDefault();
                $("#new_motivo").modal('show');
            })
        }


        //Save departamento empleado
        let form_data_motivo = document.getElementById('form_data_motivo');
        if (form_data_motivo) {
            form_data_motivo.addEventListener('submit', async (e) => {
                e.preventDefault();
                let formData = new FormData(form_data_motivo);

                for (let [key, value] of formData.entries()) {
                    let labelTextContent = document.querySelector('label[for="' + key + '"]').textContent;
                    if (!value.trim()) {
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${labelTextContent} es requerido.`
                        });
                        return;
                    }
                }
                
                let response = await axios.post(route('app.incapacidades.motivo.save'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response.data.status === "success") {
                    Toast.fire({
                        icon: "success",
                        title: response.data.message
                    });
                    $("#new_motivo").modal('hide');
                    let motivos = $("#motivo").selectize()[0].selectize;
                    
                    motivos.addOption({
                        value: response.data.results.id,
                        text: response.data.results.motivo
                    });
                    motivos.addItem(response.data.results.id);
                    form_data_motivo.reset();
                } else if (response.data.status === "warning") {
                    Toast.fire({
                        icon: "warning",
                        title: response.data.message
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.data.message
                    });
                }
            })
        }
    } catch (err) {
        console.log(err)
    }
});

function calc_diff_days(fecha1, fecha2, input) {
    let fecha1_format = moment(fecha1);
    let fecha2_format = moment(fecha2);
    let days_diff = fecha2_format.diff(fecha1_format, 'days');
    document.getElementById(input).value = days_diff + ' días de incapacidad';
}

function attachEventsDT() {
    let buttonsAddConsults = document.querySelectorAll('.btn-consulta');
    if (buttonsAddConsults) {
        buttonsAddConsults.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                let ref_id = btn.dataset.ref;
                let codigo_empleado = btn.dataset.codigo_emp;
                let opcion = btn.dataset.opcion;
                
                sessionStorage.setItem('opcionSelected',opcion);
                if(opcion === "citas"){
                    sessionStorage.setItem('cita_id',ref_id);
                }else if(opcion === "colaboradores"){
                    sessionStorage.removeItem('cita_id');
                }
                sessionStorage.removeItem('consulta_id'); //remove consulta_id

                console.log(sessionStorage.getItem('cita_id'));
                //datos preparados
                sessionStorage.removeItem('is_data_preparado');
                if(opcion === "preparados"){
                    loadDataConsulta(ref_id);
                }else{
                    verifyEmpleado(codigo_empleado,ref_id);
                }

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

            //evento de carga is remove element de incapacidad (child)
            if (component_incapacidad.contains(component_incapacidad_child)) {
                component_incapacidad.removeChild(component_incapacidad_child);
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
            //document.getElementById('motivo_consulta').value = result.data.results.motivo;
            //new code
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
        } else if(result.data.status === "exists_consult_proceso"){
            Swal.fire({
                title: "Aviso",
                text: "El colaborador tiene una consulta pendiente de revisión.",
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