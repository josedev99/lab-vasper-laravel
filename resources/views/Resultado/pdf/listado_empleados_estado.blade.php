<?php
date_default_timezone_set('America/El_Salvador'); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilos/styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>.::LISTADADO DE COLABORADORES::.</title>
  <style>
    html {
      margin-top: 10px;
      margin-left: 28px;
      margin-right: 20px;
      margin-bottom: 10px;
    }

    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 12px;
      margin-top: 3px;
    }

    #pacientes {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
      text-align: center;
      text-transform: uppercase;
    }

    #pacientes td,
    #pacientes th {
      border: 1px solid #ddd;
      padding: 10px 8px;
    }

    #pacientes th {
      padding-top: 3px;
      padding-bottom: 3px;
      text-align: center;
      background-color: #020202;
      color: #f2f2f2;
    }

    .input-report {
      font-family: Helvetica, Arial, sans-serif;
      border: none;
      border-bottom: 2.2px dotted #C8C8C8;
      text-align: left;
      background-color: transparent;
      font-size: 12px;
      width: 90%;
      padding: 3px 10px
    }
  </style>
</head>

<body>
  @if(count($data_final) == 0)
  <table style="width: 100%;margin-top:2px">
    <tr>
      <td width="25%" style="width: 25%;margin:0px">
        @if($empresa['logo'] != "")
        <img src='{{ "storage/".$empresa['logo'] }}' width="100" height="60" />
        @endif
      </td>

      <td width="50%" style="width: 75%;margin:0px">
        <table style="width:100%">
          <tr>
            <td style="text-align: center;margin-top: 0px;font-family: Helvetica, Arial, sans-serif;">
              <b style="font-size:15px">{{ strtoupper($empresa['nombre']) }}</b> <br>
              <b style="font-size:14px;">{{ strtoupper($sucursal['nombre']) }}</b> <br><br>
              <h5 style="font-size:13px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin: 0px">-</h5>
            </td>
          </tr>
        </table>
        <!--fin segunda tabla-->
      </td>
      <td width="25%" style="width: 25%;margin:0px">
        <table>
          <tr>
            <td style="text-align:right; font-size:12px;color: #008C45"><strong></strong></td>
          </tr>
          <tr>
            <td style="color:red;text-align:right; font-size:12px;color: #CD212A"><strong>&nbsp;<span></strong></td>
          </tr>
        </table>
        <!--fin segunda tabla-->
      </td>
      <!--fin segunda columna-->
    </tr>
  </table>
  <h4>SIN INFORMACIÓN PARA MOSTRAR.</h4>
  @else
  @for($i = 0; $i < count($data_final); $i++) <table style="width: 100%;margin-top:2px">
    <tr>
      <td width="25%" style="width: 25%;margin:0px">
        @if($empresa['logo'] != "")
        <img src='{{ "storage/".$empresa['logo'] }}' width="100" height="60" />
        @endif
      </td>

      <td width="50%" style="width: 75%;margin:0px">
        <table style="width:100%">
          <tr>
            <td style="text-align: center;margin-top: 0px;font-family: Helvetica, Arial, sans-serif;">
              <b style="font-size:15px">{{ strtoupper($empresa['nombre']) }}</b> <br>
              <b style="font-size:14px;">{{ strtoupper($sucursal['nombre']) }}</b> <br><br>
              <h5 style="font-size:13px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin: 0px">
                DEPARTAMENTO/AREA DE {{ strtoupper($data_final[$i]['area']) }}</h5>
            </td>
          </tr>
        </table>
        <!--fin segunda tabla-->
      </td>
      <td width="25%" style="width: 25%;margin:0px">
        <table>
          <tr>
            <td style="text-align:right; font-size:12px;color: #008C45"><strong></strong></td>
          </tr>
          <tr>
            <td style="color:red;text-align:right; font-size:12px;color: #CD212A"><strong>&nbsp;<span></strong></td>
          </tr>
        </table>
        <!--fin segunda tabla-->
      </td>
      <!--fin segunda columna-->
    </tr>
    </table>
    <table width="100%" id="pacientes" style="margin-top: 0px">
      <tr>
        <th>#</th>
        <th>NOMBRE</th>
        <th>CARGO</th>
        <th>ESTADO</th>
        <th>FECHA ATENCIÓN</th>
        <th>FIRMA COLABORADOR</th>
      </tr>
      <?php
  $contador = 1;
  foreach ($data_final[$i]['items'] as $value) { ?>
      <tr>
        <td>{{ $contador}}</td>
        <td>{{ $value['colaborador'] }}</td>
        <td>{{ $value['cargo'] }}</td>
        <td>{{ '' }}</td>
        <td>{{ '' }}</td>
        <td>{{ '' }}</td>
      </tr>
      <?php 
    $contador ++;
  } ?>
    </table>
    @if($i < (count($data_final) - 1)) <div style="page-break-after:always;">
      </div>
      @endif
      @endfor
      @endif
</body>

</html>