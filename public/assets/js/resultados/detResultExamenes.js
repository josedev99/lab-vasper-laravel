let data_examenes_empleado = sessionStorage.getItem('data_examenes_empleado');
data_examenes_empleado = JSON.parse(data_examenes_empleado);
document.addEventListener('DOMContentLoaded', () => {
    //title content page
    document.getElementById('title_content_page').textContent = data_examenes_empleado.jornada;
    document.getElementById('display_nombre_empleado').textContent = data_examenes_empleado.nombre_empleado;
    listarExamenesEmpleado();
    //procesar formulario
    let form_resultado_exam = document.getElementById('form_resultado_exam');
    if (form_resultado_exam) {
        form_resultado_exam.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(form_resultado_exam);
            let selectedExamData = JSON.parse(sessionStorage.getItem('selectedExamData'));
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
            axios.post(route('result.examen.save'), formData)
                .then((result) => {
                    if (result.data.status === "success") {
                        $("#modal_resultado_examen").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                        });
                    } else if (result.data.status === "success") {
                        Swal.fire({
                            title: "Aviso",
                            text: result.data.message,
                            icon: "warning"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error al momento de procesar la solicitud.',
                            icon: "error"
                        });
                    }
                    $("#dt_examenes_empleado").DataTable().ajax.reload(null, false);
                }).catch((err) => {
                    console.log(err);
                });
        })
    }
})

function listarExamenesEmpleado() {
    dataTable("dt_examenes_empleado", route('result.examenes.detalle'), { empleado_id: data_examenes_empleado.empleado_id, jornada_id: data_examenes_empleado.jornada_id });
}

function ingresarResultado(element) {
    //clear input type radio
    let inputs = document.querySelectorAll('input[name="optionResultado"]');
    inputs.forEach(input => input.checked = false);

    let cat_examen = element.dataset.cat_examen;
    let examen = element.dataset.examen;
    let empleado_id = element.dataset.empleado_id;
    let jornada_id = element.dataset.jornada_id;
    let examen_id = element.dataset.examen_id;

    let data = {
        cat_examen,
        empleado_id,
        jornada_id,
        examen_id
    }
    //mostrar examen seleccionado
    document.getElementById('display_examen').textContent = examen;

    getEmpleadoById(empleado_id); //mostrar informacion del empleado
    getExamenResultado(jornada_id, empleado_id, examen_id,cat_examen);

    sessionStorage.setItem('selectedExamData', JSON.stringify(data));
    $("#modal_resultado_examen").modal('show');
}
//obtener datos del empleado
function getEmpleadoById(empleado_id) {
    axios.post(route('empleado.info.columns'), { empleado_id: empleado_id })
        .then((result) => {
            if (result.data) {
                document.getElementById('display_nombre').textContent = result.data.nombre;
                document.getElementById('display_genero').textContent = result.data.genero;
                document.getElementById('display_edad').textContent = result.data.edad + ' AÑOS';
            } else {
                document.getElementById('display_nombre').textContent = '';
                document.getElementById('display_genero').textContent = '';
                document.getElementById('display_edad').textContent = '';
            }
        }).catch((err) => {
            console.log(err)
        });
}
function getExamenResultado(jornada_id, empleado_id, examen_id,cat_examen) {
    /* let component = document.getElementById('resultado_examen');
    component.innerHTML = ``; */
    axios.post(route('result.examen.obtener'), { jornada_id, empleado_id, examen_id,cat_examen })
        .then((result) => {
            if (result.data.status !== "warning") {
                let eval_examen = result.data.evaluacion;
                //validacion para checked segun evaluacion
                let inputs_check = document.querySelectorAll('input[name="optionResultado"]');
                inputs_check.forEach(input => input.checked = false);
                if(eval_examen === "Normal"){
                    document.getElementById('checkNormal').checked = true;
                }else if(eval_examen === "Alterado"){
                    document.getElementById('checkAlterado').checked = true;
                }
                let resultado = result.data.resultado;

                //validacion para encabezado de tabla
                if (resultado.categoria === "QUIMICA") {
                    
                    //validaciones
                    if (resultado.examen === "ACIDO URICO") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            valores_normales: 'Hombre: 3.4 - 7.0 mg/dl <br> Mujer: 2.4 - 5.7 mg/dl'
                        });
                    } else if (resultado.examen === "GLUCOSA") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            valores_normales: '75-115 mg/dl'
                        });
                    } else if (resultado.examen === "COLESTEROL") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            valores_normales: 'Menor a 190 mg/dl'
                        });
                    }else if (resultado.examen === "CREATININA") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            valores_normales: 'Mujer: 0.50-0.90 mg/dl <br> Hombre: 0.60-1.10 mg/dl'
                        });
                    }else if (resultado.examen === "TRIGLICERIDOS") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            valores_normales: 'Valores elevados mayores de: 200 mg/dl'
                        });
                    }else if (resultado.examen === "SGOT") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            Umedida: 'UL',
                            valores_normales: 'Mujer: Hasta 31 U/I <br> Hombre: Hasta 37 U/I'
                        });
                    }else if (resultado.examen === "SGPT") {
                        TableExamenesStandar('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            Umedida: 'UL',
                            valores_normales: 'Mujer: Hasta 32 U/I <br> Hombre: Hasta 42 U/I'
                        });
                    }else{
                        TableExamenesStandar('resultado_examen');
                    }
                } else {
                    //array_exaemenes nombre heces
                    let array_nombres_examen_heces = ['HECES','EXAMENES GENERAL DE HECES','EGH'];
                    let array_orina = ['ORINA','EGO'];
                    let array_hemograma = ['HEMOGRAMA COMPLETO','HEMOGRAMA'];
                    let array_opto = ['SALUD VISUAL (OPTOMETRÍA)','OPTOMETRIA'];

                    if(array_nombres_examen_heces.includes(resultado.examen.toUpperCase())){
                        TableHecesExamen('resultado_examen',resultado);
                    }else if(array_orina.includes(resultado.examen)){ //en desarrollo
                        TableOrinaExamen('resultado_examen',resultado);
                    }else if(array_hemograma.includes(resultado.examen)){
                        TableHemogramaExamen('resultado_examen',resultado);
                    }else if(array_opto.includes(resultado.examen)){
                        resultOptometria('resultado_examen',resultado);
                    }else if (resultado.examen === "BACILOSCOPIA") {
                        TableExamenBaciloscopia('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            muestra: 'Esputo'
                        });
                    }else if (["CULTIVO FARINGEO","EXOFARINGEO"].includes(resultado.examen.toUpperCase())) {
                        TableExamenExofaringeo('resultado_examen',{
                            examen: resultado.examen,
                            aisla: resultado.aisla,
                            sensible: resultado.sensible,
                            resiste: resultado.resiste,
                            muestra: 'Secreción faríngea'
                        });
                    }else if (["VDRL","R.P.R"].includes(resultado.examen)) {
                        TableExamenBaciloscopia('resultado_examen',{
                            examen: resultado.examen,
                            resultado: resultado.resultado,
                            muestra: 'Sangre'
                        });
                    }
                }
            } else {
                document.getElementById('resultado_examen').innerHTML = `
                    <div class="alert alert-info alert-dismissible p-1 mx-0 my-2" role="alert">
                        <i class="bi bi-info-circle me-1"></i>
                        El resultado de examen no esta disponible.
                    </div>
                `;
            }
        }).catch((err) => {
            console.log(err);
        });
}

function addImageExamen(element){
    let examen = element.dataset.examen;
    document.getElementById('display_examen_img').textContent = examen.toUpperCase();

    let cat_examen = element.dataset.cat_examen;
    let empleado_id = element.dataset.empleado_id;
    let jornada_id = element.dataset.jornada_id;
    let examen_id = element.dataset.examen_id;

    sessionStorage.setItem('cat_examen',cat_examen);
    sessionStorage.setItem('emp_id',empleado_id);
    sessionStorage.setItem('jornada_id',jornada_id);
    sessionStorage.setItem('examen_id',examen_id);

    $("#modal_examen_resultado").modal('show');
}