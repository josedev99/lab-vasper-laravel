document.addEventListener('DOMContentLoaded', () => {
    dataTable("dt_listado_clientes", route('cliente.listar'), {});
    //init loaded js
    flatpickr("#fecha_nac", {
        locale: "es",
        maxDate: "today",
        minDate: "1910",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });
    //form data orden
    const form_data_orden = document.getElementById('form_data_orden');
    //button
    let btnModalCliente = document.getElementById('btnModalCliente');
    if (btnModalCliente) {
        btnModalCliente.addEventListener('click', (e) => {
            e.stopPropagation();
            form_data_orden.reset();
            document.getElementById('btnSaveCliente').innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
            let list_items_examenes = document.getElementById('list-items-examenes');
            list_items_examenes.innerHTML = `<p class="m-0 p-0 text-danger">Categoria no seleccionada.</p>`;
            axios.post(route('examen.getExamenes'))
            .then((response) => {
                if (response.data.length > 0) {
                    array_data_examenes = response.data;
                    displayExamenesSelected();
                    list_categorias(array_data_examenes);
                }
                $("#modal-form-cliente").modal('show');
            }).catch((err) => {
                console.log(err);
            });
        })
    }
    //validacion de form-data-orden
    if (form_data_orden) {
        form_data_orden.addEventListener('submit', async (e) => {
            e.preventDefault();
            let formData = new FormData(form_data_orden);
            //validacion de inputs
            let inputs = document.querySelectorAll('.validInput');
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
            const examenesFiltrados = array_data_examenes.reduce((acc, categoria) => {
                const examenesChecked = categoria.examenes.filter(examen => examen.check_examen === true);
                return acc.concat(examenesChecked);
            }, []);
            //Validate count array
            if(examenesFiltrados.length === 0){
                Swal.fire({
                    title: "Aviso",
                    text: 'Para registrar esta orden, debe agregar por lo menos un examen.',
                    icon: "warning"
                });return;
            }

            let btnSaveOrden = document.getElementById('btnSaveCliente');
            btnSaveOrden.disabled = true;
            btnSaveOrden.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> procesando...`;

            formData.append('data_examenes',JSON.stringify(examenesFiltrados));

            axios.post(route('cliente.save.orden'), formData)
            .then((response) => {
                if (response.data.status === "success") {
                    let cliente = formData.get('nombre_empleado');
                    Swal.fire({
                        title: "¿Reimprimir boleta de indicaciones?",
                        text: `PACIENTE: ${cliente}`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, reimprimir",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            imp_boleta_examen_pdf(response.data.results.id); //Generar el pdf de orden
                        }else{
                            Swal.fire({
                                title: "Éxito",
                                text: response.data.message,
                                icon: "success"
                            });
                        }
                    });
                    array_data_examenes = [];
                    $("#modal-form-cliente").modal('hide');
                    form_data_orden.reset();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.data.message,
                        icon: "error"
                    });
                }
                $("#dt_listado_clientes").DataTable().ajax.reload(null, false);

                let btnSaveOrden = document.getElementById('btnSaveCliente');
                btnSaveOrden.disabled = false;
                btnSaveOrden.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
            }).catch((err) => {
                console.log(err);
                let btnSaveOrden = document.getElementById('btnSaveCliente');
                btnSaveOrden.disabled = false;
                btnSaveOrden.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
                Swal.fire({
                    title: "Aviso",
                    text: "Ocurrió un error al procesar la solicitud. Por favor, verifica la información ingresada.",
                    icon: "warning"
                });
            });
        });
    }
})

//function para inhabilitar empleado
function toggleEmpleadoStatus(element) {
    let empleado_id = element.dataset.empleado_id;
    let status = element.dataset.status;
    let title = element.title;
    Swal.fire({
        title: `${title}?`,
        text: "Accion para cambiar el estado del colaborador.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('app.empleado.toggleStatus'), {
                empleado_id: empleado_id,
                status: status
            }).then((result) => {
                if (result.data.status === 'success') {
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    $("#dt_listado_empleados").DataTable().ajax.reload();
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

function editEmpleado(element) {
    let empleado_id = element.dataset.ref;
    sessionStorage.setItem('empleado_id', empleado_id);//save

    axios.post(route('app.empleados.getEmpleado'), { ref_emp: empleado_id }, {
        headers: {
            'Content-type': 'multipart/form-data',
            'Content-Encoding': 'gzip'
        }
    }).then((response) => {
        let data = response.data;

        document.querySelector('select[name="categoria_empleado"]').value = data.categoria;
        document.querySelector('input[name="codigo_empleado"]').value = data.codigo_empleado;
        document.querySelector('input[name="nombre_empleado"]').value = data.nombre;
        document.querySelector('input[name="telefono"]').value = data.telefono;
        document.querySelector('select[name="genero_emp"]').value = data.genero;
        document.querySelector('input[name="fecha_ing_emp"]').value = data.fecha_ingreso;
        document.querySelector('input[name="fecha_nac_emp"]').value = data.fecha_nacimiento;

        $("#depto_emp").selectize()[0].selectize.setValue(data.area_depto_id);
        let select_cargos = $("#cargo_emp").selectize()[0].selectize;
        if (data.cargo_id !== null) {
            getCargosArea(data.area_depto_id, () => {
                select_cargos.setValue(data.cargo_id);
            })
        } else {
            select_cargos.clear();
        }
        document.querySelector('select[name="sucursal_emp"]').value = data.sucursal_id;
        //textContente btn save
        document.querySelector('.btn-save-emp').innerHTML = `<i class="bi bi-floppy"></i> Actualizar`;
        $("#modal_new_empleado").modal('show');
    }).catch(err => {
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error al momento de cargar la información.',
            icon: "error"
        });
        console.log(err)

    });
}

function destroyEmp(element) {
    let empleado_id = element.dataset.ref;
    Swal.fire({
        title: "¿Eliminar empleado?",
        text: "Esta acción es irreversible.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(async (result) => {
        if (result.isConfirmed) {
            let response = await axios.post(route('app.empleados.destroy'), { empleado_id }, {
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
                    $("#dt_listado_empleados").DataTable().ajax.reload(null, false);
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
}

function imp_boleta_examen_pdf(id){
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let form = document.createElement('form');
    form.action = route('cliente.boleta.examenes');
    form.method = "POST";
    form.target = '_blank';
    let input_csrf = document.createElement('input');
    input_csrf.name = "_token";
    input_csrf.value = token_csrf;
    form.appendChild(input_csrf);

    let inputID = document.createElement('input');
    inputID.name = 'id';
    inputID.value = id;
    form.appendChild(inputID);
    document.body.appendChild(form);
    form.submit();
    form.remove();
}