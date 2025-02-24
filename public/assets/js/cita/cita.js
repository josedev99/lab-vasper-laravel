document.addEventListener('DOMContentLoaded', Main);
var horario_empresa_cita = [];
//Funcion principal para cargar la logica
function Main(){
    try{
        $('#hora_cita').selectize(); //inicializar selectize
        validateNumberTel("telefono_emp");

        flatpickr("#fecha_cita",{
            locale: "es",
            maxDate: "2050",
            minDate: "today",
            dateFormat: "d/m/Y",
            disableMobile: "true",
        });
        flatpickr("#fecha_inicio_sintoma",{
            locale: "es",
            maxDate: "2050",
            minDate: "2020",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            disableMobile: "true",
        });
        //change fecha cita
        let fecha_cita = document.getElementById('fecha_cita');
        if(fecha_cita){
            fecha_cita.addEventListener('change', (e) => {
                getHorariosCita(e.target.value);
            })
        }
        //Save form submit
        let form_data_cita = document.getElementById('form_data_cita');
        if(form_data_cita){
            form_data_cita.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('emit...');
                let formData = new FormData(form_data_cita);
                for (let [key, value] of formData.entries()) {
                    let input = (document.querySelector('input[name="'+key+'"]') !== null) ? document.querySelector('input[name="'+key+'"]') : document.querySelector('.selectize-input');
                    
                    let labelTextContent = document.querySelector('label[for="'+key+'"]');
                    labelTextContent = (labelTextContent !== null) ? labelTextContent.title : '';
                    if (!value.trim()) {
                        input.classList.add('border-valid');              
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${labelTextContent} es requerido.` 
                            });
                        return;
                    }else{
                        input.classList.remove('border-valid');
                    }
                }
                form_data_cita.submit();
            })
        }
    }catch(err){
        console.log(err);
    }
}

function getHorariosCita(fecha_cita){
    //obtener sucursal
    let sucursal_id = document.getElementById('sucursal_id');

    axios.post(route('cita.public.horarios'),{fecha_cita: fecha_cita, sucursal_id: sucursal_id.value},{
        headers:{
            'Content-Type': 'multipart/form-data',
            'Content-Encoding': 'gzip'
        }
    }).then((result) => {
        let data = result.data;
        let select_horario = $('#hora_cita').selectize()[0].selectize;
        select_horario.clear();
        select_horario.clearOptions();
        data.forEach((horario) => {
            select_horario.addOption({
                value: horario.hora,
                text: horario.hora
            });
        });
        select_horario.refreshItems();
    }).catch((err) => {
        console.log(err);
    });
}

/* 
DESCARGAR INFORMACION DE CITA
*/

document.addEventListener('DOMContentLoaded', ()=> {
    try{
        let btn_download_img = document.getElementById('btn_download_img');
        if(btn_download_img){
            btn_download_img.addEventListener('click', ()=>{
                html2canvas(document.getElementById('download_info_cita'),{scale: 2}).then(function(canvas) {
                    // Crear enlace para la descarga
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png',1.0);
                    link.download = 'mi_cita.png';
                    link.click();
                  });
            })
        }
    }catch(err){
        console.log(err)
    }
})