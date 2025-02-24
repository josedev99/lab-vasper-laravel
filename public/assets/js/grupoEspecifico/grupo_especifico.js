var items_examenes_selected = [];


//agregar nuevo grupo 
       //Agergar nuevo factor riesgo
       let btnAddGrupo = document.querySelector('.btnAddGrupo');
       if (btnAddGrupo) {
           btnAddGrupo.addEventListener('click', (event) => {
               event.preventDefault();
               $("#modal_form_new_grupo").modal('show');
           });
       }

//init add

document.addEventListener('DOMContentLoaded', (event) => {
    try {
        console.log("DOM content loaded"); // Verifica si el DOM se ha cargado
        get_data_perfil(); //obtener los factores riesgos y examenes [Perfil]
        let examenesSelectize = $("#examenesSelectize").selectize()[0].selectize;
        $(document).on('keydown', function(event) {
            if (event.key === 'Enter' && examenesSelectize.isFocused) {
                addNewItemPerfilRiesgo();
                examenesSelectize.clear();
            }
        });
        //agregar items a la lista de perfil
        let formAddItem = document.getElementById('formAddItem');
        if (formAddItem) {
            formAddItem.addEventListener('submit', (event) => {
                event.preventDefault();
                let selectedFactorRiesgo1 = sessionStorage.getItem('selectedFactorRiesgo1');
                let index = array_perfil_riesgo.findIndex(perfil => perfil.factor_riesgo === selectedFactorRiesgo1);
                if (index !== -1) {
                    items_examenes_selected.forEach(item => {
                        console.log("Item seleccionado:", item); // Imprime cada examen que se va a agregar
                        console.log(item); // Aquí imprime cada examen que se va a agregar
                        //validacion para evitar insertar dos examenes iguales
                        let indexExamen = array_perfil_riesgo[index].examenes.findIndex(examen => examen.nombre === item.nombre);
                        if (indexExamen === -1) {
                            array_perfil_riesgo[index].examenes.push({
                                id: item.id,
                                factor_riesgo_id: item.factor_riesgo_id,
                                categoria: item.categoria,
                                nombre: item.nombre,
                                status: item.status
                            });
                        } else {
                            Toast.fire({
                                icon: "warning",
                                title: `El examen ${item.nombre} ya existe en la lista de factor de riesgo.`
                            });
                        }
                    })
                    loadPerfil();
                    items_examenes_selected = [];
                    Toast.fire({
                        icon: "success",
                        title: `Examenes agregado exitosamente.`
                    });
                    $("#modal_new_examen_factor_riesgo").modal('hide');
                }
            })
        }
 
        //procesar formulario de agregado de factor riesgo
        let form_gru_o = document.getElementById('form_gru_o');
        if (form_gru_o) {
            form_gru_o.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(form_gru_o);
                axios.post(route('grupo.save'), formData)
                    .then((result) => {
                        let message = result.data.message;
                        //caso de error
                        if (result.data.status === "success") {
                            Toast.fire({
                                icon: "success",
                                title: `${message}`
                            });
                            $("#modal_form_new_grupo").modal('hide');
                            form_gru_o.reset();
                            //add item factor
                            array_perfil_riesgo.push(result.data.data);
                            loadPerfil();
                        } else if (result.data.status === "errorForm") {
                            Toast.fire({
                                icon: "warning",
                                title: `${message}`
                            });
                        } else if (result.data.status === "warning") {
                            Toast.fire({
                                icon: "warning",
                                title: `${message}`
                            });
                            form_gru_o.reset();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: `${message}`
                            });
                        }
                    }).catch((err) => {
                        console.log(err)
                        Toast.fire({
                            icon: "error",
                            title: `Ha ocurrido un error inesperado, intente nuevamente.`
                        });
                    });
            })
        }
        //procesar guardados de cambios perfil
        let btnSaveChangePerfil = document.getElementById('btnSaveChangePerfil1');
        if (btnSaveChangePerfil) {
            btnSaveChangePerfil.addEventListener('click', (event) => {
                event.stopPropagation();
                guardarDatos();
            })
        }
    } catch (err) {
        console.log(err);
    }
})

function guardarDatos() {
    //validaciones
    if (array_perfil_riesgo.length > 0) {
        let formData = new FormData();
        formData.append('data_perfil1', JSON.stringify(array_perfil_riesgo));
        console.log(array_perfil_riesgo)
        axios.post(route('grupos.save'), formData)
            .then((result) => {
                let message = result.data.message;
                if (result.data.status === 'success') {
                    Toast.fire({
                        icon: "success",
                        title: `${message}`
                    });
                    items_examenes_selected = [];
                    array_perfil_riesgo = [];
                    get_data_perfil();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: `${message}`
                    });
                }
            }).catch((err) => {
                Toast.fire({
                    icon: "error",
                    title: `Ha ocurrido un error inesperado, intente nuevamente.`
                });
                console.log(err);
            });
    }
}

function loadPerfil() {
    let component_body_grupo = document.getElementById('component_body_grupo');
    component_body_grupo.innerHTML = '';

    if (array_perfil_riesgo.length > 0) {
        array_perfil_riesgo.forEach((item, index) => {
            //validaciones
            if (item.removeStatus === false) {
                let rowItem = document.createElement('tr'); // Creamos un elemento tr
                let idElement = 'td-' + index + 1;
                let id_th_icon = 'i-' + index + 1;
                let id_span_factor = 'span-' + index + 1;
                rowItem.innerHTML = `
                    <td class="custom-td" style="width: 30%;text-align: left !important"><span class="custom-badge" id="${id_span_factor}">${item.factor_riesgo}<i class="bi bi-x-circle btn-close-span" data-factor_riesgo_id="${item.factor_riesgo_id}" data-factor_riesgo="${item.factor_riesgo}" id="${id_th_icon}" title="Remover factor riesgo ${item.factor_riesgo}" onclick="deleteItemFactor(this)" style="display:none;background:none !important"></i></span></td>
                    <td class="custom-td" style="width: 60%;text-align: left !important" id="${idElement}"></td>
                    <td class="custom-td" style="width: 10%">
                        <button title="Agregar examenes a un factor riesgo" type="button" data-factor_riesgo_id="${item.factor_riesgo_id}" data-factor_riesgo="${item.factor_riesgo}" onclick="addItemExamen(this)" class="btn btn-outline-info btn-sm" style="padding: 1px 6px;border: none">
                            <i class="bi bi-plus-circle" style="font-size: 18px;"></i>
                        </button>
                    </td>
                `;
                let elementSpanFactor = rowItem.querySelector('#' + id_span_factor);
                elementSpanFactor.onmouseover = (event) => {
                    elementSpanFactor.querySelector(`#${id_th_icon}`).style.display = 'block';
                }
                elementSpanFactor.onmouseout = (event) => {
                    elementSpanFactor.querySelector(`#${id_th_icon}`).style.display = 'none';
                }

                let td = rowItem.querySelector(`#${idElement}`);

                item.examenes.forEach((examen, index) => {
                    //validacion
                    if (examen.status === true) {
                        let span_id = 'span-' + index + 1;
                        let spanElement = document.createElement('span');

                        spanElement.className = "badge bg-success element";
                        spanElement.innerHTML = `
                            <i class="bi bi-x-circle btn-close-span" data-factor_riesgo="${item.factor_riesgo}" data-examen="${examen.nombre}" id="${span_id}" title="Remover examen ${examen.nombre}" onclick="deleteItemExamen(this)" style="display:none"></i>
                            <i class="bi bi-check-circle me-1"></i>${examen.nombre}
                        `;
                        spanElement.onmouseover = (event) => {
                            spanElement.querySelector(`#${span_id}`).style.display = 'block';
                        }
                        spanElement.onmouseout = (event) => {
                            spanElement.querySelector(`#${span_id}`).style.display = 'none';
                        }
                        td.appendChild(spanElement); // Agregamos el span al td
                    }
                });

                component_body_grupo.appendChild(rowItem); // Agregamos el tr al cuerpo de la tabla
            }
        });
    } else {
        component_body_grupo.innerHTML = '<tr><td colspan="3" class="text-center">Sin datos para mostrar.</td></tr>';
    }

}

//Funcion para eliminar items
function deleteItemExamen(element) {
    Swal.fire({
        title: "¿Eliminar examen?",
        text: "Esta acción es irreversible.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            let factor_riesgo = element.dataset.factor_riesgo;
            let examen = element.dataset.examen;

            for (let x = 0; x < array_perfil_riesgo.length; x++) {
                for (let y = 0; y < array_perfil_riesgo[x].examenes.length; y++) {
                    if (factor_riesgo === array_perfil_riesgo[x].factor_riesgo && examen === array_perfil_riesgo[x].examenes[y].nombre) {
                        array_perfil_riesgo[x].examenes[y].status = false;
                    }
                }
            }
            loadPerfil();

            Toast.fire({
                icon: "success",
                title: "El examen ha sido eliminado correctamente."
            });
        }
    });
}
//Funcion agregar item
function addItemExamen(element) {
    loadNewExamenes(); //cargar detalles de examenes
    let factor_riesgo = element.dataset.factor_riesgo;
    let factor_riesgo_id = element.dataset.factor_riesgo_id;

    console.log(factor_riesgo, factor_riesgo_id)

    sessionStorage.setItem('selectedFactorRiesgo1', factor_riesgo); //Se utiliza para agregar nuevos examenes a x factor
    sessionStorage.setItem('factor_riesgo_id', factor_riesgo_id); //se utiliza para agregar nuevos item

    let items_examenes_factor = array_perfil_riesgo.filter((item) => item.factor_riesgo === factor_riesgo);
    let examenes = items_examenes_factor[0].examenes;
    let div = document.getElementById('display_examenes');
    div.innerHTML = '';
    if (examenes.length > 0) {
        examenes.forEach((item) => {
            if (item.status) {
                let td = `
                   <span class="badge element" style="background: #f3f0fd !important">
                       <i class="bi bi-check-circle me-1"></i>${item.nombre}
                   </span>
               `;
                div.innerHTML += td;
            }
        })
    } else {
        div.innerHTML = '<p class="text-center">Sin examenes</p>';
    }

    //
    document.getElementById('display-factor-riesgo').textContent = factor_riesgo;
    $("#modal_new_examen_factor_riesgo").modal('show');
}
//add item array
function selectedItemExamen() {
    addNewItemPerfilRiesgo();
}

function addNewItemPerfilRiesgo() {
    let examen = document.getElementById('examenesSelectize');
    if (examen.value !== "") {
        let dataItem = JSON.parse(atob(examen.value));

        let selectedFactorRiesgo1 = sessionStorage.getItem('selectedFactorRiesgo1');
        let factor_riesgo_id = sessionStorage.getItem('factor_riesgo_id');
        //validar que no existe en la lista de factor de riesgo
        let indexFactorRiesgo = array_perfil_riesgo.findIndex(perfil => perfil.factor_riesgo === selectedFactorRiesgo1);
        if (Array.isArray(dataItem)) {
            dataItem.forEach(examen => {
                examen.factor_riesgo = selectedFactorRiesgo1;
                examen.factor_riesgo_id = parseInt(factor_riesgo_id);
                examen.status = true;

                let index = items_examenes_selected.findIndex((item) => item.nombre === examen.nombre);
                let indexExamen = array_perfil_riesgo[indexFactorRiesgo].examenes.findIndex(item => item.nombre === examen.nombre); //index examen factor riesgo
                if (index === -1 && indexExamen === -1) {
                    items_examenes_selected.push(examen);
                } else {
                    Toast.fire({
                        icon: "warning",
                        title: "Este examen ya existe en la lista."
                    });
                }
            })
        } else {

            dataItem.factor_riesgo = selectedFactorRiesgo1;
            dataItem.factor_riesgo_id = parseInt(factor_riesgo_id);
            dataItem.status = true;

            let indexExamen = array_perfil_riesgo[indexFactorRiesgo].examenes.findIndex(examen => examen.nombre === dataItem.nombre); //index examen factor de riesgo
            let index = items_examenes_selected.findIndex((item) => item.nombre === dataItem.nombre);
            if (index === -1 && indexExamen === -1) {
                items_examenes_selected.push(dataItem);
            } else {
                Toast.fire({
                    icon: "warning",
                    title: "Este examen ya existe en la lista."
                });
            }
        }
    }
    loadNewExamenes();
}

function loadNewExamenes() {
    let rows_examenes_selected = document.getElementById('rows_examenes_selected');
    rows_examenes_selected.innerHTML = '';

    if (items_examenes_selected.length > 0) {
        items_examenes_selected.forEach((item, index) => {
            let rowItem = document.createElement('tr'); // Creamos un elemento tr
            rowItem.innerHTML = `
                <td style="width: 45%;border: 1px solid #ced4da">${item.factor_riesgo}</td>
                <td style="width: 45%;border: 1px solid #ced4da">${item.nombre}</td>
                <td style="width: 10%;border: 1px solid #ced4da"><button type="button" onclick="removeItemExamenSelected(this)" data-index="${index}" class="btn btn-outline-danger btn-sm" style="border: none;"><i class="bi bi-x-circle-fill"></i></button></td>
            `;
            rows_examenes_selected.appendChild(rowItem); // Agregamos el tr al cuerpo de la tabla
        });
    } else {
        rows_examenes_selected.innerHTML = `<tr><td colspan="3" style="width:100%;border: 1px solid #ced4da">SIN INFORMACIÓN PARA MOSTRAR.</td></tr>`;
    }
}
//Funcion para remover examene seleccionado
function removeItemExamenSelected(element) {
    let index = element.dataset.index;
    items_examenes_selected.splice(index, 1);
    loadNewExamenes();
}

function get_data_perfil() {
    console.log("Ejecutando get_data_perfil()"); // Para asegurarte de que la función se está ejecutando
    axios.post(route('grupo.data'))
        .then((result) => {
            // Aquí imprimimos los datos tal como vienen de la base de datos
            console.log("Datos recibidos de la BD:", result.data); // Imprime los datos completos recibidos

            array_perfil_riesgo = result.data;
            console.log("Array perfil riesgo:", array_perfil_riesgo); // Verifica el array donde almacenas los datos

            // Si necesitas ver los elementos específicos dentro del array
            array_perfil_riesgo.forEach((perfil, index) => {
                console.log(`Perfil ${index}:`, perfil); // Imprime cada perfil con su índice
                console.log("Exámenes asociados a este perfil:", perfil.examenes); // Imprime los exámenes asociados
            });

            loadPerfil();
        })
        .catch((err) => {
            console.log("Error al obtener los datos:", err); // Si hay algún error en la solicitud
        });
}


//eliminar item factor
function deleteItemFactor(element) {
    Swal.fire({
        title: "¿Eliminar este factor riesgo?",
        text: "Esta acción es irreversible.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            let factor_riesgo_id = parseInt(element.dataset.factor_riesgo_id);

            let index = array_perfil_riesgo.findIndex(item => item.factor_riesgo_id === factor_riesgo_id);
            if (index !== -1) {
                array_perfil_riesgo[index].removeStatus = true;
                loadPerfil();
            }

            Toast.fire({
                icon: "success",
                title: "El factor riesgo ha sido eliminado correctamente."
            });
        }
    });
}

//new code --- check option examen

function checkOpcionExamen(element) {
    let value = element.value;
    axios.post(route('perfil.examen.obtener'), { optionExamPerfil: value })
        .then((result) => {
            if (result.data.length > 0) {
                let examenesSelectize = $("#examenesSelectize").selectize()[0].selectize;
                let array_examenes = result.data;
                examenesSelectize.clear();
                examenesSelectize.clearOptions();
                array_examenes.forEach((item) => {
                    //validar si es un array obj
                    let value = null;
                    if (Array.isArray(item.examenes)) {
                        value = item.examenes;
                    } else {
                        value = item;
                    }
                    examenesSelectize.addOption({
                        value: btoa(JSON.stringify(value)),
                        text: item.nombre
                    });
                })
                examenesSelectize.refreshOptions();
            }
        }).catch((err) => {
            console.log(err);
        });
}