var items_areas_riesgo_jornadas = [];
var items_factor_riesgo_selected = [];
var calendar_seg_ocupacional = null;
document.addEventListener("DOMContentLoaded", () => {
    getDataJornadaSegOcupacional();
    //procesar actualizar de jornada
    let form_jornada_upd = document.getElementById('form_jornada_upd');
    if(form_jornada_upd){
        form_jornada_upd.addEventListener('submit', (event)=> {
            event.preventDefault();
            let formData = new FormData(form_jornada_upd);
            //validacion
            let jornada_up_nombre = document.getElementById('jornada_up_nombre').value;
            let fecha_up_jornada = document.getElementById('fecha_up_jornada').value;

            if(jornada_up_nombre.trim() === ""){
                Toast.fire({
                    title: "El nombre de la jornada es obligatorio.",
                    icon: "warning"
                });return;
            }

            if(fecha_up_jornada.trim() === ""){
                Toast.fire({
                    title: "La fecha de la jornada es obligatorio.",
                    icon: "warning"
                });return;
            }

            axios.post(route('jornada.update'),formData)
            .then((result) => {
                if(result.data.status === "success"){
                    form_jornada_upd.reset();
                    $("#modal_jornada_editar").modal('hide');
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success",
                    });
                    getDataJornadaSegOcupacional();
                    $("#dt_listado_jornadas").DataTable().ajax.reload(null,false);
                }else if(result.data.status === "exists"){
                    Swal.fire({
                        title: "Aviso",
                        text: result.data.message,
                        icon: "warning",
                    });
                }else{
                    Swal.fire({
                        title: "Error",
                        text: result.data.message,
                        icon: "error",
                    });
                }
            }).catch((err) => {
                console.log(err);
            });
        })
    }
});


function getDataJornadaSegOcupacional() {
    axios
        .post(route("jornada.ocupacional.cantidad"))
        .then((result) => {
            let eventData = result.data;
            let calendarEl = document.getElementById(
                "calendar-jornada-ocupacional"
            );
            calendar_seg_ocupacional = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth",
                locale: "es",
                allDayText: "Todos",
                headerToolbar: {
                    right: "dayGridMonth listWeek today prev next",
                },
                buttonText: {
                    today: "Hoy",
                    month: "Mes", // Cambiar "Month" a "Mes"
                    list: "Lista",
                },
                dateClick: function (info) {
                    let fechaSelected = info.dateStr;

                    let currentDay = moment.tz("America/El_Salvador");

                    //validar que la fecha sea actual o mayor
                    let fecha1 = moment(fechaSelected).format("YYYY-MM-DD");
                    let fecha2 = moment(currentDay).format("YYYY-MM-DD");
                    if (fecha1 >= fecha2) {
                        document
                            .getElementById("form_jornada_ocupacional")
                            .reset(); //reset formulario
                        //seleted fecha de jornada seguridad ocupacional
                        document.getElementById("fecha_jornada").value =
                            fechaSelected;
                        document
                            .querySelector('input[name="nombre_jornada"]')
                            .focus();
                        $("#modal_crear_jornada").modal("show");
                        //getDepartamentosEmp();
                        getExamensForJorn();
                    } else {
                        Swal.fire({
                            title: "Aviso",
                            text: "Fecha no válida.",
                            icon: "warning",
                        });
                    }
                },
                eventClick: function (info) {
                    let eventDate = info.event.start;
                    let formatDate = moment(eventDate).format("YYYY-MM-DD");
                    getListarJornadas(formatDate);
                },
                events: eventData,
                eventColor: "#3788d8",
                eventBorderColor: "transparent",
            });
            calendar_seg_ocupacional.render();
        })
        .catch((err) => {
            console.log(err);
        });
}

//listar las jornadas de seguridad ocupacional por fecha
function getListarJornadas(fecha_selected) {
    dataTable('dt_listado_jornadas',route('jornada.listar'),{fecha_jornada: fecha_selected});
    $("#modal_listar_jornadas").modal("show");

}

function getExamensForJorn() {
    const selectElement = $("#exa_jornadas")[0].selectize;
    selectElement.clear();
    selectElement.clearOptions();
    selectElement.addOption({
        value: "",
        text: "Seleccionar...",
        disabled: true,
    });
    selectElement.addOption({
        value: "examenes_lab_j",
        text: "EXÁMENES DE LABORATORIO CLÍNICO",
    });
    selectElement.addOption({
        value: "OPTOMETRIA",
        text: "SALUD VISUAL (OPTOMETRIA)"
    });
    axios.post(route("cat.exa.jornadas"))
        .then((result) => {
            let items = result.data;
            items.forEach((examen) => {
                selectElement.addOption({
                    value: examen.id,
                    text: examen.nombre,
                });
            });
    }).catch((err) => console.log(err));
}

function initSelectizeJornadasExamenes() {
    let cat_exa_jorn_selectize = $("#exa_jornadas").selectize()[0].selectize;
    cat_exa_jorn_selectize.on("change", function (value) {
        
        let categoriaSelect = cat_exa_jorn_selectize.getValue();
        getExaCategoriaExa(categoriaSelect);

        if(colaboradoresAreaJorn.length > 0){
            colaboradoresAreaJorn = []; //clear change select
            renderResults();
        }
    });
}

$(document).ready(function () {
    initSelectizeJornadasExamenes();
});

function getExaCategoriaExa(categoriaSelect) {
    axios
        .post(route("deptos.for.jorn"), { categoriaSelect })
        .then((result) => {
            let data = result.data;
            let deptos_jornada = document.getElementById("component_body_departamentos");
            deptos_jornada.innerHTML = "";

            if (data.length > 0) {
                data.forEach((item, index) => {
                    let colDiv = document.createElement("div");
                    colDiv.className = "col-sm-12 col-md-3 col-lg-2 col-xl-2 text-center";            
                    colDiv.style.boxShadow = "0px 2px 6px rgba(0, 0, 0, 0.1)"; 
                    colDiv.style.margin = "10px";
                    colDiv.style.padding = "10px"; 
                    colDiv.style.borderRadius = "5px";
                    let iconContainer = document.createElement("div");
                    iconContainer.style.display = "inline-flex";
                    iconContainer.style.gap = "10px";           
                    iconContainer.innerHTML = `                
                        <i class="bi bi-plus-circle" data-iddp="${item.iddp}" onclick="selectDeptoJornada(this)" style="font-size: 20px; cursor: pointer"></i>
                    `;            

                    colDiv.appendChild(iconContainer);
                    colDiv.innerHTML += `<p class="mb-0" style="margin-top: 5px;font-size: 13px">${item.departamento}</p>`;            
                    
                    deptos_jornada.appendChild(colDiv);
                });
            }
        })
        .catch((err) => console.log(err));
}

function selectDeptoJornada(checkbox) {
    let id_depto = checkbox.getAttribute("data-iddp");//id depto

    //validar tipo de jornada
    let whiteListRegex = /optometría|visual|jornada visual|salud visual \(optometría\)/i;//expresion

    let cat_exa_jorn_selectize = $("#exa_jornadas").selectize()[0].selectize;
    let examen_id = cat_exa_jorn_selectize.getValue();

    let jornada_textContent = cat_exa_jorn_selectize.getItem(examen_id)[0].textContent;

    if(whiteListRegex.test(jornada_textContent)){
        let inputOptions = document.querySelectorAll('input[name="checkAgendar"]');
        inputOptions.forEach(input => input.checked = false);
        //guardar id depto para obtener despues los areas
        sessionStorage.setItem('jornada_examen_id',examen_id);
        sessionStorage.setItem('id_depto',id_depto);
        $("#modal-optometria-jornada").modal('show');
    }else if(examen_id === "examenes_lab_j"){
        document.getElementById('select-all-areas-j').checked = false;
        getListAreasJorn(id_depto);
    }else{
        Toast.fire({
            title: "No hay información para mostrar.",
            icon: "warning"
        });
    }
}

function getListAreasJorn(id_depto){
    axios.post(route("get.data.jornada"), { id_depto })
        .then((result) => {
            let data = result.data.result_areas;
            let modalAreasjorn = new bootstrap.Modal("#modal-areas-jornadas");
            modalAreasjorn.show();
            
            //LISTAR AREAS
            let areas_jornada = document.getElementById("area_for_jorn");
            areas_jornada.innerHTML = "";
            if (data.length > 0) {

                data.forEach((item, index) => {
                    let rowItem = document.createElement("tr");
                    rowItem.innerHTML = `
                    <td style="width: 15%; border: 1px solid #ced4da;">
                    <input type='checkbox'  onclick='addAreaJornada(this)' class="item_area_jornadas"
                    value='${item.id}' data-idpt="${item.idp}" data-areaname="${item.nombre}">
                    </td>
                    <td style="width: 85%; border: 1px solid #ced4da;">${item.nombre}</td>
                    `;
                    areas_jornada.appendChild(rowItem);
                });
            }

        }).catch((err) => console.log(err));
}
let colaboradoresAreaJorn = [];

async function addAllAreaJornada(elem) {
    let checkboxes = document.getElementsByClassName("item_area_jornadas");
    if (elem.checked) {
        try {                  
          
            for (let i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                    let idpto = checkboxes[i].dataset.idpt;
                    let area = checkboxes[i].dataset.areaname;
                    await getColaboradoresExaAreasJornadas(checkboxes[i].value,idpto,area);
            }   
          
            renderResults()
        } catch (error) {
            console.error(error);
        }
    } else {
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
        }
        colaboradoresAreaJorn = [];
        renderResults();
    }
    $("#modal-areas-jornadas").modal('hide');
}

async function getColaboradoresExaAreasJornadas(id_area,idpto,area) {
    try {
        
        const response = await axios.post(route("colaboradores.area.examenes"), { id_area,idpto,area});
        const data = response.data;
        //validacion para data duplicada
        if (data.length > 0) {            
            colaboradoresAreaJorn = [...colaboradoresAreaJorn, ...data];
            const uniqueElementArray = colaboradoresAreaJorn.filter((item, index, self) =>
                index === self.findIndex((t) => (
                    parseInt(t.id_area) === parseInt(item.id_area) && parseInt(t.empleado_id) === parseInt(item.empleado_id)
                ))
            );
            colaboradoresAreaJorn = uniqueElementArray;
        }
        renderResults(); //se agreago para solucionar bug en check por area, si no se llama la funcion no muestra el area checkeada
    } catch (error) {
        console.error(`Error al obtener datos del área ${id_area}:`, error);
        throw error; 
    }
}

function addAreaJornada(elem){
    let idpto = elem.dataset.idpt;
    let area = elem.dataset.areaname;
    if(elem.checked){
        getColaboradoresExaAreasJornadas(elem.value,idpto,area);
    }else{
        let data = colaboradoresAreaJorn.filter(item => parseInt(item.id_area) !== parseInt(elem.value));
        colaboradoresAreaJorn = data;
        renderResults();
    }
}

function renderResults(){
    const areasUnicas = [...new Set(colaboradoresAreaJorn.map(col => col.area))];
    document.getElementById('total_colaboradores').innerHTML = `<i class="bi bi-people-fill"></i> ${colaboradoresAreaJorn.length} COLABORADORES`;
    let tabla = document.createElement('table');
    tabla.className = 'table table-bordered';
    tabla.style.fontSize = '13px';
    
    // Crear encabezado
    let thead = tabla.createTHead();
    let headerRow = thead.insertRow();
    ['Área', 'Empleado', 'Exámenes'].forEach(text => {
        let th = document.createElement('th');
        th.textContent = text;
        headerRow.appendChild(th);
    });
    
    let tbody = tabla.createTBody();
    if(areasUnicas.length > 0){
        areasUnicas.forEach(area => { 
            const colaboradoresArea = colaboradoresAreaJorn.filter(col => col.area === area);  
         
            colaboradoresArea.forEach((colaborador, index) => {
                let row = tbody.insertRow();          
              
                let cellArea = row.insertCell();
                if (index === 0) {
                    cellArea.innerHTML = `<span class="d-flex justify-content-center align-items-center" id="span-${index}" style="cursor:pointer"> ${area.toUpperCase()} (${colaboradoresArea.length}) <i id="icon-${index}" title="Eliminar ${area.toUpperCase()}" data-id_area="${colaborador.id_area}" data-idpto="${colaborador.idpto}" data-cargo="${colaborador.cargo}" onclick="removeItemArea(this)" class="bi bi-x-circle-fill text-danger mx-2" style="font-size: 20px; cursor: pointer;display:none"></i></span>`;
                    cellArea.rowSpan = colaboradoresArea.length;
                    cellArea.className = 'align-middle';
    
                    //funcionalidad para hover
                    let spanArea = cellArea.querySelector(`#span-${index}`);
                    let iconDelete = cellArea.querySelector(`#icon-${index}`);
                    spanArea.addEventListener("mouseover", function(event) {
                        iconDelete.style.display = 'block';
                    });
                    spanArea.addEventListener("mouseleave", function(event) {
                        iconDelete.style.display = 'none';
                    });
                } else {
                    cellArea.remove();
                }
                
                let cellEmpleado = row.insertCell();
                cellEmpleado.textContent = colaborador.empleado;            
                // Celda de exámenes
                let cellExamenes = row.insertCell();
                cellExamenes.textContent = colaborador.examenes.map(examen => examen.examen_nombre).join(', ');
            });
        });
    }else{
        let row = tbody.insertRow();
        let cellSinInfo = row.insertCell();
        cellSinInfo.textContent = 'No hay información para mostrar.';
        cellSinInfo.colSpan = 3;
        cellSinInfo.classList.add('text-center');
    }
    const contenedor = document.getElementById('contenedor-tabla');  
    contenedor.innerHTML = ""; 
    contenedor.appendChild(tabla);
    /* Swal.fire({
        title: "Éxito",
        text: `Se han agregado <b>${totalColaboradores}</b> colaboradores y <b>${totalExamenes} examenes</b>` ,
        icon: "success"
    }); */
}


let formjorn = document.getElementById('form_jornada_ocupacional');

if (formjorn) {
    formjorn.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let formData = new FormData(formjorn);             
        formData.append('detdeptos', JSON.stringify(colaboradoresAreaJorn));
        axios.post(route("jornada.reg"), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then((result) => {
            let data = result.data;
            if(data.status === "success"){
                $("#modal_crear_jornada").modal('hide');
                document.getElementById("component_body_departamentos").innerHTML = ``;

                getDataJornadaSegOcupacional();
                colaboradoresAreaJorn = [];
                renderResults();
                Swal.fire({
                    title: "Éxito",
                    text: data.message,
                    icon: "success",
                });
            }else if(data.status === "warning"){
                Swal.fire({
                    title: "Aviso",
                    text: data.message,
                    icon: "warning",
                });
            }else{
                Swal.fire({
                    title: "Error",
                    text: data.message,
                    icon: "error",
                });
            }
        })
        .catch((err) => {
            console.log(err);

        });
    });
}

//nuevas funcionalidades
function showDetails(element){
    let jornada_id = element.dataset.jornada_id;
    let jornada = element.dataset.nombre;
    //set title modal detalle jornada
    document.getElementById('display_nombre_jornada').textContent = jornada;

    dataTable('dt_detalle_jornada',route('jornada.listar.detalle'),{jornada_id});

    $("#modal_detalle_jornada").modal('show');
}

//editar
function editJornada(element){
    let jornada_id = element.dataset.jornada_id;
    let jornada = element.dataset.nombre;

    axios.post(route('jornada.obtener'),{jornada_id})
    .then((result) => {
        if(result.data){
            document.getElementById('jornada_up_nombre').value = result.data.nombre;
            document.getElementById('fecha_up_jornada').value = result.data.fecha_jornada;
        }else{
            document.getElementById('jornada_up_nombre').value = '';
            document.getElementById('fecha_up_jornada').value = '';
        }
        $("#modal_jornada_editar").modal('show');
    }).catch((err) => {
        console.log(err)
    });

}

//method para remover items area
function removeItemArea(element){
    let id_area = element.dataset.id_area;
    //let idpto = element.dataset.idpto;
    //let cargo = element.dataset.cargo;
    Swal.fire({
        title: "¿Estás seguro de eliminar esta área?",
        text: "Esta acción eliminará área seleccionada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            let dataDeptoAreaInclude = colaboradoresAreaJorn.filter(item => parseInt(item.id_area) != parseInt(id_area));
            colaboradoresAreaJorn = dataDeptoAreaInclude;
            renderResults();
        }
      });
}

function closeModalArea(element){
    $("#modal-areas-jornadas").modal('hide');
}
//code jornada visual
document.addEventListener('DOMContentLoaded', ()=> {
    let btnAddJorVisual = document.getElementById('btnAddJorVisual');
    if(btnAddJorVisual){
        btnAddJorVisual.addEventListener('click',(event) => {
            event.stopPropagation();
            let optionICheck = document.querySelector('input[name="checkAgendar"]:checked');
            if(optionICheck !== null){
                let value = optionICheck.value;
                let examen_id = sessionStorage.getItem('jornada_examen_id');
                let id_depto = sessionStorage.getItem('id_depto');

                getEmpByRiesgoOrDepto(value,examen_id,id_depto);
                $("#modal-optometria-jornada").modal('hide');
            }else{
                Swal.fire({
                    title: "Aviso",
                    text: 'Seleccione una opción.',
                    icon: "warning",
                });
            }
        })
    }
})

function getEmpByRiesgoOrDepto(option,examen_id,depto_id){
    if(option !== ""){
        axios.post(route('colaboradores.examenes.opcion'),{option,examen_id,depto_id})
        .then((result) => {
            let data = result.data;
            if(data.length > 0){
                colaboradoresAreaJorn = data;
                renderResults();
            }else{
                Toast.fire({
                    title: "No hay información.",
                    icon: "warning"
                });
            }
        }).catch((err) => {
            console.log(err);
        });
    }else{
        console.log('error datos alterado manualmente.')
    }
}