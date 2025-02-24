document.addEventListener('DOMContentLoaded', ()=>{  
    dataTable("listado_incapacitados",route('app.incapacidades.dt'));
    //flatpickr de resumen     
    flatpickr("#rango_incapacidades", {
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                locale: "es",
                altFormat: "F j, Y",
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        date_rango();
                    }
                }
            });
          
            flatpickr("#rangoCategoriaInca", {
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                locale: "es",
                altFormat: "F j, Y",
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        categoria();
                    }
                }
            });
          
            flatpickr("#rango_departamento", {
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                locale: "es",
                altFormat: "F j, Y",
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        dep();
                    }
                }
            });
          
          
            flatpickr("#rango_riesgo", {
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                locale: "es",
                altFormat: "F j, Y",
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        date_riesgo();
                    }
                }
            });
});


//button edicion
let table = new DataTable('#listado_incapacitados');
table.on('draw.dt', attachEventsDetincap);

//button mensual
let table1 = new DataTable('#listado_incapacitados_Mes');
table1.on('draw.dt', EventTabMes);

//button rango
let table2 = new DataTable('#listado_incapacitados_rango');
table2.on('draw.dt', EventTabMes);

//button rango
let table3 = new DataTable('#listado_incapacitados_Activas');
table3.on('draw.dt', EventTabMes);

//button rango
let table4 = new DataTable('#listar_ranking');
table4.on('draw.dt', EventTabRank);

let btn_new_incapacidad = document.querySelector('.btn_new_incapacidad');

if(btn_new_incapacidad){
    btn_new_incapacidad.addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelector('.btn-save-inc').innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
        let btnRegistrar = document.querySelector('#btn-regi'); // Seleccionamos el botón de registrar
        document.querySelector('input[name="colaborador"]').value = "";
        document.querySelector('input[name="cargo_col"]').value = "";
        document.querySelector('input[name="codigo_empleado_dui"]').value = "";
        document.querySelector('input[name="diagnostico"]').value = "";
        document.querySelector('input[name="codigo_empleado_dui"]').removeAttribute('readonly');

        let btnUpdate = document.querySelector('#btn-update'); // Seleccionamos el botón de registrar

        // Ocultamos el botón de registrar
                if (btnUpdate) {
                    btnUpdate.classList.add('d-none'); // Oculta el botón de "Registrar"
                }

        // Ocultamos el botón de registrar
        if (btnRegistrar) {
            btnRegistrar.classList.remove('d-none'); // Oculta el botón de "Registrar"
        }
        resetForm();

        $("#modal_crear_incapacidad").modal('show');
        const rangoFechaIncapacidadPicker = flatpickr("#rango_fecha_incapacidad", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            locale: "es",
            altFormat: "F j, Y",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];
                    const timeDifference = endDate.getTime() - startDate.getTime();
                    const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1; // +1 para incluir ambos días
                    document.getElementById('dias_incapacitado').value = dayDifference + " Días de incapacidad";
                } else {
                    document.getElementById('dias_incapacitado').value = '';
                }
            }
        });

        // Limpiar al abrir el modal
        rangoFechaIncapacidadPicker.clear();
        document.getElementById('dias_incapacitado').value = ''; // Limpiar también el campo de días incapacitados

        // Inicializar flatpickr para la fecha de expedición
        const fechaExpedicionPicker = flatpickr("#fecha_expedicion", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true,
            locale: "es",
        });

        // Limpiar al abrir el modal
        fechaExpedicionPicker.clear();  

        // Inicializar y limpiar los selectize
        $('#motivo').selectize();
        $('#riesgo').selectize();
        $('#tipo_incapacidad').selectize();
        $('#departamento_col').selectize();

        // Limpiar las selecciones de los selectize
        $("#motivo").selectize()[0].selectize.clear();
        $("#riesgo").selectize()[0].selectize.clear();
        $("#tipo_incapacidad").selectize()[0].selectize.clear();
        $("#departamento_col").selectize()[0].selectize.clear();
    });
}

let codigoEmpleadoInput = document.querySelector('input[name="codigo_empleado_dui"]');

codigoEmpleadoInput.addEventListener('blur', function() {
    ejecutarConsulta();
});

codigoEmpleadoInput.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        ejecutarConsulta();
    }
});

function ejecutarConsulta() {
    let codigoEmpleado = codigoEmpleadoInput.value;

    if (codigoEmpleado.length >= 5) {  
        axios.post('/incapacidades/datosColaborador', {
                codigo_empleado: codigoEmpleado
            })
            .then(function(response) {
                    let empleado = response.data.empleado[0];  

                    document.querySelector('input[name="colaborador"]').value = empleado.nombre;
                    document.querySelector('input[name="cargo_col"]').value = empleado.cargo;
                    let selectize = $('#departamento_col')[0].selectize;
                    selectize.setValue(empleado.area_depto_id);
                
            })
            .catch(function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Paciente no encontrado!",
                  });
            });
    }
}

   //modal departamento
        let btn_add_motivo = document.querySelector('.btn-add-motivo');
        if(btn_add_motivo){
            btn_add_motivo.addEventListener('click', (e)=>{
                e.preventDefault();
                $("#new_motivo").modal('show');
            })
        }


 //Save departamento empleado
 let form_data_motivo = document.getElementById('form_data_motivo');
 if(form_data_motivo){
     form_data_motivo.addEventListener('submit', async (e)=>{
         e.preventDefault();
         let formData = new FormData(form_data_motivo);

         for (let [key, value] of formData.entries()) {
             let labelTextContent = document.querySelector('label[for="'+key+'"]').textContent;
             if (!value.trim()) {                        
                 Toast.fire({
                     icon: "warning",
                     title: `El campo ${labelTextContent} es requerido.` 
                   });
                 return;
             }
         }

         
         let response = await axios.post(route('app.incapacidades.motivo.save'),formData,{
             headers: {
                 'Content-Type': 'multipart/form-data'
             }
         });
         if(response.data.status === "success"){
             Toast.fire({
                 icon: "success",
                 title: response.data.message 
               });
               console.log(response.data)
               $("#new_motivo").modal('hide');
             let motivos = $("#motivo").selectize()[0].selectize;
             console.log(motivos)
             motivos.addOption({
                 value: response.data.results.id,
                 text: response.data.results.motivo
             });
             motivos.addItem(response.data.results.id);
             form_data_motivo.reset();
         }else if(response.data.status === "warning"){
             Toast.fire({
                 icon: "warning",
                 title: response.data.message 
               });
         }else{
             Toast.fire({
                 icon: "error",
                 title: response.data.message 
               });
         }
     })
 }               
            

    if (btn_new_incapacidad) {
        form_data_emp.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            let formData = new FormData(form_data_emp);
            
            // Capturar el rango de fechas del campo correspondiente
            let rangoFecha = document.getElementById('rango_fecha_incapacidad').value; // Asegúrate de que este ID sea el correcto
            console.log(rangoFecha)
            // Separar las fechas de inicio y fin
            let fechas = rangoFecha.split(' a ');
            let fechaInicio = fechas[0]; // Fecha de inicio
            let fechaFin = fechas[1]; // Fecha de fin
            console.log(fechaInicio, fechaFin)
            // Agregar las fechas de inicio y fin al FormData
            formData.append('fecha_inicio', fechaInicio);
            formData.append('fecha_fin', fechaFin);
            // Validación de inputs
            let inputs = document.querySelectorAll('.validInputInc');
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
            
            // Validación para el campo departamento
            let depto_emp = document.getElementById('departamento_col');
            if (depto_emp.value === "") {
                Toast.fire({
                    icon: "warning",
                    title: `El campo área/departamento es requerido.`
                });
                return;
            }
                // Validación para el campo departamento
                let motivoEmp = document.getElementById('motivo');
                if (motivoEmp.value === "") {
                    Toast.fire({
                        icon: "warning",
                        title: `El campo motivo es requerido.`
                    });
                    return;
                }
                    // Validación para el campo departamento
                    let riesgoEmp = document.getElementById('riesgo');
                    if (riesgoEmp.value === "") {
                        Toast.fire({
                            icon: "warning",
                            title: `El campo riesgo es requerido.`
                        });
                        return;
                    }
                            // Validación para el campo departamento
                            let tipoIncEmp = document.getElementById('tipo_incapacidad');
                            if (tipoIncEmp.value === "") {
                                Toast.fire({
                                    icon: "warning",
                                    title: `El campo tipo incapacidad es requerido.`
                                });
                                return;
                            }
                    // Validación para el campo departamento
                    let DateIncEmp = document.getElementById('fecha_expedicion');
                    if (DateIncEmp.value === "") {
                        Toast.fire({
                            icon: "warning",
                            title: `El campo fecha de expedicion es requerido.`
                        });
                        return;
                    }
            // Cambiar el texto del botón para indicar que está procesando
            let btnProcesarInc = document.querySelector('.btn-save-inc');
            btnProcesarInc.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
            
    // Enviar la solicitud usando Axios
    let response = await axios.post(route('app.incapacidades.save'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    });

    // Manejar la respuesta
    if (response.status === 200) {
        form_data_emp.reset(); // Reinicia el formulario
        
        $("#modal_crear_incapacidad").modal('hide'); // Cerrar el modal
        dataTable("listado_incapacitados",route('app.incapacidades.dt'));

        if (response.data.status === "success") {
            Swal.fire({
                title: "Éxito",
                text: response.data.message,
                icon: "success"
            });
        } else {
            Swal.fire({
                title: "Error",
                text: response.data.message,
                icon: "error"
            });
        }

        // Recargar la tabla
    // $("#dt_listado_incapacidades").DataTable().ajax.reload(null, false);
    } else if (response.status === 422) {
        Swal.fire({
            title: "Aviso",
            text: "Ocurrió un error al procesar la solicitud. Por favor, verifica la información ingresada.",
            icon: "warning"
        });
    }

    // Restablecer el texto del botón
    btnProcesarInc.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
    });
    }

    function attachEventsDetincap() {
        let btnEdicions = document.querySelectorAll('.btn-incp');
        let btnRegistrar = document.querySelector('#btn-regi'); 
        let btnUpdate = document.querySelector('#btn-update'); 

        if (btnEdicions) {
            btnEdicions.forEach(btn => {
                btn.addEventListener('click', async () => {
                    let empleado_id = btn.dataset.ref;
                
                    if (btnRegistrar) {
                        btnRegistrar.classList.add('d-none'); 
                    }

                    if (btnUpdate) {
                        btnUpdate.classList.remove('d-none');
                    }

                    let response = await axios.post(route('app.incapacidad.getEmpleado'), {
                        ref_emp: empleado_id
                    }, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                            'Content-Encoding': 'gzip'
                        }
                    });
                    resetForm();
                    let data = response.data;
                    console.log(response.data)
                    document.querySelector('input[name="colaborador"]').value = data.colaborador;
                    document.querySelector('input[name="cargo_col"]').value = data.cargo;
                    document.querySelector('input[name="codigo_empleado_dui"]').value = data.codigo_empleado;
                    document.querySelector('input[name="codigo_empleado_dui"]').setAttribute('readonly', true);
                    document.querySelector('input[name="diagnostico"]').value = data.diagnostico;
                    if (data.categoria_incapacidad) {
                        document.querySelector(`input[name="categoria_incapacidad"][value="${data.categoria_incapacidad}"]`).checked = true;
                    } else {
                        // Si no hay valor, desmarcar todos los botones de radio
                        document.querySelectorAll('input[name="categoria_incapacidad"]').forEach(radio => {
                            radio.checked = false;
                        });
                    }
                    // Inicializar flatpickr para el campo de fecha de expedición
                    flatpickr("#fecha_expedicion", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        allowInput: true,
                        locale: "es",        // Localización en español
                        defaultDate: data.fecha_expedicion  // Asignar la fecha desde la base de datos
                    });

                    document.querySelector('input[name="dias_incapacitado"]').value = data.dias_incapacidad;

                    $('#motivo').selectize();
                    $('#riesgo').selectize();
                    $('#tipo_incapacidad').selectize();
                    $('#departamento_col').selectize();
                    let selectizeDepartamento = $('#departamento_col')[0].selectize;
                    selectizeDepartamento.setValue(data.departamento);
                    let selectizeMotivo = $('#motivo')[0].selectize;
                    selectizeMotivo.setValue(data.motivo1);
                    let selectizeRiesgo = $('#riesgo')[0].selectize;
                    selectizeRiesgo.setValue(data.riesgo);
                    let selectizeTipoIncapacidad = $('#tipo_incapacidad')[0].selectize;
                    selectizeTipoIncapacidad.setValue(data.tipo_incapacidad);
                    let fechaInicio = data.periodo;   // Obtener fecha de inicio desde la base de datos
                    let fechaFin = data.periodo_final; // Obtener fecha de fin desde la base de datos
                    // Inicializar flatpickr con las fechas predeterminadas
                    flatpickr("#rango_fecha_incapacidad", {
                        mode: "range",
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        allowInput: true,
                        locale: "es",
                        defaultDate: [fechaInicio, fechaFin],
                        onChange: function(selectedDates, dateStr, instance) {
                            if (selectedDates.length === 2) {
                                const startDate = selectedDates[0];
                                const endDate = selectedDates[1];
                                const timeDifference = endDate.getTime() - startDate.getTime();
                                const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1; // +1 para incluir ambos días
                                document.getElementById('dias_incapacitado').value = dayDifference + " Días de incapacidad";
                            } else {
                                document.getElementById('dias_incapacitado').value = '';
                            }
                        }
                        // Asignar fechas de inicio y fin
                        
                    });

                    $("#modal_crear_incapacidad").modal('show');
                });
            });
        }
    }

    function resetForm() {
        // Resetear el formulario
        document.getElementById("form_data_emp").reset();
    
        // Limpiar manualmente los campos con readonly o valores no incluidos en reset
        document.getElementById("colaborador").value = "";
        document.getElementById("cargo_col").value = "";
        document.getElementById("dias_incapacitado").value = "";
    
        // Si tienes select con valores por defecto, asegúrate de limpiarlos si no se resetean automáticamente
        document.getElementById("departamento_col").selectedIndex = 0;
        document.getElementById("motivo").selectedIndex = 0;
        document.getElementById("riesgo").selectedIndex = 0;
        document.getElementById("tipo_incapacidad").selectedIndex = 0;
    }


document.getElementById('filtro_ranking').addEventListener('change', function() {
    var filtro = this.value;
    var rangoInputContainer = document.getElementById('rango_ranking').parentElement;

    if (filtro === 'rango') {
        rangoInputContainer.style.visibility = 'visible';
        rangoInputContainer.style.height = 'auto';

        flatpickr('#rango_ranking', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            locale: {
                firstDayOfWeek: 1
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) { // Solo ejecuta la función cuando se seleccionan ambas fechas
                    consultarDatosPorRango(dateStr);
                }
            }
        });
    } else {
        rangoInputContainer.style.visibility = 'hidden';
        rangoInputContainer.style.height = '0'; 
    }

    if (filtro === 'todas') {
        consultarDatosPorFiltro('todas');
    }
});

// Función separada para manejar la consulta en función del rango de fechas
function consultarDatosPorRango(rangoFechas) {
    var fechas = rangoFechas.split(' to '); // Separar las dos fechas

    if (fechas.length === 2) {
        let fechaStart = fechas[0];
        let fechaEnd = fechas[1];

        if (fechaStart && fechaEnd) {
            dataTable("listar_ranking", route('app.incapacidadesRanking.dt', { filtro: 'rango', fechaStart: fechaStart, fechaEnd: fechaEnd }));
        } else {
            console.warn('Por favor, selecciona un rango de fechas válido.');
        }
    } else {
        console.warn('Por favor, selecciona un rango de fechas válido.');
    }
}

// Función para consultar los datos cuando se selecciona el filtro "todas"
function consultarDatosPorFiltro(filtro) {
    dataTable("listar_ranking", route('app.incapacidadesRanking.dt', { filtro: filtro }));
}

document.addEventListener('DOMContentLoaded', function() {
    var rangoInputContainer = document.getElementById('rango_ranking').parentElement;
    rangoInputContainer.style.visibility = 'hidden'; 
    rangoInputContainer.style.height = '0';
});


document.getElementById('contact-tab').addEventListener('click', function() {
    console.log("Botón de pestaña 'Activas' clickeado.");

    dataTable("listado_incapacitados_Activas", route('app.incapacidadesActivas.dt'));

    // Enviar los datos con axios sin await
    axios.post(route('app.resumen.DatosActivos'), {
     })
     .then(function (response) {
         // Manejar la respuesta del servidor
         console.log('Datos recibidos:', response.data);
         let riesgos_contador = response.data.riesgos_contador;

         let categories = Object.keys(riesgos_contador);
         let series = Object.values(riesgos_contador);
         let options = {
             chart: {
               type: 'pie',
               height: 350
             },
             series: series, 
             labels: categories,
             title: {
               text: 'Segun Incapacidad',
               align: 'center'
             },
             dataLabels: {
               enabled: true,
               formatter: function (val, opts) {
                 const exactValue = opts.w.config.series[opts.seriesIndex];
                 return val.toFixed(1) + "% (" + exactValue + ")";
               }
             },
             tooltip: {
               y: {
                 formatter: function (val) {
                   return val + " incapacidades";
                 }
               }
             }
           };
           
           if (window.chart) {
             window.chart.destroy();
           }
           
           window.chart = new ApexCharts(document.querySelector("#chart_Activas"), options);
           window.chart.render();
           





     })
     .catch(function (error) {
         // Manejar el error
         console.error('Error al enviar los datos:', error);
         Swal.fire({
             icon: 'error',
             title: 'Error',
             text: 'Hubo un error al enviar los datos. Por favor, intenta de nuevo.',
         });
     });


});

function EventTabMes() {
    let btnEdicions = document.querySelectorAll('.btn-o-detall');

    if (btnEdicions) {
        btnEdicions.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empleado_id = btn.dataset.emp_ref;

                // Realizamos la solicitud para obtener los datos del empleado
                let response = await axios.post(route('app.incapacidad.getEmpleado'), {
                    ref_emp: empleado_id
                }, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                });

                let data = response.data;
                console.log(data)
                // Asignar los datos a los elementos en el modal
                document.getElementById('detalle_colaborador').innerText = data.colaborador;
                document.getElementById('detalle_cargo').innerText = data.cargo;
                document.getElementById('detalle_codigo_empleado').innerText = data.codigo_empleado;
                document.getElementById('detalle_diagnostico').innerText = data.diagnostico;
                document.getElementById('detalle_fecha_expedicion').innerText = data.fecha_expedicion;
                document.getElementById('detalle_dias_incapacitado').innerText = `${data.dias_incapacidad}`;
                document.getElementById('detalle_motivo').innerText = data.motivo;

                // Riesgo: Asignar el valor y marcar la opción correspondiente
                marcarCheckbox('enfermedad_comun', data.riesgo === 'Enfermedad Comun');
                marcarCheckbox('enfermedad_profesional', data.riesgo === 'Enfermedad Profesional');
                marcarCheckbox('accidente_comun', data.riesgo === 'Accidente Comun');
                marcarCheckbox('accidente_trabajo', data.riesgo === 'Accidente de Trabajo');
                marcarCheckbox('maternidad', data.riesgo === 'Maternidad');

                // Régimen del trabajador
                marcarCheckbox('regimen_general', data.regimen === 'Regimen General');
                marcarCheckbox('trabajador_independiente', data.regimen === 'Trabajador Independiente');

                // Tipo de incapacidad: Inicial o Prórroga
                marcarCheckbox('incapacidad_inicial', data.tipo_incapacidad === 'Inicial');
                marcarCheckbox('incapacidad_prorroga', data.tipo_incapacidad === 'Prorroga');

                // Periodo de incapacidad
                document.getElementById('detalle_periodo').innerText = data.rango_incapacidad;

                // Mostrar el modal
                $('#modal_ver_incapacidad').modal('show');
            });
        });
    }
}

// Función para marcar/desmarcar checkboxes
function marcarCheckbox(id, estado) {
    const checkbox = document.getElementById(id);
    if (checkbox) {
        checkbox.checked = estado;
    }
}

function EventTabRank() {
    let btnEdicions = document.querySelectorAll('.btn-o-detEmpl');

    if (btnEdicions) {
        btnEdicions.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empleado_id = btn.dataset.emp_ref;
                let fechaStart = btn.dataset.fecha_start;
                let fechaEnd = btn.dataset.fecha_end;

                console.log(empleado_id);
                
                // Realizamos la solicitud para obtener los datos del empleado
                let response = await axios.post(route('app.incapacidad.archivosVarios'), {
                    ref_emp: empleado_id,
                    fecha_start: fechaStart,
                    fecha_end: fechaEnd
                }, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                });

                let data = response.data;
                console.log(data);

                // Obtener el contenedor del acordeón
                const accordionContainer = document.getElementById('accordionIncapacidades');
                accordionContainer.innerHTML = ''; // Limpiamos el contenido anterior

                // Generar acordeón con los datos recibidos
                data.forEach((incapacidad, index) => {
                    const accordionItem = `
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading${index}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="false" aria-controls="collapse${index}">
                                    Incapacidad ${index + 1}: ${incapacidad.colaborador}
                                </button>
                            </h2>
                            <div id="collapse${index}" class="accordion-collapse collapse" aria-labelledby="heading${index}" data-bs-parent="#accordionIncapacidades">
                                <div class="accordion-body">
                                    <div class="incapacidad-container">
                                        <div class="incapacidad-header">
                                            INCAPACIDAD MÉDICA
                                        </div>
                                        <div class="incapacidad-info">
                                            <div><strong>Código:</strong> ${incapacidad.codigo_empleado}</div>
                                            <div><strong>Nombre:</strong> ${incapacidad.colaborador}</div>
                                            <div><strong>Cargo:</strong> ${incapacidad.cargo}</div>
                                        </div>
                                        <div class="incapacidad-body">
                                            <table class="incapacidad-table">
                                                <tr>
                                                    <th>Diagnóstico</th>
                                                    <td>${incapacidad.diagnostico}</td>
                                                </tr>
                                                <tr>
                                                    <th>Fecha Expedición</th>
                                                    <td>${incapacidad.fecha_expedicion}</td>
                                                </tr>
                                                <tr>
                                                    <th>Días incapacitado</th>
                                                    <td>${incapacidad.dias_incapacidad}</td>
                                                </tr>
                                                <tr>
                                                    <th>Motivo</th>
                                                    <td>${incapacidad.motivo}</td>
                                                </tr>
                                                <tr>
                                                    <th>Rango de Fecha</th>
                                                    <td>${incapacidad.rango_incapacidad}</td>
                                                </tr>
                                                <tr>
                                                    <th>Riesgo / Tipo</th>
                                                    <td>
                                                        <div class="riesgo-tipo">
                                                            <div><strong>Riesgo:</strong> ${incapacidad.riesgo}</div>
                                                            <div><strong>Tipo:</strong> ${incapacidad.tipo_incapacidad}</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    accordionContainer.innerHTML += accordionItem;
                });
                // Mostrar el modal
                $('#modal_ver_incapacidades').modal('show');
            });
        });
    }
}





// FUNCION PARA CATEGORIA TIPO ISSS CE, EXTERNA
async function categoria() {
    let rangoFechas = document.getElementById('rangoCategoriaInca')._flatpickr.selectedDates;
    let fechaStart = rangoFechas.length > 0 ? rangoFechas[0].toISOString().split('T')[0] : null;
    let fechaEnd = rangoFechas.length > 1 ? rangoFechas[1].toISOString().split('T')[0] : null;

    // Formatear las fechas a dd/mm/yyyy
    function formatearFecha(fecha) {
        if (!fecha) return 'Fecha no disponible';
        let partesFecha = fecha.split('-');
        return `${partesFecha[2]}/${partesFecha[1]}/${partesFecha[0]}`;
    }

    let fechaStartFormateada = formatearFecha(fechaStart);
    let fechaEndFormateada = formatearFecha(fechaEnd);

    dataTable("listado_incapacitados_Mes", route('app.incapacidadesMes.dt', { fechastart: fechaStart, fechaEnd: fechaEnd }));

    try {
        let response = await axios.post(route('app.resumen.DatosMes'), {
            fechastart: fechaStart,
            fechaEnd: fechaEnd
        });
        let categoria_contador = response.data.categoria_contador;
        let categories = Object.keys(categoria_contador);  // ['externo', 'isss', 'isss_ce']
        let series = Object.values(categoria_contador);    // [3, 1, 1]
        let labelFechas = `Incapacidades de ${fechaStartFormateada || 'Fecha inicio'} a ${fechaEndFormateada || 'Fecha final'}`;
        const backgroundColors = [
            'rgba(153, 102, 255, 0.2)',
            'rgba(75, 192, 192, 0.2)',    // Color 4
         'rgba(54, 162, 235, 0.2)',    // Color 5
        ];

        const borderColors = [
            'rgb(153, 102, 255)',
            'rgb(75, 192, 192)',    // Color 4 sin transparencia
            'rgb(54, 162, 235)',    // Color 5 sin transparencia
  
        ];

        let datasetBackgroundColors = categories.map((_, index) => backgroundColors[index % backgroundColors.length]);
        let datasetBorderColors = categories.map((_, index) => borderColors[index % borderColors.length]);
        var options = {
            type: 'bar',  // Cambiar a tipo de gráfico de barras
            data: {
                labels: categories, // Categorías en el eje X
                datasets: [{
                    label: labelFechas,  // Usar las fechas en el label
                    data: series, // Valores en el eje Y
                    backgroundColor: datasetBackgroundColors, // Colores de fondo
                    borderColor: datasetBorderColors, // Colores de borde
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true  // Asegura que comience desde 0
                    }
                },
                
            }
        };
        if (window.chart) {
            window.chart.destroy();
        }
        var ctx = document.getElementById('chart_categoria');
        if (ctx && ctx.getContext) {
            var chartCtx = ctx.getContext('2d');
            window.chart = new Chart(chartCtx, options);
        } else {
            console.error("El elemento con id 'chart_Mensual' no es un canvas o no existe.");
        }

    } catch (error) {
        console.error('Error al enviar los datos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al enviar los datos. Por favor, intenta de nuevo.',
        });
    }
}

// Función para obtener los valores y disparar la lógica
function date_rango() {
    let rangoFechas = document.getElementById('rango_incapacidades')._flatpickr.selectedDates;
    let fechaStart = rangoFechas.length > 0 ? rangoFechas[0].toISOString().split('T')[0] : null;
    let fechaEnd = rangoFechas.length > 1 ? rangoFechas[1].toISOString().split('T')[0] : null;

    dataTable("listado_incapacitados_rango", route('app.incapacidadesRan.dt', {    
        fechastart: fechaStart,
        fechaEnd: fechaEnd 
    }));

    // Enviar los datos con axios sin await
    axios.post(route('app.resumen.DatosRango'), {
        fechastart: fechaStart,
        fechaEnd: fechaEnd
    })
    .then(function (response) {
        // Manejar la respuesta del servidor
        console.log('Datos recibidos:', response.data);
        
        let departamentos = response.data.departamentos;

        // Extraer categorías y series
        let categories = Object.keys(departamentos);
        let menorIgual3 = categories.map(dep => departamentos[dep].menor_igual_3);
        let mayor3 = categories.map(dep => departamentos[dep].mayor_3);

        // Opciones para el gráfico de líneas
        let options = {
            chart: {
                type: 'line',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            series: [{
                name: 'Menor o igual a 3 días',
                data: menorIgual3
            }, {
                name: 'Mayor a 3 días',
                data: mayor3
            }],
            xaxis: {
                categories: categories,
                title: {
                    text: 'Departamentos'
                }
            },
            yaxis: {
                title: {
                    text: 'Cantidad de Incapacidades'
                }
            },
            title: {
                text: 'Incapacidades por Dias/Departamento ',
                align: 'center'
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val) {
                        return val + " incapacidades";
                    }
                }
            }
        };

        // Verificar si hay un gráfico existente y destruirlo
        if (window.chart) {
            window.chart.destroy();
        }

        // Crear nuevo gráfico con ApexCharts
        window.chart = new ApexCharts(document.querySelector("#chart_rango"), options);
        window.chart.render();
    })
    .catch(function (error) {
        // Manejar el error
        console.error('Error al enviar los datos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al enviar los datos. Por favor, intenta de nuevo.',
        });
    });
}

function date_riesgo() {
    // Obtener el rango de fechas
    let rangoFechas = document.getElementById('rango_riesgo')._flatpickr.selectedDates;
    let fechaStart = rangoFechas.length > 0 ? rangoFechas[0].toISOString().split('T')[0] : null;
    let fechaEnd = rangoFechas.length > 1 ? rangoFechas[1].toISOString().split('T')[0] : null;

    // Llamar a la tabla de datos
    dataTable("listado_incapacitados_riesgo", route('app.incapacidadesRan.dt', {   
        fechastart: fechaStart,
        fechaEnd: fechaEnd
    }));

    // Enviar los datos con axios
    axios.post(route('app.resumen.DatosRiesgo'), {
        fechastart: fechaStart,
        fechaEnd: fechaEnd
    })
    .then(function (response) {
        // Manejar la respuesta del servidor
        console.log('Datos recibidos:', response.data);
        let riesgos_contador = response.data.riesgos_contador;

        // Convertir el objeto a arreglos para usar en Chart.js
        let categories = Object.keys(riesgos_contador);  // Etiquetas del eje X (por ejemplo, categorías de riesgo)
        let seriesData = Object.values(riesgos_contador); // Valores de incapacidades por riesgo

        // Destruir el gráfico existente si es necesario
        if (window.myChart) {
            window.myChart.destroy();
        }

        // Crear la nueva gráfica de línea con Chart.js
        const ctx = document.getElementById('chart_riesgo').getContext('2d');
        window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: categories,  // Etiquetas en el eje X
                datasets: [{
                    label: 'Incapacidades',
                    data: seriesData,  // Valores en el eje Y
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.1 // Hace que la línea sea más suave
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Riesgo',
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cantidad de incapacidades'
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Incapacidades por Riesgo',
                        font: {
                            size: 18
                        }
                    }
                }
            }
        });
    })
    .catch(function (error) {
        // Manejar el error
        console.error('Error al enviar los datos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al enviar los datos. Por favor, intenta de nuevo.',
        });
    });
}


function dep() {
    // Obtener el rango de fechas
    let rangoFechas = document.getElementById('rango_departamento')._flatpickr.selectedDates;
    let fechaStart = rangoFechas.length > 0 ? rangoFechas[0].toISOString().split('T')[0] : null;
    let fechaEnd = rangoFechas.length > 1 ? rangoFechas[1].toISOString().split('T')[0] : null;

    // Llamar a la tabla de datos
    dataTable("listado_incapacitados_departamento", route('app.incapacidadesRan.dt', {   
        fechastart: fechaStart,
        fechaEnd: fechaEnd
    }));

    // Enviar los datos con axios
    axios.post(route('app.resumen.DatosDepartamento'), {
        fechastart: fechaStart,
        fechaEnd: fechaEnd
    })
    .then(function (response) {
        // Manejar la respuesta del servidor
        console.log('Datos recibidos:', response.data);

        // Preparar los datos para Chart.js
        let departamentos = Object.keys(response.data.departamentos); // Nombres de los departamentos
        let cantidades = Object.values(response.data.departamentos); // Cantidades de incapacidades

        // Crear el gráfico de línea
        let ctx = document.getElementById('chart_departamento').getContext('2d');
        if (window.chart) {
            window.chart.destroy(); // Destruir el gráfico existente antes de crear uno nuevo
        }
        window.chart = new Chart(ctx, {
            type: 'line', // Tipo de gráfico de línea
            data: {
                labels: departamentos, // Etiquetas (departamentos)
                datasets: [{
                    label: 'Cantidad de Incapacidades por Departamento',
                    data: cantidades, // Datos (cantidades de incapacidades)
                    borderColor: 'rgba(75, 192, 192, 1)', // Color de la línea
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo bajo la línea
                    fill: true, // Rellenar el área bajo la línea
                    tension: 0.4 // Suavizar la línea
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Incapacidades por Departamento'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Departamentos'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cantidad de Incapacidades'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    })
    .catch(function (error) {
        // Manejar el error
        console.error('Error al enviar los datos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al enviar los datos. Por favor, intenta de nuevo.',
        });
    });
}
