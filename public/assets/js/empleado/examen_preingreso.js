document.addEventListener('DOMContentLoaded', (event) => {
    try{
        let form_examen_preingreso = document.getElementById('form_examen_preingreso');
        if(form_examen_preingreso){
            form_examen_preingreso.addEventListener('submit', (e)=> {
                e.preventDefault();
                let formData = new FormData();
                //validaciones
                if(array_files_upload.length > 0){
                    let empleado_id = sessionStorage.getItem('emp_id');
                    formData.append('empleado_id',empleado_id);
                    //validacion de elementos a insertar
                    let fileCount = array_files_upload.filter(item => !item.disk);
                    if(fileCount.length === 0){
                        Toast.fire({
                            icon: "warning",
                            title: `No hay examenes nuevos para cargar.`
                        });return;
                    }
                    var indexElementFile = 0;
                    for (let i = 0; i < array_files_upload.length; i++) {
                        //validacion para insercion
                        if(array_files_upload[i].disk === false){
                            formData.append('files[]', array_files_upload[i]);
                            formData.append(`examen_${indexElementFile}`, array_files_upload[i].examen);
                            formData.append(`resultado_${indexElementFile}`, array_files_upload[i].resultado);
                            formData.append(`typeFile_${indexElementFile}`, array_files_upload[i].typeFile);
                            formData.append(`disk_${indexElementFile}`, array_files_upload[i].disk);
                            indexElementFile += 1;
                        }
                    }

                    axios.post(route('examen.preingreso.save'),formData,{
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then((result) => {
                        if(result.data.status === "success"){
                            Swal.fire({
                                title: "Éxito",
                                text: result.data.message,
                                icon: "success"
                            });
                            array_files_upload = [];
                            form_examen_preingreso.reset();
                            sessionStorage.removeItem('emp_id');
                            $("#modal_examen_preingreso").modal('hide');
                        }else if(result.data.status === "warning"){
                            Swal.fire({
                                title: "Aviso",
                                text: result.data.message,
                                icon: "warning"
                            });
                        }else {
                            Swal.fire({
                                title: "Error",
                                text: result.data.message,
                                icon: "error"
                            });
                        }
                    }).catch((err) => {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error inesperado, intente nuevamente.',
                            icon: "error"
                        });
                        console.log(err);
                    });
                }else{
                    Swal.fire({
                        title: "Aviso",
                        text: 'No existen examenes cargados.',
                        icon: 'warning'
                    });
                }
            })
        }
    }catch(err){
        console.log(err)
    }
})
    //GUARDAD DE POSINCAPACIDAD

document.addEventListener('DOMContentLoaded', (event) => {
    try{
        let form_examen_posincapacidad = document.getElementById('form_examen_posincapacidad');
        if(form_examen_posincapacidad){
            form_examen_posincapacidad.addEventListener('submit', (e)=> {
                e.preventDefault();
                let formData = new FormData();
                //validaciones
                if(array_files_upload.length > 0){
                    let empleado_id = sessionStorage.getItem('emp_id');
                    formData.append('empleado_id',empleado_id);
                    //validacion de elementos a insertar
                    let fileCount = array_files_upload.filter(item => !item.disk);
                    if(fileCount.length === 0){
                        Toast.fire({
                            icon: "warning",
                            title: `No hay examenes nuevos para cargar.`
                        });return;
                    }
                    var indexElementFile = 0;
                    for (let i = 0; i < array_files_upload.length; i++) {
                        //validacion para insercion
                        if(array_files_upload[i].disk === false){
                            formData.append('files[]', array_files_upload[i]);
                            formData.append(`examen_${indexElementFile}`, array_files_upload[i].nombre_examen);
                            formData.append(`resultado_${indexElementFile}`, array_files_upload[i].resultado_examen);
                            formData.append(`typeFile_${indexElementFile}`, array_files_upload[i].typeFile);
                            formData.append(`disk_${indexElementFile}`, array_files_upload[i].disk);
                            indexElementFile += 1;
                        }
                    }

                    axios.post(route('examen.pposincapacidad.save'),formData,{
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then((result) => {
                        if(result.data.status === "success"){
                            Swal.fire({
                                title: "Éxito",
                                text: result.data.message,
                                icon: "success"
                            });
                            array_files_upload = [];
                            form_examen_posincapacidad.reset();
                            sessionStorage.removeItem('emp_id');
                            $("#modal_examen_posinca").modal('hide');
                        }else if(result.data.status === "warning"){
                            Swal.fire({
                                title: "Aviso",
                                text: result.data.message,
                                icon: "warning"
                            });
                        }else {
                            Swal.fire({
                                title: "Error",
                                text: result.data.message,
                                icon: "error"
                            });
                        }
                    }).catch((err) => {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error inesperado, intente nuevamente.',
                            icon: "error"
                        });
                        console.log(err);
                    });
                }else{
                    Swal.fire({
                        title: "Aviso",
                        text: 'No existen examenes cargados.',
                        icon: 'warning'
                    });
                }
            })
        }
    }catch(err){
        console.log(err)
    }
})

function get_examenes_preingreso(empleado_id){
    axios.post(route('examen.preingreso.obtener'),{empleado_id: empleado_id},{
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
    .then((result) => {
        if(result.data.length > 0){
            let dbFiles = result.data;
            dbFiles.forEach(element => {
                array_files_upload.push(element);
            });
            cargarImagen(array_files_upload, 'imagesPreview');
            cargarFilesPDF(array_files_upload, 'items_files_preview');
        }else{

        }
    }).catch((err) => {
        console.log(err)
    });
}



function get_examenes_posinca(empleado_id){
    axios.post(route('examen.postinca.obtener'),{empleado_id: empleado_id},{
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
    .then((result) => {
        if(result.data.length > 0){
            let dbFiles = result.data;
            dbFiles.forEach(element => {
                array_files_upload.push(element);
            });
            cargarImagen2(array_files_upload, 'imagesPreviewInca');
            cargarFilesPDFInca(array_files_upload, 'items_files_previewInca');
        }else{

        }
    }).catch((err) => {
        console.log(err)
    });
}