var array_files_upload = [];
let compressedImageBlob;
let resizingFactor = 0.8;
let quality = 0.8;

document.addEventListener('DOMContentLoaded', () => {
    loadImage();
    loadImageInca();

});

function readFileAsDataURL(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => resolve(e.target.result);
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
    });
}

function loadImage() {

    try {
        let loadImgFilePDF = document.querySelector('.loadImgFilePDF');
        let inputFile = document.getElementById('upload_file_input');
        if (loadImgFilePDF) {
            loadImgFilePDF.addEventListener('click', (event) => {
                inputFile.click();
            })
        }
        //cargar imagen
        inputFile.addEventListener('change', function (event) {
            let file = event.target.files[0];
            if (file.type.startsWith('image/')) {
                readFileAsDataURL(file).then((dataImg) => {
                    alertResultExamen(event,dataImg);
                }).catch((error) => {
                    console.error("Error leyendo archivo", error);
                });
            }else{
                alertResultExamen(event);
            }
        });
    } catch (err) {
        console.log(err);
    }
}

function alertResultExamen(event,dataImg = ''){
    Swal.fire({
        title: "",
        icon: "info",
        html: `
            <style>
                .swal2-icon-show{
                    display: none !important;
                }
            </style>
            <div class="card p-1" style="margin-top: 20px;">
                <img src="${dataImg}" alt="" width="450">
            </div>
            <div class="card my-3">
                <div class="card-header p-1">RESULTADOS</div>
                <div class="card-body p-1">                            
                    <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                        <div class="content-input">
                            <input id="examen" name="examen" type="text"
                                class="custom-input material" value="" placeholder=" " style="text-transform:uppercase">
                            <label class="input-label" for="examen">Examen</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="checkbox icheck-turquoise d-inline">
                            <input type="radio" name="checkResultado" id="checkResult1" value="BUENO">
                            <label for="checkResult1" style="font-size: 13px">BUENO</label>
                        </div>
                        <div class="checkbox icheck-warning d-inline">
                            <input type="radio" name="checkResultado" id="checkResult2" value="REGULAR">
                            <label for="checkResult2" style="font-size: 13px">REGULAR</label>
                        </div>
                        <div class="checkbox icheck-danger d-inline">
                            <input type="radio" name="checkResultado" id="checkResult3" value="MALO">
                            <label for="checkResult3" style="font-size: 13px">MALO</label>
                        </div>                            
                    </div>
                </div>
            <div>
        `,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: `
          <i class="fa fa-thumbs-up"></i> Guardar!
        `,
        confirmButtonAriaLabel: "Guardar",
        cancelButtonText: `
          <i class="fa fa-thumbs-down"></i> Cancelar
        `,
        preConfirm: () => {
            // Obtener valores de los campos
            const examen = document.getElementById('examen').value;
            const resultado = document.querySelector(`input[name='checkResultado']:checked`);

            // Validar que ambos campos estén llenos
            if (!examen || !resultado) {
                Swal.showValidationMessage('Por favor, llena todos los campos.');
                return false;
            }
            return {
                examen: examen,
                resultado: resultado.value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let examen = result.value.examen;
            let resultado = result.value.resultado;
            const file = event.target.files[0];
            const allowed_EXT = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
            //new atributos
            file.examen = examen.toUpperCase();
            file.resultado = resultado;
            file.disk = false;

            if (!allowed_EXT.exec(file.name)) {
                Swal.fire({
                    title: "Aviso",
                    text: 'El archivo seleccionado no es soportado.',
                    icon: 'warning'
                });
                return;
            }
            //validar el tipo de archivo
            if (file.type.startsWith('image/')) {
                file.typeFile = 'image';
            } else if (file.type.startsWith('application/pdf')) {
                file.typeFile = 'pdf';
            }

            array_files_upload.push(file);
            cargarImagen(array_files_upload, 'imagesPreview');
            cargarFilesPDF(array_files_upload, 'items_files_preview');
        }
    }).catch((err) => {
        console.log(err);
    });
}
function loadImageInca() {
    try {
        let loadImgFilePDFInca = document.querySelector('.loadImgFilePDFInca');
        let inputFileInca = document.getElementById('upload_file_input_inca');
        if (loadImgFilePDFInca) {
            loadImgFilePDFInca.addEventListener('click', (event) => {
                inputFileInca.click();
            })
        }
        //cargar imagen
        inputFileInca.addEventListener('change', function (event) {
            let file = event.target.files[0];
            if (file.type.startsWith('image/')) {
                readFileAsDataURL(file).then((dataImg) => {
                    alertResultExamenIncap(event,dataImg);
                }).catch((error) => {
                    console.error("Error leyendo archivo", error);
                });
            }else{
                alertResultExamenIncap(event);
            }
        });

    } catch (err) {
        console.log(err);
    }
}
function alertResultExamenIncap(event,dataImg = ''){
    Swal.fire({
        title: "",
        icon: "info",
        html: `
            <style>
                .swal2-icon-show{
                    display: none !important;
                }
            </style>
            <div class="card p-1" style="margin-top: 20px;">
                <img src="${dataImg}" alt="" width="450">
            </div>
            <div class="card my-3">
                <div class="card-header p-1">RESULTADOS</div>
                <div class="card-body p-1">                            
                    <div class="col-sm-12 col-md-12 col-lg-12 my-2">
                        <div class="content-input">
                            <input id="nombre_examen" name="nombre_examen" type="text"
                                class="custom-input material" value="" placeholder=" " style="text-transform:uppercase">
                            <label class="input-label" for="nombre_examen">Nombre del Examen</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="checkbox icheck-turquoise d-inline">
                            <input type="radio" name="resultado_examen" id="resultado_examen1" value="BUENO">
                            <label for="resultado_examen1" style="font-size: 13px">BUENO</label>
                        </div>
                        <div class="checkbox icheck-warning d-inline">
                            <input type="radio" name="resultado_examen" id="resultado_examen2" value="REGULAR">
                            <label for="resultado_examen2" style="font-size: 13px">REGULAR</label>
                        </div>
                        <div class="checkbox icheck-danger d-inline">
                            <input type="radio" name="resultado_examen" id="resultado_examen3" value="MALO">
                            <label for="resultado_examen3" style="font-size: 13px">MALO</label>
                        </div>                            
                    </div>
                </div>
            <div>
            `,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: `
            <i class="fa fa-thumbs-up"></i> Guardar!
            `,
                    confirmButtonAriaLabel: "Guardar",
                    cancelButtonText: `
            <i class="fa fa-thumbs-down"></i> Cancelar
            `,
        preConfirm: () => {
            // Obtener valores de los campos
            const nombre_examen = document.getElementById('nombre_examen').value;
            const resultado_examen = document.querySelector(`input[name='resultado_examen']:checked`);
            // Validar que ambos campos estén llenos
            if (!nombre_examen || !resultado_examen) {
                Swal.showValidationMessage('Por favor, llena todos los campos.');
                return false;
            }
            return {
                nombre_examen: nombre_examen,
                resultado_examen: resultado_examen.value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let nombre_examen = result.value.nombre_examen;
            let resultado_examen = result.value.resultado_examen;

            const file = event.target.files[0];
            const allowed_EXT = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
            //new atributos
            file.nombre_examen = nombre_examen.toUpperCase();
            file.resultado_examen = resultado_examen;
            file.disk = false;

            if (!allowed_EXT.exec(file.name)) {
                Swal.fire({
                    title: "Aviso",
                    text: 'El archivo seleccionado no es soportado.',
                    icon: 'warning'
                });
                return;
            }
            //validar el tipo de archivo
            if (file.type.startsWith('image/')) {
                file.typeFile = 'image';
            } else if (file.type.startsWith('application/pdf')) {
                file.typeFile = 'pdf';
            }

            array_files_upload.push(file);
            cargarImagen2(array_files_upload, 'imagesPreviewInca');
            cargarFilesPDFInca(array_files_upload, 'items_files_previewInca');
        }
    }).catch((err) => {
        console.log(err);
    });
}
function cargarImagen(array_images, id_html) {
    let images_html = ``;
    let imagePreview = document.getElementById(id_html);
    imagePreview.innerHTML = '';
    //validaciones
    let data = array_images.filter(item => item.typeFile === "image");
    if (data.length > 0) {
        for (let index = 0; index < array_images.length; index++) {
            let file = array_images[index];
            //comprobar si es de disco o temporal
            if (file.disk === true && file.typeFile === "image") {
                let color_resultado = getColorString(file.resultado, 'bg');
                images_html += `
                <div class="imagen_preview_display">
                    <div class="image_details">
                        <span class="badge element" style="background: #e7f3e9;color: #020202;">
                            <i class="bi bi-check-circle me-1" style="font-size:10px"></i>EXAMEN: ${file.examen}
                        </span>
                        <span class="badge ${color_resultado} element">
                            <i class="bi bi-check-circle me-1" style="font-size:10px"></i>RESULTADO: ${file.resultado}
                        </span>
                    </div>
                    <span title="Eliminar archivo" onclick="removeItemFileDisk(this)" data-id="${file.id}" class="close__floating"><i class="bi bi-x-lg"></i></span>
                    <div style="width: 100%;height: 100%;">
                        <img src="${file.content}" onclick="fullScreenImage(this)" alt="EXAMEN: ${file.examen} - RESULTADO: ${file.resultado}" width="250" style="cursor:pointer;border-radius: 5px;cursor: pointer;transition: 0.3s;height:inherit">
                    </div>
                </div>
                `;
                imagePreview.innerHTML = images_html;
            } else if (file && file.disk === false && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let color_resultado = getColorString(file.resultado, 'bg');

                    images_html += `
                            <div class="imagen_preview_display">
                                <div class="image_details">
                                    <span class="badge element" style="background: #e7f3e9;color: #020202;">
                                        <i class="bi bi-check-circle me-1" style="font-size:10px"></i>EXAMEN: ${file.examen}
                                    </span>
                                    <span class="badge ${color_resultado} element">
                                        <i class="bi bi-check-circle me-1" style="font-size:10px"></i>RESULTADO: ${file.resultado}
                                    </span>
                                </div>
                                <span title="Eliminar archivo" onclick="removeItemImg(this)" data-id="${file.lastModified}" class="close__floating"><i class="bi bi-x-lg"></i></span>
                                <div style="width: 100%;height: 100%;">
                                    <img src="${e.target.result}" onclick="fullScreenImage(this)" alt="EXAMEN: ${file.examen} - RESULTADO: ${file.resultado}" width="250" style="cursor:pointer;border-radius: 5px;cursor: pointer;transition: 0.3s;height:inherit">
                                </div>
                            </div>
                            `;
                    imagePreview.innerHTML = images_html;
                };
                reader.readAsDataURL(file);
            }
        }
    } else {
        imagePreview.innerHTML = '<p class="m-0">No hay archivos cargados.</p>'
    }
}

function cargarFilesPDF(array_files, id_element) {
    let items_files_html = document.getElementById(id_element);
    items_files_html.innerHTML = '';
    //validaciones
    let data = array_files.filter(item => item.typeFile === "pdf");
    if (data.length > 0) {
        //creamos el elemento para guardar los archivos
        let element_ul = document.createElement('ul');
        element_ul.classList.add('list-group', 'list-group-flush');
        for (let index = 0; index < array_files.length; index++) {
            let file = array_files[index];
            //validacion segun carga de archivos
            if (file.disk === true && file.typeFile === "pdf") {
                let color_resultado = getColorString(file.resultado, 'text');

                element_ul.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center p-1 ${color_resultado}" style="font-size:14px">${file.examen}.pdf <div><i data-content="${file.content}" onclick="openPDF(this)" title="Vista previa de ${file.examen}.pdf" class="bi bi-eye text-success mx-2" style="font-size: 22px;cursor: pointer;"></i><i data-id="${file.id}" onclick="removeItemFileDisk(this)" title="Eliminar pdf" class="bi bi-x-circle text-danger" style="font-size: 20px;cursor: pointer"></i></div></li>`;
                items_files_html.appendChild(element_ul);
            } else if (file && file.disk === false && file.type.startsWith('application/pdf')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let color_resultado = getColorString(file.resultado, 'text');

                    element_ul.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center p-1 ${color_resultado}" style="font-size:14px">${array_files[index].examen}.pdf <div><i data-content="${e.target.result}" onclick="openPDF(this)" title="Vista previa de ${array_files[index].examen}.pdf" class="bi bi-eye text-success mx-2" style="font-size: 22px;cursor: pointer;"></i> <i data-id="${file.lastModified}" onclick="removeFilePDF(this)" title="Eliminar pdf" class="bi bi-x-circle text-danger" style="font-size: 20px;cursor: pointer;"></i></div></li>`;
                    items_files_html.appendChild(element_ul);
                };
                reader.readAsDataURL(file);
            }
        }
    } else {
        items_files_html.innerHTML = '<p class="m-0 text-center">No hay archivos cargados.</p>'
    }
}

function openPDF(element){
    let contentPDF = element.dataset.content;
    document.getElementById('content-pdf').src = contentPDF;
    $("#modal_vista_previa_pdf").modal('show');
}

function getColorString(color_resultado, class_color) {
    let bg_span = '';
    if (color_resultado == "BUENO") {
        bg_span = class_color + '-success'
    } else if (color_resultado == "REGULAR") {
        bg_span = class_color + '-warning'
    } else if (color_resultado == "MALO") {
        bg_span = class_color + '-danger'
    }
    return bg_span;
}

function removeItemImg(element) {
    let lastModified = element.dataset.id;
    let index = array_files_upload.findIndex(item => parseInt(item.lastModified) === parseInt(lastModified));
    if (index !== -1) {
        array_files_upload.splice(index, 1);
        cargarImagen(array_files_upload, 'imagesPreview');
    }
}
function removeItemImg2(element) {
    let lastModified = element.dataset.id;
    let index = array_files_upload.findIndex(item => parseInt(item.lastModified) === parseInt(lastModified));
    if (index !== -1) {
        array_files_upload.splice(index, 1);
        cargarImagen2(array_files_upload, 'imagesPreviewInca');
    }
}
function removeFilePDF(element) {
    let lastModified = element.dataset.id;
    let index = array_files_upload.findIndex(item => parseInt(item.lastModified) === parseInt(lastModified));
    if (index !== -1) {
        array_files_upload.splice(index, 1);
        cargarFilesPDF(array_files_upload, 'items_files_preview');
    }
}
//en desarrollo
function compressImage(imgToCompress, resizingFactor, quality) {
    // showing the compressed image
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");

    const originalWidth = imgToCompress.width;
    const originalHeight = imgToCompress.height;

    const canvasWidth = originalWidth * resizingFactor;
    const canvasHeight = originalHeight * resizingFactor;

    canvas.width = canvasWidth;
    canvas.height = canvasHeight;

    context.drawImage(
        imgToCompress,
        0,
        0,
        originalWidth * resizingFactor,
        originalHeight * resizingFactor
    );

    // reducing the quality of the image
    canvas.toBlob(
        (blob) => {
            if (blob) {
                compressedImageBlob = blob;
                compressedImage.src = URL.createObjectURL(compressedImageBlob);
            }
        },
        "image/jpeg",
        quality
    );
}

//remove imagen del disk
function removeItemFileDisk(element) {
    let id = element.dataset.id;

    axios.post(route('examen.preingreso.deleteItem'), { id: id }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        if (result.data.status === 'success') {
            let index = array_files_upload.findIndex(item => item.id == id);

            Toast.fire({
                icon: "success",
                title: result.data.message
            });
            array_files_upload.splice(index, 1);

            cargarImagen(array_files_upload, 'imagesPreview');
            cargarFilesPDF(array_files_upload, 'items_files_preview');
        } else {
            Toast.fire({
                icon: "error",
                title: result.data.message
            });
        }
    }).catch((err) => {
        Toast.fire({
            icon: "error",
            title: 'Ha ocurrido un error inesperado.'
        });
        console.log(err);
    });
}




function cargarImagen2(array_images, id_html) {
    let images_html = ``;
    let imagePreview = document.getElementById(id_html);
    imagePreview.innerHTML = '';
    //validaciones
    let data = array_images.filter(item => item.typeFile === "image");
    if (data.length > 0) {
        for (let index = 0; index < array_images.length; index++) {
            let file = array_images[index];
            //comprobar si es de disco o temporal
            if (file.disk === true && file.typeFile === "image") {
                let color_resultado = getColorString(file.resultado_examen, 'bg');
                images_html += `
                <div class="imagen_preview_display">
                    <div class="image_details">
                        <span class="badge element" style="background: #e7f3e9;color: #020202;">
                            <i class="bi bi-check-circle me-1" style="font-size:10px"></i>EXAMEN: ${file.nombre_examen}
                        </span>
                        <span class="badge ${color_resultado} element">
                            <i class="bi bi-check-circle me-1" style="font-size:10px"></i>RESULTADO: ${file.resultado_examen}
                        </span>
                    </div>
                    <span title="Eliminar archivo" onclick="removeItemFileDisk2(this)" data-id="${file.id}" class="close__floating"><i class="bi bi-x-lg"></i></span>
                    <div style="width: 100%;height: 100%;">
                        <img src="${file.content}" onclick="fullScreenImagePostIncap(this)" alt="EXAMEN: ${file.examen} - RESULTADO: ${file.resultado}" width="250" style="cursor:pointer;border-radius: 5px;cursor: pointer;transition: 0.3s;height:inherit">
                    </div>
                </div>
                `;
                imagePreview.innerHTML = images_html;
            } else if (file && file.disk === false && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let color_resultado = getColorString(file.resultado_examen, 'bg');

                    images_html += `
                            <div class="imagen_preview_display">
                                <div class="image_details">
                                    <span class="badge element" style="background: #e7f3e9;color: #020202;">
                                        <i class="bi bi-check-circle me-1" style="font-size:10px"></i>EXAMEN: ${file.nombre_examen}
                                    </span>
                                    <span class="badge ${color_resultado} element">
                                        <i class="bi bi-check-circle me-1" style="font-size:10px"></i>RESULTADO: ${file.resultado_examen}
                                    </span>
                                </div>                 
                                <span title="Eliminar archivo" onclick="removeItemImg2(this)" data-id="${file.lastModified}" class="close__floating"><i class="bi bi-x-lg"></i></span>
                                <div style="width: 100%;height: 100%;">
                                    <img src="${e.target.result}" onclick="fullScreenImagePostIncap(this)" alt="EXAMEN: ${file.examen} - RESULTADO: ${file.resultado}" width="250" style="cursor:pointer;border-radius: 5px;cursor: pointer;transition: 0.3s;height:inherit">
                                </div>
                            </div>
                            `;
                    imagePreview.innerHTML = images_html;
                };
                reader.readAsDataURL(file);
            }
        }
    } else {
        imagePreview.innerHTML = '<p class="m-0">No hay archivos cargados.</p>'
    }
}

function cargarFilesPDFInca(array_files, id_element) {
    let items_files_html = document.getElementById(id_element);
    items_files_html.innerHTML = '';
    //validaciones
    let data = array_files.filter(item => item.typeFile === "pdf");
    if (data.length > 0) {
        //creamos el elemento para guardar los archivos
        let element_ul = document.createElement('ul');
        element_ul.classList.add('list-group', 'list-group-flush');
        for (let index = 0; index < array_files.length; index++) {
            let file = array_files[index];
            //validacion segun carga de archivos
            if (file.disk === true && file.typeFile === "pdf") {
                let color_resultado = getColorString(file.resultado_examen, 'text');

                element_ul.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center p-1 ${color_resultado}" style="font-size:14px">${file.nombre_examen}.pdf <div><i data-content="${file.content}" onclick="openPDF(this)" title="Vista previa de ${file.nombre_examen}.pdf" class="bi bi-eye text-success mx-2" style="font-size: 22px;cursor: pointer;"></i><i data-id="${file.id}" onclick="removeItemFileDisk2(this)" title="Eliminar pdf" class="bi bi-x-circle text-danger" style="font-size: 20px;cursor: pointer"></i></div></li>`;

                items_files_html.appendChild(element_ul);
            } else if (file && file.disk === false && file.type.startsWith('application/pdf')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let color_resultado = getColorString(file.resultado_examen, 'text');

                    element_ul.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center p-1 ${color_resultado}" style="font-size:14px">${array_files[index].nombre_examen}.pdf <div><i data-content="${e.target.result}" onclick="openPDF(this)" title="Vista previa de ${array_files[index].nombre_examen}.pdf" class="bi bi-eye text-success mx-2" style="font-size: 22px;cursor: pointer;"></i> <i data-id="${file.lastModified}" onclick="removeFilePDF(this)" title="Eliminar pdf" class="bi bi-x-circle text-danger" style="font-size: 20px;cursor: pointer;"></i></div></li>`;

                    items_files_html.appendChild(element_ul);
                };
                reader.readAsDataURL(file);
            }
        }
    } else {
        items_files_html.innerHTML = '<p class="m-0 text-center">No hay archivos cargados.</p>'
    }
}


//remove imagen del disk
function removeItemFileDisk2(element) {

    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!"
    }).then((result) => {
        if (result.isConfirmed) {


            let id = element.dataset.id;

            axios.post(route('examen.postIncapacidad.deleteItem'), { id: id }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((result) => {
                if (result.data.status === 'success') {
                    let index = array_files_upload.findIndex(item => item.id == id);

                    Toast.fire({
                        icon: "success",
                        title: result.data.message
                    });
                    array_files_upload.splice(index, 1);

                    cargarImagen2(array_files_upload, 'imagesPreviewInca');
                    cargarFilesPDFInca(array_files_upload, 'items_files_previewInca');
                } else {
                    Toast.fire({
                        icon: "error",
                        title: result.data.message
                    });
                }
            }).catch((err) => {
                Toast.fire({
                    icon: "error",
                    title: 'Ha ocurrido un error inesperado.'
                });
                console.log(err);
            });
        }
    });


}

