<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>ORDEN DE EXAMENES</title>
  <style>
    html {
      margin-top: 0;
      margin-left: 28px;
      margin-right: 20px;
      margin-bottom: 0;
    }

    .info_empleado{
      font-size: 12px;
      font-family: Helvetica, Arial, sans-serif;
      text-align: center;
    }
    .container_table{
      border: 1px solid #000;border-radius: 4px;
    }

    .stilot1 {
      padding: 5px;
      font-size: 12px;
      font-family: Helvetica, Arial, sans-serif;
      border-collapse: collapse;
      text-align: center;
    }

    .stilot2 {
      text-align: center;
      font-size: 11px;
      font-family: Helvetica, Arial, sans-serif;
    }

    .table2 {
      border-collapse: collapse;
      width: 100%;
    }
  </style>
</head>

<body>

  <div style="margin-top:30px;height:200px">
    <table style="width: 100%;margin-bottom:4px">
      <tr>
        <td width="10%">
          <img src='{{ public_path('assets/img/vasperoficial.jpg') }}' width="140" height="50" />
        </td>

        <td width="60%">
          <table style="width:95%;">
            <tr>
              <td style="text-align:center; font-size:16px"><strong>LABORATORIO CLINICO VASPER</strong> || <strong>Lic. Carlos Andrés Vásquez Peraza</strong></td>
            </tr>
            <tr>
              <td style="text-align:center; font-size:12px">Calle Francisco Gavidia y Final Calle Gerardo Barrios #9-A, Ciudad Arce<span id="date"></span><span>Telefonos: 23330-9801&nbsp;&nbsp;</span><br>E-mail: labclinicovasper@gmail.com&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-align:center; font-size:11px"><?php date_default_timezone_set('America/El_Salvador');$hoy = date("d-m-Y H:i:s");echo $hoy; ?></span></td>
            </tr>
          </table><!--fin segunda tabla-->
        </td>
        <td width="10%">
          <table>

          </table><!--fin segunda tabla-->
        </td> <!--fin segunda columna-->
      </tr>
    </table>
    <table class="table2">
      <tr>
        <td colspan="100" style="width: 100%;">
          <div class="container_table" style="padding: 6px">
          <table class="table2">
            <tr>
              <td colspan="20" class="info_empleado" style="20%;padding: 0px 10px 0px 0px"><strong>CÓDIGO:</strong> <br> {{ $data_orden['codigo_empleado'] }}</td>
              <td colspan="30" class="info_empleado" style="30%;border-left: 1px solid #000;padding: 0px 10px 0px 0px"><strong>NOMBRE:</strong> <br> {{ $data_orden['colaborador'] }}</td>
              <td colspan="20" class="info_empleado" style="20%;border-left: 1px solid #000;padding: 0px 10px 0px 0px"><strong>DEPARTAMENTO:</strong> <br> - {{-- {{ $data_orden['area_depto'] }} --}}</td>
              <td colspan="30" class="info_empleado" style="30%;border-left: 1px solid #000;padding: 0px"><strong>EMPRESA:</strong> <br> {{ strtoupper($data_orden['empresa']) }}</p></td>
              </tr>
          </table>
        </div>
        </td>
      </tr>
    </table>
    <div class="container_table" style="margin-top: 4px">
      <table width="100%" class="table2">
        <tr>
          <td colspan="100" style="text-align: center; background:#000;color:#fff;border-bottom: 1px solid #000;border-top-left-radius: 4px;border-top-rigth-radius: 4px" class="stilot1">EXAMENES</td>
        </tr>
        <tr style="height:40px;">
          <td colspan="100" style="border-bottom: 1px solid #000;font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center;margin:20px;height: 70px;white-space: wrap;"
            align="center">
            @php
                $id = 0;
            @endphp
            @for($i=0; $i < count($data_orden['examenes']); $i++)
              @php
                $id += 1;
              @endphp
                {!! "<b>". $id."</b>. " . ucfirst($data_orden['examenes'][$i]["examen"]) !!}&nbsp;&nbsp;&nbsp;
              @if($i == 5)
                <br>
              @endif
            @endfor
          </td>
        <tr>
          <td colspan="100"
            style="font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center;margin:20px;white-space: wrap;text-align: justify;padding: 5px"
            align="center">
            @php
              $recomendacion = "<strong>RECOMENDACIONES PARA RECOLECCIÓN DE MUESTRAS: </strong><br><strong>Examenes sanguineos: </strong>
        Cena previa normal. Presentarse con un ayuno estricto de 12 a 14 horas. 
        Puede ingerir agua si lo desea.
        Si toma algun medicamento este debe ser tomado luego de haberse realizado el examen.<br><br>";
            @endphp
  
            @for($i=0; $i < count($data_orden['examenes']); $i++)
              @php
                 $heces = "<strong>Heces: </strong>En el recipiente color verde de boca ancha, colocar una pequeña cantidad de muestra.
                  Con ayuda de una espátula, tomar la muestra y colocarla en el frasco. Esta muestra no debe tener contacto ni con el inodoro ni con la orina para evitar el deterioro de parásitos.<br>
                ";
                $baciloscopia ="<strong> Baciloscopia (muestra de esputo o flema): </strong>
                En el recipiente transparente boca ancha tome una muestra de esputo, inspirando fuertemente y expulsando con un esfuerzo dentro de tos dentro del recipiente. 
                La muestra debe ser tomada en ayunas y sin cepillarse los dientes.<br><br>
                ";
  
                $exo ="<strong>Exudado faringeo: </strong>Se le tomara una muestra de la garganta llamada cepillado de garganta.
                No se deben usar enjuagues bucales antisepticos antes del examen.
                Se realiza en ayunas y sin haberse cepillado los dientes.<br><br>";
  
                $orina ="<strong>Orina: </strong>Lavar el área genital con jabón y abundante agua. Secar minuciosamente.
                Se recomienda que sea la primera orina del día.
                Inicie la micción en el baño y, a mitad del chorro, coloque el frasco. Tapar inmediatamente. No colocar plástico, papel u otro material entre la boca del frasco y la tapadera.<br><br>";
              @endphp
              @if (in_array($data_orden['examenes'][$i]["categoria"],['COPROLOGIA']))
                @php
                  $recomendacion = $recomendacion . $heces;
                @endphp
              @elseif ($data_orden['examenes'][$i]["examen"] == "BACILOSCOPIA")
                @php
                  $recomendacion = $recomendacion . $baciloscopia;
                @endphp
              @elseif (in_array($data_orden['examenes'][$i]["categoria"],["EGO",'UROLOGIA']))
                @php
                  $recomendacion = $recomendacion . $orina;
                @endphp
              @elseif ($data_orden['examenes'][$i]["categoria"] == "BACTERIOLOGIA")
                @php
                  $recomendacion = $recomendacion . $exo;
                @endphp
              @endif
            @endfor
            
       {!! $recomendacion !!}
       {{-- {!! "<b>Espirometria:</b>No fumar dos horas antes de la prueba. Indicarle al medico si tiene gripe.<br> <b>Audiometría.</b>" !!} --}}
  
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>

</html>