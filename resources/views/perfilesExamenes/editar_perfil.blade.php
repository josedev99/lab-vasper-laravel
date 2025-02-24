<style>
    .t-tr,
    .t-td {
        border: 1px solid #dee2e6;
    }
</style>
<div class="modal fade" id="modal_edit_perfil" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false"
    tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">EDITAR PERFIL DE EXAMENES</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-1 mb-0">
                    <form id="form_edit_examen_perfil">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body p-1" style="box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                                    <table id="dt_cat_edit_examenes" width="100%" data-order='[[ 0, "desc" ]]'
                                        class="table-hover table-striped">
                                        <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                            <tr
                                                style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                                <th style="text-align:center">#</th>
                                                <th style="text-align:center">CATEGORIA</th>
                                                <th style="text-align:center">EXAMEN</th>
                                                <th style="text-align:center">SELECC.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body p-1" style="box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                                    <div class="col-sm-12 col-md-12 mb-2">
                                        <div class="content-input mb-2">
                                            <input id="id_erfil" name="id_erfil" type="hidden" />

                                            <input name="nombre_perfiledit" id="nombre_perfiledit" type="text" class="custom-input material"
                                                value="" placeholder=" " placeholder=" "
                                                style="text-transform: uppercase">
                                            <label class="input-label" for="nombre_perfil"
                                                title="nombre del perfil">Nombre
                                                perfil</label>
                                        </div>
                                    </div>
                                    <table width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                        <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                            <tr
                                                style="min-height:10px;border-radius: 3px;border:1px solid #000;font-style: normal;font-size: 12px">
                                                <th style="text-align:center;width:5%">#</th>
                                                <th style="text-align:center;width:30%">CATEGORIA</th>
                                                <th style="text-align:center;width:55%">EXAMEN</th>
                                                <th style="text-align:center;width:10%">ACC.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider" style="font-size: 12px;text-align:center"
                                            id="items_examenes_perfilEdit">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btnSaveOrden"><i
                                    class="bi bi-floppy"></i> Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>