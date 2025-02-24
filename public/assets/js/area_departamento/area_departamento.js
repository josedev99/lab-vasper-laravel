//area.depto.save
var items_areas_deptos = [];
var items_factor_riesgo_selected = [];
var nivel = 0;
var array_riesgos = [];
document.addEventListener('DOMContentLoaded', () => {
    try {
        //obtener datos
        getDepartamentosEmp();

        let btnNewDepto = document.getElementById('btnNewDepto');
        if (btnNewDepto) {
            btnNewDepto.addEventListener('click', (e) => {
                e.stopPropagation();
                $("#modal_new_departamento_crear").modal('show');
            })
        }

        let factor_riesgo_selectize = $("#select_factor_riesgo").selectize()[0].selectize;
        $(document).on('keydown', function (event) {
            if (event.key === 'Enter' && factor_riesgo_selectize.isFocused) {
                addItemRiesgo();
                factor_riesgo_selectize.clear();
            }
        });

        let form_data_detop_area = document.getElementById('form_data_detop_area');
        if (form_data_detop_area) {
            form_data_detop_area.addEventListener('submit', async (e) => {
                e.preventDefault();
                let formData = new FormData(form_data_detop_area);
                for (let [key, value] of formData.entries()) {
                    let labelTextContent = document.querySelector('label[for="' + key + '"]').textContent;
                    if (!value.trim()) {
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${labelTextContent} es requerido.`
                        });
                        return;
                    }
                }
                let response = await axios.post(route('area.depto.save'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response.data.status === "success") {
                    Toast.fire({
                        icon: "success",
                        title: response.data.message
                    });

                    $("#modal_new_departamento_crear").modal('hide');
                    form_data_detop_area.reset();
                    getDepartamentosEmp();
                } else if (response.data.status === "warning") {
                    Toast.fire({
                        icon: "warning",
                        title: response.data.message
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: response.data.message
                    });
                }
            })
        }
    } catch (err) {
        console.log(err);
    }
});

function showIconDeleteDepto(element) {
    let index = element.dataset.index;
    let tag_i = element.querySelector(`#i-${atob(index)}`);
    //validar para mostrar
    if (tag_i.classList.contains('d-none')) {
        tag_i.classList.remove('d-none');
        tag_i.classList.add('d-inline');
    } else {
        tag_i.classList.remove('d-inline');
        tag_i.classList.add('d-none');
    }
}

function showIconDeleteRiesgo(element) {
    let index = element.dataset.index;
    let tag_i = element.querySelector(`#${index}`);
    //validar para mostrar
    if (tag_i.classList.contains('d-none')) {
        tag_i.classList.remove('d-none');
        tag_i.classList.add('d-inline');
    } else {
        tag_i.classList.remove('d-inline');
        tag_i.classList.add('d-none');
    }
}

//eliminar departamento
function deleteDeptoItem(element) {
    let id = element.dataset.id;
    Swal.fire({
        title: "Eliminar departamento?",
        text: "Esta acción es irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('area.depto.remove'), { id: id }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((result) => {
                    if (result.data.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: result.data.message,
                        });
                        items_areas_deptos = [];
                        getDepartamentosEmp();
                    } else if (result.data.status === "no-delete") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Aviso',
                            text: result.data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.data.message,
                        });
                    }
                }).catch((err) => {
                    console.log(err)
                });
        }
    });
}
//obtener departamentos relacionados con riesgos
function getDepartamentosEmp() {
    axios.post(route("area.depto.obtener"))
        .then(result => {
            let items = result.data;
            items_areas_deptos = items;
            loadDepartamentos();
        }).catch(err => console.log(err))
}

//Seleccionar el area/ departamento
//funcion para seleccionar una area
function selectedItem(element) {

    document.getElementById("component_select_cargo").style.display = "none";
    $("#select_cargos").selectize()[0].selectize.clear();
    $("#select_cargos").selectize()[0].selectize.clearOptions();
    let inputs = document.querySelectorAll('input[name="optionCategoria"]');
    inputs.forEach(input => input.checked = false);

    let id = element.dataset.id;
    //display departamento
    let objDepto = items_areas_deptos.find(item => parseInt(item.id) === parseInt(id));
    document.getElementById('display_depto').textContent = objDepto.nombre;

    sessionStorage.setItem('area_emp_id', id);
    items_factor_riesgo_selected = [];
    showItemsFactoRiesgo();
    //save session key area_depto
    sessionStorage.setItem('area_emp_id', id);

    axios.post(route('factor.detalle.obtener'), { id: id })
        .then(result => {
            if (result.data.status === "success") {
                nivel = parseInt(result.data.jerarquia);
                let data_riesgos = result.data.factores_riesgos;
                if (data_riesgos.length > 0) {
                    let factor_riesgo = $("#select_factor_riesgo").selectize()[0].selectize;
                    factor_riesgo.clearOptions();
                    data_riesgos.forEach(item => {
                        factor_riesgo.clear();
                        let value = btoa(JSON.stringify(item.detalle));
                        factor_riesgo.addOption({
                            value: value,
                            text: item.factor_riesgo
                        });
                    });
                }
            } else {

            }
        })
        .catch(err => console.log(err))

    $("#modal_selected_factor_riesgo").modal('show');
}

function showItemsFactoRiesgo() {
    let rows_factor_riesgo = document.getElementById('rows_factor_riesgo');
    rows_factor_riesgo.innerHTML = '';

    if (items_factor_riesgo_selected.length > 0) {
        items_factor_riesgo_selected.forEach((item, index) => {
            let rowItem = document.createElement('tr'); // Creamos un elemento tr
            rowItem.innerHTML = `
                <td style="width: 45%;border: 1px solid #ced4da">${item.departamento}</td>
                <td style="width: 45%;border: 1px solid #ced4da">${item.factor_riesgo}</td>
                <td style="width: 10%;border: 1px solid #ced4da"><button type="button" onclick="rmFactorRiesgo(this)" data-index="${index}" class="btn btn-outline-danger btn-sm" style="border: none;"><i class="bi bi-x-circle-fill"></i></button></td>
            `;
            rows_factor_riesgo.appendChild(rowItem); // Agregamos el tr al cuerpo de la tabla
        });
    } else {
        rows_factor_riesgo.innerHTML = `<tr><td colspan="3" style="width:100%;border: 1px solid #ced4da">SIN INFORMACIÓN PARA MOSTRAR.</td></tr>`;
    }
}

function rmFactorRiesgo(element) {
    let index = element.dataset.index;
    items_factor_riesgo_selected.splice(index, 1);
    showItemsFactoRiesgo();
}

function addItemsFactorDepartamento() {
    //obtener id de departamento
    let array_items_depto_riesgos = [];
    let optionCategoria = document.querySelector('input[name="optionCategoria"]:checked');
    //validacion de categoria
   
    if (optionCategoria === null) {
        Toast.fire({
            icon: "error",
            title: 'Seleccionar una categoria a ingresar.'
        });
        return;
    }

    let area_depto_id = sessionStorage.getItem("area_emp_id");
    let formData = new FormData();
    formData.append('items_riesgos_examenes', JSON.stringify(items_factor_riesgo_selected));

    axios.post(route('area.depto.riesgo.save'), formData)
        .then((result) => {
            console.log(result);
            if (result.data.status === "success") {
                Toast.fire({
                    icon: "success",
                    title: result.data.message
                });
                getDepartamentosEmp();
                $("#modal_selected_factor_riesgo").modal("hide");
            } else {
                Toast.fire({
                    icon: "error",
                    title: result.data.message
                });
            }
        }).catch((err) => {
            console.log(err);
        });
}

function showItemsFactoRiesgo() {
    let rows_factor_riesgo = document.getElementById('rows_factor_riesgo');
    rows_factor_riesgo.innerHTML = '';

    if (items_factor_riesgo_selected.length > 0) {
        items_factor_riesgo_selected.forEach((item, index) => {
            let rowItem = document.createElement('tr'); // Creamos un elemento tr
            rowItem.innerHTML = `
                <td style="width: 45%;border: 1px solid #ced4da">${item.departamento}</td>
                <td style="width: 45%;border: 1px solid #ced4da">${item.factor_riesgo}</td>
                <td style="width: 10%;border: 1px solid #ced4da"><button type="button" onclick="rmFactorRiesgo(this)" data-index="${index}" class="btn btn-outline-danger btn-sm" style="border: none;"><i class="bi bi-x-circle-fill"></i></button></td>
            `;
            rows_factor_riesgo.appendChild(rowItem); // Agregamos el tr al cuerpo de la tabla
        });
    } else {
        rows_factor_riesgo.innerHTML = `<tr><td colspan="3" style="width:100%;border: 1px solid #ced4da">SIN INFORMACIÓN PARA MOSTRAR.</td></tr>`;
    }
}

function getArrayCargos(data) {
    let cargos = [];
    data.areas.forEach(area => {
        cargos = cargos.concat(area.cargos);
    });
    return cargos;
}
function getArrayFactorRiesgos(data) {
    let factor_riesgo = [];
    data.areas.forEach(area => {
        factor_riesgo = factor_riesgo.concat(area.riesgos);
    });
    return factor_riesgo;
}

function addItemRiesgo() {
    let factor_riesgo = document.getElementById("select_factor_riesgo");
    if (factor_riesgo.value !== "") {
        let categoria = document.querySelector('input[name="optionCategoria"]:checked')
        if (categoria === null) {
            Toast.fire({
                icon: "warning",
                title: 'Seleccione una categoria para continuar.'
            });
            return;
        }
        let area_depto_id = sessionStorage.getItem("area_emp_id");

        let array_riesgo_examenes = JSON.parse(atob(factor_riesgo.value));
        //push items riesgos y examenes

        let index = items_areas_deptos.findIndex(item => parseInt(item.id) === parseInt(area_depto_id));
        if (index !== -1) {
            //validacion segun categoria jerarquia empresa
            let selectedRiesgo = array_riesgo_examenes[0].nombre;
            let riesgo_id = array_riesgo_examenes[0].riesgo_id;
            //validar si ya existe en el array principal
            let departamento = "";
            if (categoria.value === "departamentos") {
                //validar segun categoria jerarquia
                let indexDepto = items_areas_deptos.findIndex(depto => parseInt(depto.id) === parseInt(area_depto_id));
                let data = items_areas_deptos[indexDepto];
                let array_cargos = getArrayCargos(data);
                let array_riesgos = getArrayFactorRiesgos(data);

                let exists_counter = 0;
                for (let x = 0; x < array_cargos.length; x++) {
                    let indexExists = array_riesgos.findIndex(item => parseInt(item.cargo_id) === parseInt(array_cargos[x].id) && parseInt(item.factor_riesgo_id) === parseInt(riesgo_id));
                    if (indexExists !== -1) {
                        exists_counter++;
                    }
                }
                //validacion para alerta
                if (exists_counter === array_cargos.length) {
                    Toast.fire({
                        icon: "warning",
                        title: 'El factor de riesgo ya existe en todos los cargos.'
                    }); return;
                }

                departamento = items_areas_deptos[index].nombre;
            } else if (categoria.value === "areas") {
                //validacion para evitar errores
                let select_area = document.getElementById('select_cargos').value;
                if (select_area.value === "") {
                    Toast.fire({
                        icon: "warning",
                        title: 'Por favor, seleccione una area.'
                    });
                    return;
                }
                let objCargo = JSON.parse(atob(select_area));
                //validaciones
                let exists_counter = 0;
                for (let x = 0; x < objCargo.cargos.length; x++) {
                    let indexExists = objCargo.riesgos.findIndex(item => parseInt(item.cargo_id) === parseInt(objCargo.cargos[x].id) && parseInt(item.factor_riesgo_id) === parseInt(riesgo_id));
                    if (indexExists !== -1) {
                        exists_counter++;
                    }
                }
                //validacion para alerta
                if (exists_counter === objCargo.cargos.length) {
                    Toast.fire({
                        icon: "warning",
                        title: 'El factor de riesgo ya existe en todos los cargos.'
                    }); return;
                }
                //set cargo_id
                area_depto_id = objCargo.id;
                //set nombre cargo
                departamento = objCargo.nombre;
            } else if ((categoria.value === "cargos")) {
                //validacion para select cargo
                let select_cargo = document.getElementById('select_cargos').value;
                if (select_cargo === "") {
                    Toast.fire({
                        icon: "warning",
                        title: 'Por favor, seleccione un cargo.'
                    });
                    return;
                }

                let objCargo = JSON.parse(atob(select_cargo));
                //set cargo_id
                area_depto_id = objCargo.id;
                //set nombre cargo
                departamento = objCargo.nombre;
                //validar si ya existe en cargo
                let indexCargo = array_riesgos.findIndex(riesgo => parseInt(riesgo.factor_riesgo_id) === parseInt(array_riesgo_examenes[0].riesgo_id) && riesgo.nombre === array_riesgo_examenes[0].nombre);

                if (indexCargo !== -1) {
                    Toast.fire({
                        icon: "warning",
                        title: 'El factor riesgo ya esta asignado al cargo.'
                    }); return;
                }
            }
            //validar que no existe ese factor riesgo en array
            let indexRiesgo = items_factor_riesgo_selected.findIndex(item => item.factor_riesgo === selectedRiesgo);
            if (indexRiesgo === -1) {
                //agregar items a array
                items_factor_riesgo_selected.push({
                    area_depto_id: area_depto_id,
                    departamento: departamento,
                    factor_riesgo: selectedRiesgo,
                    categoria: categoria.value,
                    riesgo_examenes: array_riesgo_examenes
                });
                showItemsFactoRiesgo();
            } else {
                Toast.fire({
                    icon: "warning",
                    title: 'El factor riesgo ya existe en la lista.'
                });
            }
        }
    }
}

function removeItemRiesgo(element) {
    let cargo_id = element.dataset.area_depto_id;
    let factor_riesgo_id = element.dataset.factor_riesgo_id;
    let categoria = element.dataset.categoria;

    Swal.fire({
        title: "Eliminar factor de riesgo?",
        text: "Esta acción es irreversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('area.depto.riesgos.remove'), {
                cargo_id: cargo_id,
                factor_riesgo_id: factor_riesgo_id,
                categoria: categoria
            }).then((result) => {
                if (result.data.status === 'success') {
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    getDepartamentosEmp();
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
    });

}

function checkJerarquia(element) {
    let value = element.value;
    let area_id = sessionStorage.getItem('area_emp_id');
    let component_select_cargo = document.getElementById('component_select_cargo');
    //validaciones
    let indexArea = items_areas_deptos.findIndex(area => parseInt(area.id) === parseInt(area_id));

    if (value === 'departamentos') {
        component_select_cargo.style.display = 'none';
    } else if (value === 'areas') {
        document.getElementById('d-label-select-nivel').textContent = 'Areas: ';
        component_select_cargo.style.display = 'block';
        if (indexArea !== -1) {
            loadSelectCargos(items_areas_deptos[indexArea].areas);
        }
        component_select_cargo.style.display = 'block';
    } else if (value === 'cargos') {
        document.getElementById('d-label-select-nivel').textContent = 'Cargos: ';
        if (indexArea !== -1) {
            let data = items_areas_deptos.filter(depto => parseInt(depto.id) === parseInt(area_id));
            let data_cargos = extraerCargos(data);

            loadSelectCargos(data_cargos);
        }
        component_select_cargo.style.display = 'block';
    }
}

function extraerCargos(data) {
    const arraysDeCargos = data.map(departamento => {
        const cargosPorDepartamento = departamento.areas.map(area => {
            return area.cargos || [];
        });
        return cargosPorDepartamento.flat(); //nivel 1
    });
    return arraysDeCargos.flat(); //nivel 1
}

function loadSelectCargos(data_select) {
    let $selectCargo = $("#select_cargos").selectize()[0].selectize;
    $selectCargo.clear();
    $selectCargo.clearOptions();
    data_select.forEach(cargo => {
        $selectCargo.addOption({
            value: btoa(JSON.stringify(cargo)),
            text: cargo.nombre
        })
    })
}

function loadDepartamentos() {
    let component_body_departamentos = document.getElementById('component_body_departamentos');
    component_body_departamentos.innerHTML = '';

    if (items_areas_deptos.length > 0) {
        items_areas_deptos.forEach((depto) => {
            let totalCargos = 0;
            depto.areas.forEach(area => {
                totalCargos += area.cargos.length;
            });

            if (depto.areas.length > 0) {
                let departamentoRowCreated = false;

                depto.areas.forEach((area) => {
                    area.cargos.forEach((cargo, index) => {
                        const tr = document.createElement('tr');
                        let tags_td = '';
                        let riesgos_td_id = 'riesgo' + (index);
                        let examenes_td_id = 'examen' + (index);

                        if (!departamentoRowCreated) {
                            tags_td += `
                                <td class="custom-td" rowspan="${totalCargos}" style="width: 10%; text-align: center !important;">
                                    <span style="padding: 2px 10px; position: relative;display:flex;justify-content: center;align-items:center">
                                        ${depto.nombre.toUpperCase()}
                                        <i title="Agregar un factor de riesgo" data-id="${depto.id}" onclick="selectedItem(this)" class="bi bi-plus-circle" style="font-size: 20px; cursor:pointer;margin: 0px 0px 0px 8px;"></i>
                                    </span>
                                </td>
                            `;
                            departamentoRowCreated = true;
                        }

                        if (index === 0) {
                            let rowSpanCargos = area.cargos.length;
                            tags_td += `
                                <td class="custom-td" rowspan="${rowSpanCargos}" style="width: 10%; text-align: center !important;">
                                    <span style="cursor: pointer; padding: 2px 10px; position: relative;">
                                        ${area.nombre.toUpperCase()}
                                    </span>
                                </td>
                            `;
                        }

                        tags_td += `
                            <td class="custom-td" style="width: 15%; text-align: center !important;">
                                ${cargo.nombre.toUpperCase()}
                            </td>
                            <td class="custom-td" style="width: 20%; text-align: center !important;" id="${riesgos_td_id}"></td>
                            <td class="custom-td" style="width: 45%; text-align: center !important;" id="${examenes_td_id}"></td>
                        `;

                        tr.innerHTML = tags_td;

                        // Mostrar los riesgos relacionados con el cargo
                        if (area.riesgos) {
                            let riesgos_td = tr.querySelector(`#${riesgos_td_id}`);
                            area.riesgos.forEach((riesgo, index) => {
                                let id_span = 'span-' + (index + 1);
                                let tag_span = '';
                                if (riesgo.categoria === "cargos" && parseInt(cargo.id) === parseInt(riesgo.cargo_id)) {
                                    tag_span = `
                                        <span data-index="${id_span}" onclick="showIconDeleteRiesgo(this)" class="badge bg-success element" style="cursor:pointer">
                                            <i class="bi bi-check-circle me-1"></i>${riesgo.nombre.toUpperCase()}
                                            <i id="${id_span}" class="bi bi-x-circle btn-close-span d-none" data-area_depto_id="${cargo.id}" data-factor_riesgo_id="${riesgo.factor_riesgo_id}" data-categoria="${riesgo.categoria}" onclick="removeItemRiesgo(this)"></i>
                                        </span>
                                    `;
                                }
                                riesgos_td.innerHTML += tag_span;
                            });
                        }

                        // Mostrar los exámenes relacionados con el cargo
                        if (area.examenes) {
                            let examenes_td = tr.querySelector(`#${examenes_td_id}`);
                            area.examenes.forEach((examen) => {
                                let tag_span = '';
                                if (examen.categoria === "cargos" && parseInt(cargo.id) === parseInt(examen.cargo_id)) {
                                    tag_span = `<span class="badge bg-success element"><i class="bi bi-check-circle me-1"></i>${examen.examen}</span>`;
                                }
                                examenes_td.innerHTML += tag_span;
                            });
                        }
                        // filas al cuerpo de la tabla
                        component_body_departamentos.appendChild(tr);
                    });
                });
            }
        });
    } else {
        component_body_departamentos.innerHTML = '<tr><td colspan="6" class="text-center">Sin datos para mostrar.</td></tr>';
    }
}



function btn_jerarquia(){
    console.log("hola")
        // Realizar la consulta con Axios
        axios.get(route('app.det_jerarquia.check'))
        .then((response) => {
            const data = response.data;
            console.log(data)
            // Verificar si existen datos
            if (data.exists) {
                // Si existen datos, pintar filas según el tipo
                pintarFilasPorTipo(data.tipo);
                $("#CrearJerarquia").modal('show');
            } else {
                // Si no existen datos, mostrar SweetAlert con opciones
                Swal.fire({
                    title: 'Seleccione la jerarquía',
                    html: `
                      <div style="text-align: left; display: flex; gap: 15px; align-items: center;">
                        <div>
                          <input type="radio" id="depto-area-cargo" name="jerarquia" value="1">
                          <label for="depto-area-cargo">Depto, Area, Cargo</label>
                        </div>
                        <div>
                          <input type="radio" id="area-cargo" name="jerarquia" value="2">
                          <label for="area-cargo">Area y Cargo</label>
                        </div>
                        <div>
                          <input type="radio" id="cargo" name="jerarquia" value="3">
                          <label for="cargo">Cargo</label>
                        </div>
                      </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    preConfirm: () => {
                        // Capturar valor seleccionado
                        const selectedOption = document.querySelector('input[name="jerarquia"]:checked');
                        if (!selectedOption) {
                            Swal.showValidationMessage('Debe seleccionar una opción');
                        }
                        return selectedOption ? selectedOption.value : null;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const selectedTipo = result.value;

                // Enviar la selección al servidor para guardarla
                axios.post(route('app.det_jerarquia.save'), {
                    tipo: selectedTipo
                }).then((saveResponse) => {
                    if (saveResponse.data.success) {
                        // Pintar filas en el modal después de guardar
                        pintarFilasPorTipo(result.value);
                        $("#CrearJerarquia").modal('show');
                    }
                }).catch((error) => {
                    console.error('Error al guardar la jerarquía:', error);
                });
                       // Abrir modal y pintar filas según el valor seleccionado
                    }
                });
            }
        })
        .catch((error) => {
            console.error('Error en la consulta Axios', error);
        });
}



let jerarquia = {
    departamento: null,
    areas: []
};



function pintarFilasPorTipo(tipo) {
    const modalBody = document.querySelector("#CrearJerarquia .modal-body .row");
    modalBody.innerHTML = '';  // Limpiar contenido previo

    let jerarquia = []; // Estructura de datos para almacenar la jerarquía

    let filas = '';
    if (tipo == '1') {
        filas += `
        <div class="col-12 mt-4 m-0 p-0" style="border: 1px solid #dadce0;">
            <table style="width: 100%;border-collapse:collapse">
                <thead style="font-size: 13px;text-align: center;">
                    <tr>
                        <th class="left-th bg-custom-color" style="width: 30%">Departamento <button id="addDepartamentoBtn" class="btn btn-sm btn-link"><i class="bi bi-plus-circle" style="font-size: 18px;cursor: pointer;color:white;"></i></button></th>
                        <th class="bg-custom-color" style="width: 35%">Áreas</th>
                        <th class="rigth-th bg-custom-color" style="width: 35%">Cargos</th>
                    </tr>
                </thead>
                <tbody id="jerarquiaTableBody" style="font-size: 13px; text-align: center;">
                </tbody>
            </table>
        </div>
        <div class="col-12 text-end mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="guardarJerarquia">Guardar Jerarquía</button>
        </div>`;

        modalBody.innerHTML = filas;  // Insertar las filas en el modal

        // Función para validar la jerarquía
        function validarJerarquia(jerarquia) {
            if (jerarquia.length === 0) {
                return {
                    valid: false,
                    message: 'Debe agregar al menos un departamento'
                };
            }

            for (const depto of jerarquia) {
                if (depto.areas.length === 0) {
                    return {
                        valid: false,
                        message: `El departamento "${depto.nombre}" debe tener al menos un área`
                    };
                }

                for (const area of depto.areas) {
                    if (area.cargos.length === 0) {
                        return {
                            valid: false,
                            message: `El área "${area.nombre}" en el departamento "${depto.nombre}" debe tener al menos un cargo`
                        };
                    }
                }
            }

            return { valid: true };
        }

        // Función para eliminar elementos
        function eliminarElemento(id, tipo) {
            if (tipo === 'departamento') {
                const departamento = jerarquia.find(dept => dept.id === id);
                if (departamento && (departamento.areas.length > 0 || departamento.areas.some(area => area.cargos.length > 0))) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Se eliminarán todas las áreas y cargos asociados a este departamento.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar todo',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            realizarEliminacion(id, tipo);
                            Swal.fire(
                                '¡Eliminado!',
                                'El departamento y todos sus elementos asociados han sido eliminados.',
                                'success'
                            );
                        }
                    });
                } else {
                    realizarEliminacion(id, tipo);
                }
            } else {
                realizarEliminacion(id, tipo);
            }
        }

        function realizarEliminacion(id, tipo) {
            const elemento = document.getElementById(id);
            if (elemento) {
                if (tipo === 'departamento') {
                    // Get the department from jerarquia
                    const deptIndex = jerarquia.findIndex(dept => dept.id === id);
                    if (deptIndex !== -1) {
                        jerarquia.splice(deptIndex, 1);
                    }
                    
                    // Remove all associated rows
                    const rowspan = parseInt(elemento.querySelector('td').getAttribute('rowspan') || 1);
                    let nextRow = elemento.nextElementSibling;
                    for (let i = 1; i < rowspan; i++) {
                        if (nextRow) {
                            const temp = nextRow.nextElementSibling;
                            nextRow.remove();
                            nextRow = temp;
                        }
                    }
                    elemento.remove();
                } else if (tipo === 'area') {
                    // Update department rowspan
                    const parentDeptCell = elemento.previousElementSibling?.querySelector('td[rowspan]');
                    if (parentDeptCell) {
                        const currentRowspan = parseInt(parentDeptCell.getAttribute('rowspan'));
                        parentDeptCell.setAttribute('rowspan', currentRowspan - 1);
                    }
                    
                    // Remove from jerarquia
                    jerarquia.forEach(dept => {
                        const areaIndex = dept.areas.findIndex(area => area.id === id);
                        if (areaIndex !== -1) {
                            dept.areas.splice(areaIndex, 1);
                        }
                    });
                    
                    elemento.remove();
                } else if (tipo === 'cargo') {
                    // Remove from jerarquia
                    jerarquia.forEach(dept => {
                        dept.areas.forEach(area => {
                            const cargoIndex = area.cargos.findIndex(cargo => cargo.id === id);
                            if (cargoIndex !== -1) {
                                area.cargos.splice(cargoIndex, 1);
                            }
                        });
                    });
                    
                    elemento.remove();
                }

                console.log('Jerarquía actualizada:', jerarquia);
            }
        }

        // Agregar evento al botón para añadir departamento
        document.getElementById('addDepartamentoBtn').addEventListener('click', () => {
            Swal.fire({
                title: 'Agregar Departamento',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('El nombre del departamento es obligatorio');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const departamento = result.value;
                    const uniqueId = `dept_${Date.now()}`;

                    const newRow = document.createElement('tr');
                    newRow.id = uniqueId;
                    newRow.innerHTML = `
                    <td rowspan="1" id="${uniqueId}_cell">
                        ${departamento} 
                        <button class="btn btn-sm btn-link addAreaBtn" data-dept-id="${uniqueId}" 
                            onclick="addArea('${uniqueId}')">
                            Agregar+
                        </button>
                        
                        <button class="btn btn-sm btn-link deleteBtn" data-id="${uniqueId}" data-tipo="departamento" 
                            onclick="deleteDepartamento('${uniqueId}')" 
                            style="color: red;">
                            Eliminar-
                        </button>
                    </td>
                    <td></td>
                    <td></td>
                `;        
                
                    document.getElementById('jerarquiaTableBody').appendChild(newRow);

                    // Agregar a la estructura de datos
                    jerarquia.push({ id: uniqueId, nombre: departamento, areas: [] });
                    console.log('Jerarquía actualizada:', jerarquia);

                    // Evento para eliminar departamento
                    newRow.querySelector('.deleteBtn').addEventListener('click', (e) => {
                        const id = e.target.getAttribute('data-id');
                        const tipo = e.target.getAttribute('data-tipo');
                        eliminarElemento(id, tipo);
                    });

// Evento para agregar áreas
newRow.querySelector('.addAreaBtn').addEventListener('click', (e) => {
    const deptId = e.target.getAttribute('data-dept-id');
    Swal.fire({
        title: 'Agregar Área',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        cancelButtonText: 'Cancelar',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('El nombre del área es obligatorio');
            }
            return value;
        }
    }).then((areaResult) => {
        if (areaResult.isConfirmed) {
            const area = areaResult.value;
            const areaId = `area_${Date.now()}`;

            const areaRow = document.createElement('tr');
            areaRow.id = areaId;
            areaRow.innerHTML = `
                <td>
                    ${area} 
                    <button class="btn btn-sm btn-link addCargoBtn" data-area-id="${areaId}">Cargo +</button>
                    <button class="btn btn-sm btn-link deleteBtn" data-id="${areaId}" data-tipo="area">x</button>
                </td>
                <td id="cargos_${areaId}"></td>
            `;

            // Obtener el contenedor del departamento
            const deptCell = document.getElementById(`${deptId}_cell`);
            const deptRow = deptCell.parentNode;

            // Encontrar la última fila de área del departamento
            let lastAreaRow = deptRow;
            let nextRow = deptRow.nextElementSibling;

            // Buscar la última fila de área existente
            while (nextRow) {
                if (nextRow.id && nextRow.id.startsWith('area_')) {
                    lastAreaRow = nextRow;  // Actualizar la última área encontrada
                }
                nextRow = nextRow.nextElementSibling;
            }

            // Insertar la nueva fila de área después de la última fila de área encontrada
            deptRow.parentNode.insertBefore(areaRow, lastAreaRow.nextSibling);

            // Actualizar el rowspan del departamento
            const currentRowspan = parseInt(deptCell.getAttribute('rowspan') || 1);
            deptCell.setAttribute('rowspan', currentRowspan + 1);

            // Agregar a la estructura de datos
            const departamento = jerarquia.find(dept => dept.id === deptId);
            if (departamento) {
                departamento.areas.push({ id: areaId, nombre: area, cargos: [] });
            }
            console.log('Jerarquía actualizada:', jerarquia);

            // Evento para eliminar área
            areaRow.querySelector('.deleteBtn').addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                const tipo = e.target.getAttribute('data-tipo');
                eliminarElemento(id, tipo);
            });

            // Evento para agregar cargos a esta área
            areaRow.querySelector('.addCargoBtn').addEventListener('click', (e) => {
                const areaId = e.target.getAttribute('data-area-id');
                Swal.fire({
                    title: 'Agregar Cargo',
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('El nombre del cargo es obligatorio');
                        }
                        return value;
                    }
                }).then((cargoResult) => {
                    if (cargoResult.isConfirmed) {
                        const cargo = cargoResult.value;
                        const cargoId = `cargo_${Date.now()}`;
                        const cargosContainer = document.getElementById(`cargos_${areaId}`);
                        const cargoDiv = document.createElement('div');
                        cargoDiv.id = cargoId;
                        cargoDiv.innerHTML = `
                            ${cargo}
                            <button class="btn btn-sm btn-link deleteBtn" data-id="${cargoId}" data-tipo="cargo">x</button>
                        `;
                        cargosContainer.appendChild(cargoDiv);

                        // Agregar a la estructura de datos
                        jerarquia.forEach(dept => {
                            const area = dept.areas.find(a => a.id === areaId);
                            if (area) {
                                area.cargos.push({ id: cargoId, nombre: cargo });
                            }
                        });
                        console.log('Jerarquía actualizada:', jerarquia);

                        // Evento para eliminar cargo
                        cargoDiv.querySelector('.deleteBtn').addEventListener('click', (e) => {
                            const id = e.target.getAttribute('data-id');
                            const tipo = e.target.getAttribute('data-tipo');
                            eliminarElemento(id, tipo);
                        });
                    }
                });
            });
        }
    });
});

                }
            });
        });

        // Agregar evento al botón guardar
        document.getElementById('guardarJerarquia').addEventListener('click', () => {
            const validacion = validarJerarquia(jerarquia);
            
            if (!validacion.valid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: validacion.message
                });
                return;
            }

            // Show loading state
            Swal.fire({
                title: 'Guardando...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });

            // Prepare data for sending
            const dataToSend = {
                tipo: tipo,
                jerarquia: jerarquia
            };

            // Send data to controller
            axios.post(route('jerarquia.save'), dataToSend)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'La jerarquía se ha guardado correctamente',
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close modal and refresh if needed
                                $("#CrearJerarquia").modal('hide');
                                // Optionally refresh the page or update the UI
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(response.data.message || 'Error al guardar la jerarquía');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al guardar la jerarquía. Por favor, intente nuevamente.'
                    });
                });
        });
    }
// Update the eliminarElemento function to update department rowspan
function realizarEliminacion(id, tipo) {
    const elemento = document.getElementById(id);
    if (elemento) {
        if (tipo === 'departamento') {
            // Get the department from jerarquia
            const deptIndex = jerarquia.findIndex(dept => dept.id === id);
            if (deptIndex !== -1) {
                jerarquia.splice(deptIndex, 1);
            }
            
            // Remove all associated rows
            const rowspan = parseInt(elemento.querySelector('td').getAttribute('rowspan') || 1);
            let nextRow = elemento.nextElementSibling;
            for (let i = 1; i < rowspan; i++) {
                if (nextRow) {
                    const temp = nextRow.nextElementSibling;
                    nextRow.remove();
                    nextRow = temp;
                }
            }
            elemento.remove();
        } else if (tipo === 'area') {
            // Update department rowspan
            const parentDeptCell = elemento.previousElementSibling?.querySelector('td[rowspan]');
            if (parentDeptCell) {
                const currentRowspan = parseInt(parentDeptCell.getAttribute('rowspan'));
                parentDeptCell.setAttribute('rowspan', currentRowspan - 1);
            }
            
            // Remove from jerarquia
            jerarquia.forEach(dept => {
                const areaIndex = dept.areas.findIndex(area => area.id === id);
                if (areaIndex !== -1) {
                    dept.areas.splice(areaIndex, 1);
                }
            });
            
            elemento.remove();
        } else if (tipo === 'cargo') {
            // Remove from jerarquia
            jerarquia.forEach(dept => {
                dept.areas.forEach(area => {
                    const cargoIndex = area.cargos.findIndex(cargo => cargo.id === id);
                    if (cargoIndex !== -1) {
                        area.cargos.splice(cargoIndex, 1);
                    }
                });
            });
            
            elemento.remove();
        }
    }
}    

}
