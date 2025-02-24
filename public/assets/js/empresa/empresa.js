//CREACION DE EMPRESAS
document.addEventListener('DOMContentLoaded', function() {
    let urlDataTableEmpresa = window.location.origin + "/Empresas/show";
    dataTable("empresas", urlDataTableEmpresa);

    let table = new DataTable('#empresas');
    table.on('draw.dt', EventEmpresa);

})

async function EventEmpresa() {
    let btnHorarios = document.querySelectorAll('.btn-o-horario');
    let btnjerarquia = document.querySelectorAll('.btn-o-jerarquia')
    let btnEdit = document.querySelectorAll('.btn-o-edit');
    let btnData = document.querySelectorAll('.btn-o-data');
    let btn_add_depto = document.querySelectorAll('.btn-o-pluss');
    let btn_add_ver_jerarquia = document.querySelectorAll('.btn-o-ver');
    let btn_add_sucursal = document.querySelectorAll('.btn-add-sucursal')

    if (btn_add_sucursal) {
        btn_add_sucursal.forEach((btnAdd) => {
            let empresa_id = btnAdd.dataset.emp_ref; // Acceder correctamente a data-emp_ref
            btnAdd.addEventListener('click', (e) => {
                e.preventDefault();
                $("#CrearSucursal").modal('show');
                 // Agregar el ID de la empresa a la URL
            let urlDataTableSucursal = window.location.origin + "/Sucursal/show/" + empresa_id;
            dataTable("sucursales", urlDataTableSucursal);
            // Llama a la función de la tabla con la URL que incluye el ID
        });
    });
}


    if (btnHorarios) {
        btnHorarios.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empleado_id = btn.dataset.emp_ref;
                $("#ModalCrearHorario").modal("show");

                try {
                    // Hacer la petición a la API para obtener los horarios de la empresa
                    const response = await axios.get(`/getHorarioEmpresa/${empleado_id}`);

                    const horarios = response.data.horarios; // Horarios por día { "lunes": ["08:00", "09:00"], ... }
                    const rangoHorario = response.data.rango_horario; // Obtener el valor del rango horario
                    const selectedRange = parseInt(rangoHorario); // Convertir rango horario a número

                    // Generar los slots de tiempo según el rango seleccionado
                    function generateTimeSlots(range) {
                        let startTime = 7 * 60; // 7:00 AM en minutos
                        let endTime = 18 * 60;  // 6:00 PM en minutos
                        let timeSlots = [];
                        for (let time = startTime; time < endTime; time += range) {
                            let hours = Math.floor(time / 60);
                            let minutes = time % 60;
                            let period = hours < 12 ? 'AM' : 'PM';
                            if (hours > 12) hours -= 12;
                            let formattedTime = `${hours}:${minutes < 10 ? '0' : ''}${minutes} ${period}`;
                            timeSlots.push(formattedTime);
                        }
                        return timeSlots;
                    }

                    // Actualizar los slots de todos los días
                    function updateSlotsForAllDays(range) {
                        ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'].forEach(function(day) {
                            let daySlotContainer = document.getElementById(`${day}-slots`);
                            daySlotContainer.innerHTML = ''; // Limpiar slots anteriores

                            if (range) {
                                let slots = generateTimeSlots(range);
                                slots.forEach(function(slot) {
                                    daySlotContainer.innerHTML += `
                                        <div>
                                            <input type="checkbox" name="${day}-slot" value="${slot}" class="time-slot-checkbox">
                                            <label class="slot-label">${slot}</label>
                                        </div>
                                    `;
                                });
                            }
                        });
                    }

                    // Generar y pintar los slots con el rango obtenido
                    updateSlotsForAllDays(selectedRange);

                    // Marcar los checkboxes correspondientes a los horarios obtenidos
                    const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                    days.forEach(day => {
                        if (horarios[day]) {
                                                    // Marcar las horas correspondientes
                            horarios[day].forEach(hora => {
                                let checkbox = document.querySelector(`#${day}-slots input[value="${hora}"]`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            });
                        }
                    });

                    // Asignar el valor al select de rango horario
                    let selectRangoHorario = document.getElementById('rango_horario');
                    if (selectRangoHorario) {
                        selectRangoHorario.value = rangoHorario || ""; // Dejar en "Seleccionar" si no hay valor
                    }

                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudieron cargar los horarios de la empresa",
                    });
                }
            });
        });
    }

    if (btnEdit) {
        btnEdit.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empresa_id = btn.dataset.emp_id;
    
                try {
                    let response = await axios.post(route('app.empresa.getEmpresa'), { ref_emp: empresa_id }, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                            'Content-Encoding': 'gzip'
                        }
                    });
    
                    // Extracting data from the response
                    let empresa = response.data[0]; 
                    // Populate modal fields
                    document.getElementById('ActualizarEmpresaa').value = empresa.nombre || '';
                    document.getElementById('ActdireccionEmpresa').value = empresa.direccion || '';
                    document.getElementById('ActtelefonoEmpresa').value = empresa.telefono || '';
                    document.getElementById('ActCelularEmpresa').value = empresa.celular || '';
                    document.getElementById('ActnRegistroEmpresa').value = empresa.no_registro || '';
                    document.getElementById('Actgiro').value = empresa.giro || '';
    
                    // If the logo exists, display it
                    if (empresa.logo) {
                        document.getElementById('previewImage').src = `/storage/${empresa.logo}`;
                    } else {
                        // In case no logo exists, reset the src attribute
                        document.getElementById('previewImage').src = '';
                    }
    
                    // Show the modal only after data is populated
                    $("#ActualizarEmpresa").modal("show");
    
                } catch (error) {
                    console.error('Error loading empresa data:', error);
                }
            });
        });
    }




    
     //modal departamento
 if (btn_add_depto) {
    btn_add_depto.forEach((btnAdd) => {
        btnAdd.addEventListener('click', (e) => {
            e.preventDefault();
            dataTable("departamentos", route('app.dep.dt'),{});

            $("#modal_new_departamento").modal('show');

        })
    })
}    

if (btnjerarquia) {
    btnjerarquia.forEach((btnAdd) => {
        btnAdd.addEventListener('click', (e) => {
            e.preventDefault();

            // Realizar la consulta con Axios
            axios.get(route('app.det_jerarquia.check'))
                .then((response) => {
                    const data = response.data;
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
        });
    });
}

if (btn_add_ver_jerarquia){

    btn_add_ver_jerarquia.forEach((btnAdd) => {
        btnAdd.addEventListener('click', (e) => {
            e.preventDefault();
            // Abre el modal y carga los datos desde la base de datos
    axios.get(route('app.det_jerarquia.check'))
    .then((response) => {
        const data = response.data;
        
        if (data.exists) {
            // Si los datos existen, los mostramos en el modal
            pintarFilasConDatosGuardados(data.data);
            $("#CrearJerarquia").modal('show');
        } else {
            // Lógica para manejar cuando no hay datos
            console.log('No hay datos guardados');
        }
    })
    .catch((error) => {
        console.error('Error al cargar jerarquía existente', error);
    });

        })
    })



function pintarFilasConDatosGuardados(data) {
    const modalBody = document.querySelector("#CrearJerarquia .modal-body .row");
    modalBody.innerHTML = '';  // Limpiar contenido previo
    
    let filas = `
        <div class="col-12 mt-4 m-0 p-0" style="border: 1px solid #dadce0;">
            <table style="width: 100%;border-collapse:collapse">
                <thead style="font-size: 13px;text-align: center;">
                    <tr>
                        <th class="left-th bg-custom-color" style="width: 30%">Departamento</th>
                        <th class="bg-custom-color" style="width: 35%">Áreas</th>
                        <th class="rigth-th bg-custom-color" style="width: 35%">Cargos</th>
                    </tr>
                </thead>
                <tbody id="jerarquiaTableBody" style="font-size: 13px; text-align: center;">`;
                
    // Iteramos sobre departamentos, áreas y cargos
    data.departamentos.forEach((departamento) => {
        const areas = data.areas.filter(area => area.id_depto === departamento.id);
        
        areas.forEach((area, index) => {
            const cargos = data.cargos.filter(cargo => cargo.id_area === area.id);
            
            filas += `
                <tr>
                    ${index === 0 ? `<td rowspan="${areas.length}">${departamento.nombre}</td>` : ''}
                    <td>${area.nombre}</td>
                    <td>${cargos.map(cargo => cargo.nombre).join(', ')}</td>
                </tr>`;
        });
    });
    
    filas += `</tbody></table></div>`;
    
    modalBody.innerHTML = filas;
}

}

let jerarquia = {
    departamento: null,
    areas: []
};
if (btnData) {
    btnData.forEach((btnAdd) => {
        btnAdd.addEventListener('click', async (e) => {
            e.preventDefault();

            let empresa_id = btnAdd.dataset.emp_id;

            try {
                // Consulta Axios
                const response = await axios.post('/datosUser', {
                    empresa_id: empresa_id,
                });

                // Generar HTML para la tabla
                let tableHtml = `
                    <table border="1" style="width:100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                response.data.forEach((item) => {
                    tableHtml += `
                        <tr>
                            <td>${item.usuario}</td>
                            <td>${item.contr}</td>
                        </tr>
                    `;
                });

                tableHtml += `
                        </tbody>
                    </table>
                `;

                // Mostrar SweetAlert con la tabla
                Swal.fire({
                    title: "Información de Usuarios",
                    html: tableHtml,
                    icon: "info",
                });
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo obtener la información.",
                    icon: "error",
                });
                console.error(error);
            }
        });
    });
}


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
                        <button class="addAreaBtn btn-sm" data-dept-id="${uniqueId}" 
                            onclick="addArea('${uniqueId}')" 
                            style="
                                background-color: #28a745;
                                color: white;
                                border: none;
                                border-radius: 50px;
                                padding: 5px 10px;
                                font-size: 12px;
                                cursor: pointer;
                                display: inline-flex;
                                align-items: center;
                            ">
                            <i class="bi bi-plus-circle" style="margin-right: 4px;"></i>
                   Área
                        </button>
                
                        <button class="deleteBtn btn-sm" data-id="${uniqueId}" data-tipo="departamento" 
                            onclick="deleteDepartamento('${uniqueId}')" 
                            style="
                                background-color: red;
                                color: white;
                                border: none;
                                border-radius: 50px;
                                padding: 5px 10px;
                                font-size: 12px;
                                cursor: pointer;
                                display: inline-flex;
                                align-items: center;
                            ">
                            <i class="fa fa-trash" style="margin-right: 4px;"></i> Eliminar
                        </button>
                    </td>
                    <td></td>
                    <td></td>
                `;
                
                    document.getElementById('jerarquiaTableBody').appendChild(newRow);

                    // Agregar a la estructura de datos
                    jerarquia.push({ id: uniqueId, nombre: departamento, areas: [] });
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
                                
                                // Insertar la nueva fila de área después de la última fila del departamento
                                const deptCell = document.getElementById(`${deptId}_cell`);
                                const deptRow = deptCell.parentNode;
                                deptRow.parentNode.insertBefore(areaRow, deptRow.nextSibling);

                                // Actualizar el rowspan del departamento
                                const currentRowspan = parseInt(deptCell.getAttribute('rowspan') || 1);
                                deptCell.setAttribute('rowspan', currentRowspan + 1);

                                // Agregar a la estructura de datos
                                const departamento = jerarquia.find(dept => dept.id === deptId);
                                if (departamento) {
                                    departamento.areas.push({ id: areaId, nombre: area, cargos: [] });
                                }

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
}



document.addEventListener('DOMContentLoaded', () => {
        let btn_newdepa = document.querySelector('.btn_new_depa');
        if (btn_newdepa) {
            btn_newdepa.addEventListener('click', (e) => {
                e.stopPropagation();
                $("#modal_new_departamento_crear").modal('show');

            })
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
            dataTable("departamentos", route('app.dep.dt'),{});

            $("#modal_new_departamento_crear").modal('hide');
            form_data_detop_area.reset();
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



function CrearEmpresa() {
    $("#CrearEmpresa").modal("show");
        document.querySelectorAll('#CrearEmpresa input').forEach(input => {
        input.value = '';
        input.classList.remove('error');
    });

    document.getElementById('existenciaValue').value = '';
    document.getElementById('idEmpresa').value = '';
}

function CrearSucursal() {
    $("#CrearSucursales").modal("show");
    document.getElementById('CrearUsuario').reset();
    
    document.querySelectorAll('.custom-input, .form-select').forEach(input => {
        input.classList.remove('error');
    });
}
function loadImage() {
    document.getElementById('file_logo').click();
}

document.getElementById('file_logo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    const previewImage = document.getElementById('showImagePreview');
    const loadingIndicator = document.getElementById('loading');
    const errorContainer = document.getElementById('errors');
    const errorList = document.getElementById('errorList');

    errorContainer.classList.add('d-none');
    errorList.innerHTML = '';

    if (file) {
        // Show loading indicator
        loadingIndicator.classList.remove('d-none');

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            loadingIndicator.classList.add('d-none');
        };

        reader.onerror = function() {
            loadingIndicator.classList.add('d-none');
            errorContainer.classList.remove('d-none');
            errorList.innerHTML = '<li>Error al cargar la imagen. Por favor, inténtelo de nuevo.</li>';
        };

        reader.readAsDataURL(file);
    } else {
        previewImage.src = '';
        loadingIndicator.classList.add('d-none');
    }
});

function saveEmpresa() {
    const formData = new FormData();

    // Append form data
    formData.append('nombreEmpresa', document.getElementById('nombreEmpresa').value);
    formData.append('direccionEmpresa', document.getElementById('direccionEmpresa').value);
    formData.append('telefonoEmpresa', document.querySelector('input[name="telefonoEmpresa"]').value);
    formData.append('celularEmpresa', document.querySelector('input[name="celularEmpresa"]').value);
    formData.append('nRegistroEmpresa', document.getElementById('nRegistroEmpresa').value);
    formData.append('giro', document.querySelector('input[name="giro"]').value);
    formData.append('encargado', document.querySelector('input[name="encargado"]').value);

    let id = document.getElementById('idEmpresa').value;
    let exist = document.getElementById('existenciaValue').value;
        formData.append('id', id);
        formData.append('exist', exist); 
    // Append image file
    const imageFile = document.getElementById('file_logo').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    axios.post('/empresa/guardar', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        Swal.fire({
            icon: 'success',
            title: 'Usuario registrado',
            text: 'Usuario registrado con éxito',
        })
        $("#CrearEmpresa").modal("hide");
        let urlDataTableEmpresa = window.location.origin + "/Empresas/show";
        dataTable("empresas", urlDataTableEmpresa);
        // Recibir los datos de la empresa creada
        const empresaData = response.data;

        // Llamada API para enviar los datos a otro sistema
        enviarDatosAOtroSistema(empresaData);
    })
    .catch(error => {   
        console.error('Error al guardar la empresa:', error);
    
    });

}


document.addEventListener('DOMContentLoaded', function() {
    let rangoHorario = document.getElementById('rango_horario');
    let selectedRange = 0;

    rangoHorario.addEventListener('change', function() {
        selectedRange = parseInt(this.value);
        updateSlotsForAllDays();
    });
    function generateTimeSlots(range) {
        let startTime = 7 * 60; // 7:00 AM in minutes
        let endTime = 18 * 60;  // 6:00 PM in minutes
        let timeSlots = [];
        for (let time = startTime; time < endTime; time += range) {
            let hours = Math.floor(time / 60);
            let minutes = time % 60;
            let period = hours < 12 ? 'AM' : 'PM';
            if (hours > 12) hours -= 12;
            let formattedTime = `${hours}:${minutes < 10 ? '0' : ''}${minutes} ${period}`;
            timeSlots.push(formattedTime);
        }
        return timeSlots;
    }

    function updateSlotsForAllDays() {
        ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'].forEach(function(day) {
            let daySlotContainer = document.getElementById(`${day}-slots`);
            daySlotContainer.innerHTML = '';
            if (selectedRange) {
                let slots = generateTimeSlots(selectedRange);
                slots.forEach(function(slot) {
                    daySlotContainer.innerHTML += `
                        <div>
                            <input type="checkbox" name="${day}-slot" value="${slot}" class="time-slot-checkbox">
                            <label class="slot-label">${slot}</label>
                        </div>
                    `;
                });
            }
        });
    }


    document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            if (!selectedRange) {
                // Muestra la alerta si no se ha seleccionado el rango
                Swal.fire({
                    icon: 'warning',
                    title: 'Rango no seleccionado',
                    text: 'Por favor, seleccione un rango antes de continuar.',
                    confirmButtonColor: '#007bff'
                });
                selectAllCheckbox.checked = false;
                return;
            }

            let day = this.dataset.day;
            let daySlotContainer = document.getElementById(`${day}-slots`);

            if (this.checked) {
                // Selecciona todos los checkboxes para ese día
                daySlotContainer.querySelectorAll('.time-slot-checkbox').forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                // Deselecciona todos los checkboxes para ese día
                daySlotContainer.querySelectorAll('.time-slot-checkbox').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    });
});

document.getElementById('BtnRegHor').addEventListener('click', function (event) {
    event.preventDefault();

    const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    let horariosSeleccionados = {};
    days.forEach(function (day) {
        let checkboxes = document.querySelectorAll(`#${day}-slots .time-slot-checkbox:checked`);
        if (checkboxes.length > 0) {
            horariosSeleccionados[day] = [];
            checkboxes.forEach(function (checkbox) {
                horariosSeleccionados[day].push(checkbox.value); // Almacenar la hora seleccionada
            });
        }
    });
      let rangoHorario = document.getElementById('rango_horario');
      let rangoHorario1 = document.getElementById('rango_horario');
      let obj = rangoHorario1.value;
      rangoHorario.value = 0; // Restablecer a "Seleccionar"
      selectedRange = 0;
      ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'].forEach(function(day) {
        let daySlotContainer = document.getElementById(`${day}-slots`);
        daySlotContainer.innerHTML = '';
        if (selectedRange) {
            let slots = generateTimeSlots(selectedRange);
            slots.forEach(function(slot) {
                daySlotContainer.innerHTML += `
                    <div>
                        <input type="checkbox" name="${day}-slot" value="${slot}" class="time-slot-checkbox">
                        <label class="slot-label">${slot}</label>
                    </div>
                `;
            });
        }
    });    
    axios.post('/SaveHorario', {
        horariosSeleccionados: horariosSeleccionados,
        obj: obj
    })
    .then(function(response) {
        Swal.fire({
            icon: "success",
            title: "exito...",
            text: "Horario agregado exitosamente!",
          });
        horariosSeleccionados = {};

        days.forEach(function (day) {
            let checkboxes = document.querySelectorAll(`#${day}-slots .time-slot-checkbox`);
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false; // Desmarcar los checkboxes
            });
              let selectAllCheckbox = document.querySelector(`.select-all[data-day="${day}"]`);
              if (selectAllCheckbox) {
                  selectAllCheckbox.checked = false; // Desmarcar "select-all"
              }
        });
      let rangoHorario = document.getElementById('rango_horario');
      rangoHorario.value = ""; // Restablecer a "Seleccionar"
        $('#ModalCrearHorario').modal('hide'); 
        
    })
    .catch(function(error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Paciente no encontrado!",
          });
    });

});



function triggerImageUpload() {
    document.getElementById('file_image').click();
}

document.getElementById('file_image').addEventListener('change', function(event) {
    const selectedFile = event.target.files[0];
    const fileReader = new FileReader();

    const imagePreview = document.getElementById('previewImage');
    const loadingMessage = document.getElementById('loadingMessage');
    const errorBox = document.getElementById('imageErrors');
    const errorMessages = document.getElementById('errorMessages');

    errorBox.classList.add('d-none');
    errorMessages.innerHTML = '';

    if (selectedFile) {
        // Mostrar indicador de carga
        loadingMessage.classList.remove('d-none');

        fileReader.onload = function(e) {
            imagePreview.src = e.target.result;
            loadingMessage.classList.add('d-none');
        };

        fileReader.onerror = function() {
            loadingMessage.classList.add('d-none');
            errorBox.classList.remove('d-none');
            errorMessages.innerHTML = '<li>Error al cargar la imagen. Por favor, inténtelo de nuevo.</li>';
        };

        fileReader.readAsDataURL(selectedFile);
    } else {
        imagePreview.src = '';
        loadingMessage.classList.add('d-none');
    }
});


function ActualizarEmpresa() {
    const nombreEmpresa = document.getElementById('ActualizarEmpresaa').value;
    const direccionEmpresa = document.getElementById('ActdireccionEmpresa').value;
    const telefonoEmpresa = document.getElementById('ActtelefonoEmpresa').value;
    const celularEmpresa = document.getElementById('ActCelularEmpresa').value;
    const nRegistroEmpresa = document.getElementById('ActnRegistroEmpresa').value;
    const giroEmpresa = document.getElementById('Actgiro').value;
    const logo = document.getElementById('file_image').files[0];

    // Crear un FormData para enviar con la imagen
    let formData = new FormData();
    formData.append('nombreEmpresa', nombreEmpresa);
    formData.append('direccionEmpresa', direccionEmpresa);
    formData.append('telefonoEmpresa', telefonoEmpresa);
    formData.append('celularEmpresa', celularEmpresa);
    formData.append('nRegistroEmpresa', nRegistroEmpresa);
    formData.append('giro', giroEmpresa);
    // Solo agregar la imagen si se seleccionó
    if (logo) {
        formData.append('image', logo);
    }

    // Enviar la solicitud Ajax usando Axios
    axios.post('/actualizar-empresa', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
     Swal.fire({
            icon: "success",
            title: "exito...",
            text: "Empresa actualizada correctamente!",
          });
                  let urlDataTableEmpresa = window.location.origin + "/Empresas/show";
        dataTable("empresas", urlDataTableEmpresa);
        $('#ActualizarEmpresa').modal('hide'); 
    })
    .catch(error => {
        Swal.fire({
               icon: "error",
               title: "Error",
               text: "Error al actualizar la empresa",
       });
    });
}


function Nuevas(id) {

    axios.post('/sucursal/edit', { id: id })
        .then(response => {
            const sucursalData = response.data[0]; // Accedemos al primer objeto en el array
            // Mostrar los datos en los campos del formulario
            document.getElementById('nombreSucursal1').value = sucursalData.nombre;
            document.getElementById('direccionSucursal1').value = sucursalData.direccion;
            document.getElementById('telefonoSucursal1').value = sucursalData.telefono;
            document.getElementById('emailSucursal1').value = sucursalData.email;
            document.getElementById('encargadoSucursal1').value = sucursalData.encargado;
            document.getElementById('id_susc').value = sucursalData.id;
            document.getElementById('id_empr').value = sucursalData.empresa_id;

            // Mostrar el modal después de cargar los datos
         //   const modal = new bootstrap.Modal(document.getElementById('EditSucursales'));
            //  modal.show();
            $("#EditSucursales").modal('show');

        })
        .catch(error => {
            console.error('Error:', error.response ? error.response.data : error);
            alert('Hubo un problema al procesar su solicitud.');
        });
}



document.getElementById('saveSucursalButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevents the modal from closing
    saveSucursal();
});
document.getElementById('EditSucursalButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevents the modal from closing
    saveEditSucursal();
});

function saveEditSucursal(){

    const form = document.getElementById('form_edit_sucursal');
    const inputs = form.querySelectorAll('input');
    let isValid = true;
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    if (!isValid) {
        alert('Por favor, complete todos los campos obligatorios.');
        return;
    }

    const formData = new FormData(form);

    // Send data to the server using Axios
    axios.post('/sucursal/save_edit', formData)
        .then(response => {
            if (response.data.success) {
                form.reset(); 
                Swal.fire({
                    icon: 'success',
                    title: 'Sucursal Actualizada',
                    text: 'Se actualizo exitosamente.',
                })
                let empresa_id = 0
                $("#EditSucursales").modal('hide');
                let urlDataTableSucursal = window.location.origin + "/Sucursal/show/" + empresa_id;
                dataTable("sucursales", urlDataTableSucursal);
               
            } else {
                alert('Error al guardar la Sucursal.');
            }
        })
        .catch(error => {
            console.error('Error:', error.response ? error.response.data : error);
            alert('Hubo un problema al procesar su solicitud.');
        });
}

function saveSucursal() {
    const form = document.getElementById('form_new_sucursal');
    let isValid = true;
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    if (!isValid) {
        alert('Por favor, complete todos los campos obligatorios.');
        return;
    }

    const formData = new FormData(form);

    // Send data to the server using Axios
    axios.post('/sucursal/guardar', formData)
        .then(response => {
            if (response.data.success) {
                form.reset(); 
                Swal.fire({
                    icon: 'success',
                    title: 'Sucursal registrada',
                    text: 'Sucursal guardada exitosamente.',
                })
            let empresa_id = 0
                $("#CrearSucursales").modal('hide');
                let urlDataTableSucursal = window.location.origin + "/Sucursal/show/" + empresa_id;
                dataTable("sucursales", urlDataTableSucursal);
               
            } else {
                alert('Error al guardar la Sucursal.');
            }
        })
        .catch(error => {
            console.error('Error:', error.response ? error.response.data : error);
            alert('Hubo un problema al procesar su solicitud.');
        });
}


/**
 * Listado de colaboradores sincronizados
 */
function listarDTColab(data){
    let content_dt = document.getElementById('listados_colab_dt');
    content_dt.innerHTML = ``;
    data.forEach( (item,index) => {
        let rowDt = document.createElement('tr');
        let tds = `
            <td>${index + 1}</td>
            <td>${item.fecha_atencion} ${item.hora_atencion}</td>
            <td>${item.paciente}</td>
            <td>${item.codigo_emp}</td>
            <td>${item.depto_area}</td>
            <td>${item.telefono}</td>
        `;
        rowDt.innerHTML = tds;
        content_dt.appendChild(rowDt);
    })
}