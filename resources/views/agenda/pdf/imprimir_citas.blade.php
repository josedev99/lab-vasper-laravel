<?php
date_default_timezone_set('America/El_Salvador'); 
//$hoy = date("d-m-Y");
//$dateTime= date("d-m-Y H:i:s");
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>.::Control de asistencia de citas ::.</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        html {
            margin-top: 5px;
            margin-left: 15px;
            margin-right: 15px;
            margin-bottom: 5px;
        }

        .input-report {
            font-family: Helvetica, Arial, sans-serif;
            border: none;
            border-bottom: 2.2px dotted #C8C8C8;
            text-align: left;
            background-color: transparent;
            font-size: 13px;
            width: 100%;
            padding: 5px 0px;
        }

        #watermark {
            position: fixed;
            top: 15%;
            width: 100%;
            opacity: .20;
            z-index: -1000;
        }

        #tabla_reporte_citas {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
            text-align: center;
            text-transform: uppercase;
        }

        #tabla_reporte_citas td,
        #tabla_reporte_citas th {
            border: 1px solid #ddd;
            padding: 6px 4px;
        }

        #tabla_reporte_citas tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #tabla_reporte_citas tr:hover {
            background-color: #ddd;
        }

        #tabla_reporte_citas th {
            padding-top: 2px;
            padding-bottom: 2px;
            text-align: center;
            background-color: #4c5f70;
            color: white;
        }
    </style>

</head>

<body>

    <html>
    <div id="watermark">
        {{-- <img src="../dist/img/Logo_Gobierno.jpg" width="700" height="700" /> --}}
    </div>

    <table style="width: 100%;margin-top:2px" width="100%">
        <td width="25%" style="width:10%;margin:0px">
            @if($empresa['logo'] != "")
            <img src='{{ "storage/".$empresa['logo'] }}' width="90" height="50" />
            @endif
        </td>

        <td width="60%" style="width:75%;margin:0px">
            <table style="width:100%">
                <tr>
                    <td
                        style="text-align: center;margin-top: 0px;font-size:18px;font-family: Helvetica, Arial, sans-serif;">
                        <b>{{ strtoupper($empresa['nombre']) }}</b>
                    </td>
                </tr>
                <tr>
                    <td
                        style="text-align: center;margin-top: 0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;color: #012970">
                        <b>CONTROL DE ASISTENCIA DE CITAS</b>
                    </td>
                </tr>
                <tr>
                    <td
                        style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;color: #012970">
                        <b>FECHA: {{ date("d-m-Y",strtotime($fecha_cita)) }}</b>
                    </td>
                </tr>
            </table>
        </td>

        <td width="25%" style="width:15%;margin:0px">
        </td>
    </table>
    <!--fin tabla-->

    <table width="100%" style="width: 100%;margin-top: 0px i !important ">
        <tr>
            <td colspan="60" style="width: 60%;"><input type="text" class="input-report" value="REVISADO POR:"></td>
            <td colspan="20" style="width: 20%;padding: 0px 30px"><input type="text" class="input-report" value="FIRMA: "></td>
            <td colspan="20" style="width: 20%;padding-right: 10px"><input type="text" class="input-report" value="SELLO: "></td>
        </tr>
    </table>
    <table width="100%" id="tabla_reporte_citas" data-order='[[ 1, "desc" ]]' style="margin: 4px 0px">
        <tr>
            <th colspan="5" style="width:5%">#</th>
            <th colspan="10" style="width:10%">CODIGO EMP.</th>
            <th colspan="35" style="width:35%">COLABORADOR</th>
            <th colspan="10" style="width:10%">TELÉFONO</th>
            <th colspan="8" style="width:8%">FECHA</th>
            <th colspan="8" style="width:8%">HORA</th>
            <th colspan="24" style="width:24%">FIRMA</th>
        </tr>
        <tbody style="font-size:11px">
            @foreach($data as $value)
            <tr>
                <td colspan="5" style="width:5%;">{{ $value["id"] }}</td>
                <td colspan="10" style="width:10%">{{ $value["codigo"] }}</td>
                <td colspan="35" style="width:35%">{{ $value["nombre"] }}</td>
                <td colspan="10" style="width:10%">{{ $value["telefono"] }}</td>
                <td colspan="8" style="width:8%">{{ $value["fecha"] }}</td>
                <td colspan="8" style="width:8%">{{ $value["hora"] }}</td>
                <td colspan="24" style="width:24%"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div style="page-break-after:always;"></div> --}}
</body>

</html>