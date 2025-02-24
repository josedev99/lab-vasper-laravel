//var global
var flat_filter_fechas = null;

var menu_tabs = {
    evaluacion_tab: true
}
document.addEventListener('DOMContentLoaded', () => {
    //init datatable
    listar_resultado_optometria();
    //calendar range
    calendar_range_filter();

    //button para recargar
    let btn_reload_info = document.getElementById('btn-reload-info');
    if(btn_reload_info){
        btn_reload_info.addEventListener('click', () => {
            listar_resultado_optometria(flat_filter_fechas.selectedDates);
        });
    }
    //filtrar por estado y agencia
    let filtrar_estado = $("#filtrar_estado").selectize()[0].selectize;
    let filtrar_agencia = $("#filtrar_agencia").selectize()[0].selectize;
    filtrar_estado.on('change', (value) => {
        listar_resultado_optometria(flat_filter_fechas.selectedDates);
    })
    filtrar_agencia.on('change', (value) => {
        listar_resultado_optometria(flat_filter_fechas.selectedDates);
    })
})

window.addEventListener("resize", () => {
    calendar_range_filter();
});

function calendar_range_filter(){
    const anchoPantalla = window.innerWidth;
    let opcionesCalendar = {
        locale: "es",
        mode: "range",
        maxDate: "2050",
        minDate: "2024",
        dateFormat: "d/m/Y",
        disableMobile: "true",
        showMonths: (anchoPantalla < 680) ? 1 : 2,
        onChange: function (array_fechas) {
            listar_resultado_optometria(array_fechas);
        },
    };
    flat_filter_fechas = flatpickr("#filtrar_fecha", opcionesCalendar);
}

function listar_resultado_optometria(array_fechas = []) {
    if(array_fechas.length === 2){
        let fDesde = moment(array_fechas[0]).format("YYYY-MM-DD");
        let fHasta = moment(array_fechas[1]).format("YYYY-MM-DD");
        //estado
        let filtrar_estado = document.getElementById('filtrar_estado');
        if(!filtrar_estado){
            filtrar_estado = '';
        }
        filtrar_estado = filtrar_estado.value;
        //agencia
        let filtrar_agencia = document.getElementById('filtrar_agencia');
        if(!filtrar_agencia){
            filtrar_agencia = '';
        }
        filtrar_agencia = filtrar_agencia.value;

        dataTable("dt_resultado_optometria", route('opto.result.listar'), { fDesde: fDesde,fHasta: fHasta,evaluacion: filtrar_estado, agencia: filtrar_agencia });
    }
}

//evaluar event de button

document.addEventListener('DOMContentLoaded', (event) => {
    event.stopPropagation();
    let btnTabEvaluacion = document.querySelector('#btnTabEvaluacion');
    let btnTabAtencion = document.querySelector('#btnTabAtencion');
    let resultado_select = document.querySelector('#resultado_select');
    let component_button_pdf = document.getElementById('component_button_pdf');

    if (btnTabEvaluacion && btnTabAtencion) {
        btnTabEvaluacion.addEventListener('click', () => {
            //buttin hide
            component_button_pdf.style.display = 'none';
            document.getElementById('titleAccion').textContent = '';
            listar_resultado_optometria();
        })
        btnTabAtencion.addEventListener('click', () => {
            component_button_pdf.style.display = 'block';
            listar_atencion_result();
        })

        resultado_select.addEventListener('change', () => {
            let textContentSelect = resultado_select.options[resultado_select.selectedIndex].textContent.toUpperCase();
            let titleAction = '';
            //validacion
            if (textContentSelect === "NORMALES") {
                titleAction = `COLABORADORES CON 0 RESULTADOS ALTERADOS.`;
            } else if (textContentSelect === "RESUMEN") {
                titleAction = `RESUMEN`;
            } else {
                titleAction = `COLABORADORES CON ${textContentSelect}`;
            }
            document.getElementById('titleAccion').textContent = titleAction;
            listar_atencion_result();
        })
    }
})

//button para descargar pdf
try {
    let btn_download_pdf = document.getElementById('btn_download_pdf');
    if (btn_download_pdf) {
        btn_download_pdf.addEventListener('click', (event) => {
            event.stopPropagation();
            //validacion
            let jornada_id = document.querySelector('#jornada_atencion');
            let resultado = document.querySelector('#resultado_select');
            if (jornada_id.value === "") {
                Toast.fire({
                    icon: "warning",
                    title: "La jornada es obligatorio."
                }); return;
            }
            if (resultado.value === "") {
                Toast.fire({
                    icon: "warning",
                    title: "El tipo de resultado es obligatorio."
                }); return;
            }
            downloadPDF(route('result.atencion.pdf'), {
                jornada_id: jornada_id.value,
                resultado: resultado.value
            });
        })
    }
} catch (err) {
    console.log(err);
}

function downloadPDF(route, object) {
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let formPDF = document.createElement('form');
    formPDF.action = route;
    formPDF.method = 'post';
    formPDF.target = '_blank';
    //input token
    let input_csrf = document.createElement('input');
    input_csrf.name = "_token";
    input_csrf.value = token_csrf;
    formPDF.appendChild(input_csrf);
    //input data
    let input_data = document.createElement('input');
    input_data.name = 'data';
    input_data.value = btoa(JSON.stringify(object));

    formPDF.appendChild(input_data);
    document.body.appendChild(formPDF);
    formPDF.submit();
    formPDF.remove();
}

//funcion para evaluar estado de resultados

function toggleEstadoEval(element) {
    let data_emp_evaluar = sessionStorage.getItem('data_emp_evaluar');
    let estado = element.checked ? 'Evaluado' : 'Sin evaluar';

    Swal.fire({
        title: "¿Cambiar el estado?",
        text: "Esta acción modificará el estado de las evaluaciones.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('evaluar.resultados.examenes'), { data: data_emp_evaluar, estado })
                .then((result) => {
                    if (result.data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                        });
                        $("#dt_atencion_resultados").DataTable().ajax.reload(null, false);
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
        }
    });
}

//modal para resultado de optometria
function showResultOptoConsult(element) {

    let empleado_id = element.dataset.empleado_id;
    let consulta_id = element.dataset.consulta_id;

    //session
    sessionStorage.setItem('session-data-opto-consult', JSON.stringify({
        empleado_id,
        consulta_id
    }));
    //servicio checkes
    let inputs_check = document.querySelectorAll('input[name="optionService"]');
    inputs_check.forEach(input => input.checked = false);
    if(element.dataset.servicio === "Si"){
        document.getElementById('icheckSi').checked = true;
    }else if(element.dataset.servicio === "No"){
        document.getElementById('icheckNo').checked = true;
    }

    document.getElementById('display_nombre').textContent = element.dataset.nombre_empleado;
    document.getElementById('display_genero').textContent = element.dataset.genero;
    document.getElementById('display_edad').textContent = element.dataset.edad;

    axios.post(route('examen.opto.result'), { empleado_id, consulta_id })
        .then((result) => {
            $("#modal_resultado_examen").modal('show');
            let data = result.data;

            if (Object.keys(data.data).length > 0) {
                let consulta = data.data;
                let eval_examen = consulta.evaluacion;
                //validacion para checked segun evaluacion
                let inputs_check = document.querySelectorAll('input[name="optionResultado"]');
                inputs_check.forEach(input => input.checked = false);
                if (eval_examen === "Normal") {
                    document.getElementById('checkNormal').checked = true;
                } else if (eval_examen === "Alterado") {
                    document.getElementById('checkAlterado').checked = true;
                }
                resultOptometria('resultado_examen', consulta);
            } else {
                document.getElementById('resultado_examen').innerHTML = `
                <div class="alert alert-info alert-dismissible p-1 mx-0 my-2" role="alert">
                    <i class="bi bi-info-circle me-1"></i>
                    El resultado de examen no esta disponible.
                </div>
            `;
            }
        }).catch((err) => {
            console.log(err)
        });
}
//formulario para guardar la evaluacion de optometria
document.addEventListener('DOMContentLoaded', () => {
    let formEvalOpto = document.getElementById('form_resultado_exam');
    if (formEvalOpto) {
        formEvalOpto.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(formEvalOpto);
            let selectedExamData = JSON.parse(sessionStorage.getItem('session-data-opto-consult'));
            //validaciones
            let optionResultado = document.querySelector('input[name="optionResultado"]:checked');
            if (optionResultado === null) {
                Toast.fire({
                    icon: "warning",
                    title: `seleccione una opción para continuar con la evaluación del examen.`
                }); return;
            }
            for (let [key, value] of Object.entries(selectedExamData)) {
                if (!value) {
                    Toast.fire({
                        icon: "warning",
                        title: "Se han detectado valores modificados manualmente. No es posible continuar."
                    }); return;
                } else {
                    formData.append(key, value);
                }
            }

            axios.post(route('examen.eval.opto.save'), formData)
                .then((result) => {
                    if (result.data.status === "success") {
                        $("#modal_resultado_examen").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                        });
                    }
                    $("#dt_resultado_optometria").DataTable().ajax.reload(null, false);
                }).catch((err) => {
                    console.log(err);
                });
        })
    }
})

//mostrar resultados evaluado de optometria
function showResultOpto(element){
    let jornada_id = element.dataset.jornada_id;
    let empleado_id = element.dataset.empleado_id;
    let consulta_id = element.dataset.consulta_id;

    let nombre_empleado = element.dataset.nombre_empleado;

    sessionStorage.setItem('data_emp_evaluar', btoa(JSON.stringify({
        empleado_id, jornada_id, consulta_id
    })));

    document.getElementById('display_empleado_nombre').textContent = nombre_empleado;

    axios.post(route('examen.opto.result'), { empleado_id, jornada_id, consulta_id })
        .then((result) => {
            $("#modal_result_ex_empleado").modal('show');

            let data = result.data;
            if (Object.keys(data.data).length > 0) {
                let consulta = data.data;
                //checked input estado
                let icheckEstado = document.getElementById('icheckEstado');
                if (consulta.estado_evaluacion === "Evaluado") {
                    icheckEstado.checked = true;
                } else {
                    icheckEstado.checked = false;
                }

                resultOptometria('list_items_resultado', consulta);
            } else {
                document.getElementById('list_items_resultado').innerHTML = `
                <div class="alert alert-info alert-dismissible p-1 mx-0 my-2" role="alert">
                    <i class="bi bi-info-circle me-1"></i>
                    El resultado de examen no esta disponible.
                </div>
            `;
            }
        }).catch((err) => {
            console.log(err)
            Toast.fire({
                icon: "warning",
                title: "Ha ocurrido un error al momento de obtener la información."
            }); return;
        });
}