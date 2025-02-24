//componente historial
const component_form = document.getElementById('component_form_consulta');
const component_historial = document.getElementById('component_historial');
//check input historial
const checkHisConsult = document.getElementById('checkHisConsult');
/**
 * Check historial consultas
 */
document.addEventListener('DOMContentLoaded', () => {
    try{
        if(checkHisConsult){
            checkHisConsult.addEventListener('click', (e) => {
                if(e.target.checked){
                    component_form.classList.replace('col-md-12','col-md-8');
                    component_historial.classList.remove('d-none');
                }else{
                    component_form.classList.replace('col-md-8','col-md-12');
                    component_historial.classList.add('d-none');
                }
            })
        }
    }catch(err){
        console.log(err);
    }
})

/**
 * Function para listar las consultas creadas
 * @param {int} empleado_id
 */
function showHistorialConsultas(empleado_id) {
    axios.post(route('registroMedico.consulta.obtener'), { empleado_id: empleado_id }, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Content-Encoding': 'gzip'
        }
    }).then((result) => {
        let lists_consultas = document.getElementById('lists_consultas');
        lists_consultas.innerHTML = ``;

        if (result.data.length > 0) {
            $('#component-check-historial-consult').attr("style", "display: block !important"); //ocultar check input historial

            let data = result.data;
            data.forEach((item) => {
                let accordionId = item.id + '-accordion';
                let flush_collapseOne = item.id + '-flush-collapseOne';

                let card = document.createRange().createContextualFragment(/*html*/`
                <div class="card mb-2">
                    <div class="card-body px-2 py-1">
                        <div class="accordion accordion-flush" id="${accordionId}">
                            <div class="accordion-item">
                                <h2 class="accordion-header d-flex justify-content-between"
                                    id="flush-headingOne" style="border-radius: 3px;">
                                    <span data-consulta_id="${item.id}" onclick="deleteItemConsulta(this)" class="btn btn-outline-danger btn-sm" style="border: none; font-size: 14px;"><i class="bi bi-trash3"></i></span>
                                    <button class="accordion-button collapsed px-1 py-0"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#${flush_collapseOne}"
                                        aria-expanded="false" aria-controls="${flush_collapseOne}"
                                        style="font-size: 14px"> <h3 class="p-1 m-0" style="font-size: 14px;color:#000"><strong>Fecha & hora: </strong> ${item.fecha}</h3></button>
                                </h2>
                                <div id="${flush_collapseOne}" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne"
                                    data-bs-parent="#${accordionId}" style="">
                                    <hr>
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">                                                
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Consulta por: </b><span style="font-size: 14px">${item.motivo_consulta}</span></p>
                                                </div>
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Diagnóstico: </b><span style="font-size: 14px">${item.diagnostico}</span></p>
                                                </div>
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Inicio de sintomas: </b><span style="font-size: 14px">${item.fecha_inicio_sintoma}</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="card-body p-1">
                                                    <div style="border: 1px solid #dadce0">
                                                        <table width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                                            <thead style="color:white;min-height:10px;border-radius: 2px;"
                                                            class="bg-dark">
                                                                <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                                                    <th colspan="100" style="text-align:center;width: 100%">SIGNOS VITALES Y MEDIDAS ANTROPOMÉTRICAS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size: 12px;text-align:center" id="${item.id}">
                                                                <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 13px;color:#020202;background:#cde4f5">
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-right: 1px solid #dadce0">FC(Ipm)</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-right: 1px solid #dadce0">FR(rpm)</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-right: 1px solid #dadce0">PA(ps/pd)</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;">Temp(°C)</th>
                                                                </tr>
                                                                <tr style="font-size: 13px">
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.fc_ipm}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.fr_rpm}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.pa_ps_pd}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%">${item.temperatura}</td>
                                                                </tr>
                                                                <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 13px;color:#020202;background:#cde4f5">
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-left: 1px solid #dadce0">Saturación Oxig(%).</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-left: 1px solid #dadce0">Peso(Kg)</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;border-left: 1px solid #dadce0">Talla(cm)</th>
                                                                    <th colspan="25" style="text-align:center;width: 25%;">IMC</th>
                                                                </tr>
                                                                <tr style="font-size: 13px">
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.saturacion}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.peso_kg}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%;border-right: 1px solid #dadce0">${item.talla_cm}</td>
                                                                    <td colspan="25" style="text-align: center;width:25%">${item.imc}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Riesgo: </b><span style="font-size: 14px">${item.riesgo}</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Incapacidad: </b><span style="font-size: 14px">${item.incapacidad}</span></p>
                                                </div>
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Días: </b><span style="font-size: 14px">${item.days_incap}</span></p>
                                                </div>
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Diagnóstico incapacidad: </b><span style="font-size: 14px">${item.diag_incap}</span></p>
                                                </div>
                                                <div class="card-header p-1" style="color: #3e434b">
                                                    <p class="m-0"><b>Tipo incapacidad: </b><span style="font-size: 14px">${item.tipo_incapacidad}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Accordion without outline borders -->
                    </div>
                </div>
                `);

                lists_consultas.appendChild(card);
            })
        } else {
            //validar//si el componente esta activo, ocultar
            if(component_form.classList.contains('col-md-8')){
                component_form.classList.replace('col-md-8','col-md-12');
                component_historial.classList.add('d-none');
                checkHisConsult.checked = false;
            }
            //ocultar check input historial
            $('#component-check-historial-consult').attr("style", "display: none !important");

            lists_consultas.innerHTML = `
            <div class="card mb-2">
                <div class="card-body px-2 py-1">
                    <div class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h2 class="accordion-header d-flex justify-content-between"
                                id="flush-headingOne" style="border-radius: 3px;">
                                <h3 class="p-1 m-0" style="font-size: 14px;color:#000">No hay consultas registradas.</h3>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }
    }).catch((err) => {
        console.log(err);
    });
}

/**
 * Function para eliminar consulta
 *  version: 1.0.0
 */

function deleteItemConsulta(element){
    let consulta_id = element.dataset.consulta_id;
    Swal.fire({
        title: "Estás seguro de eliminar esta consulta?",
        text: "Esta acción es irreversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('consulta.delete'),{consulta_id: consulta_id},{headers: {
                'Content-Type': 'multipart/form-data'
            }})
            .then((result) => {
                if (result.data.status === "success") {
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    showHistorialConsultas(result.data.results.id);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: result.data.message,
                        icon: result.data.status
                    });
                }
            }).catch((err) => {
                console.log(err);
            });
        }
    });
}