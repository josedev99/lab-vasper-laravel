document.addEventListener('DOMContentLoaded', (event) => {
    try{
        let form_examen_resultado = document.getElementById('form_examen_preingreso');
        if(form_examen_resultado){
            form_examen_resultado.addEventListener('submit', (e)=> {
                e.preventDefault();
                let formData = new FormData();
                //validaciones
                if(array_files_upload.length > 0){
                    let cat_examen = sessionStorage.getItem('cat_examen');
                    let empleado_id = sessionStorage.getItem('emp_id');
                    let jornada_id = sessionStorage.getItem('jornada_id');
                    let examen_id = sessionStorage.getItem('examen_id');

                    formData.append('cat_examen',cat_examen);
                    formData.append('empleado_id',empleado_id);
                    formData.append('jornada_id',jornada_id);
                    formData.append('examen_id',examen_id);
                    
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

                    axios.post(route('examen.result.img.save'),formData,{
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then((result) => {
                        console.log(result);
                        if(result.data.status === "success"){
                            Swal.fire({
                                title: "Éxito",
                                text: result.data.message,
                                icon: "success"
                            });
                            array_files_upload = [];
                            form_examen_resultado.reset();
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
                        console.log(result)
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
    axios.post(route('examen.result.img.obtener'),{empleado_id: empleado_id},{
        headers:{
            'Content-Type': 'multipart/form-data'
        }
    })
    .then((result) => {
        console.log(result);
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