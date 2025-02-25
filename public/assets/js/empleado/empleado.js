
document.addEventListener('DOMContentLoaded', () => {
    dataTable("dt_listado_empleados", route('app.empleados.dt'), {});
    //init loaded js
    //Flatpicker
    flatpickr(".fecha_nac_emp", {
        locale: "es",
        maxDate: "today",
        minDate: "1910",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });
    //obtener las sucursales por empresa
    let empresa_emp = $("#empresa_emp").selectize()[0].selectize;
    empresa_emp.on('change',function(value){
        getSucursales(value);
        //get departamentos
        getDeptosEmpresa(value);
    })
    try {
        //form data empleado
        let form_data_emp = document.getElementById('form_data_emp');
        //button

        let btn_new_empleado = document.querySelector('.btn_new_empleado');
        if (btn_new_empleado) {
            btn_new_empleado.addEventListener('click', (e) => {
                e.stopPropagation();
                form_data_emp.reset();
                sessionStorage.removeItem('empleado_id');//destroy empleado_id
                //modify textContent
                $("#depto_emp").selectize()[0].selectize.clear();
                $("#cargo_emp").selectize()[0].selectize.clearOptions();

                $("#empresa_emp").selectize()[0].selectize.clear();
                $("#sucursal_emp").selectize()[0].selectize.clearOptions();

                document.querySelector('.btn-save-emp').innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
                $("#modal_new_empleado").modal('show');
            })
        }
        if (form_data_emp) {
            form_data_emp.addEventListener('submit', async (e) => {
                e.preventDefault();
                let formData = new FormData(form_data_emp);
                let empleado_id = sessionStorage.getItem('empleado_id') === null ? '' : sessionStorage.getItem('empleado_id');
                formData.append('empleado_id', empleado_id);
                //validacion de inputs
                let inputs = document.querySelectorAll('.validInputEmpleado');
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
                let depto_area = formData.get('depto_emp');
                if (depto_area === "") {
                    Toast.fire({
                        icon: "warning",
                        title: `El campo área/departamento es requerido.`
                    });
                    return;
                }
                let cargo_emp = formData.get('cargo_emp');
                if (cargo_emp === "") {
                    Toast.fire({
                        icon: "warning",
                        title: `El cargo del colaborador es requerido.`
                    });
                    return;
                }
                //validacion de empresa y sucursal
                let empresa_emp = formData.get('empresa_emp');
                let sucursal_emp = formData.get('sucursal_emp');
                if(empresa_emp.trim() === ""){
                    Swal.fire({
                        title: "Aviso",
                        text: 'Seleccione una empresa para continuar.',
                        icon: "warning"
                    });
                    return;
                }
                if(sucursal_emp.trim() === ""){
                    Swal.fire({
                        title: "Aviso",
                        text: 'Seleccione una sucursal para continuar.',
                        icon: "warning"
                    });
                    return;
                }
                let btnProcesarEmp = document.querySelector('.btn-save-emp');
                btnProcesarEmp.disabled = true;
                btnProcesarEmp.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> procesando...`;
                axios.post(route('app.empleados.save'), formData)
                .then((response) => {
                    form_data_emp.reset();
                    sessionStorage.removeItem('empleado_id');//destroy empleado_id

                    $("#modal_new_empleado").modal('hide');
                    if (response.data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: response.data.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.data.message,
                            icon: "error"
                        });
                    }
                    btnProcesarEmp.disabled = false;
                    btnProcesarEmp.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
                    $("#dt_listado_empleados").DataTable().ajax.reload(null, false);
                }).catch((err) => {
                    console.log(err);
                    Swal.fire({
                        title: "Aviso",
                        text: "Ocurrió un error al procesar la solicitud. Por favor, verifica la información ingresada.",
                        icon: "warning"
                    });
                    btnProcesarEmp.disabled = false;
                    btnProcesarEmp.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
                });
            });
        }
    } catch (err) {
        if (err.status === 500) {
            Swal.fire({
                title: "Error",
                text: 'Ha ocurrido un error al proccesar esta solicitud.',
                icon: "error"
            });
        } else if (err.status === 422) {
            Swal.fire({
                title: "Error",
                text: 'Por favor, verificar si la información ingresada es correcta.',
                icon: "error"
            });
        }
    }
})

function getDeptosEmpresa(empresa_id, done = null){
    axios.post(route('empresa.deptos.obtener'),{empresa_id})
    .then((result) => {
        let data = result.data;
        let depto_emp = $("#depto_emp").selectize()[0].selectize;
        depto_emp.clear();
        depto_emp.clearOptions();
        data.forEach((depto) => {
            depto_emp.addOption({
                value: depto.id,
                text: depto.area
            });
        });
        if(done && typeof done === "function"){
            done();
        }
    }).catch((err) => {
        console.log(err);
    });
}

function getSucursales(empresa_id, done = null){
    axios.post(route('empresa.sucursales.obtener'),{empresa_id})
    .then((result) => {
        let data = result.data;
        let sucursal_emp = $("#sucursal_emp").selectize()[0].selectize;
        sucursal_emp.clear();
        sucursal_emp.clearOptions();
        if(data.length > 0){
            data.forEach((sucursal) => {
                sucursal_emp.addOption({
                    value: sucursal.id,
                    text: sucursal.nombre
                });
            });
            if (data.length === 1) {
                sucursal_emp.setValue(data[0].id);
            }

            if(done !== null && typeof done === "function"){
                done();
            }
        }
    }).catch((err) => {
        console.log(err);
    });
}

function getJornadasByEmpresa(empresa_id){
    sessionStorage.setItem('paciente_empresa_id',empresa_id);
    axios.post(route('empresa.jornadas'),{empresa_id, filtrar_fecha: false})
    .then((result) => {
        let data = result.data;
        let jornada_orden = $("#jornada_orden")[0].selectize;
        jornada_orden.clear();
        jornada_orden.clearOptions();
        if(data.length > 0){
            data.forEach((item)=>{
                jornada_orden.addOption({
                    value: item.id,
                    text: item.nombre
                });
            })
        }
    }).catch((err) => {
        console.log(err);
    });
}
function newOrdenExamenes(event){
    let btn = event;
    //save session id empleado
    let empleado_ref = btn.dataset.empleado_id;
    let empresa_id = btn.dataset.empresa_id;
    sessionStorage.setItem('emp_id', empleado_ref);

    //ocultar button para nueva orden
    let btnAddExamenHide = document.querySelector('.btnAddExamen');
    btnAddExamenHide.style.display = 'none';
    document.getElementById('contents_inputs_jornada').style.display = 'block';
    document.querySelector('.btnSaveOrden').style.display = 'block';
    //add selectize
    $("#jornada_orden").selectize();

    let empleado = btn.dataset.nombre;
    let empresa = btn.dataset.empresa;
    let sucursal = btn.dataset.sucursal;
    //set html element
    document.getElementById('nombre_empleado_html').innerHTML = `<i class="bi bi-person-circle"></i> <strong>EMPLEADO: </strong>${empleado}`;
    document.getElementById('nombre_empresa_html').innerHTML = `<i class="bi bi-building-fill"></i> <strong>EMPRESA: </strong>${empresa}`;
    document.getElementById('nombre_sucursal_html').innerHTML = `<i class="bi bi-house-check"></i> <strong>SUCURSAL: </strong>${sucursal}`;
    //Clear display list examenes
    let list_items_examenes = document.getElementById('list-items-examenes');
    list_items_examenes.innerHTML = `<p class="m-0 p-0 text-danger">Categoria no seleccionada.</p>`;
    getJornadasByEmpresa(empresa_id);
    axios.post(route('examen.getExamenes'))
    .then((response) => {
        if(response.status === 200){
            array_data_examenes = response.data;
            displayExamenesSelected();
            list_categorias(array_data_examenes);
            $("#modal_nueva_orden_examen").modal('show');
        }
    }).catch((err) => {
        console.log(err);
    });
}

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

/**
 * FUncionalidad de depto
 */
document.addEventListener('DOMContentLoaded', () => {
    //selectize
    let $select = $('#depto_emp').selectize();

    $select[0].selectize.on('change', function (value) {
        let empresa_id = $("#empresa_emp").selectize()[0].selectize.getValue();
        getCargosArea(value,empresa_id);
    });
})

function getCargosArea(area_id, empresa_id, callback = null) {
    axios.post(route('area.cargos.obtener'), { area_id, empresa_id })
        .then((result) => {
            let select_cargos = $("#cargo_emp").selectize()[0].selectize;
            select_cargos.clear();
            select_cargos.clearOptions();
            if (result.data.length) {
                let data = result.data;
                data.forEach((item) => {
                    select_cargos.addOption({
                        value: item.id,
                        text: item.nombre
                    })
                })
                if(result.data.length === 1){
                    select_cargos.setValue(data[0].id);
                }
                if (callback && typeof callback === "function") {
                    callback();
                }
            }
        }).catch((err) => {
            console.log(err)
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
        console.log(data);
        document.querySelector('input[name="codigo_empleado"]').value = data.codigo_empleado;
        document.querySelector('input[name="nombre_empleado"]').value = data.nombre;
        document.querySelector('input[name="telefono"]').value = data.telefono;
        document.querySelector('select[name="genero_emp"]').value = data.genero;
        document.querySelector('input[name="fecha_nac_emp"]').value = data.fecha_nacimiento;
        //datos empresa
        $("#empresa_emp").selectize()[0].selectize.setValue(data.empresa_id);
        getSucursales(data.empresa_id, ()=>{
            $("#sucursal_emp").selectize()[0].selectize.setValue(data.sucursal_id);
        })
        getDeptosEmpresa(data.empresa_id, ()=>{
            $("#depto_emp").selectize()[0].selectize.setValue(data.area_depto_id);
            let select_cargos = $("#cargo_emp").selectize()[0].selectize;
            if (data.cargo_id !== '') {
                getCargosArea(data.area_depto_id, data.empresa_id, () => {
                    select_cargos.setValue(data.cargo_id);
                })
            } else {
                select_cargos.clear();
            }
        });

        //textContente btn save
        document.querySelector('.btn-save-emp').innerHTML = `<i class="bi bi-floppy"></i> Actualizar`;
        $("#modal_new_empleado").modal('show');
    }).catch(err => {
        console.log(err)
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error al momento de cargar la información.',
            icon: "error"
        });

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