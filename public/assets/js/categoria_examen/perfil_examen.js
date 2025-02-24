var array_perfiles_examenes = [];

function listar_perfiles_orden(){
    axios.post(route('examen.perfil.save'))
    .then((result) => {
        let rows_examenes_html = document.getElementById('rows_items_perfil');
        rows_examenes_html.innerHTML = ``;
        if(result.data.length > 0){
            let data = result.data;
            array_perfiles_examenes = data;

            data.forEach((element,index) => {
                let id_element = btoa(element.perfil);
                let idAccordionBody = btoa(element.perfil + "-list");
                let examenes = element.examenes;

                let cardItem = document.createRange().createContextualFragment(/*html*/`
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="card p-1 mb-2">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="checkbox icheck-turquoise mt-0">
                                        <input type="checkbox" data-index="${index}" onclick="selectedPerfilCheck(this)" id="${id_element}" value="${element.perfil}" />
                                        <label for="${id_element}" style="font-size: 16px"><strong style="color: #012970">${element.perfil}</strong></label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <i class="bi bi-inbox"></i>
                                </div>
                            </div>

                            <!-- List group Numbered -->
                            <ol class="list-group list-group-numbered list-group-flush" style="font-size: 14px" id="${idAccordionBody}">
                            </ol><!-- End List group Numbered -->
                        </div>
                    </div>
                </div>
                `);
                let contentLi = cardItem.getElementById(idAccordionBody);
                examenes.forEach((examen)=>{
                    let rows_examen = document.createRange().createContextualFragment(/*html*/`
                        <li class="list-group-item py-1">${examen.examen}</li>
                    `);
                    contentLi.appendChild(rows_examen);
                })
                rows_examenes_html.appendChild(cardItem);
            });
        }else{
            rows_examenes_html.innerHTML = `<div class="col-sm-12 mt-2"><div class="alert alert-light p-1" role="alert">No se encontraron perfiles.</div></div>`;
        }
    }).catch((err) => {
        console.log(err);
    });
}

function selectedPerfilCheck(element){
    let index = element.dataset.index;
    if(element.checked){
        array_perfiles_examenes[index].check_perfil = element.checked;
    }else{
        array_perfiles_examenes[index].check_perfil = element.checked;
    }
}