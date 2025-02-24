document.addEventListener('DOMContentLoaded', () => {
    flatpickr("#filtro_rango_fechas", {
        mode: "range",
        locale: "es",
        /* maxDate: "today", */
        minDate: "1910",
        dateFormat: "d/m/Y",
        disableMobile: true
    });
    //init datatable
    //listar_orden_examenes();
    //selectize de select de jornada_evaluacion
    $("#empresas_select").selectize({
        onChange: function(value){
            getJornadasByEmpresa();
            listar_orden_examenes();
        }
    });
    document.getElementById('filtro_rango_fechas').addEventListener('change', (e) => {
        getJornadasByEmpresa();
        listar_orden_examenes();
    })
    let select_jornadas = $("#jornada_lab")[0].selectize;
    select_jornadas.on('change', function (value) {
        listar_orden_examenes();
    });
    //init precargar jornada si ya se ha seleccionado previamente
    let selected_jornada_id = sessionStorage.getItem('selected_jornada_id');

    if (selected_jornada_id !== null) {
        setTimeout(() => {
            let selected_jornada = JSON.parse(sessionStorage.getItem('data_examenes_empleado'));
            const option = Object.values(select_jornadas.options).find(opt => opt.text === selected_jornada.jornada);
            if (option) {
                select_jornadas.setValue(option.value); // set valor correspondiente
                listar_orden_examenes();
            } else {
                listar_orden_examenes();
            }
        }, 1000);
    }
})

function getJornadasByEmpresa(done = null){
    let empresa_id = $("#empresas_select").selectize()[0].selectize.getValue();
    let rango_fechas = document.getElementById('filtro_rango_fechas').value;

    axios.post(route('empresa.jornadas'),{empresa_id: empresa_id, fechas: rango_fechas, filtrar_fecha: true})
    .then((result) => {
        console.log(result);
        let data = result.data;
        let jornada_orden = $("#jornada_lab")[0].selectize;
        jornada_orden.clear();
        jornada_orden.clearOptions();
        if(data.length > 0){
            data.forEach((item)=>{
                jornada_orden.addOption({
                    value: item.id,
                    text: item.nombre
                });
            })
            if(done !== null && typeof done === "function"){
                done();
            }
        }
    }).catch((err) => {
        console.log(err);
    });
}
function listar_orden_examenes() {
    let empresa_id = $("#empresas_select").selectize()[0].selectize.getValue();
    let jornada_id = $("#jornada_lab").selectize()[0].selectize.getValue();
    dataTable("dt_evaluacion_resultados", route('lab.orden.listar'), { empresa_id,jornada_id });
}

function redirectToExamenes(element) {
    let empleado_id = element.dataset.empleado_id;
    let jornada_id = element.dataset.jornada_id;
    let nombre_empleado = element.dataset.nombre_empleado;
    //textContent de select jornada
    let jornadas_selectize = $('#jornada_lab')[0].selectize;
    let jornda_value = jornadas_selectize.getValue(); // Obtiene el valor seleccionado
    let jornada_textContent = jornadas_selectize.getItem(jornda_value).text();

    let data = { empleado_id, jornada_id, jornada: jornada_textContent, nombre_empleado: nombre_empleado };

    sessionStorage.setItem('data_examenes_empleado', JSON.stringify(data));

    let tag_a = document.createElement('a');
    tag_a.href = route('lab.examenes.ingresar') + `?key1=${empleado_id}&key2=${jornada_id}`;
    tag_a.click();
}

//Reimprimir orden de resultado
function printOrdenExamenes(element){
    let orden_id = element.dataset.orden_id;
    let empresa_id = element.dataset.empresa_id;
    let colaborador = element.dataset.nombre_empleado;
    Swal.fire({
        title: "¿Reimprimir boleta de indicaciones?",
        text: `PACIENTE: ${colaborador}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, reimprimir",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            generar_orden_pdf({id: orden_id, empresa_id});
        }
    });
}
function generar_orden_pdf(data){
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let form = document.createElement('form');
    form.action = route('orden.examenes.pdf');
    form.method = "POST";
    form.target = '_blank';
    let input_csrf = document.createElement('input');
    input_csrf.name = "_token";
    input_csrf.value = token_csrf;
    form.appendChild(input_csrf);

    let inputData = document.createElement('input');
    inputData.name = 'data';
    inputData.value = JSON.stringify(data);
    form.appendChild(inputData);
    document.body.appendChild(form);
    form.submit();
    form.remove();
}