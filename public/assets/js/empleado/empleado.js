
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
                let depto_emp = document.getElementById('depto_emp');
                if (depto_emp.value === "") {
                    Toast.fire({
                        icon: "warning",
                        title: `El campo área/departamento es requerido.`
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
                    console.log(response);
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
        //button edicion
        let table = new DataTable('#dt_listado_empleados');
        table.on('draw.dt', attachEventsDetEmp);
        //Save departamento empleado
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

                let response = await axios.post(route('app.empleados.depto.save'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response.data.status === "success") {
                    Toast.fire({
                        icon: "success",
                        title: response.data.message
                    });
                    let objeOptionSelect = {
                        value: response.data.results.id,
                        text: response.data.results.departamento
                    }
                    /*===== Codigo para agregar departamento desde la modal main ======*/
                    let departamento = $("#depto_emp").selectize()[0].selectize;
                    departamento.addOption(objeOptionSelect);
                    departamento.addItem(response.data.results.id);
                    /*===== Codigo para agregar departamento desde la modal de consulta ======*/
                    let depto_area_select = $("#area_departamento_emp").selectize()[0].selectize;
                    depto_area_select.addOption(objeOptionSelect);
                    depto_area_select.addItem(response.data.results.id);

                    $("#modal_new_departamento").modal('hide');
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
        console.log(result);
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
    axios.post(route('examen.getExamenes'),{empresa_id})
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

function attachEventsDetEmp() {
    let btnOrdenExamen = document.querySelectorAll('.btn-o-examen');
    //button para modal de nuevo orden de perfil
    let btnOrdenPerfil = document.querySelectorAll('.btn-o-perfil');
    //new btn para abril modal de examenes preingreso
    if (btnOrdenExamen && btnOrdenPerfil) {
        //Agregar nueva orden de perfil
        btnOrdenPerfil.forEach((btn) => {
            btn.addEventListener('click', async (e) => {
                e.stopPropagation();
                //save session id empleado
                let empleado_ref = btn.dataset.emp_ref;
                sessionStorage.setItem('emp_id', empleado_ref);

                let empleado = btn.dataset.nombre;
                let empresa = btn.dataset.empresa;
                let sucursal = btn.dataset.sucursal;
                //set html element
                document.getElementById('colaborador_html').innerHTML = `<i class="bi bi-person-circle"></i> <strong>EMPLEADO: </strong>${empleado}`;
                document.getElementById('empresa_html').innerHTML = `<i class="bi bi-building-fill"></i> <strong>EMPRESA: </strong>${empresa}`;
                document.getElementById('sucursal_html').innerHTML = `<i class="bi bi-house-check"></i> <strong>SUCURSAL: </strong>${sucursal}`;
                listar_perfiles_orden();//se declara en perfil_examen.js
                $("#modal_nueva_orden_perfil").modal('show');
            })
        })
    }
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
        getCargosArea(value);
    });
})

function getCargosArea(area_id, callback = null) {
    axios.post(route('area.cargos.obtener'), { area_id })
        .then((result) => {
            if (result.data.length) {
                let select_cargos = $("#cargo_emp").selectize()[0].selectize;
                select_cargos.clear();
                select_cargos.clearOptions();
                let data = result.data;
                data.forEach((item) => {
                    select_cargos.addOption({
                        value: item.id,
                        text: item.nombre
                    })
                })
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
        document.querySelector('input[name="codigo_empleado"]').value = data.codigo_empleado;
        document.querySelector('input[name="nombre_empleado"]').value = data.nombre;
        document.querySelector('input[name="telefono"]').value = data.telefono;
        document.querySelector('select[name="genero_emp"]').value = data.genero;
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
        //datos empresa
        $("#empresa_emp").selectize()[0].selectize.setValue(data.empresa_id);
        getSucursales(data.empresa_id, ()=>{
            $("#sucursal_emp").selectize()[0].selectize.setValue(data.sucursal_id);
        })
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