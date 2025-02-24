var result_exam_ing = [];
/**
 * var para guardar datos generales de examen
 */
var items_data_examen = []; //data examen

let data_examenes_empleado = sessionStorage.getItem('data_examenes_empleado');
data_examenes_empleado = JSON.parse(data_examenes_empleado);
document.addEventListener('DOMContentLoaded', () => {
    //title content page
    document.getElementById('title_content_page').textContent = data_examenes_empleado.jornada;
    document.getElementById('display_nombre_empleado').textContent = data_examenes_empleado.nombre_empleado;
    listar_detalle_orden();
    //procesar formulario
    let form_resultado_exam = document.getElementById('form_resultado_exam');
    if (form_resultado_exam) {
        form_resultado_exam.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(form_resultado_exam);

            let { empleado_id, jornada_id, cat_examen, categoria, categoria_id } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('cat_examen', cat_examen);
            formData.append('categoria', categoria);

            formData.append('data_resultado', JSON.stringify(result_exam_ing));

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-ingresar-resultado").modal('hide');
                    form_resultado_exam.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }
    //Procesar form de heces
    let formExaGenHeces = document.getElementById('form-exa-gen-heces');
    if (formExaGenHeces) {
        formExaGenHeces.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(formExaGenHeces);

            let { empleado_id, jornada_id, categoria, cat_examen } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('categoria', categoria);
            formData.append('cat_examen', cat_examen);
            //new parametros datos examen
            formData.append('prop_data_examen',JSON.stringify(items_data_examen));

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-form-heces").modal('hide');
                    formExaGenHeces.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }
    //form examen general de orina
    let formExaGenOrina = document.getElementById('form-exa-gen-orina');
    if (formExaGenOrina) {
        formExaGenOrina.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(formExaGenOrina);

            let { empleado_id, jornada_id, categoria, cat_examen } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('cat_examen', cat_examen);
            formData.append('categoria', categoria);
            //new parametros datos examen
            formData.append('prop_data_examen',JSON.stringify(items_data_examen));

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-form-orina").modal('hide');
                    formExaGenOrina.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }
    //form examen de hemograma
    let formExaHemograma = document.getElementById('form-exa-hemograma');
    if (formExaHemograma) {
        formExaHemograma.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(formExaHemograma);

            let { empleado_id, jornada_id, categoria, cat_examen } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('categoria', categoria);
            formData.append('cat_examen', cat_examen);
            //new parametros datos examen
            formData.append('prop_data_examen',JSON.stringify(items_data_examen));

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-form-hemograma").modal('hide');
                    formExaHemograma.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }

    //procesar resultado de examen de baciloscopia
    let formResultBaciloscopia = document.getElementById('form_result-bacteriologia');
    if (formResultBaciloscopia) {
        formResultBaciloscopia.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(formResultBaciloscopia);

            let { empleado_id, jornada_id, categoria, cat_examen } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('categoria', categoria);
            formData.append('cat_examen', cat_examen);
            //new parametros datos examen
            formData.append('prop_data_examen',JSON.stringify(items_data_examen));
            //validacion

            let array_examenes = result_exam_ing.map( examen => examen.examen);
            for (let index = 0; index < array_examenes.length; index++) {

                if(array_examenes[index] === "BACILOSCOPIA"){
                    let resultado = document.querySelector('input[name="resultado_baciloscopia"]');
                    if (resultado.value.trim() === "") {
                        Toast.fire({
                            icon: "warning",
                            title: "El campo de resultado es obligatorio."
                        }); return;
                    }

                }else if(array_examenes[index] === "CULTIVO FARINGEO"){
                    let array_aisla_exof = $("#aisla_exof").selectize()[0].selectize.getValue();
                    let array_sensible_exof = $("#sensible_exof").selectize()[0].selectize.getValue();
                    let array_resiste_exof = $("#resiste_exof").selectize()[0].selectize.getValue();
                    let refiere_exof = $("#refiere_exof").selectize()[0].selectize.getValue();
                    //validaciones
                    if (array_aisla_exof.length === 0) {
                        Toast.fire({
                            icon: "warning",
                            title: "El campo (se aisla) es obligatorio."
                        }); return;
                    }
                    //validated
                    let val_normal = "NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA NORMAL";
                    if(!array_aisla_exof.includes(val_normal)){
                        if (array_sensible_exof.length === 0) {
                            Toast.fire({
                                icon: "warning",
                                title: "El campo (sensible a) es obligatorio."
                            }); return;
                        }
                        if (array_resiste_exof.length === 0) {
                            Toast.fire({
                                icon: "warning",
                                title: "El campo (resiste a) es obligatorio."
                            }); return;
                        }
                    }else{
                        $("#sensible_exof").selectize()[0].selectize.clear();
                        $("#resiste_exof").selectize()[0].selectize.clear();
                    }
        
                    if (refiere_exof === "") {
                        Toast.fire({
                            icon: "warning",
                            title: "El campo (refiere a) es obligatorio."
                        }); return;
                    }
        
                    let aisla_exof = array_aisla_exof.join(';');
                    let sensible_exof = array_sensible_exof.join(';')
                    let resiste_exof = array_resiste_exof.join(';');
        
                    //add form
                    formData.append('aisla_exofaringeo', aisla_exof);
                    formData.append('sensible_exofaringeo', sensible_exof);
                    formData.append('resiste_exofaringeo', resiste_exof);
                    formData.append('refiere_exofaringeo', refiere_exof);
                }   
            }

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-resultado-bacteriologia").modal('hide');
                    formResultBaciloscopia.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }
    //form resultado rpr
    let formResultRpr = document.getElementById('form_result-rpr');
    if (formResultRpr) {
        formResultRpr.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(formResultRpr);

            let { empleado_id, jornada_id, categoria, cat_examen } = getSessionDataExamen();

            formData.append('empleado_id', empleado_id);
            formData.append('jornada_id', jornada_id);
            formData.append('categoria', categoria);
            formData.append('cat_examen', cat_examen);
            //new parametros datos examen
            formData.append('prop_data_examen',JSON.stringify(items_data_examen));

            let resultado = document.querySelector('input[name="resultado_rpr"]');
            if (resultado.value.trim() === "") {
                Toast.fire({
                    icon: "warning",
                    title: "El campo de resultado es obligatorio."
                }); return;
            }

            axios.post(route('lab.resultado.save'), formData)
                .then((result) => {
                    let data = result.data;
                    Swal.fire({
                        title: data.titleMsg,
                        text: data.message,
                        icon: data.status
                    });
                    $("#modal-resultado-rpr").modal('hide');
                    formResultRpr.reset();
                    $("#dt_examenes_empleado").DataTable().ajax.reload();
                }).catch((err) => {
                    console.log(err);
                    Toast.fire({
                        icon: "warning",
                        title: "Ha ocurrido un error al momento de procesar la solicitud."
                    }); return;
                });
        })
    }
})

function listar_detalle_orden() {
    dataTable("dt_examenes_empleado", route('lab.orden.detalle'), { empleado_id: data_examenes_empleado.empleado_id, jornada_id: data_examenes_empleado.jornada_id });
}

//Ingresar resultados
function ingresarResultado(element) {
    let cat_examen = element.dataset.cat_examen;
    let empleado_id = element.dataset.empleado_id;
    let jornada_id = element.dataset.jornada_id;
    let categoria_id = element.dataset.categoria_id;
    let categoria = element.dataset.categoria;
    sessionStorage.setItem('lab_empleado_id', empleado_id);
    sessionStorage.setItem('lab_jornada_id', jornada_id);
    sessionStorage.setItem('lab_cat_examen', cat_examen);
    sessionStorage.setItem('lab_categoria', categoria);
    sessionStorage.setItem('lab_categoria_id', categoria_id);

    axios.post(route('lab.examen-resultado'), {
        cat_examen, empleado_id, jornada_id, categoria, categoria_id
    }).then((result) => {
        if (categoria === "QUIMICA") {
            document.getElementById('display_examen').textContent = categoria;

            document.getElementById('form_resultado_exam').reset();
            showFormQuimica(result);
            $("#modal-ingresar-resultado").modal('show');

        } else if (categoria === "UROLOGIA") {
            items_data_examen = [];
            //EXAMEN GENERAL DE ORINA
            let data = result.data;
            data.forEach((examen) => {
                if(examen.examen === "EGO"){
                    items_data_examen.push({
                        examen_id: examen.id,
                        jornada_id: examen.jornada_id,
                        empleado_id: examen.empleado_id
                    });

                    document.getElementById('form-exa-gen-orina').reset();
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                    $("#modal-form-orina").modal('show');
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: "Este examen no esta disponible."
                    }); return;
                }
            });
        }else if(categoria === "COPROLOGIA"){
            items_data_examen = [];

            let data = result.data;
            data.forEach((examen) => {
                if(['HECES', 'EGH', 'EXAMEN GENERAL DE HECES'].includes(examen.examen.toUpperCase())){
                    items_data_examen.push({
                        examen_id: examen.id,
                        jornada_id: examen.jornada_id,
                        empleado_id: examen.empleado_id
                    });

                    document.getElementById('form-exa-gen-heces').reset();
                    //validacion
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                    $("#modal-form-heces").modal('show');
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: "Este examen no esta disponible."
                    }); return;
                }
            });
        }else if(categoria === "INMUNOLOGIA"){
            items_data_examen = [];
            let data = result.data;
            data.forEach( (examen) => {
                if(['R.P.R', 'VDRL'].includes(examen.examen.toUpperCase())){
                    items_data_examen.push({
                        examen_id: examen.id,
                        jornada_id: examen.jornada_id,
                        empleado_id: examen.empleado_id
                    });
                    document.getElementById('form_result-rpr').reset();
                    $("#modal-resultado-rpr").modal('show');
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: "Este examen no esta disponible."
                    }); return;
                }
            })

        }else if(categoria === "BACTERIOLOGIA"){
            let data = result.data;
            result_exam_ing = data;
            items_data_examen = [];
            
            let content_form_bacteriologia = document.getElementById('content_form_bacteriologia');
            content_form_bacteriologia.innerHTML = '';
            
            document.getElementById('form_result-bacteriologia').reset();

            data.forEach(examen => {
                let data = {
                    examen_id: examen.id,
                    jornada_id: examen.jornada_id,
                    empleado_id: examen.empleado_id
                };

                if(examen.examen === "BACILOSCOPIA"){
                    items_data_examen.push(data);

                    let card = document.createElement('div');
                    card.classList.add('card', 'm-0', 'p-1', 'mb-3');

                    let contentCard = document.createRange().createContextualFragment(/*html*/`
                            <div class="card-header p-1 bg-dark text-center text-white">
                                <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE : <span style="font-weight:700">${examen.examen}</span></h4>
                            </div>
                            <div class="card-body p-2 m-0">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="content-input">
                                            <input name="resultado_baciloscopia" type="search" list="baciloscopia_default" class="custom-input material" value="" placeholder=" ">
                                            <label class="input-label" for="resultado_baciloscopia">Resultado</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="content-input">
                                            <input name="observaciones_baciloscopia" type="search"
                                                class="custom-input material" value="" placeholder=" ">
                                            <label class="input-label" for="observaciones_baciloscopia">Observaciones</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `);

                    card.appendChild(contentCard);
                    content_form_bacteriologia.appendChild(card);
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                
                }else if(['CULTIVO FARINGEO', 'EXOFARINGEO'].includes(examen.examen)){
                    items_data_examen.push(data);

                    let card = document.createElement('div');
                    card.classList.add('card', 'm-0', 'p-1', 'mb-1');
                    let contentCard = document.createRange().createContextualFragment(/*html*/`
                        <div class="card-header p-1 bg-dark text-center text-white">
                            <h4 class="m-0" style="font-size: 14px">INGRESAR RESULTADOS DE : <span
                                    style="font-weight:700">${examen.examen}</span></h4>
                        </div>
                        <div class="card-body p-2 m-0">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="aisla_exof" class="input-group-title1">Se aisla: </label>
                                        <select name="aisla_exof" id="aisla_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option
                                                value="NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA NORMAL">
                                                NO SE AISLAN PATOGENOS, CRECIMIENTOS DE BACTERIAS DE LA MICROBIOTA
                                                NORMAL
                                            </option>
                                            <option value="KLEBSIELLA PNEUMONAE">KLEBSIELLA PNEUMONAE
                                            </option>
                                            <option value="STREPTOCOCCUS BETA HEMOLITICO DEL GRUPO A">STREPTOCOCCUS BETA
                                                HEMOLITICO DEL GRUPO A
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="sensible_exof" class="input-group-title1">Sensible a: </label>
                                        <select name="sensible_exof" id="sensible_exof"
                                            class="form-select border-radius" data-toggle="tooltip"
                                            data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="AMOXICILINA/ACIDO CLAVULANICO">AMOXICILINA/ACIDO CLAVULANICO
                                            </option>
                                            <option value="IMIPENEM">IMIPENEM</option>
                                            <option value="TRIMETROPRIM/SULFAMETOXALE">TRIMETROPRIM/SULFAMETOXALE
                                            </option>
                                            <option value="CIPROFLOXACINA">CIPROFLOXACINA</option>
                                            <option value="CEFIXIME">CEFIXIME</option>
                                            <option value="FOSFOMICINA">FOSFOMICINA</option>
                                            <option value="CEFADROXIL">CEFADROXIL</option>
                                            <option value="CEFTAZIDIMA">CEFTAZIDIMA</option>
                                            <option value="CEFOTAXIMA">CEFOTAXIMA</option>
                                            <option value="CEFOXITIN">CEFOXITIN</option>
                                            <option value="CEFEPIME">CEFEPIME</option>
                                            <option value="CEFTRIAXONA">CEFTRIAXONA</option>
                                            <option value="LEVOFLAXACINA">LEVOFLAXACINA</option>
                                            <option value="CEFOTAXIN">CEFOTAXIN</option>
                                            <option value="CEFUROXIME">CEFUROXIME</option>
                                            <option value="AUGMENTIN">AUGMENTIN</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMIKIN">AMIKIN</option>
                                            <option value="CEFTRIAXONE">CEFTRIAXONE</option>
                                            <option value="CLARITROMICINA">CLARITROMICINA</option>
                                            <option value="AMOXICICILINA">AMOXICICILINA</option>
                                            <option value="TMP+SMT">TMP+SMT</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMPICILINA">AMPICILINA</option>
                                            <option value="PIPERACILINA/TAZOBACTAM">PIPERACILINA/TAZOBACTAM</option>
                                            <option value="AMIKACINA">AMIKACINA</option>
                                            <option value="CLINDAMICINA">CLINDAMICINA</option>
                                            <option value="VANCOMICINA">VANCOMICINA</option>
                                            <option value="NORFLOXACINA">NORFLOXACINA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="resiste_exof" class="input-group-title1">Resiste a: </label>
                                        <select name="resiste_exof" id="resiste_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="AMOXICILINA/ACIDO CLAVULANICO">AMOXICILINA/ACIDO CLAVULANICO
                                            </option>
                                            <option value="IMIPENEM">IMIPENEM</option>
                                            <option value="TRIMETROPRIM/SULFAMETOXALE">TRIMETROPRIM/SULFAMETOXALE
                                            </option>
                                            <option value="CIPROFLOXACINA">CIPROFLOXACINA</option>
                                            <option value="CEFIXIME">CEFIXIME</option>
                                            <option value="FOSFOMICINA">FOSFOMICINA</option>
                                            <option value="CEFADROXIL">CEFADROXIL</option>
                                            <option value="CEFTAZIDIMA">CEFTAZIDIMA</option>
                                            <option value="CEFOTAXIMA">CEFOTAXIMA</option>
                                            <option value="CEFOXITIN">CEFOXITIN</option>
                                            <option value="CEFEPIME">CEFEPIME</option>
                                            <option value="CEFTRIAXONA">CEFTRIAXONA</option>
                                            <option value="LEVOFLAXACINA">LEVOFLAXACINA</option>
                                            <option value="CEFOTAXIN">CEFOTAXIN</option>
                                            <option value="CEFUROXIME">CEFUROXIME</option>
                                            <option value="AUGMENTIN">AUGMENTIN</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMIKIN">AMIKIN</option>
                                            <option value="CEFTRIAXONE">CEFTRIAXONE</option>
                                            <option value="CLARITROMICINA">CLARITROMICINA</option>
                                            <option value="AMOXICICILINA">AMOXICICILINA</option>
                                            <option value="TMP+SMT">TMP+SMT</option>
                                            <option value="GENTAMICINA">GENTAMICINA</option>
                                            <option value="AMPICILINA">AMPICILINA</option>
                                            <option value="PIPERACILINA/TAZOBACTAM">PIPERACILINA/TAZOBACTAM</option>
                                            <option value="AMIKACINA">AMIKACINA</option>
                                            <option value="CLINDAMICINA">CLINDAMICINA</option>
                                            <option value="VANCOMICINA">VANCOMICINA</option>
                                            <option value="NORFLOXACINA">NORFLOXACINA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <label for="refiere_exof" class="input-group-title1">Referido a: </label>
                                        <select name="refiere_exof" id="refiere_exof" class="form-select border-radius"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">Selecccionar</option>
                                            <option value="LABORATORIO VIDLAB">LABORATORIO VIDLAB</option>
                                            <option value="LABORATORIO SOUNDY CALL">LABORATORIO SOUNDY CALL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    card.appendChild(contentCard);
                    content_form_bacteriologia.appendChild(card);
                    $("#aisla_exof").selectize({
                        maxItems: null,
                        delimiter: ",",
                        plugins: ['remove_button'],
                        persist: false
                    })[0].selectize.clear();
                    $("#sensible_exof").selectize({
                        maxItems: null,
                        delimiter: ",",
                        plugins: ['remove_button'],
                        persist: false
                    })[0].selectize.clear();
                    $("#resiste_exof").selectize({
                        maxItems: null,
                        delimiter: ",",
                        plugins: ['remove_button'],
                        persist: false
                    })[0].selectize.clear();
        
                    $("#refiere_exof").selectize()[0].selectize.clear();
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                }
            })
            $("#modal-resultado-bacteriologia").modal('show');
        }else if(categoria === "HEMATOLOGIA"){
            items_data_examen = [];

            let data = result.data;
            data.forEach((examen) => {
                if(examen.examen === "HEMOGRAMA COMPLETO"){
                    
                    items_data_examen.push({
                        examen_id: examen.id,
                        jornada_id: examen.jornada_id,
                        empleado_id: examen.empleado_id
                    });

                    document.getElementById('form-exa-hemograma').reset();
                    $("#modal-form-hemograma").modal('show');
                    if(Object.keys(examen.resultado).length > 0){
                        loadDataResultado(examen.resultado);
                    }
                }
            })
        }

    }).catch((err) => {
        console.log(err);
    });
}
function showFormQuimica(result) {
    let content_form = document.getElementById('content-form');
    content_form.innerHTML = '';

    result_exam_ing = result.data.examenes;//array

    document.querySelector('input[name="observaciones_quimica"]').value = result.data.observaciones;

    let divRow = document.createElement('div');
    divRow.classList.add('row');

    result_exam_ing.forEach((examen) => {
        let input = document.createRange().createContextualFragment(/*html*/`
            <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2 mb-2">
                <div class="content-input">
                    <span class="medida">mg/dl</span>
                    <input onkeyup="updResulExam(this)" data-exam_id="${examen.id}" data-empleado_id="${examen.empleado_id}" name="res_${examen.examen.toLowerCase()}" type="number"
                        class="custom-input material input-resultado nextInputEnter" step=".001" value="${examen.resultado}" placeholder=" " style="text-transform: uppercase">
                    <label class="input-label" for="resultado">${examen.examen}</label>
                </div>
            </div>
        `);
        divRow.appendChild(input);
    })
    content_form.appendChild(divRow);
}

function updResulExam(element) {
    let exam_id = parseInt(element.dataset.exam_id);
    let empleado_id = parseInt(element.dataset.empleado_id);

    let index = result_exam_ing.findIndex(resultado => resultado.id === exam_id && resultado.empleado_id === empleado_id);
    result_exam_ing[index].resultado = element.value;
}

function loadDataResultado(data) {
    //validacion segun examen
    if (['GLUCOSA', 'COLESTEROL', 'TRIGLICERIDOS', 'CREATININA', 'ACIDO URICO', 'SGOT', 'SGPT'].includes(data.examen.toUpperCase())) {
        document.querySelector('input[name="resultado"]').value = data.resultado;
        document.querySelector('input[name="observaciones"]').value = data.observaciones;
    } else if (data.examen.toUpperCase() === "BACILOSCOPIA") {
        document.querySelector('input[name="resultado_baciloscopia"]').value = data.resultado;
        document.querySelector('input[name="observaciones_baciloscopia"]').value = data.observaciones;
    } else if (['VDRL', 'R.P.R'].includes(data.examen.toUpperCase())) {
        document.querySelector('input[name="resultado_rpr"]').value = data.resultado;
        document.querySelector('input[name="observaciones_rpr"]').value = data.observaciones;
    } else if (["CULTIVO FARINGEO", "EXOFARINGEO"].includes(data.examen.toUpperCase())) {

        $("#aisla_exof").selectize()[0].selectize.setValue(data.aisla.split(";"));
        $("#sensible_exof").selectize()[0].selectize.setValue(data.sensible.split(";"));
        $("#resiste_exof").selectize()[0].selectize.setValue(data.resiste.split(";"));
        $("#refiere_exof").selectize()[0].selectize.setValue(data.refiere);

    } else if (data.examen === "EGO") {
        document.querySelector('input[name="ego_color"]').value = data.color;
        document.querySelector('input[name="ego_olor"]').value = data.olor;
        document.querySelector('input[name="ego_aspecto"]').value = data.aspecto;
        document.querySelector('input[name="ego_densidad"]').value = data.densidad;
        document.querySelector('input[name="ego_esterasas"]').value = data.est_leuco;
        document.querySelector('input[name="ego_nitritos"]').value = data.nitritos_orina;
        document.querySelector('input[name="ego_ph"]').value = data.ph;
        document.querySelector('input[name="ego_proteinas"]').value = data.proteinas;
        document.querySelector('input[name="ego_glucosa"]').value = data.glucosa;
        document.querySelector('input[name="ego_cetonas"]').value = data.cetonas;
        document.querySelector('input[name="ego_urobili"]').value = data.urobilinogeno;
        document.querySelector('input[name="ego_bilirrubina"]').value = data.bilirrubina;
        document.querySelector('input[name="ego_sangre_ocul"]').value = data.sangre_oculta;

        document.querySelector('input[name="ego_cilidros"]').value = data.cilindros;
        document.querySelector('input[name="ego_leucocitos"]').value = data.leucocitos;
        document.querySelector('input[name="ego_hematies"]').value = data.hematies;
        document.querySelector('input[name="ego_cel_epiteliales"]').value = data.cel_epiteliales;
        document.querySelector('input[name="ego_filamentos"]').value = data.filamentos_muco;
        document.querySelector('input[name="ego_bacterias"]').value = data.bacterias;
        document.querySelector('input[name="ego_cristales"]').value = data.cristales;
        document.querySelector('input[name="ego_observaciones"]').value = data.observaciones;
    } else if (data.examen === "EGH") {
        document.querySelector('input[name="egh_color"]').value = data.color;
        document.querySelector('input[name="egh_consistencia"]').value = data.consistencia;
        document.querySelector('input[name="egh_mucus"]').value = data.mucus;
        document.querySelector('input[name="egh_macroscopicos"]').value = data.macroscopicos;
        document.querySelector('input[name="egh_microscopicos"]').value = data.microscopicos;

        document.querySelector('input[name="egh_hematies"]').value = data.hematies;
        document.querySelector('input[name="egh_leucocitos"]').value = data.leucocitos;
        document.querySelector('input[name="egh_protozoarios"]').value = data.protozoarios;
        document.querySelector('input[name="egh_activos"]').value = data.activos;
        document.querySelector('input[name="egh_quistes"]').value = data.quistes;
        document.querySelector('input[name="egh_metazoarios"]').value = data.metazoarios;
        document.querySelector('input[name="egh_observaciones"]').value = data.observaciones;
    } else if (data.examen === "HEMOGRAMA COMPLETO") {
        document.querySelector('input[name="gr_hemato"]').value = data.gr_hemato;
        document.querySelector('input[name="ht_hemato"]').value = data.ht_hemato;
        document.querySelector('input[name="hb_hemato"]').value = data.hb_hemato;
        document.querySelector('input[name="vcm_hemato"]').value = data.vcm_hemato;
        document.querySelector('input[name="hcm_hemato"]').value = data.hcm_hemato;
        document.querySelector('input[name="cmhc_hemato"]').value = data.cmhc_hemato;
        document.querySelector('input[name="gota_gruesa"]').value = data.gota_hema;

        document.querySelector('input[name="gb_hemato"]').value = data.gb_hemato;
        document.querySelector('input[name="linfocitos_hemato"]').value = data.linfocitos_hemato;
        document.querySelector('input[name="monocitos_hemato"]').value = data.monocitos_hemato;
        document.querySelector('input[name="eosinofilos_hemato"]').value = data.eosinofilos_hemato;
        document.querySelector('input[name="basinofilos_hemato"]').value = data.basinofilos_hemato;

        document.querySelector('input[name="banda_hemato"]').value = data.banda_hemato;
        document.querySelector('input[name="segmentado_hemato"]').value = data.segmentados_hemato;
        document.querySelector('input[name="metamielo_hemato"]').value = data.metamielo_hemato;
        document.querySelector('input[name="mielocitos_hemato"]').value = data.mielocitos_hemato;
        document.querySelector('input[name="blasto_hemato"]').value = data.blastos_hemato;

        document.querySelector('input[name="plaquetas_hemato"]').value = data.plaquetas_hemato;
        document.querySelector('input[name="reticulocitos_hemato"]').value = data.reti_hemato;
        document.querySelector('input[name="eritrosedimentacion_hemato"]').value = data.eritro_hemato;
        document.querySelector('input[name="otros_hemato"]').value = data.otros_hema;
    }
}

function getSessionDataExamen() {
    return {
        empleado_id: sessionStorage.getItem('lab_empleado_id'),
        jornada_id: sessionStorage.getItem('lab_jornada_id'),
        cat_examen: sessionStorage.getItem('lab_cat_examen'),
        categoria: sessionStorage.getItem('lab_categoria'),
        categoria_id: sessionStorage.getItem('lab_categoria_id')
    }
}

function formTemplateStandar(id_element, data = null) {
    document.getElementById(id_element).innerHTML = '';
    let divRow = document.createElement('div');
    divRow.classList.add('row');

    divRow.innerHTML = `
        <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
            <div class="content-input">
                <span class="medida">mg/dl</span>
                <input name="resultado" type="number"
                    class="custom-input material input-resultado" step=".00001" value="" placeholder=" " style="text-transform: uppercase">
                <label class="input-label" for="resultado"></label>
            </div>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-8 mb-2">
            <div class="content-input">
                <input name="observaciones" type="search"
                    class="custom-input material" value="" placeholder=" " style="text-transform: uppercase">
                <label class="input-label" for="observaciones">Observaciones</label>
            </div>
        </div>
    `;

    document.getElementById(id_element).appendChild(divRow);
}

document.addEventListener('DOMContentLoaded', () => {
    /**
     * DATALIST EXAMEN DE HECES
     */
    let colores = ["Amarillo", "Café", "Verde", "Blanca", "Roja"];
    let consistencia = ["Formada", "Blanda", "Pastosa", "Liquida", "Dura"];
    let mucus = ["Negativo", "Positivo", "Positivo +", "Positivo ++", "Positivo +++"];
    let macros = ["Escasos", "Moderada cantidad", "Abundantes"];
    let micros = ["Escasos", "Moderada cantidad", "Abundantes"];
    let hematies = ["No se observan"];
    let quistes = ["No se observan", "Blastocystis hominis", "Endolimax nana", "Giardia lamblia", "Chilomastix mesnili", "Iodamoeba butschlii", "Entamoeba coli", "Entamoeba histolica", "Balantidium coli", "Acanthamoeba spp"];
    let metazoarios = ["No se observan", "Shistosoma mansoni", "Trichuris trichura", "Ascaris lumbricoides", "Hymendepis nana", "Hymendepis nana", "Strongyloides stercolaris", "Taenia spp"];

    //call function createDataList
    createDataList('colores_default', colores);
    createDataList('consistencia_default', consistencia);
    createDataList('mucus_default', mucus);
    createDataList('macro_default', macros);
    createDataList('micro_default', micros);
    createDataList('hematies_default', hematies);
    createDataList('leucocitos_default', hematies);
    createDataList('quistes_default', quistes);
    createDataList('metazoarios_default', metazoarios);
    createDataList('activos_default', hematies);
    /**
     * DATALIST EXAMEN DE ORINA
     */
    let color = ["Amarillo"];
    let aspecto = ["Limpio", "Turbio", "Leve turbio"];
    let densidad = ["1.020", "1.005", "1.010", "1.015", "1.020", "1.025", "1.030"];
    let esterasas = ["Negativo", "10-25 leu/ul", "75 leu/ul", "125 leu/ul", "500 leu/ul"];
    let nitritos = ["Negativo", "Positivo +", "Positivo++", "Positivo+++"];
    let ph = ["5.0", "5.5", "6.0", "6.5", "7.0", "7.5", "8.0", "8.5", "9.0"];
    let proteinas = ["Negativo", "15mg/dl", "30mg/dl", "100mg/dl", "300mg/dl", "2000mg/dl"];
    let glucosa = ["Negativo", "100mg/dl", "250mg/dl", "500mg/dl", "1000mg/dl", "2000mg/dl"];
    let cetonas = ["Negativo", "5mg/dl", "15mg/dl", "40mg/dl", "80mg/dl", "160mg/dl"];
    let urobilinogeno = ["Negativo", "0.2mg/dl", "1mg/dl", "2mg/dl", "4mg/dl", "8mg/dl", "12mg/dl"];
    let bilirrubina = ["Negativo", "1mg/dl", "2mg/dl", "4mg/dl"];
    let sangre_oculta = ["Negativo", "Trazas", "5Ery/ul", "10Ery/ul", "50Ery/ul", "250Ery/ul"];
    let cilindros = ["No se observan"];
    let leucocitos = ["6-8 x campo"];
    let epiteliales = ["Escamosas escasas", "Escamosas moderada cantidad", "Escamosas abundantes", "Redondas escasas", "Redondas moderada", "Redondas abundantes"];
    let filamentos = ["No se observa"];
    let cristales = ["No se observa"];
    let bacterias = ["No se observan", "Escasas", "Moderada cantidad", "Abundantes"];

    createDataList('color_default', color);
    createDataList('aspecto_default', aspecto);
    createDataList('densidad_default', densidad);
    createDataList('esterasas_default', esterasas);
    createDataList('nitrito_default', nitritos);
    createDataList('ph_default', ph);
    createDataList('proteinas_default', proteinas);
    createDataList('glucosa_default', glucosa);
    createDataList('cetona_default', cetonas);
    createDataList('urobilinogeno_default', urobilinogeno);
    createDataList('bilirrubina_default', bilirrubina);
    createDataList('sangre_ocult_default', sangre_oculta);
    createDataList('cilindros_default', cilindros);
    createDataList('leucocitos_default', leucocitos);
    createDataList('hematiies_default', hematies);
    createDataList('cel_epiteliales_default', epiteliales);
    createDataList('filamento_default', filamentos);
    createDataList('bacterias_default', bacterias);
    createDataList('cristales_default', cristales);
    //Baciloscopia
    let baci = ["No se observan Bacilos Ácido-Alcohol Resistente", "Positivo"];
    createDataList('baciloscopia_default', baci);
    //rpr
    let resrpr = ["NO REACTIVO A LA FECHA", "REACTIVO"];
    createDataList('rpr_default', resrpr);
})

function createDataList(id_list, data) {
    let dataList = document.createElement('datalist');
    dataList.id = id_list;
    data.forEach(color => {
        let option = document.createElement('option');
        option.value = color;
        dataList.appendChild(option);
    })
    document.body.appendChild(dataList);
}

//code de vasper
document.addEventListener('DOMContentLoaded', () => {
    Number.prototype.format = function (n, x, s, c) {
        let re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));
        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };
})

function setInputFilter(textbox, inputFilter) {
    ["input"].forEach(function (event) {
        textbox.addEventListener(event, function () {
            if (this.id === "gr_hemato" || this.id === "gb_hemato" || this.id === "plaquetas_hemato") {
                if (this.value !== "") {
                    let str = this.value;
                    let oldstr = str.substring(0, str.length - 1);
                    let millares = ",";
                    let decimales = ".";
                    str = str.split(millares).join("");
                    if (isNaN(str)) {
                        this.value = oldstr;
                    } else {
                        let numero = parseInt(str);
                        this.value = numero.format(0, 3, millares, decimales);
                    }
                }
            }
        });
    });
}
setInputFilter(document.getElementById("gr_hemato"), function (value) {
    let regex = new RegExp(/^-?\d*$/);
    //test the regexp
    return regex.test(value);
});

setInputFilter(document.getElementById("gb_hemato"), function (value) {
    let regex = new RegExp(/^-?\d*$/);
    //test the regexp
    return regex.test(value);
});
setInputFilter(document.getElementById("plaquetas_hemato"), function (value) {
    let regex = new RegExp(/^-?\d*$/);
    //test the regexp
    return regex.test(value);
});