<style>
    [class*=icheck-]>input:first-child+label::before {
        border-radius: 50% !important;
    }

    [class*=icheck-] {
        margin-top: 0px !important;
    }

    .container__images {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .imagen_preview_display {
        position: relative;
        height: 250px !important;
        width: auto;
        border: 1px dotted #e8e8e9;
        display: flex;
        justify-content: center;
        margin: 6px;
        padding: 10px;
        border-radius: 6px;
    }

    .close__floating {
        position: absolute;
        top: -10px;
        right: -10px;
        height: 25px;
        width: 25px;
        background: red;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        color: #fbfbfb;
    }

    .upload_file-header {
        border: 2px dotted #aba0a0;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px;
        cursor: pointer;
    }

    .upload_file_body {
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        padding: 4px 12px;
    }

    .upload_file-icon {
        font-size: 20px;
        color: #5a79a5;
    }

    .file_pdf {
        border-radius: 6px;
        border: 1px solid #f7e2e2;
        padding: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
    }

    .file_pdf i {
        font-size: 90px;
    }

    .image_details {
        position: absolute;
        bottom: 8px;
        left: 10px;
    }

    /* 
        STYLE PARA FULL SCREEN IMAGEN
    */
    /* The Modal (background) */
    .container_fullscreen_image {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .fullscreen-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .fullscreen-content,
    #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .fullscreen-content {
            width: 100%;
        }
    }
</style>

<div class="modal fade" id="modal_examen_resultado" data-bs-backdrop="static" data-bs-focus="false"
    data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7" id="modal_orden_examen">SUBIR RESULTADO DEL EXAMEN: <span id="display_examen_img"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_examen_preingreso">
                    <div class="card p-1 m-1 shadow-lg">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="p-0 m-0" style="display: inline-block"><span class="badge bg-light"
                                            style="font-size: 13px !important; color:#6c757d"
                                            id="display_colaborador_examen"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-1 mb-0">
                        <div class="card-body p-1">
                            <div class="upload_file-header loadImgFilePDF">
                                <div class="upload_file-icon">
                                    <span style="font-size: 14px">Haz clic aquí para cargar el resultado del examen.</span>
                                    <i class="bi bi-image"></i>
                                    <i class="bi bi-filetype-pdf"></i>
                                    <input id="upload_file_input" multiple type="file" accept="image/*,.pdf"
                                        style="display: none">
                                </div>
                            </div>
                            <div class="upload_file_body my-2">
                                <div class="card-header p-1">
                                    <h4 class="m-0" style="font-size: 14px">Vista previa de imagenes y archivos</h4>
                                </div>
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-8">
                                            <div class="card m-0 p-1 shadow-lg">
                                                <div class="card-header p-1">
                                                    Imagenes
                                                </div>
                                                <div class="card-body p-1 container__images" id="imagesPreview">
                                                    <p class="m-0" style="font-size:14px">No hay archivos cargados.</p>
                                                </div>
                                                <div id="modal_fullscreen" class="container_fullscreen_image">
                                                    <span class="close">&times;</span>
                                                    <img class="fullscreen-content" id="preview_image_fullscreen">
                                                    <div id="caption"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="card m-0 p-1 shadow-lg">
                                                <div class="card-header p-1">
                                                    Archivos
                                                </div>
                                                <div class="card-body p-1" id="items_files_preview">
                                                    <p class="m-0 text-center" style="font-size:14px">No hay archivos
                                                        cargados.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 m-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btnSaveOrden"><i
                                    class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>