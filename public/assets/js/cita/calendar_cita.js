document.addEventListener('DOMContentLoaded', Main);
var calendar = '';

function Main() {
    $('#hora_cita').selectize(); //inicializar selectize
    //init selectize y flatpick
    flatpickr("#fecha_cita", {
        locale: "es",
        maxDate: "2050",
        minDate: "2024",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });
    flatpickr("#fecha_inicio_sintoma",{
        locale: "es",
        maxDate: "2050",
        minDate: "2020",
        dateFormat: "d/m/Y",
        disableMobile: "true",
    });
    //Funtions
    getCalendarCitados();
    //Event btn
    let btnShowCitasAll = document.querySelector('.btnShowCitasAll');
    if (btnShowCitasAll) {
        btnShowCitasAll.addEventListener('click', () => {
            document.getElementById('btn_views_pdf').style.display = 'none';
            showCitasAgendadas();
        });
    }
    //Save nueva cita
    let form_data_cita = document.getElementById('form_data_cita');
    if (form_data_cita) {
        form_data_cita.addEventListener('submit', (e) => {
            e.preventDefault();
            let form_data = new FormData(form_data_cita);
            let inputs = document.querySelectorAll('.i-validate-cita');
            for (let i = 0; i < inputs.length; i++) {
                let input = inputs[i];
                let labelTitle = document.querySelector('label[for="' + input.name + '"]').title;
                if (input.value.trim() === "") {
                    input.classList.add('border-valid');
                    Toast.fire({
                        icon: "warning",
                        title: `El campo ${labelTitle} es requerido.`
                    });
                    return;
                } else {
                    input.classList.remove('border-valid');
                }
            }
            //validar hora cita
            let hora_cita = document.getElementById('hora_cita');
            if(hora_cita.value === ""){
                let labelTitle = document.querySelector('label[for="'+ hora_cita.name + '-selectized' +'"]').title;
                document.querySelector('.selectize-input').classList.add('border-valid');
                Toast.fire({
                    icon: "warning",
                    title: `El campo ${labelTitle} es requerido.`
                });return;
            }else{
                document.querySelector('.selectize-input').classList.remove('border-valid');
            }
            //session edicion cita
            if(sessionStorage.getItem('edic_cita_id')){
                form_data.append('cita_id',sessionStorage.getItem('edic_cita_id'));
            }
            //disabled button
            document.querySelector('.btnSaveCitaCalendar').disabled = true;
            axios.post(route('cita.save.calendar'), form_data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((result) => {
                if (result.data.status === "success") {
                    //remove session storage
                    if(sessionStorage.getItem('edic_cita_id')){
                        sessionStorage.removeItem('edic_cita_id');
                    }

                    $("#modal_agendar_cita").modal('hide');
                    getCalendarCitados();//renderizar nuevamente el calendario
                    //CLEAR selectize
                    $("#hora_cita").selectize()[0].selectize.clear();
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    form_data_cita.reset();
                    //validacion  de registros en datatable
                    if($("#dt_citados_agenda").DataTable().rows().data().length > 0){
                        $("#dt_citados_agenda").DataTable().ajax.reload(null,false);

                    }
                }else if(result.data.status === 'warning'){
                    Swal.fire({
                        title: "Aviso",
                        text: result.data.message,
                        icon: "warning"
                    });
                    $("#hora_cita").selectize()[0].selectize.clear();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: result.data.message,
                        icon: "error"
                    });
                }
                document.querySelector('.btnSaveCitaCalendar').disabled = false;
            })
            .catch((err) => {
                document.querySelector('.btnSaveCitaCalendar').disabled = false;
                console.log(err)
            })

        })
    }
    //Filtrar busqueda de horario por sucursal
    let sucursal_selected = document.querySelector('select[name="sucursal_emp"]');
    let fecha_cita = document.getElementById('fecha_cita');

    if (sucursal_selected) {
        sucursal_selected.addEventListener('change', (e) => {
            let fecha_cita = document.getElementById('fecha_cita').value;
            if (fecha_cita.trim() !== "" && e.target.value !== "") {
                verifyHorariosSuc(fecha_cita, e.target.value);
            } else if (fecha_cita.trim() !== "" && e.target.value === "") {
                Toast.fire({
                    icon: "warning",
                    title: "Por favor, seleccione una sucursal para comprobar la disponibilidad de horarios."
                });
            } else if (fecha_cita.trim() === "" && e.target.value !== "") {
                Toast.fire({
                    title: "Debe elegir una fecha para comprobar la disponibilidad de horarios.",
                    icon: "warning"
                });
            }
        })
        //Filtrar disponibilidad de horario por fecha
        if(fecha_cita){
            fecha_cita.addEventListener('change', (e) => {
                if(sucursal_selected.value !== "" && e.target.value !== ""){
                    verifyHorariosSuc(e.target.value,sucursal_selected.value);
                }
            })
        }
    }

    /**
     * INPUT CODIGO COLABORADOR EVENT KEYUP
     */
    let inut_codigo_colab = document.querySelector('input[name="codigo_empleado"]');
    if(inut_codigo_colab){
        inut_codigo_colab.addEventListener('keyup', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if(e.target.value.length > 2){
                document.getElementById('display_loader_cita').style.display = 'block';
                document.getElementById('display_loader_cita').textContent = 'Buscando colaborador...';
                setTimeout(()=>{
                    loadDataCita(e.target.value);
                    document.getElementById('display_loader_cita').style.display = 'none';
                },1000);
            }
        })
    }
    //Obtener los eventos del datatable
    let table = new DataTable('#dt_citados_agenda');
    table.on('draw.dt', attachEventsDT);
    /**
     * Visualizar PDF de citados
     */
    let btn_views_pdf = document.getElementById('btn_views_pdf');
    if(btn_views_pdf){
        btn_views_pdf.addEventListener('click', (e) => {
            let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let fecha_cita = sessionStorage.getItem('fecha_cita_pdf');
            let form = document.createElement('form');
            form.action = route('cita.pdf.generar');
            form.method = "POST";
            form.target = '_blank';
            let input_csrf = document.createElement('input');
            input_csrf.name = "_token";
            input_csrf.value = token_csrf;
            form.appendChild(input_csrf);

            let inputFecha = document.createElement('input');
            inputFecha.name = 'fecha_cita';
            inputFecha.value = fecha_cita;
            form.appendChild(inputFecha);
            document.body.appendChild(form);
            form.submit();
            form.remove();
            sessionStorage.removeItem('fecha_cita_pdf');//remove session
        })
    }
}

function attachEventsDT(){
    let btnEventEdit = document.querySelectorAll('.btn-event-edit');
    let btnEventAnular = document.querySelectorAll('.btn-event-anular');
    if(btnEventEdit && btnEventAnular){
        btnEventEdit.forEach((btn)=>{
            btn.addEventListener('click', (e) => {
                let cita_id = btn.dataset.cita_id;
                loadDataCitaEdicion(cita_id);
            })
        })

        //button anular cita
        btnEventAnular.forEach((btn)=>{
            btn.addEventListener('click', (e) => {
                let cita_id = btn.dataset.cita_id;
                let codigo_empleado = btn.dataset.cod_empleado;
                let nombre = btn.dataset.nombre
                anularCita(cita_id,codigo_empleado,nombre);
            })
        })
    }
}

function loadDataCitaEdicion(cita_id){
    axios.post(route('cita.data.obtener.id'),{
        cita_id: cita_id
    },{
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        if(result.data !== "not-data"){
            sessionStorage.setItem('edic_cita_id',cita_id);
            //text buttton a editar
            document.getElementById('btnSaveCitaCalendar').innerHTML = `<i class="bi bi-floppy"></i> ACTUALIZAR`;
            document.getElementById('display_title_modal').textContent = 'ACTUALIZAR INFORMACIÓN DE LA CITA';
            document.getElementById('display_title_card').textContent = 'ACTUALIZAR INFORMACIÓN DE LA CITA';

            let data = result.data;
            //obtener los horarios disponibles
            verifyHorariosSuc(data.fecha_cita, data.sucursal_id, ()=>{
                let hora_cita = $("#hora_cita").selectize()[0].selectize;
                hora_cita.addOption({
                    value: data.hora_cita,
                    text: data.hora_cita
                });
                hora_cita.addItem(data.hora_cita);
            });
            //input formulario cita
            document.querySelector('select[name="sucursal_emp"]').value = data.sucursal_id;
            document.querySelector('input[name="codigo_empleado"]').value = data.codigo_empleado;
            document.querySelector('input[name="nombre_empleado"]').value = data.nombre;
            document.querySelector('input[name="telefono"]').value = data.telefono;
            document.querySelector('input[name="fecha_cita"]').value = data.fecha_cita;

            document.getElementById('motivo').value = data.motivo;
            document.querySelector('input[name="fecha_inicio_sintoma"]').value = data.fecha_inicio_sintoma;

            $("#modal_agendar_cita").modal('show');
        }else{
            Toast.fire({
                title: "Ha ocurrido un error al momento de solicitar la información.",
                icon: "warning"
            });
        }
    }).catch((err) => {
        console.log(err);
    });
    
}
/**
 * Function anular cita
 */
function anularCita(cita_id,codigo_emp,nombre){
    Swal.fire({
        title: "Desea anular esta cita?",
        text: `Cita a nombre de: ${nombre}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, anular",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('cita.anular'),{cita_id},{headers: {'Content-Type': 'multipart/form-data'}})
            .then((result) => {
                if(result.data.status === "success"){
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    //reload datatable
                    $("#dt_citados_agenda").DataTable().ajax.reload(null,false);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: result.data.message,
                        icon: "error"
                    });
                }
            }).catch((err) => {
                console.log(err);
            });
        }
      });
}

function loadDataCita(codigo_empleado){
    axios.post(route('cita.data.obtener'),{
        codigo_empleado: codigo_empleado
    },{
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((result) => {
        if(result.data !== "not-data"){
            document.querySelector('input[name="nombre_empleado"]').value = result.data.nombre;
            document.querySelector('input[name="telefono"]').value = result.data.telefono;
        }else{
            document.querySelector('input[name="nombre_empleado"]').value = '';
            document.querySelector('input[name="telefono"]').value = '';
        }
    }).catch((err) => {
        console.log(err);
    });
}

function getCalendarCitados() {
    axios.post(route('cita.calendarAll'))
        .then((result) => {
            let eventData = result.data;
            let calendarEl = document.getElementById('calendar-agenda-cita');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es",
                allDayText: 'Todos',
                headerToolbar: {
                    right: 'dayGridMonth listWeek today prev next'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',      // Cambiar "Month" a "Mes"
                    list: 'Lista'
                },
                dateClick: function (info) {
                    let fechaSelected = info.dateStr;
                    let formatDate = moment(fechaSelected).format('DD/MM/YYYY');
                    let form_data_cita = document.getElementById('form_data_cita');
                    //clear form
                    if(form_data_cita) { form_data_cita.reset();}
                    $("#hora_cita").selectize()[0].selectize.clear();

                    let currentDay = moment.tz('America/El_Salvador');

                    //validar que la fecha sea actual o mayor
                    let fecha1 = moment(fechaSelected).format('YYYY-MM-DD');
                    let fecha2 = moment(currentDay).format('YYYY-MM-DD');

                    if (fecha1 >= fecha2) {
                        agendarCita(formatDate);
                    }else{
                        Swal.fire({
                            title: "Aviso",
                            text: 'Fecha no válida.',
                            icon: "warning"
                        });
                    }              
                },
                eventClick: function (info) {
                    let eventDate = info.event.start;
                    let formatDate = moment(eventDate).format('YYYY-MM-DD');
                    //mostrar button pdf
                    document.getElementById('btn_views_pdf').style.display = 'block';
                    
                    showCitasAgendadas(formatDate);
                },
                events: eventData,
                eventColor: '#3788d8',
                eventBorderColor: 'transparent'
            });
            calendar.render();
        }).catch((err) => {
            console.log(err);
        });
}

function showCitasAgendadas(fecha = '') {
    $("#modal_citas").modal('show');
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let encripted_fecha = btoa(token_csrf + '_' + fecha);
    sessionStorage.setItem('fecha_cita_pdf',encripted_fecha);
    dataTable("dt_citados_agenda", route('cita.citados.emp'), { fecha: fecha });
}

function agendarCita(fecha) {
    //remove session localSession
    sessionStorage.removeItem('edic_cita_id');

    document.getElementById('btnSaveCitaCalendar').innerHTML = `<i class="bi bi-floppy"></i> GUARDAR`; //text button text modify
    document.getElementById('display_title_modal').textContent = 'REGISTRAR NUEVA CITA';
    document.getElementById('display_title_card').textContent = 'AGENDAR NUEVA CITA';

    let inputFechaCita = document.getElementById('fecha_cita');
    inputFechaCita.value = fecha;
    setTimeout(() => {
        inputFechaCita.dispatchEvent(new Event('change'));
    }, 1000);

    $("#modal_agendar_cita").modal('show');
    let sucursal_id = document.querySelector('select[name="sucursal_emp"]');
    if (sucursal_id.value !== "") {
        verifyHorariosSuc(fecha, sucursal_id.value);
    } else {
        Toast.fire({
            icon: "warning",
            title: "Por favor, seleccione una sucursal para comprobar la disponibilidad de horarios."
        });
    }
}

function verifyHorariosSuc(fecha, sucursal_id, callback = '') {
    axios.post(route('cita.public.horarios'), { fecha_cita: fecha, sucursal_id: sucursal_id }, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Content-Encoding': 'gzip'
        }
    }).then((result) => {
        let data = result.data;
        let select_horario = $('#hora_cita').selectize()[0].selectize;
        select_horario.clear();
        select_horario.clearOptions();
        if(data.length === 0){
            Toast.fire({
                icon: "warning",
                title: `Horarios no disponibles.`
            });
        }
        data.forEach((horario) => {
            select_horario.addOption({
                value: horario.hora,
                text: horario.hora
            });
        });
        select_horario.refreshItems();
        if(typeof callback === "function"){
            callback();
        }
    }).catch((err) => {
        console.log(err);
    });
}