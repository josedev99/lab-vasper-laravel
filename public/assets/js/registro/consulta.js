var quill_motivo = null;
var quill_nota_observaciones = null;
var array_diagnosticos = [];
document.addEventListener('DOMContentLoaded', App);

function App() {
    //fecha de inicio de sintoma
    flatpickr("#fecha_inicio_sintoma", {
        locale: "es",
        maxDate: "2050",
        minDate: "2022",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });

    let formConsulta = document.getElementById('register_consulta');
    if (formConsulta) {
        formConsulta.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(formConsulta);

            if (sessionStorage.getItem('is_consulta') === "Si") {

                //validacion de motivo de consulta
                let motivo_consulta = quill_motivo.root.innerHTML;
                if (quill_motivo === null) {
                    Toast.fire({
                        icon: "warning",
                        title: `El motivo de la consulta es obligatorio.`
                    }); return;
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
                    } else {
                        input.classList.remove('border-valid');
                    }
                }

                formData.append('empleado_id', sessionStorage.getItem('empleado_id'));
                formData.append('motivo_consulta', motivo_consulta);
                //new campo de observaciones
                let nota_observaciones = quill_nota_observaciones.root.innerHTML;
                formData.append('observaciones',nota_observaciones);
                //validacion segun opcion seleccionado en la Interfaz

                if (sessionStorage.getItem('opcionSelected') === "citas") {
                    formData.append('cita_id', sessionStorage.getItem('cita_id'));
                }
                //validar si es un finalizacion de consulta
                if (sessionStorage.getItem('consulta_id')) {
                    formData.append('consulta_id', sessionStorage.getItem('consulta_id'));
                }

                axios.post(route('consulta.save'), formData)
                    .then((result) => {
                        console.log(result);
                        if (result.data.status === "success") {
                            //RESET EDITOR
                            quill_motivo.root.innerHTML = '';
                            quill_nota_observaciones.root.innerHTML = '';

                            $("#modal_reg_consulta").modal('hide');
                            Swal.fire({
                                title: "Éxito",
                                text: result.data.message,
                                icon: "success"
                            });
                            formConsulta.reset();
                            //destroy cita_id
                            if (sessionStorage.getItem('opcionSelected') === "citas") {
                                sessionStorage.removeItem('cita_id');
                            }
                            //remove empleado_id
                            sessionStorage.removeItem('empleado_id');
                            //reload datatable
                            $("#dt_listados_emp").DataTable().ajax.reload(null, false);

                            //reset border valid signos vitales y medidas
                            let inputs = document.querySelectorAll('.reset_valid');
                            inputs.forEach((input) => input.style.border = '1px solid #dadce0');

                            registrarNuevaCita(result.data.results);
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
                            //remove formulario de incapacidades
                            if (component_incapacidad.contains(component_incapacidad_child)) {
                                component_incapacidad.removeChild(component_incapacidad_child);
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
/**
 * Registrar proxima cita
 */
function registrarNuevaCita(empleado_id) {
    Swal.fire({
        icon: "info",
        html: `
        <div class="card m-0 p-1">
            <div class="card-header p-1">
                <p class="m-0" style="font-size: 16px"><b>PRÓXIMA CITA </b></p>
            </div>
            <div class="card-body p-1">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="content-input">
                            <input name="fecha_cita" id="fecha_cita" type="text"
                                class="custom-input material m-0"  placeholder=" "
                                placeholder=" ">
                            <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                            <label class="input-label" for="fecha_cita"></label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="input-group">
                            <label for="hora_cita" title="hora" class="input-group-title1"></label>
                            <select name="hora_cita" id="hora_cita" class="form-select border-radius m-0" data-toggle="tooltip"
                            data-placement="bottom" title="Seleccionar hora de cita">
                            <option value="">Seleccionar hora</option>
                            </select>                                                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: `
        <i class="fa fa-save"></i> Guardar
        `,
        cancelButtonText: `
        <i class="fa fa-times"></i> Cancelar
        `,
        didOpen: () => {
            $('#hora_cita').selectize({
                dropdownParent: 'body'
            });
            flatpickr("#fecha_cita", {
                locale: "es",
                maxDate: "2050",
                minDate: "today",
                dateFormat: "d/m/Y",
                disableMobile: "true",
            });
            //Realizar la busqueda de fechas disponibles para agregandar
            let fecha_cita = document.getElementById('fecha_cita');
            if (fecha_cita) {
                fecha_cita.addEventListener('change', async (e) => {
                    let result = await axios.post(route('cita.horarios'), {
                        empleado_id,
                        fecha_cita: e.target.value
                    }, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    let data = result.data;

                    let select_horario = $('#hora_cita').selectize()[0].selectize;
                    select_horario.clear();
                    select_horario.clearOptions();
                    if (data.length === 0) {
                        select_horario.refreshItems();
                    } else {
                        data.forEach((horario) => {
                            select_horario.addOption({
                                value: horario.hora,
                                text: horario.hora
                            });
                        });
                        select_horario.refreshItems();
                    }
                })
            }
        },
        preConfirm: () => {
            const fecha_cita = document.getElementById('fecha_cita').value;
            const hora_cita = document.getElementById('hora_cita').value;

            if (fecha_cita.trim() === "") {
                Swal.showValidationMessage('La fecha de la cita es obligatorio.');
                return false;
            }
            if (hora_cita.trim() === "") {
                Swal.showValidationMessage('La hora de la cita es obligatorio.');
                return false;
            }
            return { fecha_cita, hora_cita };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fecha_cita = result.value.fecha_cita; // Aquí obtienes el valor de la dosis
            const hora_cita = result.value.hora_cita;

            saveProximaCita(empleado_id, fecha_cita, hora_cita);
        }
    });
}

function saveProximaCita(empleado_id, fecha_cita, hora_cita) {
    axios.post(route('cita.proxima_cita'), {
        empleado_id, fecha_cita, hora_cita
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        if (result.data.status === "success") {
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
    }).catch((err) => {
        console.log(err);
    });
}

function selectedReceta(element) {
    let tratamiento = element.dataset.tratamiento;
    //validacion
    let index = array_data_recetas.findIndex((receta) => receta.tratamiento === tratamiento);
    if (index !== -1) {
        Toast.fire({
            icon: "warning",
            title: 'Ya existe una receta para este tratamiento.'
        }); return;
    }
    //swal
    Swal.fire({
        icon: "info",
        html: `
        <div class="card m-0 p-1">
            <div class="card-header p-1">
                <p class="m-0" style="font-size: 16px"><b>TRATAMIENTO</b>: ${tratamiento}</p>
            </div>
            <div class="card-body p-1">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="content-input">
                            <input name="total_dispensar" type="text"
                                class="custom-input material" value=""
                                placeholder=" " placeholder=" " style="text-transform: uppercase">
                            <label class="input-label" for="total_dispensar">Total a dispensar: </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="content-input">
                            <input name="dosis_receta" type="text"
                                class="custom-input material" value=""
                                placeholder=" " placeholder=" " style="text-transform: uppercase">
                            <label class="input-label" for="dosis_receta">Dosis: </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="content-input">
                            <input readonly name="prescrito_receta" type="text"
                                class="custom-input material" value="Médico"
                                placeholder=" " placeholder=" " style="text-transform: uppercase">
                            <label class="input-label" for="prescrito_receta">Prescrito por: </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: `
        <i class="fa fa-save"></i> Guardar
        `,
        cancelButtonText: `
        <i class="fa fa-times"></i> Cancelar
        `,
        preConfirm: () => {
            const dosis = document.querySelector('input[name="dosis_receta"]').value;
            const total_dispensar = document.querySelector('input[name="total_dispensar"]').value;

            if (!dosis && !total_dispensar) {
                Swal.showValidationMessage('Los campos no pueden estar vacío');
                return false;
            }
            return { dosis, total_dispensar };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const dosisReceta = result.value.dosis; // Aquí obtienes el valor de la dosis
            const total_dispensar = result.value.total_dispensar;
            addItemReceta(tratamiento, total_dispensar, dosisReceta);
        }
    });
}

/**
 * QUILL JS EDITOR code
 */

try {
    document.addEventListener('DOMContentLoaded', () => {
        const toolbarOptions = [
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline'],        // toggled buttons
            ['blockquote'],
            ['link'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'align': [] }],

            ['clean']                                     // remove formatting button
        ];
        quill_motivo = new Quill('#motivo-editor', {
            theme: 'snow',
            placeholder: 'Ingresa el motivo de la consulta',
            modules: {
                toolbar: toolbarOptions
            }
        });


        var Embed = Quill.import('blots/embed');

        class TemplateMarker extends Embed {
            static create(value) {
                let node = super.create(value);
                node.setAttribute('data-marker', value.marker);
                node.setAttribute('data-title', value.title);

                node.style.color = '#212529';
                node.style.backgroundColor = '#cde4f5';
                node.style.border = 'none';
                node.style.padding = '3px';
                node.style.borderRadius = '6px';
                node.style.fontSize = '12px';
                node.style.fontWeight = '700';

                node.innerHTML = value.title;
                return node;
            }

            static value(node) {
                return {
                    marker: node.getAttribute('data-marker'),
                    title: node.getAttribute('data-title'),
                };
            }
        }

        TemplateMarker.blotName = 'TemplateMarker';
        TemplateMarker.tagName = 'span';

        Quill.register({
            'formats/TemplateMarker': TemplateMarker
        });

        quill_nota_observaciones = new Quill('#nota-observaciones-editor', {
            theme: 'snow',
            placeholder: 'Ingresa una nota o observación',
            modules: {
                toolbar: toolbarOptions
            }
        });

        // Evento al hacer clic en el ícono del span
        document.querySelector('.btn-event-cat').addEventListener('click', function () {
            addItemEditorObservaciones();
        });
        //Evento enter
        document.getElementById('categoria_observaciones').addEventListener('keydown', (event) => {
            if(event.key === "Enter" && event.target === document.activeElement){
                addItemEditorObservaciones();
                event.preventDefault();
            }
        });
    })
} catch (err) {
    console.log(err);
}
function addItemEditorObservaciones(){
    let inputValCategoria = document.getElementById('categoria_observaciones').value.trim();

    if (inputValCategoria !== "") {
        let range = quill_nota_observaciones.getSelection(true);

        quill_nota_observaciones.insertEmbed(
            range.index,
            'TemplateMarker',
            {
                marker: 'custom-marker',
                title: inputValCategoria.toUpperCase()
            }
        );

        quill_nota_observaciones.insertText(range.index + 1, ' ', Quill.sources.USER);
        quill_nota_observaciones.setSelection(range.index + 2, Quill.sources.SILENT);

        document.getElementById('categoria_observaciones').value = '';//clear input
    } else {
        Toast.fire({
            icon: "warning",
            title: 'Ingresar una categoria.'
        }); return;
    }
}
/**
 * Function para obtener una consulta mediante ID
 * @param {int} consulta_id
 */
function loadDataConsulta(consulta_id) {
    axios.post(route('consulta.obtener_consulta'), { consulta_id: consulta_id }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        if (result.data.isData === "Si") {
            let data = result.data;
            sessionStorage.setItem('empleado_id', data.empleado_id);
            sessionStorage.setItem('consulta_id', data.consulta_id);
            sessionStorage.setItem('is_data_preparado','Si');

            sessionStorage.setItem('is_consulta', 'Si');

            showHistorialConsultas(data.empleado_id);
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

            document.querySelector('.cod_colaborador').value = data.codigo_empleado;
            document.querySelector('.nombre_colaborador').value = data.colaborador;
            document.querySelector('.telefono_colaborador').value = data.telefono;
            //set fecha inicio sintoma
            if (data.fecha_inicio_sintoma !== "") {
                document.getElementById('fecha_inicio_sintoma').value = data.fecha_inicio_sintoma;
                document.getElementById('fecha_inicio_sintoma').dispatchEvent(new Event('change'));
            } else {
                document.getElementById('fecha_inicio_sintoma').value = ''; //set empty
            }
            quill_motivo.root.innerHTML = `<p>${data.motivo}</p>`;
            //cargar datos de signos vitales y medidas
            document.querySelector('input[name="signo_vital_fc"]').value = data.fc_ipm;
            document.querySelector('input[name="signo_vital_fr"]').value = data.fr_rpm;
            document.querySelector('input[name="signo_vital_pa"]').value = data.pa_ps_pd;
            document.querySelector('input[name="medida_temp"]').value = data.temperatura;
            document.querySelector('input[name="signo_vital_saturacion"]').value = data.saturacion;
            document.querySelector('input[name="medida_peso"]').value = data.peso_kg;
            document.querySelector('input[name="medida_talla"]').value = data.talla_cm;
            document.querySelector('input[name="medida_imc"]').value = data.imc;
            $("#modal_reg_consulta").modal('show');
        }
    }).catch((err) => {
        console.log(err);
    });
}

/**
 * Filtrar diagostico basada en CIE10
 */
document.addEventListener('DOMContentLoaded', ()=>{
    try{
        let input_diagnostico = document.getElementById('diagnostico_consulta');
        if(input_diagnostico){
            input_diagnostico.addEventListener('keyup', (e) => {
                e.stopPropagation();
                let value = e.target.value;                
                getCIE10filter(value);
            })
            /* input_diagnostico.addEventListener('keydown', (event) => {
                if(){
                    
                }
            }) */
        }
    }catch(err){
        console.log(err)
    }
})
function getCIE10filter(codeValue){
    axios.post(route('cie10.filtrar'),{
        'code_descripcion': codeValue
    },{
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        let cie10_container = document.getElementById('cie10-container');
        let showItemsCIE10 = document.getElementById('showItemsCIE10');
        showItemsCIE10.innerHTML = '';
        if(result.data.length > 0){
            cie10_container.style.display = 'block';
            let data = result.data;
            data.forEach((item)=> {
                let row = `<tr>
                                <td class="t-border" colspan="10" style="width: 10%">${item.codigo_capitulo}</td>
                                <td class="t-border" colspan="10" style="width: 10%">${item.codigo_bloque}</td>
                                <td class="t-border" colspan="10" style="width: 10%">${item.codigo}</td>
                                <td class="t-border" colspan="70" style="width: 70%">${item.descripcion}</td>
                                <td class="t-border" colspan="10" style="width: 10%"><button type="button" data-codigo="${item.codigo}" data-descripcion="${item.descripcion}" onclick="selectedItemCie10(this)" class="btn btn-outline-info btn-sm btn-add-item-cie10"><i class="bi bi-plus-circle"></i></button></td>
                            </tr>`;
                showItemsCIE10.innerHTML += row;
            })
        }else{
            cie10_container.style.display = 'none';
        }
        console.log(result);
    }).catch((err) => {
        console.log(err);
    });
}

function selectedItemCie10(element){
    let codigo = element.dataset.codigo;
    let descripcion = element.dataset.descripcion;
    let index = array_diagnosticos.findIndex((item) => item.codigo === codigo);
    /* if(index === -1){
        array_diagnosticos.push({
            codigo,descripcion
        });
    }else{

    } */
    document.getElementById('diagnostico_consulta').value = `${codigo} : ${descripcion}`;
    document.getElementById('cie10-container').style.display = 'none';
}