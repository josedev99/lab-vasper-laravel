document.addEventListener('DOMContentLoaded', (e) => {
    dataTableCustom('tabla_credit_planilla', route('credito.listar.planilla'), {estado_credito: 'resumen'}, [5, 6, 7], true);
    //Estado de credito
    let estado_credito = document.getElementById('estado_credito');
    if (estado_credito) {
        estado_credito.addEventListener('change', (e) => {
            let tipo_credito = document.querySelectorAll('button[option="credito"]');
            tipo_credito.forEach((element) => {
                option_active = element.dataset.tipo;
                    let data = {
                        estado_credito: e.target.value
                    };
                    if (element.dataset.tipo === "c-planilla") {
                        dataTableCustom('tabla_credit_planilla', route('credito.listar.planilla'), data, [5, 6, 7], true);
                    }
            })
        })
    }
})

function listPacientes(element) {
    let paciente_id = element.dataset.titular_id;
    let numero_orden = element.dataset.numero_orden;
    let titular = element.dataset.titular;
    axios.post(route('credito.detalle.obtener'), { paciente_id, numero_orden })
        .then((result) => {
            let data = result.data;
            document.getElementById('titular').textContent = "| TITULAR: " + titular;
            showItemBenefiServicios(data.infoPacienteCredito);
            $("#modalListadoCreditos").modal('show');
        })
        .catch((err) => {
            console.log(err)
        })
}

function showItemBenefiServicios(data) {
    $("#trowBeneficiosService").html('');
    let html = '';
    data.forEach((item) => {
        html += `
        <tr style="font-size:12px;text-align: center">
            <td colspan="4" style="width: 100%;border:1px solid #f2f2f2;background:#c5e2f6"><strong>${item.paciente + ' | FECHA DE VENTA: ' + item.fechaHora}</strong></th>
        </tr>`;

        item.productos.forEach((producto) => {
            html += `
            <tr style="font-size:12px;text-align: center">
                <td style="width: 15%;border:1px solid #f2f2f2">${producto.cantidad}</th>
                <td style="width: 20%;border:1px solid #f2f2f2">${producto.categoria}</td>
                <td style="width: 50%;border:1px solid #f2f2f2">${producto.descripcion}</td>
                <td style="width: 15%;border:1px solid #f2f2f2"><strong>$${parseFloat(producto.precio_final).toFixed(2)}</strong></td>
            </tr>`;
        });

        html += `
        <tr style="font-size:12px;text-align: center;background:#c8c8c8">
            <td colspan="3" style="width: 85%;border:1px solid #f2f2f2;text-align:right">TOTAL</th>
            <td colspan="1" style="width: 15%;border:1px solid #f2f2f2"><strong>$${parseFloat(item.totalMonto).toFixed(2)}</strong></th>
        </tr>`;
    });

    $("#trowBeneficiosService").html(html);
}

function HistorialModal(numero_venta, numero_orden, titular_id, cobros) {
    sessionStorage.setItem('cobro-paciente_id', titular_id);//Set titular_id en session
    axios.post(route('credito.historial.abonos'), { numero_venta: numero_venta, numero_orden, titular_id, cobros })
        .then(function (response) {
            CreditoData = response.data.credito['0'];

            if (CreditoData) {
                // Obtén la referencia de la tabla
                var tableBody = document.getElementById('trow');

                while (tableBody.firstChild) {
                    tableBody.removeChild(tableBody.firstChild);
                }
                // Crea una nueva fila
                var newRow = document.createElement('tr');

                // Agrega celdas con los datos
                var pacienteCell = document.createElement('td');
                pacienteCell.textContent = CreditoData.nombres;
                pacienteCell.classList.add('centered-cell'); // Agrega la clase
                newRow.appendChild(pacienteCell);

                var duiCell = document.createElement('td');
                duiCell.textContent = CreditoData.dui;
                duiCell.classList.add('centered-cell');
                newRow.appendChild(duiCell);

                var montoCell = document.createElement('td');
                montoCell.textContent = CreditoData.monto;
                montoCell.classList.add('centered-cell');
                newRow.appendChild(montoCell);

                var sumaMontosAbonoCell = document.createElement('td');
                sumaMontosAbonoCell.textContent = CreditoData.AbonosAnt;
                sumaMontosAbonoCell.classList.add('centered-cell');
                newRow.appendChild(sumaMontosAbonoCell);

                var saldoCell = document.createElement('td');
                saldoCell.textContent = CreditoData.saldo;
                saldoCell.classList.add('centered-cell');
                newRow.appendChild(saldoCell);

                // Agrega la nueva fila a la tabla
                tableBody.appendChild(newRow);
                dataTable('datatable_historial_abonos', route('credito.historial.listar'), { 'numero_venta': numero_venta });
            } else {
                // Manejar el caso cuando no hay datos
            }

            $("#historial_ab").modal('show');
        })
        .catch(function (error) {
            console.error(error);
            Swal.fire({
                title: "Error",
                text: 'Ha ocurrido un error al obtener la información.',
                icon: "error"
            });
        });
}