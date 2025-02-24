let graficaPastel; // Variable para almacenar la gráfica
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Flatpickr con rango de fechas
    const rangeCalendar = flatpickr("#range_calendar", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "es", // Asegúrate de que el idioma español esté configurado
        onClose: function (selectedDates, dateStr, instance) {
            enviarSeleccion()
            console.log("Fechas seleccionadas:", dateStr); // Imprime las fechas seleccionadas
        }
    });

    // Abrir el calendario al hacer clic en el botón
    document.getElementById('open_calendar').addEventListener('click', function () {
        rangeCalendar.open();
    });
});

function enviarSeleccion() {
    const idJornada = document.getElementById('selecc_jornada').value;

    // Eliminar la gráfica anterior si existe
    if (graficaPastel) {
        graficaPastel.destroy(); // Destruye la gráfica previa
    }

    // Realizamos la solicitud POST usando Axios para obtener los datos de la gráfica
    axios.post('/datos-grafica_repor', { id_jornada: idJornada })
        .then((response) => {
            const data = response.data;
            // Procesamos los datos y actualizamos la gráfica
            const categorias = data.map(item => item.categoria);
            const totales = data.map(item => item.total);
            const totalSum = totales.reduce((sum, val) => sum + val, 0); // Suma total para el porcentaje

            const ctx = document.getElementById('jornadasGraf').getContext('2d');
            graficaPastel = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: categorias,
                    datasets: [{
                        data: totales,
                        backgroundColor: ['#ff6383', '#4bc0c0'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Estado de las Jornadas'
                        },
                        datalabels: {
                            color: '#fff', // Color del texto
                            formatter: (value, context) => {
                                const percentage = ((value / totalSum) * 100).toFixed(2);
                                return `${value} (${percentage}%)`; // Muestra el valor y el porcentaje
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] 
            });
        })
        .catch((error) => {
            console.error('Error al obtener los datos de la gráfica:', error);
        });

    enviarSeleccionTabla();
}

function enviarSeleccionTabla() {
    const idJornada = document.getElementById('selecc_jornada').value;

    axios.post('/datos-tabla', { id_jornada: idJornada })
        .then((response) => {
            const data = response.data;
            const tablaResumen = document.getElementById('tablaResumenNormales');
            tablaResumen.innerHTML = '';

            // Procesar datos normales
            data.normales.forEach(item => {
                const fila = `
                    <tr>
                        <td>${item.examen}</td>
                        <td>${item.cantidad}</td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" 
                                    data-emp_id="${item.ids}" 
                                    title="Ver detalles de normales" 
                                    onclick="verDetalles('normales', '${item.ids}', '${item.id_ex}')">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tablaResumen.innerHTML += fila;
            });
            const tablaResumen1 = document.getElementById('tablaResumenAlterados');
            tablaResumen1.innerHTML = '';

            // Procesar datos alterados
            data.alterados.forEach(item => {
                const fila = `
                    <tr>
                        <td>${item.examen}</td>
                        <td>${item.cantidad}</td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm" 
                                    data-emp_id="${item.ids}" 
                                    title="Ver detalles de alterados" 
                                    onclick="verDetalles('alterados', '${item.ids}', '${item.id_ex}')">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tablaResumen1.innerHTML += fila;
            });
        })
        .catch((error) => {
            console.error('Error al obtener los datos de la tabla:', error);
        });
}




function verDetalles(tipo, ids, id_ex) {
    const empleadosIds = ids.split(',');
    const idJornada = document.getElementById('selecc_jornada').value;

    axios.post('/ver-detalles-empleados', { empleadosIds: empleadosIds, id_jornada: idJornada, examen_id: id_ex })
        .then(response => {
            const empleadosLista = document.getElementById('empleados-lista');
            const resultadosExamenes = document.getElementById('resultados-examenes');
            
            // Limpiar contenido previo
            empleadosLista.innerHTML = '';
            resultadosExamenes.innerHTML = '';

            // Crear la tabla de empleados
            const table = document.createElement('table');
            table.classList.add('table', 'table-bordered', 'table-sm');
            table.style.width = '100%';
            table.style.borderCollapse = 'collapse';

            // Encabezado de la tabla
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            headerRow.style.backgroundColor = '#f2f2f2';

            // Columnas de encabezado
            const codigoHeader = document.createElement('th');
            codigoHeader.textContent = 'Código Empleado';
            codigoHeader.style.padding = '8px';
            codigoHeader.style.textAlign = 'center';
            codigoHeader.style.backgroundColor = '#d8efff';

            const areaHeader = document.createElement('th');
            areaHeader.textContent = 'Área';
            areaHeader.style.padding = '8px';
            areaHeader.style.textAlign = 'center';
            areaHeader.style.backgroundColor = '#d8efff';


            const cargoHeader = document.createElement('th');
            cargoHeader.textContent = 'Cargo';
            cargoHeader.style.padding = '8px';
            cargoHeader.style.textAlign = 'center';
            cargoHeader.style.backgroundColor = '#d8efff';

            const examenesHeader = document.createElement('th');
            examenesHeader.textContent = 'Exámenes';
            examenesHeader.style.padding = '8px';
            examenesHeader.style.textAlign = 'center';
            examenesHeader.style.backgroundColor = '#d8efff';

            headerRow.appendChild(codigoHeader);
            headerRow.appendChild(areaHeader);
            headerRow.appendChild(cargoHeader);
            headerRow.appendChild(examenesHeader);
            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Cuerpo de la tabla
            const tbody = document.createElement('tbody');
            response.data.forEach(empleado => {
                const row = document.createElement('tr');

                // Celda del código de empleado
                const codigoCell = document.createElement('td');
                codigoCell.textContent = empleado.codigo;
                codigoCell.style.padding = '8px';
                codigoCell.style.textAlign = 'center';
                row.appendChild(codigoCell);

                // Celda del área
                const areaCell = document.createElement('td');
                areaCell.textContent = empleado.area;
                areaCell.style.padding = '8px';
                areaCell.style.textAlign = 'center';
                row.appendChild(areaCell);

                // Celda del cargo
                const cargoCell = document.createElement('td');
                cargoCell.textContent = empleado.cargo;
                cargoCell.style.padding = '8px';
                cargoCell.style.textAlign = 'center';
                row.appendChild(cargoCell);

                // Celda del ícono de ojo
                const iconCell = document.createElement('td');
                iconCell.style.textAlign = 'center';
                const eyeIcon = document.createElement('i');
                eyeIcon.classList.add('bi', 'bi-eye'); // Bootstrap icon
                eyeIcon.style.cursor = 'pointer';
                eyeIcon.addEventListener('click', () => mostrarResultados(empleado.resultados_examenes));
                iconCell.appendChild(eyeIcon);
                row.appendChild(iconCell);

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            empleadosLista.appendChild(table);
        // Cambiar el título del modal según el tipo de evaluación
        const modalTitle = document.querySelector("#modal_det_resumen .modal-title");
        modalTitle.textContent = tipo === "normales" ? "Resultado Normal" : "Resultado Alterado";
            // Mostrar el modal
            $("#modal_det_resumen").modal('show');
        })
        .catch(error => {
            console.error('Error al cargar los detalles:', error);
        });
}

function mostrarResultados(resultadosExamenes) {
    const resultadosDiv = document.getElementById('resultados-examenes');
    resultadosDiv.innerHTML = ''; // Limpiar el contenido previo

    // Arrays de nombres de exámenes
    const array_nombres_examen_heces = ['HECES', 'EXAMENES GENERAL DE HECES', 'EGH'];
    const array_nombres_examen_orina = ['ORINA', 'EGO'];
    const array_nombres_examen_hemograma = ['HEMOGRAMA COMPLETO', 'HEMOGRAMA'];
    const array_opto = ['SALUD VISUAL (OPTOMETRÍA)','OPTOMETRIA'];

    resultadosExamenes.forEach((examen) => {
        // Convertir el nombre del examen a mayúsculas para asegurar coincidencias
        const examenNombre = examen.examen.toUpperCase();

        if (["ACIDO URICO", "GLUCOSA", "COLESTEROL"].includes(examenNombre)) {
            TableExamenesStandar('resultados-examenes', {
                examen: examen.examen,
                resultado: examen.resultado.resultado,
                valores_normales: examenNombre === "ACIDO URICO" ? 'Hombre: 3.4 - 7.0 mg/dl <br> Mujer: 2.4 - 5.7 mg/dl' :
                                 examenNombre === "GLUCOSA" ? '75-115 mg/dl' : 'Menos de 200 mg/dl'
            });
        } else if (array_nombres_examen_heces.includes(examenNombre)) {
            TableHecesExamen('resultados-examenes', examen.resultado);
        } else if (array_nombres_examen_orina.includes(examenNombre)) {
            TableOrinaExamen('resultados-examenes', examen.resultado);
        } else if (array_nombres_examen_hemograma.includes(examenNombre)) {
            TableHemogramaExamen('resultados-examenes', examen.resultado);
        } else if (array_opto.includes(examenNombre)) {
            resultOptometria('resultados-examenes', examen.resultado);
        } else {
            // Exámenes estándar que no están en ninguna categoría
            TableExamenesStandar('resultados-examenes', { examen: examen.examen });
        }
    });
}






