<div class="modal fade" id="modal-areas-jornadas" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 text-light" style="background: #034f84">
                <h1 class="modal-title fs-7">Seleccionar Areas</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <table style="width: 100%;text-transform:uppercase" class="table table-hover">
                    <thead style="font-size: 13px;text-align: center;">
                        <tr>
                            <th class="left-th bg-custom-color" style="width: 15%">
                                <input type="checkbox" id="select-all-areas-j" onclick="addAllAreaJornada(this)">
                            </th>
                            <th class="left-th bg-custom-color" style="width: 85%">Area</th>                            
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;text-align: center;" id="area_for_jorn">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer p-1">
                <button onclick="closeModalArea(this)" class="btn btn-outline-success btn-sm" style="border: none">Seleccionar <i class="bi bi-plus-lg"></i></button>
            </div>
        </div>
    </div>
</div>