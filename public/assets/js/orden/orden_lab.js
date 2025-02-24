
document.addEventListener('DOMContentLoaded', main)

function main(){
    try{
        let form_orden_lab = document.getElementById('form_orden_lab');
        if(form_orden_lab){
            form_orden_lab.addEventListener('submit', (e) => {
                e.preventDefault();
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
                //validar jornada
                let jornada_orden = $("#jornada_orden").selectize()[0].selectize.getValue();
                if(jornada_orden === ""){
                    Swal.fire({
                        title: "Aviso",
                        text: 'La jornada es obligatorio.',
                        icon: "warning"
                    });return;
                }
                let formData = new FormData(form_orden_lab);
                let emp_id = sessionStorage.getItem('emp_id') !== null ? sessionStorage.getItem('emp_id') : 0;
                formData.append('emp_id',emp_id);
                formData.append('data_examenes',JSON.stringify(examenesFiltrados));
                //button disabled
                document.querySelector('.btnSaveOrden').disabled = true;
                axios.post(route('orden.lab.save'),formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                }).then((result) => {
                    if(result.data.status === "success"){
                        $("#modal_nueva_orden_examen").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                          });
                        displayExamenesSelected();
                        $("#jornada_orden").selectize()[0].selectize.clear();
                        generar_orden_pdf(result.data.results); //Generar el pdf de orden
                        form_orden_lab.reset();
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
                    document.querySelector('.btnSaveOrden').disabled = false;
                }).catch((err) => {
                    console.log(err);
                    document.querySelector('.btnSaveOrden').disabled = false;
                });
            })
        }
        //Formulario para guardar orden de lab perfil
        let form_orden_lab_perfil = document.getElementById('form_orden_lab_perfil');
        if(form_orden_lab_perfil){
            form_orden_lab_perfil.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('emit...');
            })
        }
        //Agregar jornada para orden de examen
        let btnAddJornada = document.getElementById('btnAddJornada');
        if(btnAddJornada){
            btnAddJornada.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('form-jornada-orden').reset();
                $("#modal-new-jornada").modal('show');
            })
        }
    }catch(err){
        console.log(err);
    }
}

function generar_orden_pdf(data){
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let form = document.createElement('form');
    form.action = route('orden.examenes.pdf');
    form.method = "POST";
    form.target = '_blank';
    let input_csrf = document.createElement('input');
    input_csrf.name = "_token";
    input_csrf.value = token_csrf;
    form.appendChild(input_csrf);

    let inputData = document.createElement('input');
    inputData.name = 'data';
    inputData.value = JSON.stringify(data);
    form.appendChild(inputData);
    document.body.appendChild(form);
    form.submit();
    form.remove();
}

document.addEventListener('DOMContentLoaded', (event)=> {
    let form_jornada_orden = document.getElementById('form-jornada-orden');
    if(form_jornada_orden){
        form_jornada_orden.addEventListener('submit', procesarFormJornada);
    }
})

function procesarFormJornada(event){
    event.preventDefault();
    let formData = new FormData(event.target);
    //validaciones
    if(formData.get('jornada').trim() === ""){
        Swal.fire({
            title: "Aviso",
            text: 'El nombre de la jornada es obligatorio.',
            icon: "warning"
        });return;
    }
    if(formData.get('fecha_jornada') === ""){
        Swal.fire({
            title: "Aviso",
            text: 'La fecha de la jornada es obligatorio.',
            icon: "warning"
        });return;
    }

    let btnSaveJornada = document.getElementById('btnSaveJornadaOrden');
    btnSaveJornada.disabled = true;
    btnSaveJornada.innerHTML = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> registrando...`;

    let empresa_id = sessionStorage.getItem('paciente_empresa_id');
    formData.append('empresa_id', empresa_id);

    axios.post(route('jornada.save'), formData)
    .then((result) => {
        if(result.data.status === "success"){
            sessionStorage.removeItem('paciente_empresa_id');
            Swal.fire({
                title: "Éxito",
                text: result.data.message,
                icon: "success"
              });
              let {id, nombre, fecha_jornada} = result.data.result;
              let jornada_orden = $("#jornada_orden").selectize()[0].selectize;
              jornada_orden.addOption({
                value: id,
                text: `${fecha_jornada} ${nombre}`
              });
              jornada_orden.setValue(id);
            $("#modal-new-jornada").modal('hide');
        }else{
            Swal.fire({
                title: "Error",
                text: result.data.message,
                icon: "error"
              });
        }
        btnSaveJornada.disabled = false;
        btnSaveJornada.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
    }).catch((err) => {
        btnSaveJornada.disabled = false;
        btnSaveJornada.innerHTML = `<i class="bi bi-floppy"></i> Registrar`;
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error, intente nuevamente.',
            icon: "error"
          });
    });
}