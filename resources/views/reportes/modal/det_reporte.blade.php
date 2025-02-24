<style>
    /* Estilos para la tabla */
    .table-resultado {
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
        background-color: #f9f9f9;
    }

    .table-header {
        text-align: center;
        color: #f2f2f2;
        background: #0275d8;
    }
    .table-body{
        color: #020202
    }

    .td_custom {
        padding: 2px 8px;
        font-size: 13px;
        border: 1px solid #dbcfcf;
    }
    .container_info_empl{
        box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
    }
    .accordion-item.red {
    background-color: #fbe9eaaa; /* Rojo claro */
    border-color: #d01b2d; /* Rojo oscuro */
}

.accordion-item.green {
    background-color: #e6f7e6cb; /* Verde claro */
    border-color: #329c4b; /* Verde oscuro */
}

</style>
<div class="modal fade" id="modal_det_resumen" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7"></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row">
                    <!-- Parte izquierda: Tabla de empleados -->
                    <div class="col-md-6" id="empleados-lista" style="max-height: 400px; overflow-y: auto;">
                        <!-- Aquí se llenará la tabla de empleados con JavaScript -->
                    </div>
                    <!-- Parte derecha: Resultados de exámenes -->
                    <div class="col-md-6" id="resultados-examenes" style="max-height: 400px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
