let graficaBarra; // Variable para almacenar la gráfica

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Flatpickr con rango de fechas
    const rangeCalendar = flatpickr("#range_calendar_v", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "es", // Asegúrate de que el idioma español esté configurado
        onClose: function (selectedDates, dateStr, instance) {
            enviarSeleccion(dateStr); // Pasa el rango de fechas formateado
        }
    });

    // Abrir el calendario al hacer clic en el botón
    document.getElementById('open_calendar').addEventListener('click', function () {
        rangeCalendar.open();
    });
});


function enviarSeleccion(dateStr) {
    if (!dateStr.includes(' a ')) {
        console.error("Formato de fecha inválido:", dateStr);
        return;
    }

    if (graficaBarra) {
        graficaBarra.destroy();
    }

    const [startDate, endDate] = dateStr.split(' a ');
    const selectInput = document.getElementById("selecc_planta").value;  // Obtiene el valor seleccionado del select


    axios.post('/datos-grafica_repor_vis', {
        start_date: startDate,
        end_date: endDate,
        planta: selectInput 
    })
        .then((response) => {
            const data = response.data;
            if (!Array.isArray(data)) {
                console.error("Respuesta inesperada del servidor:", data);
                return;
            }

            const sucursalAgrupado = data.reduce((acc, item) => {
                if (!acc[item.sucursal]) {
                    acc[item.sucursal] = { Alterado: 0, Normal: 0 };
                }
                acc[item.sucursal][item.evaluacion] = item.total || 0;
                return acc;
            }, {});

            // Calcular el total de evaluaciones
            let totalEvaluaciones = 0;
            Object.values(sucursalAgrupado).forEach((evaluaciones) => {
                totalEvaluaciones += evaluaciones.Alterado + evaluaciones.Normal;
            });

            // Actualizar el contenido del botón en la vista
            const totalBadge = document.querySelector('#totalAtendidosBadge');
            if (totalBadge) {
                totalBadge.textContent = totalEvaluaciones;
            } else {
                console.error("Badge para 'Total Atendidos' no encontrado.");
            }

            const sucursales = Object.keys(sucursalAgrupado);
            const alteradoData = [];
            const normalData = [];
            const alteradoPorcentajes = [];
            const normalPorcentajes = [];

            sucursales.forEach(sucursal => {
                const totalPorSucursal = sucursalAgrupado[sucursal].Alterado + sucursalAgrupado[sucursal].Normal;
                const porcentajeAlterado = totalPorSucursal > 0
                    ? ((sucursalAgrupado[sucursal].Alterado / totalPorSucursal) * 100).toFixed(1)
                    : 0;
                const porcentajeNormal = totalPorSucursal > 0
                    ? ((sucursalAgrupado[sucursal].Normal / totalPorSucursal) * 100).toFixed(1)
                    : 0;

                alteradoData.push(sucursalAgrupado[sucursal].Alterado);
                normalData.push(sucursalAgrupado[sucursal].Normal);
                alteradoPorcentajes.push(porcentajeAlterado);
                normalPorcentajes.push(porcentajeNormal);
            });

            const canvas = document.getElementById('graficaBarraVisual');
            if (!canvas) {
                console.error("Canvas 'graficaBarraVisual' no encontrado.");
                return;
            }

            const ctx = canvas.getContext('2d');
            graficaBarra = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sucursales,
                    datasets: [
                        {
                            label: 'Alterado',
                            data: alteradoData,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                                formatter: (value, ctx) => {
                                    const index = ctx.dataIndex;
                                    return `${alteradoPorcentajes[index]}%`;
                                }
                            }
                        },
                        {
                            label: 'Normal',
                            data: normalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                                formatter: (value, ctx) => {
                                    const index = ctx.dataIndex;
                                    return `${normalPorcentajes[index]}%`;
                                }
                            }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        },
                        datalabels: {
                            color: 'black',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Sucursales'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...alteradoData, ...normalData) + 1,
                            title: {
                                display: true,
                                text: 'Total Evaluaciones'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
            // Inicializar los contadores
            let totalNormales = 0;
            let totalAlterados = 0;

            // Sumar los totales según la evaluación
            data.forEach((item) => {
                if (item.evaluacion === 'Normal') {
                    totalNormales += item.total;
                } else if (item.evaluacion === 'Alterado') {
                    totalAlterados += item.total;
                }
            });

            // Actualizar la tabla de Normales
            const tablaNormales = document.getElementById('tablaResumenNormalesOpto');
            tablaNormales.innerHTML = `
                <tr>
                    <td>${totalNormales}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="verDetalles('Normal')">
                        <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                </tr>
            `;

            // Actualizar la tabla de Alterados
            const tablaAlterados = document.getElementById('tablaResumenAlteradosOpto');
            tablaAlterados.innerHTML = `
                <tr>
                    <td>${totalAlterados}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="verDetalles('Alterado')">
                        <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                </tr>
            `;
        })
        .catch((error) => {
            console.error('Error al obtener los datos de la gráfica:', error);
        });
}


function enviarSeleccionVisual(){
   
        const dateStr = document.getElementById("range_calendar_v").value; // Obtiene el rango de fechas
   
        // Verificar si los campos están llenos
        if (!dateStr) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Por favor completa todos los campos antes de continuar.",
              });
            return;
        }
        enviarSeleccion(dateStr)    
}

function verDetalles(evaluacion) {
    const resultadosDiv = document.getElementById('resultados-examenes1');
    resultadosDiv.innerHTML = ''; // Limpiar el contenido previo
    // Cambiar el título del modal
    const modalTitle = document.querySelector("#modal_det_resumenVisual .modal-title");
    modalTitle.textContent = `Detalles de exámenes: ${evaluacion}`;

    // Obtener los valores del rango de fechas y del select
    const rangeInput = document.getElementById("range_calendar_v").value; // Obtiene el rango de fechas
    const selectInput = document.getElementById("selecc_planta").value;  // Obtiene el valor seleccionado del select
    const [startDate, endDate] = rangeInput.split(" a "); // Ajusta el separador según tu formato

    axios.post('/Detalles_NormaAlterados', {
        start_date: startDate,
        end_date: endDate,
        planta: selectInput,
        evaluacion: evaluacion
    })
    .then((response) => {
        const data = response.data;
        console.log(data)
        // Seleccionar contenedor donde estará la tabla
        const detallesContainer = document.getElementById('empleados-lista1');
        detallesContainer.innerHTML = ''; // Limpiar contenido previo

        // Crear la tabla
        const table = document.createElement('table');
        table.classList.add('table', 'table-bordered', 'table-sm');
        table.style.width = '100%';
        table.style.borderCollapse = 'collapse';

        // Encabezado de la tabla
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        headerRow.style.backgroundColor = '#f2f2f2';

        // Columnas de encabezado
        const headers = ['Nombre','DUI', 'Ocupación', 'Departamento', 'Acciones'];
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.padding = '8px';
            th.style.textAlign = 'center';
            th.style.backgroundColor = '#d8efff';
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        data.forEach(resultadosExamenes => {
            const row = document.createElement('tr');
            const NombreCell = document.createElement('td');
            NombreCell.style.padding = '8px';
            NombreCell.style.textAlign = 'center';

            const nameInput = document.createElement('input');
            nameInput.type = 'password';
            nameInput.value = resultadosExamenes.nombres || '-';
            nameInput.style.border = 'none';
            nameInput.style.background = 'transparent';
            nameInput.style.textAlign = 'center';
            nameInput.style.width = '80%';
            nameInput.readOnly = true; 
            const toggleIcon = document.createElement('i');
            toggleIcon.classList.add('bi', 'bi-eye'); 
            toggleIcon.style.cursor = 'pointer';
            toggleIcon.style.marginLeft = '8px';

            toggleIcon.addEventListener('click', () => {
                if (nameInput.type === 'password') {
                    nameInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    nameInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            });

            NombreCell.appendChild(nameInput);
            NombreCell.appendChild(toggleIcon);
            row.appendChild(NombreCell);
            
            
            // Celda del DUI
            const duiCell = document.createElement('td');
            duiCell.textContent = resultadosExamenes.dui || 'No registrado';
            duiCell.style.padding = '8px';
            duiCell.style.textAlign = 'center';
            row.appendChild(duiCell);

            // Celda de la ocupación
            const ocupacionCell = document.createElement('td');
            ocupacionCell.textContent = resultadosExamenes.ocupacion || 'Sin especificar';
            ocupacionCell.style.padding = '8px';
            ocupacionCell.style.textAlign = 'center';
            row.appendChild(ocupacionCell);

            // Celda del departamento
            const departamentoCell = document.createElement('td');
            departamentoCell.textContent = resultadosExamenes.empresa_dept || 'Sin asignar';
            departamentoCell.style.padding = '8px';
            departamentoCell.style.textAlign = 'center';
            row.appendChild(departamentoCell);

            // Celda del ícono de ojo
            const iconCell = document.createElement('td');
            iconCell.style.textAlign = 'center';
            const eyeIcon = document.createElement('i');
            eyeIcon.classList.add('bi', 'bi-eye'); // Bootstrap icon
            eyeIcon.style.cursor = 'pointer';
            eyeIcon.addEventListener('click', () => mostrarResultados(resultadosExamenes));
            iconCell.appendChild(eyeIcon);
            row.appendChild(iconCell);

            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        detallesContainer.appendChild(table);

        // Mostrar el modal
        $("#modal_det_resumenVisual").modal("show");
    })
    .catch((error) => {
        console.error('Error al obtener los datos de la gráfica:', error);
    });
}

function mostrarResultados(resultadosExamenes) {
    const resultadosDiv = document.getElementById('resultados-examenes1');
    resultadosDiv.innerHTML = ''; // Limpiar el contenido previo
        resultOptometria('resultados-examenes1', resultadosExamenes);

}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////- JS PARA ANALISIS DE RESULTADOS - /////////////////////////////////////////////////

let graficaBarraAnaRes; // Variable para almacenar la gráfica

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Flatpickr con rango de fechas
    const rangeCalendar = flatpickr("#rangeAnaRes", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "es", // Asegúrate de que el idioma español esté configurado
        onClose: function (selectedDates, dateStr, instance) {
            enviarSeleccionAnaRes(); // Pasa el rango de fechas formateado
        }
    });

    // Abrir el calendario al hacer clic en el botón
    document.getElementById('open_calendar1').addEventListener('click', function () {
        rangeCalendar.open();
    });
});




function enviarSeleccionAnaRes() {
    const dateStr = document.getElementById('rangeAnaRes').value;

    if (!dateStr.includes(' a ')) {
        console.error("Formato de fecha inválido:", dateStr);
        return;
    }

    if (graficaBarraAnaRes) {
        graficaBarraAnaRes.destroy();
    }

    const [startDate, endDate] = dateStr.split(' a ');
    const selectPlanta = document.getElementById("selecc_plantaRes").value;  
    const selectEtario = document.getElementById("selecc_etario").value;
    const selectGenero = document.getElementById("selecc_genero").value;

    axios.post('/datos-grafica_analisis_res', {
        start_date: startDate,
        end_date: endDate,
        planta: selectPlanta,
        etario: selectEtario,
        genero: selectGenero,
    })
        .then((response) => {
            const data = response.data;
            console.log(data)
            if (!Array.isArray(data)) {
                console.error("Respuesta inesperada del servidor:", data);
                return;
            }

            const sucursalAgrupado = data.reduce((acc, item) => {
                if (!acc[item.sucursal]) {
                    acc[item.sucursal] = { Alterado: 0, Normal: 0 };
                }
                acc[item.sucursal][item.evaluacion] = item.total || 0;
                return acc;
            }, {});

 /*            // Calcular el total de evaluaciones
            let totalEvaluaciones = 0;
            Object.values(sucursalAgrupado).forEach((evaluaciones) => {
                totalEvaluaciones += evaluaciones.Alterado + evaluaciones.Normal;
            });

            // Actualizar el contenido del botón en la vista
            const totalBadge = document.querySelector('#totalAtendidosBadge');
            if (totalBadge) {
                totalBadge.textContent = totalEvaluaciones;
            } else {
                console.error("Badge para 'Total Atendidos' no encontrado.");
            }
 */
            const sucursales = Object.keys(sucursalAgrupado);
            const alteradoData = [];
            const normalData = [];
            const alteradoPorcentajes = [];
            const normalPorcentajes = [];

            sucursales.forEach(sucursal => {
                const totalPorSucursal = sucursalAgrupado[sucursal].Alterado + sucursalAgrupado[sucursal].Normal;
                const porcentajeAlterado = totalPorSucursal > 0
                    ? ((sucursalAgrupado[sucursal].Alterado / totalPorSucursal) * 100).toFixed(1)
                    : 0;
                const porcentajeNormal = totalPorSucursal > 0
                    ? ((sucursalAgrupado[sucursal].Normal / totalPorSucursal) * 100).toFixed(1)
                    : 0;

                alteradoData.push(sucursalAgrupado[sucursal].Alterado);
                normalData.push(sucursalAgrupado[sucursal].Normal);
                alteradoPorcentajes.push(porcentajeAlterado);
                normalPorcentajes.push(porcentajeNormal);
            });

            const canvas = document.getElementById('graficaBarraResultados');
            if (!canvas) {
                console.error("Canvas 'graficaBarraResultados' no encontrado.");
                return;
            }

            const ctx = canvas.getContext('2d');
            graficaBarraAnaRes = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sucursales,
                    datasets: [
                        {
                            label: 'Alterado',
                            data: alteradoData,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                                formatter: (value, ctx) => {
                                    const index = ctx.dataIndex;
                                    return `${alteradoPorcentajes[index]}%`;
                                }
                            }
                        },
                        {
                            label: 'Normal',
                            data: normalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                                formatter: (value, ctx) => {
                                    const index = ctx.dataIndex;
                                    return `${normalPorcentajes[index]}%`;
                                }
                            }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        },
                        datalabels: {
                            color: 'black',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Sucursales'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...alteradoData, ...normalData) + 1,
                            title: {
                                display: true,
                                text: 'Total Evaluaciones'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
            // Inicializar los contadores
            let totalNormales = 0;
            let totalAlterados = 0;

            // Sumar los totales según la evaluación
            data.forEach((item) => {
                if (item.evaluacion === 'Normal') {
                    totalNormales += item.total;
                } else if (item.evaluacion === 'Alterado') {
                    totalAlterados += item.total;
                }
            });

            // Actualizar la tabla de Normales
            const tablaNormales = document.getElementById('tablaResumenNormalesOptoAnalisis');
            tablaNormales.innerHTML = `
                <tr>
                    <td>${totalNormales}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="verDetallesAnalisis('Normal')">
                        <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                </tr>
            `;

            // Actualizar la tabla de Alterados
            const tablaAlterados = document.getElementById('tablaResumenAlteradosOptoAnalisis');
            tablaAlterados.innerHTML = `
                <tr>
                    <td>${totalAlterados}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="verDetallesAnalisis('Alterado')">
                        <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                </tr>
            `;
        })
        .catch((error) => {
            console.error('Error al obtener los datos de la gráfica:', error);
        });
}



function verDetallesAnalisis(evaluacion) {
    const resultadosDiv = document.getElementById('resultados-examenes1');
    resultadosDiv.innerHTML = ''; // Limpiar el contenido previo
    // Cambiar el título del modal
    const modalTitle = document.querySelector("#modal_det_resumenVisual .modal-title");
    modalTitle.textContent = `Detalles de exámenes: ${evaluacion}`;

    const rangeInput = document.getElementById("rangeAnaRes").value; 

    const [startDate, endDate] = rangeInput.split(' a ');

    const selectPlanta = document.getElementById("selecc_plantaRes").value;  
    const selectEtario = document.getElementById("selecc_etario").value;
    const selectGenero = document.getElementById("selecc_genero").value;


 
    axios.post('/Detalles_NormaAlterados_Analisis', {
        start_date: startDate,
        end_date: endDate,
        planta: selectPlanta,
        etario: selectEtario,
        genero: selectGenero,
        evaluacion: evaluacion
    })
    .then((response) => {
        const data = response.data;
        console.log(data)
        // Seleccionar contenedor donde estará la tabla
        const detallesContainer = document.getElementById('empleados-lista1');
        detallesContainer.innerHTML = ''; // Limpiar contenido previo

        // Crear la tabla
        const table = document.createElement('table');
        table.classList.add('table', 'table-bordered', 'table-sm');
        table.style.width = '100%';
        table.style.borderCollapse = 'collapse';

        // Encabezado de la tabla
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        headerRow.style.backgroundColor = '#f2f2f2';

        // Columnas de encabezado
        const headers = ['Nombre','DUI', 'Ocupación', 'Departamento', 'Acciones'];
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.padding = '8px';
            th.style.textAlign = 'center';
            th.style.backgroundColor = '#d8efff';
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        data.forEach(resultadosExamenes => {
            const row = document.createElement('tr');
            const NombreCell = document.createElement('td');
            NombreCell.style.padding = '8px';
            NombreCell.style.textAlign = 'center';

            const nameInput = document.createElement('input');
            nameInput.type = 'password';
            nameInput.value = resultadosExamenes.nombres || '-';
            nameInput.style.border = 'none';
            nameInput.style.background = 'transparent';
            nameInput.style.textAlign = 'center';
            nameInput.style.width = '80%';
            nameInput.readOnly = true; 
            const toggleIcon = document.createElement('i');
            toggleIcon.classList.add('bi', 'bi-eye'); 
            toggleIcon.style.cursor = 'pointer';
            toggleIcon.style.marginLeft = '8px';

            toggleIcon.addEventListener('click', () => {
                if (nameInput.type === 'password') {
                    nameInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    nameInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            });

            NombreCell.appendChild(nameInput);
            NombreCell.appendChild(toggleIcon);
            row.appendChild(NombreCell);
            
            
            // Celda del DUI
            const duiCell = document.createElement('td');
            duiCell.textContent = resultadosExamenes.dui || 'No registrado';
            duiCell.style.padding = '8px';
            duiCell.style.textAlign = 'center';
            row.appendChild(duiCell);

            // Celda de la ocupación
            const ocupacionCell = document.createElement('td');
            ocupacionCell.textContent = resultadosExamenes.ocupacion || 'Sin especificar';
            ocupacionCell.style.padding = '8px';
            ocupacionCell.style.textAlign = 'center';
            row.appendChild(ocupacionCell);

            // Celda del departamento
            const departamentoCell = document.createElement('td');
            departamentoCell.textContent = resultadosExamenes.empresa_dept || 'Sin asignar';
            departamentoCell.style.padding = '8px';
            departamentoCell.style.textAlign = 'center';
            row.appendChild(departamentoCell);

            // Celda del ícono de ojo
            const iconCell = document.createElement('td');
            iconCell.style.textAlign = 'center';
            const eyeIcon = document.createElement('i');
            eyeIcon.classList.add('bi', 'bi-eye'); // Bootstrap icon
            eyeIcon.style.cursor = 'pointer';
            eyeIcon.addEventListener('click', () => mostrarResultados(resultadosExamenes));
            iconCell.appendChild(eyeIcon);
            row.appendChild(iconCell);

            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        detallesContainer.appendChild(table);

        // Mostrar el modal
        $("#modal_det_resumenVisual").modal("show");
    })
    .catch((error) => {
        console.error('Error al obtener los datos de la gráfica:', error);
    }); 

}
