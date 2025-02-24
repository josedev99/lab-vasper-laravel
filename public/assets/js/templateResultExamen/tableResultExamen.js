/**
 * 
 * @param {*} id 
 * @param {examen,resultado,valores_normales} resultado
 */
function TableExamenesStandar(id, resultado = null,element = null, isDocument = false) {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    let table = document.createElement('table');
    let table_header = document.createElement('thead');
    let table_tbody = document.createElement('tbody');

    table.classList.add('table-resultado')
    table_header.classList.add('table-header');
    table_tbody.classList.add('table-body');

    if (resultado !== null && typeof resultado === "object") {
        table_header.innerHTML = `
            <tr>
                <th>EXAMEN</th>
                <th>RESULTADO</th>
                <th>VALORES NORMALES</th>
            </tr>
        `;
        table_tbody.innerHTML = `
            <tr>
                <td class="td_custom text-center">${resultado.examen}</td>
                <td class="td_custom text-center">${resultado.resultado} ${resultado?.Umedida ?? 'mg/dl'}</td>
                <td class="td_custom">${resultado.valores_normales}</td>
            </tr>
        `;
        table.appendChild(table_header);
        table.appendChild(table_tbody);
        component.appendChild(table);
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
            `;
    }
}

function itemsExamQuimicaTable(id, arrayResultados = null,element = null, isDocument = false) {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    let table = document.createElement('table');
    let table_header = document.createElement('thead');
    let table_tbody = document.createElement('tbody');

    table.classList.add('table-resultado')
    table_header.classList.add('table-header');
    table_tbody.classList.add('table-body');

    if (arrayResultados !== null && arrayResultados.length > 0) {
        table_header.innerHTML = `
            <tr>
                <th>EXAMEN</th>
                <th>RESULTADO</th>
                <th>VALORES NORMALES</th>
            </tr>
        `;
        arrayResultados.forEach(element => {
            table_tbody.innerHTML += `
                <tr>
                    <td class="td_custom text-center">${element.examen}</td>
                    <td class="td_custom text-center">${element.resultado} ${resultado?.Umedida ?? 'mg/dl'}</td>
                    <td class="td_custom">${element.valores_normales}</td>
                </tr>
            `;
        });
        table.appendChild(table_header);
        table.appendChild(table_tbody);
        component.appendChild(table);
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
            `;
    }
}

function TableHecesExamen(id,resultado = null,element = null,isDocument = false,customTitle = ''){
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    if (resultado !== null && typeof resultado === "object") {
        let table = `
        <table class="table-resultado">
            <tr>
                <th class="td_custom_heces text-center" colspan="6">${ customTitle !== '' ? customTitle : 'HECES'}</th>
            </tr>
            <tr>
                <th class="td_custom_heces th-header" colspan="3">EXAMEN FÍSICO-QUÍMICO</th>
                <th class="td_custom_heces th-header" colspan="3">EXAMEN MICROSCOPICO</th>
            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Color:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.color}</td>
                
                <td class="td_custom_heces"><strong>Hematies:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.hematies}</td>
            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Consistencia:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.consistencia}</td>
                
                <td class="td_custom_heces"><strong>Leucocitos:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.leucocitos}</td>
            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Mucus:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.mucus}</td>
                
                <td class="td_custom_heces"><strong>Protozoarios:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.protozoarios}</td>
            </tr>
            <tr>
                <td class="td_custom_heces th-header" colspan="3" class="section-title">Restos Alimenticios</td>
                <td class="td_custom_heces"><strong>Activos:</strong> </td>
                <td class="td_custom_heces">${resultado.activos}</td>
            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Macroscópicos:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.macroscopicos}</td>

                <td class="td_custom_heces"><strong>Quistes:</strong></td>
                <td class="td_custom_heces">${resultado.quistes}</td>

            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Microscópicos:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.microscopicos}</td>
                <td class="td_custom_heces"><strong>Metazoarios:</strong></td>
                <td class="td_custom_heces" colspan="2">${resultado.metazoarios}</td>
            </tr>
            <tr>
                <td class="td_custom_heces"><strong>Observaciones:</strong></td>
                <td class="td_custom_heces" colspan="4">${resultado.observaciones}</td>
            </tr>
        </table>
    `;
    component.innerHTML = table;
    }else{
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
            `;
    }
}

function TableOrinaExamen(id, resultado = null, element = null, isDocument = false,customTitle = '') {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    if (resultado !== null && typeof resultado === "object") {
        let table = `
        <table class="table-resultado">
            <tr>
                <th class="td_custom_orina text-center" colspan="6">${ customTitle !== '' ? customTitle : 'ORINA'}</th>
            </tr>
            <tr>
                <th class="td_custom_orina th-header" colspan="3">EXAMEN FÍSICO-QUÍMICO</th>
                <th class="td_custom_orina th-header" colspan="3">EXAMEN MICROSCÓPICO</th>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Color:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.color}</td>
                
                <td class="td_custom_orina"><strong>Leucocitos:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.leucocitos}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Olor:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.olor}</td>
                
                <td class="td_custom_orina"><strong>Hematíes:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.hematies}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Aspecto:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.aspecto}</td>
                
                <td class="td_custom_orina"><strong>Cilindros:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.cilindros}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Densidad:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.densidad}</td>
                
                <td class="td_custom_orina"><strong>Bacterias:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.bacterias}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Est. Leuco:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.est_leuco}</td>
                
                <td class="td_custom_orina"><strong>Cristales:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.cristales}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>pH:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.ph}</td>
                
                <td class="td_custom_orina"><strong>Nitritos:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.nitritos_orina}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Proteínas:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.proteinas}</td>
                
                <td class="td_custom_orina"><strong>Cel. Epiteliales:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.cel_epiteliales}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Glucosa:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.glucosa}</td>
                
                <td class="td_custom_orina"><strong>Filamentos Muco:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.filamentos_muco}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Cetonas:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.cetonas}</td>
                
                <td class="td_custom_orina"><strong>Observaciones:</strong></td>
                <td class="td_custom_orina" colspan="4">${resultado.observaciones}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Urobilinógeno:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.urobilinogeno}</td>
                
                <td class="td_custom_orina" colspan="4"></td>

            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Bilirrubina:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.bilirrubina}</td>
                
                <td class="td_custom_orina" colspan="4"></td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Sangre Oculta:</strong></td>
                <td class="td_custom_orina" colspan="2">${resultado.sangre_oculta}</td>
                
                <td class="td_custom_orina" colspan="4"></td>
            </tr>
        </table>
    `;
        component.innerHTML = table;
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
        `;
    }
}

function TableHemogramaExamen(id, resultado = null, element = null, isDocument = false,customTitle = '') {
    //dev
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    if (resultado !== null && typeof resultado === "object") {
        let table = `
        <table class="table-resultado">
            <tr>
                <th class="td_custom_orina text-center" colspan="6">${ customTitle !== '' ? customTitle : 'HEMOGRAMA'}</th>
            </tr>
            <tr>
                <th class="td_custom_orina th-header" colspan="2">LÍNEA ROJA</th>
                <th class="td_custom_orina th-header" colspan="2">LÍNEA BLANCA</th>
                <th class="td_custom_orina th-header" colspan="2">VARIOS</th>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>G.R. x mm³:</strong></td>
                <td class="td_custom_orina">${resultado.gr_hemato}</td>
                
                <td class="td_custom_orina"><strong>G.B. x mm³:</strong></td>
                <td class="td_custom_orina">${resultado.gb_hemato}</td>
                
                <td class="td_custom_orina"><strong>Plaquetas:</strong></td>
                <td class="td_custom_orina">${resultado.plaquetas_hemato}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Ht %:</strong></td>
                <td class="td_custom_orina">${resultado.ht_hemato}</td>
                
                <td class="td_custom_orina"><strong>Linfocitos:</strong></td>
                <td class="td_custom_orina">${resultado.linfocitos_hemato}%</td>
                
                <td class="td_custom_orina"><strong>Reticulocitos:</strong></td>
                <td class="td_custom_orina">${resultado.reti_hemato}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>Hb g/dl:</strong></td>
                <td class="td_custom_orina">${resultado.hb_hemato}</td>
                
                <td class="td_custom_orina"><strong>Monocitos:</strong></td>
                <td class="td_custom_orina">${resultado.monocitos_hemato}%</td>
                
                <td class="td_custom_orina"><strong>Eritrosedimentación:</strong></td>
                <td class="td_custom_orina">${resultado.eritro_hemato}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>V.C.M fl:</strong></td>
                <td class="td_custom_orina">${resultado.vcm_hemato}</td>
                
                <td class="td_custom_orina"><strong>Eosinófilos:</strong></td>
                <td class="td_custom_orina">${resultado.eosinofilos_hemato}%</td>
                
                <td class="td_custom_orina" colspan="2"><strong>OTROS</strong></td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>H.C.M Pg:</strong></td>
                <td class="td_custom_orina">${resultado.hcm_hemato}</td>
                
                <td class="td_custom_orina"><strong>Basófilos:</strong></td>
                <td class="td_custom_orina">${resultado.basinofilos_hemato}%</td>
                
                <td class="td_custom_orina" colspan="2">${resultado.otros_hema}</td>
            </tr>
            <tr>
                <td class="td_custom_orina"><strong>C.M.H.C g/dl:</strong></td>
                <td class="td_custom_orina">${resultado.cmhc_hemato}</td>
                
                <td class="td_custom_orina" colspan="4"></td>
            </tr>
            <tr>
                <th class="td_custom_orina th-header" colspan="6">NEUTRÓFILOS</th>
            </tr>
            <tr>
                <td class="td_custom_orina" colspan="2"><strong>En Banda:</strong></td>
                <td class="td_custom_orina" colspan="1">${resultado.banda_hemato}%</td>

                <td class="td_custom_orina" colspan="2"><strong>Segmentados:</strong></td>
                <td class="td_custom_orina" colspan="1">${resultado.segmentados_hemato}%</td>
            </tr>
            <tr>
                <td class="td_custom_orina" colspan="2">${resultado.metamielo_hemato || ''}</td>
                <td class="td_custom_orina" colspan="2">${resultado.mielocitos_hemato || ''}</td>
                <td class="td_custom_orina" colspan="2">${resultado.blastos_hemato || ''}</td>
            </tr>
            <tr>
                <th class="td_custom_orina th-header" colspan="6">GOTA GRUESA</th>
            </tr>
            <tr>
                <td class="td_custom_orina" colspan="6">${resultado.gota_hema || ''}</td>
            </tr>
        </table>
    `;
        component.innerHTML = table;
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
        `;
    }
}
function resultOptometria(id, resultado = null, element = null, isDocument = false){
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    if (resultado !== null && typeof resultado === "object") {
        let table = `
            <div class="col-sm-12 col-md-12">
                <div class="card p-1 mx-2 mb-2">
                    <table style="margin:0px;">
                        <thead class="thead-light" style="color: #222;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #e1e8f2">
                            <tr>
                                <th colspan="7" style="width: 100%;text-align: center;text-transform: uppercase; background: #101010; color:#f2f2f2;">GRADUACIÓN (RX FINAL)</th>
                            </tr>
                            <tr>
                                <th style="text-align:center">OJO</th>
                                <th style="text-align:center">ESFERAS</th>
                                <th style="text-align:center">CILINDROS</th>
                                <th style="text-align:center">EJE</th>
                                <th style="text-align:center">ADICION</th>
                                <th style="text-align:center">PRISMA</th>
                                <th style="text-align:center">AV <span style="font-size:9px;text-transform: lowercase;">final(cc)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align:center">
                                <td style="text-align: center;">OD</td>
                                <td>${resultado.od_esfera_f || ''}</td>
                                <td>${resultado.od_cilindro_f || ''}</td>
                                <td>${resultado.od_eje_f || ''}</td>
                                <td>${resultado.od_adicion_f || ''}</td>
                                <td>${resultado.od_prisma_f || ''}</td>
                                <td>${resultado.od_av_final_cc || ''}</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align: center;">OI</td>
                                <td>${resultado.oi_esfera_f || ''}</td>
                                <td>${resultado.oi_cilindro_f || ''}</td>
                                <td>${resultado.oi_eje_f || ''}</td>
                                <td>${resultado.oi_adicion_f || ''}</td>
                                <td>${resultado.oi_prisma_f || ''}</td>
                                <td>${resultado.oi_av_final_cc || ''}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="card p-1 mx-2 mb-2">
                    <table>
                        <thead class="thead-light" style="color: #222;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #e1e8f2">
                            <tr>
                                <th colspan="6" style="width: 100%;text-align: center;text-transform: uppercase; background: #101010; color:#f2f2f2;">MEDIDAS</th>
                            </tr>
                            <tr>
                                <th style="text-align:center">OJO</th>
                                <th style="text-align: center">DISTANCIA PUPILAR</th>
                                <th style="text-align: center">ALTURA PUPILAR</th>
                                <th style="text-align: center">ALTURA OBLEA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align:center">
                                <td style="text-align:center;">OD</td>
                                <td>${resultado.dip_od || ''}</td>
                                <td>${resultado.ap_od || ''}</td>
                                <td>${resultado.ao_od || ''}</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:center;">OI</td>
                                <td>${resultado.dip_oi || ''}</td>
                                <td>${resultado.ap_oi || ''}</td>
                                <td>${resultado.ao_oi || ''}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="card p-1 mx-2 mb-2">
                    <table class="table-diagn" style="width: 100%">
                        <thead style="color: #222;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #e1e8f2;">
                            <tr>
                                <th colspan="50" style="width: 50%;border:1px solid #f2f2f2">Diagnóstico refractivo OD</th>
                                <th colspan="50" style="width: 50%;border:1px solid #f2f2f2">Diagnóstico refractivo OI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="font-size: 11px">
                                <td colspan="50" style="width: 50%;border:1px solid #f2f2f2">${resultado.diag_preliminar_od || ''}</td>
                                <td colspan="50" style="width: 50%;border:1px solid #f2f2f2">${resultado.diag_preliminar_oi || ''}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        component.innerHTML = table;
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
        `;
    }
}

function TableExamenBaciloscopia(id, resultado = null,element = null, isDocument = false) {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    let table = document.createElement('table');
    let table_header = document.createElement('thead');
    let table_tbody = document.createElement('tbody');

    table.classList.add('table-resultado')
    table_header.classList.add('table-header');
    table_tbody.classList.add('table-body');

    if (resultado !== null && typeof resultado === "object") {
        table_header.innerHTML = `
            <tr>
                <th>EXAMEN</th>
                <th>RESULTADO</th>
                <th>MUESTRA</th>
            </tr>
        `;
        table_tbody.innerHTML = `
            <tr>
                <td class="td_custom text-center">${resultado.examen}</td>
                <td class="td_custom text-center">${resultado.resultado}</td>
                <td class="td_custom">${resultado.muestra}</td>
            </tr>
        `;
        table.appendChild(table_header);
        table.appendChild(table_tbody);
        component.appendChild(table);
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
            `;
    }
}

function TableExamenExofaringeo(id, resultado = null,element = null, isDocument = false) {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    let table = document.createElement('table');
    let table_header = document.createElement('thead');
    let table_tbody = document.createElement('tbody');

    table.classList.add('table-resultado')
    table_header.classList.add('table-header');
    table_tbody.classList.add('table-body');

    if (resultado !== null && typeof resultado === "object") {
        table_header.innerHTML = `
            <tr>
                <th>EXAMEN</th>
                <th>SE AISLA</th>
                <th>SENSIBLE A</th>
                <th>RESISTENTE A</th>
                <th>MUESTRA</th>
            </tr>
        `;
        table_tbody.innerHTML = `
            <tr>
                <td class="td_custom text-center">${resultado.examen}</td>
                <td class="td_custom text-center">${resultado.aisla}</td>
                <td class="td_custom text-center">${resultado.sensible}</td>
                <td class="td_custom text-center">${resultado.resiste}</td>
                <td class="td_custom text-center">${resultado.muestra}</td>
            </tr>
        `;
        table.appendChild(table_header);
        table.appendChild(table_tbody);
        component.appendChild(table);
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
            `;
    }
}

function itemsExamenBacteriologia(id, resultado = null, element = null, isDocument = false) {
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    if (resultado && resultado.length > 0) {
        resultado.forEach(examen => {
            let card = document.createElement('div');
            card.classList.add('card','p-1','mb-1');

            let table = document.createElement('table');
            table.classList.add('table-resultado','m-0');

            let table_header = document.createElement('thead');
            let table_tbody = document.createElement('tbody');

            table_header.classList.add('table-header');
            table_tbody.classList.add('table-body');

            if (examen.examen === "BACILOSCOPIA") {
                table_header.innerHTML = `
                    <tr>
                        <th class="td_custom">EXAMEN</th>
                        <th class="td_custom">RESULTADO</th>
                        <th class="td_custom">MUESTRA</th>
                    </tr>
                `;
                table_tbody.innerHTML = `
                    <tr>
                        <td class="td_custom text-center">${examen.title}</td>
                        <td class="td_custom text-center">${examen.resultado}</td>
                        <td class="td_custom">${examen.muestra}</td>
                    </tr>
                `;
            } else if (examen.examen === "CULTIVO FARINGEO") {
                table_header.innerHTML = `
                    <tr>
                        <th class="td_custom">EXAMEN</th>
                        <th class="td_custom">SE AISLA</th>
                        <th class="td_custom">SENSIBLE A</th>
                        <th class="td_custom">RESISTENTE A</th>
                        <th class="td_custom">MUESTRA</th>
                    </tr>
                `;
                table_tbody.innerHTML = `
                    <tr>
                        <td class="td_custom text-center">${examen.title}</td>
                        <td class="td_custom text-center">${examen.aisla}</td>
                        <td class="td_custom text-center">${examen.sensible}</td>
                        <td class="td_custom text-center">${examen.resiste}</td>
                        <td class="td_custom">${examen.muestra}</td>
                    </tr>
                `;
            }

            table.appendChild(table_header);
            table.appendChild(table_tbody);
            card.appendChild(table);
            component.appendChild(card);
        });
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
        `;
    }
}

function TableExamenUrologia(id, resultado = null, element = null, isDocument = false){
    let component = isDocument ? element.querySelector(`#${id}`) : document.querySelector(`#${id}`);
    component.innerHTML = '';

    let table = document.createElement('table');
    let table_header = document.createElement('thead');
    let table_tbody = document.createElement('tbody');

    table.classList.add('table-resultado')
    table_header.classList.add('table-header');
    table_tbody.classList.add('table-body');

    if (resultado !== null && typeof resultado === "object") {
        table_header.innerHTML = `
            <tr>
                <th>EXAMEN</th>
                <th>RESULTADO</th>
                <th>MUESTRA</th>
            </tr>
        `;
        table_tbody.innerHTML = `
            <tr>
                <td class="td_custom text-center">${resultado.examen}</td>
                <td class="td_custom text-center">${resultado.resultado}</td>
                <td class="td_custom">${resultado.muestra}</td>
            </tr>
        `;
        table.appendChild(table_header);
        table.appendChild(table_tbody);
        component.appendChild(table);
    } else {
        component.innerHTML = `
            <div class="alert border-info alert-dismissible m-0 p-3" role="alert" style="font-size:13px">
                Sin resultados.
            </div>
        `;
    }
}