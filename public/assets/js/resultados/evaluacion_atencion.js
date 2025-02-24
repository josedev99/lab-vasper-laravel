var menu_tabs = {
    evaluacion_tab: true,
    atencion_tab: false
}
document.addEventListener('DOMContentLoaded', () => {
    //init datatable
    listar_evaluacion_resultados();
    //selectize de select de jornada_evaluacion
    let select_jornadas = $("#jornada_evaluacion")[0].selectize;
    select_jornadas.on('change', function (value) {
        sessionStorage.setItem('selected_jornada_id', value);
        listar_evaluacion_resultados(value);
    });
    //select para atencion por resultado
    let jornada_atencion = $("#jornada_atencion")[0].selectize;
    jornada_atencion.on('change', function (value) {
        listar_atencion_result();
    });

    //init precargar jornada si ya se ha seleccionado previamente
    let selected_jornada_id = sessionStorage.getItem('selected_jornada_id');

    if (selected_jornada_id !== null) {
        setTimeout(() => {
            let selected_jornada = JSON.parse(sessionStorage.getItem('data_examenes_empleado'));
            const option = Object.values(select_jornadas.options).find(opt => opt.text === selected_jornada.jornada);
            if (option) {
                select_jornadas.setValue(option.value); // set valor correspondiente
                listar_evaluacion_resultados();
            } else {
                listar_evaluacion_resultados();
            }
        }, 1000);
    }
})

function listar_evaluacion_resultados() {
    let jornada_id = document.getElementById('jornada_evaluacion').value;
    if (jornada_id === "") { jornada_id = null }
    dataTable("dt_evaluacion_resultados", route('result.evaluacion.listar'), { jornada_id: jornada_id });
}

function listar_atencion_result() {
    let jornada_id = document.getElementById('jornada_atencion').value;
    let resultado = document.getElementById('resultado_select').value;

    if (jornada_id === "") {
        jornada_id = null;
        Toast.fire({
            icon: "warning",
            title: "Seleccione una jornada."
        }); return;
    }
    if (resultado === "") { resultado = null }
    dataTable("dt_atencion_resultados", route('result.atencion.listar'), { jornada_id: jornada_id, resultado: resultado });
}

function redirectToExamenes(element) {
    let empleado_id = element.dataset.empleado_id;
    let jornada_id = element.dataset.jornada_id;
    let nombre_empleado = element.dataset.nombre_empleado;
    //textContent de select jornada
    let jornadas_selectize = $('#jornada_evaluacion')[0].selectize;
    let jornda_value = jornadas_selectize.getValue(); // Obtiene el valor seleccionado
    let jornada_textContent = jornadas_selectize.getItem(jornda_value).text();

    let data = { empleado_id, jornada_id, jornada: jornada_textContent, nombre_empleado: nombre_empleado };

    sessionStorage.setItem('data_examenes_empleado', JSON.stringify(data));

    let tag_a = document.createElement('a');
    tag_a.href = route('result.paciente.examenes') + `?key1=${empleado_id}&key2=${jornada_id}`;
    tag_a.click();
}

//New code

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
            listar_evaluacion_resultados();
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

//show resultados

function showResultadosPac(element) {
    let jornada_id = element.dataset.jornada_id;
    let empleado_id = element.dataset.empleado_id;
    let nombre_empleado = element.dataset.nombre_empleado;

    sessionStorage.setItem('data_emp_evaluar', btoa(JSON.stringify({
        empleado_id, jornada_id
    })));

    document.getElementById('display_empleado_nombre').textContent = nombre_empleado;

    axios.post(route('result.examenes.obtener'), {
        jornada_id, empleado_id
    }).then((result) => {
        //checked input estado
        let icheckEstado = document.getElementById('icheckEstado');
        if (result.data.estado === "Evaluado") {
            icheckEstado.checked = true;
        } else {
            icheckEstado.checked = false;
        }
        ListItemsExaResult(result.data.examenes_data);
        $("#modal_result_ex_empleado").modal('show');
    }).catch((err) => {
        console.log(err);
        Toast.fire({
            icon: "warning",
            title: "Ha ocurrido un error al momento de obtener la información."
        }); return;
    });
}

function ListItemsExaResult(data) {
    let list_items_resultado = document.querySelector('#list_items_resultado');
    list_items_resultado.innerHTML = '';
    if (data.length) {
        let accordion = document.createElement('div');
        accordion.classList.add('accordion', 'accordion-flush')
        data.forEach((item, index) => {
            let id_item = 'element-' + index;
            let id_content_list = 'content-examen-' + index;
            accordion.innerHTML += `
                <div class="accordion-item p-1 mb-1">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed p-1" type="button" data-bs-toggle="collapse" data-bs-target="#${id_item}" aria-expanded="false" aria-controls="${id_item}" style="font-size:14px">
                        ${item.categoria}
                        </button>
                    </h2>
                    <div id="${id_item}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                        <div class="accordion-body pb-1">
                            <hr style="margin:0px !important">
                            <div id="${id_content_list}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            //validacion de resultados
            let examenes = item.examenes;
            let arrayExamResultF = [];
            examenes.forEach((examen, subIndex) => {
                let color = examen.evaluacion.toUpperCase() === "ALTERADO" ? `text-danger` : `text-success`;

                if (Object.keys(examen.resultado).length > 0) {
                    let resultado = examen.resultado;

                    if (resultado.categoria.toUpperCase() === "QUIMICA") {
                        //validaciones
                        if (resultado.examen === "ACIDO URICO" && resultado.resultado !== "") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                valores_normales: 'Hombre: 3.4 - 7.0 mg/dl <br> Mujer: 2.4 - 5.7 mg/dl'
                            });
                        } else if (resultado.examen === "GLUCOSA" && resultado.resultado !== "") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span`,
                                resultado: resultado.resultado,
                                valores_normales: '75-115 mg/dl'
                            });
                        } else if (resultado.examen === "COLESTEROL" && resultado.resultado !== "") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                valores_normales: 'Menor a 190 mg/dl'
                            });
                        } else if (resultado.examen === "CREATININA") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                valores_normales: 'Mujer: 0.50-0.90 mg/dl <br> Hombre: 0.60-1.10 mg/dl'
                            });
                        } else if (resultado.examen === "TRIGLICERIDOS") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                valores_normales: 'Valores elevados mayores de: 200 mg/dl'
                            });
                        } else if (resultado.examen === "SGOT") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                Umedida: 'UL',
                                valores_normales: 'Mujer: Hasta 31 U/I <br> Hombre: Hasta 37 U/I'
                            });
                        } else if (resultado.examen === "SGPT") {
                            arrayExamResultF.push({
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                Umedida: 'UL',
                                valores_normales: 'Mujer: Hasta 32 U/I <br> Hombre: Hasta 42 U/I'
                            });
                        }
                        itemsExamQuimicaTable(id_content_list, arrayExamResultF, accordion, true);
                    } else if (resultado.categoria.toUpperCase() === "BACTERIOLOGIA") {
                        if (resultado.examen === "BACILOSCOPIA") {
                            arrayExamResultF.push({
                                examen: `${resultado.examen}`,
                                title: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                muestra: 'Esputo'
                            });
                        } else if (["CULTIVO FARINGEO", "EXOFARINGEO"].includes(resultado.examen.toUpperCase())) {
                            arrayExamResultF.push({
                                examen: `${resultado.examen}`,
                                title: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                aisla: resultado.aisla,
                                sensible: resultado.sensible,
                                resiste: resultado.resiste,
                                muestra: 'SECRECIÓN FARÍNGEA'
                            });
                        }
                        itemsExamenBacteriologia(id_content_list, arrayExamResultF, accordion, true);
                    } else {
                        let array_orina = ['ORINA', 'EGO'];
                        let array_hemograma = ['HEMOGRAMA COMPLETO', 'HEMOGRAMA'];
                        let array_opto = ['SALUD VISUAL (OPTOMETRÍA)', 'OPTOMETRIA'];

                        //array_exaemenes nombre heces
                        let array_nombres_examen_heces = ['HECES', 'EXAMENES GENERAL DE HECES', 'EGH'];
                        if (array_nombres_examen_heces.includes(resultado.examen)) {
                            let title = `<span class="${color}">${examen.examen} - ${examen.evaluacion}</span>`;
                            TableHecesExamen(id_content_list, resultado, accordion, true, title);
                        } else if (array_orina.includes(resultado.examen)) { //en desarrollo
                            let title = `<span class="${color}">${examen.examen} - ${examen.evaluacion}</span>`;
                            TableOrinaExamen(id_content_list, resultado, accordion, true, title);
                        } else if (array_hemograma.includes(resultado.examen)) {
                            let title = `<span class="${color}">${examen.examen} - ${examen.evaluacion}</span>`;
                            TableHemogramaExamen(id_content_list, resultado, accordion, true, title);
                        } else if (array_opto.includes(resultado.examen)) {
                            resultOptometria(id_content_list, resultado, accordion, true);
                        } else if (['VDRL', 'R.P.R'].includes(resultado.examen.toUpperCase())) {
                            TableExamenUrologia(id_content_list, {
                                examen: `<span class="${color}">${resultado.examen} - ${examen.evaluacion}</span>`,
                                resultado: resultado.resultado,
                                muestra: 'Sangre'
                            }, accordion, true);
                        }
                    }
                } else {
                    let accordion_body = accordion.querySelector(`#${id_content_list}`);
                    let alert = `
                        <div class="alert alert-info alert-dismissible p-1 mx-0 my-2" role="alert">
                            <i class="bi bi-info-circle me-1"></i>
                            Resultado no disponible.
                        </div>
                    `;
                    accordion_body.innerHTML = alert;
                }
            })
        });
        list_items_resultado.appendChild(accordion);
    } else {
        list_items_resultado.innerHTML = `
            <tr>
                <td class="text-center" style="width: 100%;padding:3px 8px;border: 1px solid #dbcfcf;">Sin información</td>
            </tr>
        `;
    }
}

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
    let jornada_id = element.dataset.jornada_id;
    let consulta_id = element.dataset.consulta_id;

    //session
    sessionStorage.setItem('session-data-opto-consult', JSON.stringify({
        empleado_id,
        jornada_id,
        consulta_id
    }))

    document.getElementById('display_nombre').textContent = element.dataset.nombre_empleado;
    document.getElementById('display_genero').textContent = element.dataset.genero;
    document.getElementById('display_edad').textContent = '-';

    axios.post(route('examen.opto.result'), { empleado_id, jornada_id, consulta_id })
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
                    $("#dt_evaluacion_resultados").DataTable().ajax.reload(null, false);
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