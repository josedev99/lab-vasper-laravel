document.addEventListener('DOMContentLoaded', () => {
list_examenes();
listar_perfiles_orden();

let urlDataTableEspeciales = window.location.origin + "/examenes_esp";
dataTable("examenes_esp", urlDataTableEspeciales);

let urlDataComplementarias = window.location.origin + "/examenes_comp";
dataTable("examenes_comple", urlDataComplementarias);
 
//button edicion y delete
let tablecomp = new DataTable('#examenes_comple');
tablecomp.on('draw.dt', eventoEditDele);
//button edicion y delete
let tableEsp = new DataTable('#examenes_esp');
tableEsp.on('draw.dt', eventoEditDeleEsp);

function eventoEditDele() {
    let btnEdicions = document.querySelectorAll('.btn-o-EditExa');
    let btnDestroyExam = document.querySelectorAll('.btn-o-delExa');


    if (btnEdicions) {
        //Edicion
        btnEdicions.forEach(btn => {
            btn.addEventListener('click', async () => {
                let id_ex = btn.dataset.emp_ref;
        
                // Realiza la solicitud para obtener los datos del examen
                let response = await axios.post(route('app.getExamenEsp'), { ref_emp: id_ex }, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                });
        
                let data = response.data;
        
                // Extrae el nombre actual para editar
                let nombreActual = data[0].nombre;
        
                // SweetAlert para editar el nombre
                Swal.fire({
                    title: 'Edicion complementaria',
                    input: 'text',
                    inputLabel: 'Examen',
                    inputValue: nombreActual,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (nuevoNombre) => {
                        // Validación básica para evitar guardar campos vacíos
                        if (!nuevoNombre) {
                            Swal.showValidationMessage('El nombre no puede estar vacío');
                            return false;
                        }
                        return nuevoNombre;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realiza la actualización usando Axios
                        let nuevoNombre = result.value;
        
                        axios.post(route('app.updateExamenEsp'), { 
                            id: id_ex,
                            nombre: nuevoNombre
                        })
                        .then(response => {
                            Swal.fire({
                                title: 'Actualizado',
                                text: 'El nombre ha sido actualizado con éxito.',
                                icon: 'success'
                            });
                            let urlDataComplementarias = window.location.origin + "/examenes_comp";
                            dataTable("examenes_comple", urlDataComplementarias);
 
        
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un error al actualizar el nombre.',
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
        

        //Destroy
        btnDestroyExam.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                let examen = btn.dataset.emp_ref;
                Swal.fire({
                    title: "¿Eliminar Examen?",
                    text: "Esta acción es irreversible.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let response = await axios.post(route('app.examen.destroy'), { examen }, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Content-Encodign': 'gzip'
                            }
                        });
                        if (response.status === 200) {
                            if (response.data.status === "success") {
                                Swal.fire({
                                    title: "Éxito",
                                    text: response.data.message,
                                    icon: "success"
                                });
                                let urlDataComplementarias = window.location.origin + "/examenes_comp";
                                dataTable("examenes_comple", urlDataComplementarias);   
                            } else {
                                Swal.fire({
                                    title: "Aviso",
                                    text: response.data.message,
                                    icon: response.data.status
                                });
                            }
                        }
                    }
                });
            })
        })
    }
}


function eventoEditDeleEsp() {
    let btnEdicions = document.querySelectorAll('.btn-o-EditExaes');
    let btnDestroyExam = document.querySelectorAll('.btn-o-delExaEs');

    if (btnEdicions) {
        //Edicion
        btnEdicions.forEach(btn => {
            btn.addEventListener('click', async () => {
                let id_ex = btn.dataset.emp_ref;
        
                // Realiza la solicitud para obtener los datos del examen
                let response = await axios.post(route('app.getExamenEsp'), { ref_emp: id_ex }, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                });
        
                let data = response.data;
        
                // Extrae el nombre actual para editar
                let nombreActual = data[0].nombre;
        
                // SweetAlert para editar el nombre
                Swal.fire({
                    title: 'Edicion complementaria',
                    input: 'text',
                    inputLabel: 'Examen',
                    inputValue: nombreActual,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (nuevoNombre) => {
                        // Validación básica para evitar guardar campos vacíos
                        if (!nuevoNombre) {
                            Swal.showValidationMessage('El nombre no puede estar vacío');
                            return false;
                        }
                        return nuevoNombre;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realiza la actualización usando Axios
                        let nuevoNombre = result.value;
        
                        axios.post(route('app.updateExamenEsp'), { 
                            id: id_ex,
                            nombre: nuevoNombre
                        })
                        .then(response => {
                            Swal.fire({
                                title: 'Actualizado',
                                text: 'El nombre ha sido actualizado con éxito.',
                                icon: 'success'
                            });
       
let urlDataTableEspeciales = window.location.origin + "/examenes_esp";
dataTable("examenes_esp", urlDataTableEspeciales);

        
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un error al actualizar el nombre.',
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
        

        //Destroy
        btnDestroyExam.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                let examen = btn.dataset.emp_ref;
                Swal.fire({
                    title: "¿Eliminar Examen?",
                    text: "Esta acción es irreversible.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let response = await axios.post(route('app.examen.destroy'), { examen }, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Content-Encodign': 'gzip'
                            }
                        });
                        if (response.status === 200) {
                            if (response.data.status === "success") {
                                Swal.fire({
                                    title: "Éxito",
                                    text: response.data.message,
                                    icon: "success"
                                });
                         
                            let urlDataTableEspeciales = window.location.origin + "/examenes_esp";
                            dataTable("examenes_esp", urlDataTableEspeciales);

                            } else {
                                Swal.fire({
                                    title: "Aviso",
                                    text: response.data.message,
                                    icon: response.data.status
                                });
                            }
                        }
                    }
                });
            })
        })
    }
}

let input_search = document.getElementById('input-search');
if(input_search){
    input_search.addEventListener('keyup', (e)=>{
        filtrarCatOrExamen(e.target.value);
    });
}

let input_search_perfil = document.getElementById('input-search-perfil');
if(input_search_perfil){
    input_search_perfil.addEventListener('keyup', (e)=>{
        filtrarPerfilesOrExamen(e.target.value);
    });
}

function filtrarPerfilesOrExamen(value){
    let acordeones = document.querySelectorAll('#rows_examenes_ordenP2 .accordion-item');
    
    acordeones.forEach(acordeon => {
        let perfilText = acordeon.querySelector('.accordion-button strong').getAttribute('data-cat').toLowerCase(); // Obtiene el nombre del perfil
        let examenes = acordeon.querySelectorAll('.accordion-body label'); // Selecciona todos los exámenes dentro del acordeón
        let regex = new RegExp(`${value}`, 'i'); // Crear una expresión regular con el valor de búsqueda

        let itemCat = acordeon.parentElement; // Seleccionar el contenedor principal del acordeón

        let hasMatch = false; // Bandera para saber si hay coincidencia

        // Si el título del perfil coincide con la búsqueda, mostrar el acordeón
        if (regex.test(perfilText)) {
            hasMatch = true;
        } else {
            // Si el perfil no coincide, buscar coincidencias en los exámenes
            examenes.forEach(examen => {
                let examText = examen.getAttribute('data-exam').toLowerCase();
                if (regex.test(examText)) {
                    hasMatch = true;
                }
            });
        }

        // Mostrar u ocultar acordeón según si hay coincidencias
        if (hasMatch) {
            itemCat.style.display = 'block'; // Mostrar si hay coincidencias
        } else {
            itemCat.style.display = 'none'; // Ocultar si no hay coincidencias
        }
    });
}


let btnAdExamen = document.querySelector('.btnAdExamen');
if(btnAdExamen){
    btnAdExamen.addEventListener('click', async (e)=>{
        $("#modal_new_examen").modal('show');
    })
}

        //Button para nuevo perfil
let btn_new_perfil = document.querySelector('.btn_new_perfil');
    if (btn_new_perfil) {
        btn_new_perfil.addEventListener('click', (e) => {
        e.stopPropagation();
        dataTable("dt_cat_examenes", route('examenes.dt'));
        data_examenes_perfil = [];
        list_items_examen_perfil();
        $("#modal_nuevo_perfil").modal('show');
    })
}

let btn_addcat = document.querySelector('.btn-add-catego');
if(btn_addcat){
    btn_addcat.addEventListener('click', (e)=>{
        e.stopPropagation();
        $("#modal_new_categorias").modal('show');
    })
}


let btnEspeciales = document.querySelector('.btnAdExamenEsp');
if(btnEspeciales){
    btnEspeciales.addEventListener('click', async (e)=>{
        $("#modal_new_examen_especial").modal('show');
    })
}


let btnComplementarios = document.querySelector('.btnAdExamenComp');
if(btnComplementarios){
    btnComplementarios.addEventListener('click', async (e)=>{
        $("#modal_new_examen_complementarios").modal('show');
    })
}



let form_guardarExamen = document.getElementById('form_examenCat');
if(form_guardarExamen){
    form_guardarExamen.addEventListener('submit', (e)=>{
        e.preventDefault();

        let formData = new FormData(form_guardarExamen);
        
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
        axios.post(route('examen.save'),formData)
        .then((result) => {
            if(result.data.status === "success"){
                $("#modal_new_examen").modal('hide');
                Swal.fire({
                    title: "Éxito",
                    text: result.data.message,
                    icon: "success"
                  });
                list_examenes();
                form_guardarExamen.reset();
            }else if(result.data.status === "exists"){
                Toast.fire({
                    icon: "warning",
                    title: result.data.message
                  });
            }else{
                Swal.fire({
                    title: "Error",
                    text: result.data.message,
                    icon: "error"
                  });
            }
        }).catch((err) => {
            console.log(err);
        });
    })
}



let form_guardarExamenEspecial = document.getElementById('form_examenCatEsp');
if(form_guardarExamenEspecial){
    form_guardarExamenEspecial.addEventListener('submit', (e)=>{
        e.preventDefault();

        let formData = new FormData(form_guardarExamenEspecial);
        
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
        axios.post(route('examenEspeciales.save'),formData)
        .then((result) => {
            if(result.data.status === "success"){
                $("#modal_new_examen_especial").modal('hide');
                Swal.fire({
                    title: "Éxito",
                    text: result.data.message,
                    icon: "success"
                  });
                  let urlDataTableEmpresa = window.location.origin + "/examenes_esp";
                  dataTable("examenes_esp", urlDataTableEmpresa);
                  form_guardarExamenEspecial.reset();
            }else if(result.data.status === "exists"){
                Toast.fire({
                    icon: "warning",
                    title: result.data.message
                  });
            }else{
                Swal.fire({
                    title: "Error",
                    text: result.data.message,
                    icon: "error"
                  });
            }
        }).catch((err) => {
            console.log(err);
        });
    })
}

let form_guardarExamenComplementarios = document.getElementById('form_examenCatCompl');
if(form_guardarExamenComplementarios){
    form_guardarExamenComplementarios.addEventListener('submit', (e)=>{
        e.preventDefault();

        let formData = new FormData(form_guardarExamenComplementarios);
        
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
        axios.post(route('examenComplementarios.save'),formData)
        .then((result) => {
            if(result.data.status === "success"){
                $("#modal_new_examen_complementarios").modal('hide');
                Swal.fire({
                    title: "Éxito",
                    text: result.data.message,
                    icon: "success"
                  });
                
                  let urlDataComplementarias = window.location.origin + "/examenes_comp";
                  dataTable("examenes_comple", urlDataComplementarias);
                  
                  form_guardarExamenComplementarios.reset();
            }else if(result.data.status === "exists"){
                Toast.fire({
                    icon: "warning",
                    title: result.data.message
                  });
            }else{
                Swal.fire({
                    title: "Error",
                    text: result.data.message,
                    icon: "error"
                  });
            }
        }).catch((err) => {
            console.log(err);
        });
    })
}
})

function list_examenes(){
    axios.post(route('examen.getExamenes'))
    .then((result) => {
        let rows_examenes_html = document.getElementById('rows_examenes_ordenP');
        rows_examenes_html.innerHTML = ``;
        if(result.data.length > 0){
            let data = result.data;
            array_data_examenes = data;

            data.forEach(element => {
                let id_element = btoa(element.categoria);
                let idCollapse = btoa(element.categoria + "-exams");
                let idAccordionBody = btoa(element.categoria + "-list");
                let examenes = element.examenes;

                let cardItem = document.createRange().createContextualFragment(/*html*/`
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card-body p-1 m-1" style="border: 1px solid #f6f9ff;box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5)">
                        <!-- Accordion without outline borders -->
                        <div class="accordion accordion-flush" id="${id_element}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed btn-sm pb-2 px-0 pt-0"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#${idCollapse}" aria-expanded="true"
                                        aria-controls="${idCollapse}">
                                        <strong style="font-size: 13px" data-cat="${element.categoria}">${element.categoria}</strong>
                                    </button>
                                </h2>
                                <div id="${idCollapse}" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#${id_element}">
                                    <div class="accordion-body" id=${idAccordionBody}>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `);
                let contentCard = cardItem.getElementById(idAccordionBody);
                examenes.forEach((examen)=>{
                    let rows_examen = document.createRange().createContextualFragment(/*html*/`
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item pb-1 p-0" style="border-bottom: 0.5px solid #cdd2ec7d;">
                                <label data-exam="${examen.examen}" for="${examen.examen_id}" style="font-size: 13px">${examen.examen}</label>
                            </li>
                        </ul>
                    `);
                    contentCard.appendChild(rows_examen);
                })
                rows_examenes_html.appendChild(cardItem);
            });
        }else{
            rows_examenes_html.innerHTML = `<div class="col-sm-12 mt-2"><div class="alert alert-light p-1" role="alert">No se encontraron exámenes para mostrar.</div></div>`;
        }
    }).catch((err) => {
        console.log(err);
    });
}


function filtrarCatOrExamen(value){
    if(array_data_examenes.length > 0){
        let lists_examenes = document.querySelectorAll(".accordion .list-group-item");
        lists_examenes.forEach((list)=>{
            let labelTextExamen = list.querySelector('label[data-exam]').textContent;

            let regex = new RegExp(`${value}`, 'i');

            let itemCat = list.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
            if(regex.test(labelTextExamen)){
                itemCat.style.display = 'block';
                list.parentElement.classList.add('d-block');
                list.parentElement.classList.remove('d-none');
            }else{
                //validacion para ocultar toda la categoria;
                if(list.parentElement.parentElement.querySelectorAll('.d-block').length === 0){
                    itemCat.style.display = 'none';
                }
                list.parentElement.classList.remove('d-block');
                list.parentElement.classList.add('d-none');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', (e)=>{
    try{
        //Formulario para nuevo categoria
        let form_cat_examen = document.getElementById('form_cat_examen');
        let btn_add_cat = document.querySelector('.btn-add-cat');
        if(btn_add_cat){
            btn_add_cat.addEventListener('click', (e)=>{
                e.stopPropagation();
                $("#modal_new_categorias").modal('show');
            })
        }
        if(form_cat_examen){
            form_cat_examen.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formData = new FormData(form_cat_examen);
                
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

                axios.post(route('cat.examen.save'),formData,{
                    headers:{
                        'Content-Type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                })
                .then((result) => {
                    if(result.data.status === "success"){
                        $("#modal_new_categorias").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                          });
                          list_examenes();
                          let categoria_select = $("#categoria_id").selectize()[0].selectize;
                        categoria_select.addOption({
                            value: result.data.results.id,
                            text: result.data.results.nombre
                        });
                        categoria_select.addItem(result.data.results.id);
                        form_cat_examen.reset();
                    }else if(result.data.status === "exists"){
                        Toast.fire({
                            icon: "warning",
                            title: result.data.message
                          });
                    }else{
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                          });
                    }
                }).catch((err) => {
                    console.log(err);
                });
            })
        }
    }catch(err){

    }
})



/* 
* CODE PARA PERFILES DE EXAMENES
* Version:: 1.0.0
*/

function selectedExamenPerfil(element){
    let categoria_id = element.dataset.cat_id;
    let examen_id = element.dataset.examen_id;

    let categoria = element.dataset.categoria;
    let examen = element.dataset.examen;
    
    if(element.checked){
        data_examenes_perfil.push({
            categoria_id: categoria_id,
            examen_id: examen_id,
            categoria: categoria,
            examen: examen
        });
    }else{
        let index_examen = data_examenes_perfil.findIndex((item) => item.examen_id === examen_id && item.categoria_id === categoria_id);
        if(index_examen !== -1){
            data_examenes_perfil.splice(index_examen,1);
        }
    }
    list_items_examen_perfil();
}

function list_items_examen_perfil(){
    let items_examanes_perfil = document.getElementById('items_examanes_perfil');
    items_examanes_perfil.innerHTML = ``;
    if(data_examenes_perfil.length > 0){
        data_examenes_perfil.forEach((examen,index)=>{
            let rows = `
            <tr class="t-tr">
                <td class="t-td" style="width: 5%">${index + 1}</td>
                <td class="t-td" style="width: 30%">${examen.categoria}</td>
                <td class="t-td" style="width: 55%">${examen.examen}</td>
                <td class="t-td" style="width: 10%"><button title="Eliminar examen" onclick="deleteItem(this)" data-index="${index}" data-categoria_id="${examen.categoria_id}" data-examen_id="${examen.examen_id}" class="btn btn-outline-danger btn-sm" style="border: none"><i class="bi bi-x-circle"></i></button></td>
            </tr>
            `;
            items_examanes_perfil.innerHTML += rows;
        })
    }else{
        items_examanes_perfil.innerHTML = `<tr><td colspan="4">SIN DATOS PARA MOSTRAR</td></tr>`;
    }
}

function deleteItem(element){
    let index = element.dataset.index;
    let categoria_id = element.dataset.categoria_id;
    let examen_id = element.dataset.examen_id;

    let checkAll = document.querySelectorAll('.check_examen_perfil');
    checkAll.forEach((check_input) => {
        let data_cat_id = check_input.getAttribute('data-cat_id');
        let data_examen_id = check_input.getAttribute('data-examen_id');

        if(data_cat_id === categoria_id && data_examen_id === examen_id){
            check_input.checked = false;
            data_examenes_perfil.splice(index,1);
            list_items_examen_perfil();
        }
    })
}

/**
 * save examen perfil
*/
try{
    let form_examen_perfil = document.getElementById("form_examen_perfil");
    if(form_examen_perfil){
        form_examen_perfil.addEventListener('submit', (e)=>{
            e.preventDefault();
            
            let formData = new FormData(form_examen_perfil);
                
            for (let [key, value] of formData.entries()) {
                let input = document.querySelector('input[name="'+key+'"]');
                let labelTextContent = document.querySelector('label[for="'+key+'"]').title;
                if (!value.trim()) {
                    input.classList.add('border-valid');              
                    Toast.fire({
                        icon: "warning",
                        title: `El campo ${labelTextContent} es requerido.` 
                        });
                    return;
                }else{
                    input.classList.remove('border-valid');
                }
            }
            if(data_examenes_perfil.length === 0){
                Swal.fire({
                    title: "Aviso",
                    text: 'Para registrar este perfil, debe agregar por lo menos un examen.',
                    icon: "warning"
                });return;
            }
            formData.append('examenes_perfil',JSON.stringify(data_examenes_perfil));

            axios.post(route('examenes.perfil.save'),formData,{
                headers:{
                    'Content-Type': 'multipart/form-data',
                    'Content-Encoding': 'gzip'
                }
            }).then((result) => {
                console.log(result);
                if(result.data.status === "success"){
                    data_examenes_perfil = [];
                    list_items_examen_perfil();
                    listar_perfiles_orden();
                    $("#modal_nuevo_perfil").modal('hide');
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                      });
                      form_examen_perfil.reset();
                }else if(result.data.status === "exists"){
                    Toast.fire({
                        icon: "warning",
                        title: result.data.message
                      });
                }else{
                    Swal.fire({
                        title: "Error",
                        text: result.data.message,
                        icon: "error"
                      });
                }
            }).catch((err) => {
                console.log(err);
            });
        });
    }
}catch(err){
    console.log(err)
}




var array_perfiles_examenes = [];
function listar_perfiles_orden() {
    axios.post(route('examen.perfil.save'))
    .then((result) => {
        console.log(result.data)
        let rows_perfiles_html = document.getElementById('rows_examenes_ordenP2'); // Donde se mostrará el acordeón de perfiles y exámenes
        rows_perfiles_html.innerHTML = ``;

        if (result.data.length > 0) {
            let data = result.data;
            array_perfiles_examenes = data;

            // Crear acordeón para cada perfil
            data.forEach(element => {
                let id_element = btoa(element.perfil); // Generar ID único codificado
                let idCollapse = btoa(element.perfil + "-exams"); // ID para el collapse
                let idAccordionBody = btoa(element.perfil + "-list"); // ID para el cuerpo del acordeón
                let examenes = element.examenes; // Obtener exámenes del perfil

                // Crear la estructura del acordeón
                let cardItem = document.createRange().createContextualFragment(/*html*/`
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card-body p-1 m-1" style="border: 1px solid #f6f9ff; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5)">
                        <!-- Acordeón sin bordes exteriores -->
                        <div class="accordion accordion-flush" id="${id_element}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed btn-sm pb-2 px-0 pt-0"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#${idCollapse}" aria-expanded="true"
                                        aria-controls="${idCollapse}">
                                        <!-- Icono de edición antes del nombre -->
                                        <i class="bi bi-pencil" style="cursor: pointer; margin-right: 5px; color:#008a26;" onclick="edit_perfil('${id_element}')"></i>
                                        <strong style="font-size: 13px" data-cat="${element.perfil}">
                                            ${element.perfil}
                                        </strong>
                                    </button>
                                </h2>
                                <div id="${idCollapse}" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#${id_element}">
                                    <div class="accordion-body" id="${idAccordionBody}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `);

                let contentCard = cardItem.getElementById(idAccordionBody); // Donde se insertarán los exámenes

                // Agregar cada examen dentro del acordeón del perfil
                examenes.forEach(examen => {
                    let rows_examen = document.createRange().createContextualFragment(/*html*/`
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item pb-1 p-0" style="border-bottom: 0.5px solid #cdd2ec7d;">
                                <label data-exam="${examen.examen}" for="${examen.examen_id}" style="font-size: 13px">
                                    ${examen.examen}
                                </label>
                            </li>
                        </ul>
                    `);
                    contentCard.appendChild(rows_examen); // Añadir exámenes al cuerpo del acordeón
                });

                rows_perfiles_html.appendChild(cardItem); // Añadir cada acordeón al contenedor principal
            });
        } else {
            // Si no hay perfiles, mostrar mensaje
            rows_perfiles_html.innerHTML = `<div class="col-sm-12 mt-2"><div class="alert alert-light p-1" role="alert">No se encontraron perfiles para mostrar.</div></div>`;
        }
    }).catch((err) => {
        console.log(err);
    });
}

/* 
function edit_perfil(id_perfil) {
    edit_perfil();
    data_examenes_perfil = [];
    list_items_examen_perfil2();
    $("#modal_edit_perfil").modal('show');
    console.log("Editar perfil con ID:", id_perfil);

} */

let data_examenes_perfil = []; // Variable para almacenar los exámenes del perfil actual

function edit_perfil(id_perfil) {
    dataTable("dt_cat_edit_examenes", route('examenes.dt-edit'));
    let id = atob(id_perfil);
    console.log(id);

    axios.get(route('perfil.obtener_examenes', { id: id }))
        .then((response) => {
            if (response.data.status === 'success') {
                console.log(response.data);
                data_examenes_perfil = response.data.examenes; // Obtén los exámenes seleccionados del perfil
                let inpPer = document.getElementById('nombre_perfiledit')
                let idPer = document.getElementById('id_erfil')


                inpPer.value = response.data.perfil.nombre;
                idPer.value = response.data.perfil.id;

                // Llama a la función para listar los exámenes en el modal
                list_items_examen_perfil2(); 

                // Lógica para marcar los checkboxes seleccionados
                marcarExamenesSeleccionados(); 
            }
            $("#modal_edit_perfil").modal('show');
            console.log("Editar perfil con ID:", id_perfil);
        }).catch((err) => {
            console.log(err);
        });
}

// Función para listar los exámenes del perfil en el modal
function list_items_examen_perfil2() {
    let items_examenes_perfil = document.getElementById('items_examenes_perfilEdit');
    // Verifica que el elemento no sea nulo
    if (!items_examenes_perfil) {
        console.error("Elemento con ID 'items_examenes_perfilEdit' no encontrado.");
        return; // Sal de la función si el elemento no se encuentra
    }
    items_examenes_perfil.innerHTML = ``;

    if (data_examenes_perfil.length > 0) {
        data_examenes_perfil.forEach((examen, index) => {
            let rows = `
            <tr class="t-tr">
                <td class="t-td" style="width: 5%">${index + 1}</td>
                <td class="t-td" style="width: 30%">${examen.categoria}</td>
                <td class="t-td" style="width: 55%">${examen.examen}</td>
                <td class="t-td" style="width: 10%">
                    <button title="Eliminar examen" onclick="deleteItem11(this)" data-index="${index}" data-categoria_id="${examen.categoria_id}" data-examen_id="${examen.examen_id}" class="btn btn-outline-danger btn-sm" style="border: none"><i class="bi bi-x-circle"></i></button>
                </td>
            </tr>
            `;
            items_examenes_perfil.innerHTML += rows;
        });
    } else {
        items_examenes_perfil.innerHTML = `<tr><td colspan="4">SIN DATOS PARA MOSTRAR</td></tr>`;
    }
}

// Función para manejar la selección de exámenes en el perfil
function selectedExamenPerfilEdit(element) {
    let categoria_id = element.dataset.cat_id;
    let examen_id = element.dataset.examen_id;
    let categoria = element.dataset.categoria;
    let examen = element.dataset.examen;

    if (element.checked) {
        data_examenes_perfil.push({
            categoria_id: categoria_id,
            examen_id: examen_id,
            categoria: categoria,
            examen: examen
        });
    } else {
        let index_examen = data_examenes_perfil.findIndex((item) => item.examen_id === examen_id && item.categoria_id === categoria_id);
        if (index_examen !== -1) {
            data_examenes_perfil.splice(index_examen, 1);
        }
    }
    list_items_examen_perfil2();
}

// Función para eliminar un examen de la lista
function deleteItem11(element) {
    let index = element.dataset.index;
    let categoria_id = element.dataset.categoria_id;
    let examen_id = element.dataset.examen_id;

    let checkAll = document.querySelectorAll('.check_examen_perfil');
    checkAll.forEach((check_input) => {
        let data_cat_id = check_input.getAttribute('data-cat_id');
        let data_examen_id = check_input.getAttribute('data-examen_id');

        if (data_cat_id === categoria_id && data_examen_id === examen_id) {
            check_input.checked = false;
            data_examenes_perfil.splice(index, 1);
            list_items_examen_perfil2();
        }
    });
}

// Función para marcar los exámenes seleccionados
function marcarExamenesSeleccionados() {
    let checkAll = document.querySelectorAll('.check_examen_perfil');
    checkAll.forEach((check_input) => {
        let data_cat_id = check_input.getAttribute('data-cat_id');
        let data_examen_id = check_input.getAttribute('data-examen_id');

        let examen = data_examenes_perfil.find(item => item.categoria_id === data_cat_id && item.examen_id === data_examen_id);
        if (examen) {
            check_input.checked = true;
        }
    });
}


try {
    let form_edit_examen_perfil = document.getElementById("form_edit_examen_perfil");
    if (form_edit_examen_perfil) {
        form_edit_examen_perfil.addEventListener('submit', (e) => {
            e.preventDefault();

            let formData = new FormData(form_edit_examen_perfil);
            let perfilId = document.getElementById('id_erfil').value;

            // Verificar que hay exámenes seleccionados
            if (data_examenes_perfil.length === 0) {
                Swal.fire({
                    title: "Aviso",
                    text: 'Para actualizar este perfil, debe agregar por lo menos un examen.',
                    icon: "warning"
                });
                return;
            }

            // Añadir los exámenes seleccionados al formData
            formData.append('examenes_perfil', JSON.stringify(data_examenes_perfil));
            formData.append('perfil_id', perfilId); // Asegurarse de enviar el ID del perfil

            // Enviar la petición para actualizar el perfil y los exámenes
            axios.post(route('examenes.perfil.update'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Content-Encoding': 'gzip'
                }
            }).then((result) => {
                if (result.data.status === "success") {
                    data_examenes_perfil = [];
                    list_items_examen_perfil(); // Refrescar la lista de exámenes
                    listar_perfiles_orden(); // Actualizar la lista de perfiles
                    $("#modal_edit_perfil").modal('hide'); // Cerrar el modal
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    form_edit_examen_perfil.reset();
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
            }).catch((err) => {
                console.log(err);
            });
        });
    }
} catch (err) {
    console.log(err);
}
