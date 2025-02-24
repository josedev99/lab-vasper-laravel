var array_data_examenes = [];
var data_examenes_perfil = [];
var obj_info_emp = {};
document.addEventListener('DOMContentLoaded', (e) => {
    $("#categoria_id").selectize();
    try{
        let input_search = document.getElementById('input-search');
        if(input_search){
            input_search.addEventListener('keyup', (e)=>{
                filtrarCatOrExamen(e.target.value.trim());
            });
            input_search.addEventListener('change', (e)=>{
                filtrarCatOrExamen(e.target.value.trim());
            });
        }
        //nuevo examen
        let btn_new_examen = document.querySelector('.btn_new_examen');
        if(btn_new_examen){
            btn_new_examen.addEventListener('click', async (e)=>{
                list_examenes_vista_previa();
                $("#modal_nueva_orden_examen").modal('show');
                //ocultar button para nueva orden
                let btnAddExamenHide = document.querySelector('.btnAddExamen');
                btnAddExamenHide.style.display = 'block';
                document.getElementById('contents_inputs_jornada').style.display = 'none';
                document.querySelector('.btnSaveOrden').style.display = 'none';
            });
        }
        let btnAddExamen = document.querySelector('.btnAddExamen');
        if(btnAddExamen){
            btnAddExamen.addEventListener('click', async (e)=>{
                $("#modal_new_cat_examen").modal('show');
            })
        }
        let form_examen = document.getElementById('form_examen');
        if(form_examen){
            form_examen.addEventListener('submit', (e)=>{
                e.preventDefault();

                let formData = new FormData(form_examen);
                
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
                        $("#modal_new_cat_examen").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                          });
                        list_examenes_vista_previa();
                        $("#categoria_id").selectize()[0].selectize.clear();
                        form_examen.reset();
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
        console.log(err);
    }
})
//Method para renderizar los examenes en la modal
function list_categorias(data){
    document.getElementById('modal_orden_examen').textContent = 'REGISTRAR NUEVA ORDEN DE EXAMENES';
    let list_items_categoria = document.getElementById('list-items-categoria');
    list_items_categoria.innerHTML = ``;
    let categorias = data.map((categoria)=>categoria.categoria);
    if(categorias.length === 0){
        list_items_categoria.innerHTML =`<p class="m-0 p-0 text-danger">No hay categorias.</p>`;return;
    }
    categorias.forEach((categoria,index)=>{
        let list_items = `
            <button type="button" onclick="loadExamenesCat(this)" data-index="button-${index}" data-categoria="${categoria}" class="list-group-item list-group-item-action"><i id="icon-${index}" class="bi bi-check-circle me-1 text-info"></i> ${categoria}</button>
        `;
        list_items_categoria.innerHTML += list_items;
    });
}

function loadExamenesCat(button){
    let list_items_examenes = document.getElementById('list-items-examenes');
    list_items_examenes.innerHTML = '';
    let categoria = button.dataset.categoria;
    setColorIconListItem(button.firstElementChild);
    let data = array_data_examenes.filter(item => item.categoria === categoria);
    if(data.length > 0){
        //display categoria seleccionado
        document.getElementById('display_cat_selected').textContent = categoria + ' - ';
        let rowElement = document.createElement('div');
        rowElement.classList.add('row');
        data[0].examenes.forEach((examen)=> {
            let checkElement = examen.check_examen ? "checked" : "";
            let item_examen = `
                <div class="col-sm-12 col-md-3">
                    <div class="checkbox icheck-turquoise m-0">
                        <input type="checkbox" data-categoria="${examen.categoria}" onclick="selectedExamenCheck(this)" ${checkElement} id="${examen.examen_id}" value="${examen.examen_id}" />
                        <label data-exam="${examen.examen}" for="${examen.examen_id}" style="font-size: 13px">${examen.examen}</label>
                    </div>
                </div>
            `;
            rowElement.innerHTML += item_examen;
        })
        list_items_examenes.appendChild(rowElement);
    }else{
        list_items_examenes.innerHTML = `<p class="m-0 p-0 text-danger">Categoria no seleccionada.</p>`;
    }
}

function setColorIconListItem(icon_color){
    document.querySelectorAll('.list-group-item-action').forEach(item => {
        let icon = item.firstElementChild;
        icon.classList.remove('text-info');
        icon.classList.remove('text-success');
        icon.classList.add('text-info');
    });
    if (icon_color.classList.contains('text-info')) {
        icon_color.classList.remove('text-info');
        icon_color.classList.add('text-success');
    } else {
        icon_color.classList.add('text-info');
        icon_color.classList.remove('text-success');
    }
}

function selectedExamenCheck(element){
    let categoria = element.dataset.categoria;
    let examen_id = element.id;
    let index_cat = array_data_examenes.findIndex((cate) => cate.categoria === categoria);
    if(index_cat !== -1){
        let arrs_examenes = array_data_examenes[index_cat].examenes;
        let index_examen = arrs_examenes.findIndex((examen)=> parseInt(examen.examen_id) === parseInt(examen_id));
        if(index_examen !== -1){
            array_data_examenes[index_cat].examenes[index_examen].check_examen = element.checked;
            displayExamenesSelected();
        }
    }
}

function displayExamenesSelected(){
    let display_examenes = document.getElementById('display_examenes');
    display_examenes.innerHTML = '';
    let examenes_selected = array_data_examenes.flatMap(item => item.examenes.filter(examen => examen.check_examen));    
    if(examenes_selected.length > 0){
        examenes_selected.forEach((item)=>{
            let div = document.createElement('div');
            div.style.display = 'inline-block';
            div.style.position = 'relative';
            div.classList.add('mb-2');
            div.innerHTML = `<span class="badge bg-success mx-1"><i class="bi bi-check-circle me-1"></i> ${item.examen}</span>`;
            let icon_close = document.createElement('i');
            icon_close.classList.add('bi','bi-x','rm-item');
            div.appendChild(icon_close);
            div.onmouseenter = (e)=> {
                icon_close.classList.add('active');
            }
            div.onmouseleave = (e)=> {
                icon_close.classList.remove('active');
            }
            icon_close.onclick = (e)=>{
                deleteExamenOrden(item.categoria,item.examen_id);
            }
            display_examenes.appendChild(div);
        })
    }else{
        display_examenes.textContent = 'NO HAY EXÁMENES SELECCIONADOS.';
    }
}

function deleteExamenOrden(categoria, examen_id){
    let index_cat = array_data_examenes.findIndex((cate) => cate.categoria === categoria);
    if(index_cat !== -1){
        let arrs_examenes = array_data_examenes[index_cat].examenes;
        let index_examen = arrs_examenes.findIndex((examen)=> parseInt(examen.examen_id) === parseInt(examen_id));
        if(index_examen !== -1){
            array_data_examenes[index_cat].examenes[index_examen].check_examen = false;
            displayExamenesSelected();
        }
    }
}

function filtrarCatOrExamen(value){
    if(array_data_examenes.length > 0){
        let list_items_examenes = document.getElementById('list-items-examenes');
        list_items_examenes.innerHTML = `<p class="m-0 p-0 text-danger">Categoria no seleccionada.</p>`;
        let regex = new RegExp(`${value}`, 'i');
        let data = array_data_examenes.filter(item => 
            item.examenes.some(examen => regex.test(examen.examen))
        );
        list_categorias(data);
    }
}

function list_examenes_vista_previa(){
    axios.post(route('examen.getExamenes'))
    .then((result) => {
        let rows_examenes_html = document.getElementById('rows_examenes_orden');
        rows_examenes_html.innerHTML = ``;
        if(result.data.length > 0){
            let data = result.data;
            array_data_examenes = data;//Se utiliza solo para cargar la data y filtrar los items
            //title modal
            document.getElementById('modal_orden_examen').textContent = 'LISTADO EXAMENES EXISTENTES';
            data.forEach(element => {
                let id_element = btoa(element.categoria);
                let idCollapse = btoa(element.categoria + "-exams");
                let idAccordionBody = btoa(element.categoria + "-list");
                let examenes = element.examenes;

                let cardItem = document.createRange().createContextualFragment(/*html*/`
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="card-body p-1 m-1" style="border: 1px solid #f6f9ff;box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1)">
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
                            <li class="list-group-item pb-1 p-0">
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