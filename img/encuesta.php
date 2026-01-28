<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo4 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 24px; }
.Estilo5 {color: #FFFFFF}
.Estilo6 {color: #663300}
.colortitulomenu strong {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 18px;
	color: #CC0000;
}
.encabezadodetalle {
	color: #000;
}
-->
</style>

<script>
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint2").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint2").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","getservicios1.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>

</head>

<body>
<?php 
include("conexion.php");

$con = new mysqli($host, $user, $pw, $db);
if(!$con)
{
    echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus 		    datos.</h3><hr><br>";
}
$con->set_charset("utf8");

$fechaactual=date("Y-m-d");


?>

<div align="center"><img src="img/logo_dos.png" width="262" height="81" alt="Logo" /> </div>

<form  name="form1" method="POST" action="insertarpedido.php">
  <div align="center" >
    <p>&nbsp;</p>
    <strong>Fecha:</strong>
    <input name="Fecha" type="text" /> 
    <strong>Cama:</strong>
    <input name="cama" type="text" />
<table width="478" border="0">
      <tr>
        <th colspan="2" bgcolor="#0099CC" class="encabezadodetalle" scope="col"><strong>EVALUACIÓN DE LA CALIDAD EN LA PRESTACIÓN DEL SERVICIO</strong></th>
      </tr>
      <tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>DERECHOS DEL PACIENTE
        </strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Han sido informados sus derechos y deberes como paciente</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma" id="forma">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda" >Se le ha informado quien o que especialidad le esta siendo tratada</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma2" id="forma2">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">El medico ha suministrado información sobre su enfermedad y el tratamiento que se le realizará</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma3" id="forma3">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Cuenta  con un acompañante y/o familiar que este  constantemente involucrado en el cuidado del paciente </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma4" id="forma4">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>SEGURIDAD DEL PACIENTE</strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>  
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Ha recibido  Información  sobre el uso de barandas para prevención de caídas </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma3" id="forma3">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Recibió Información  sobre la importancia de la correcta identificación en tablero </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma5" id="forma5">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Ha recibido  Información  sobre la importancia de la correcta identificación  con las manillas </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma6" id="forma6">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Ha recibido información sobre los medicamentos que recibe  y sus efectos </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma7" id="forma7">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr> 
<tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>ALIMENTACIÓN</strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>               
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Durante el ingreso le fueron consultadas sus requerimientos o preferencias nutricionales </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma7" id="forma7">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Esta recibiendo alimentación adecuada para su condición medica</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma8" id="forma8">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Le han ofrecido un menu opcional </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma9" id="forma9">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">El familiar o  acompañante conoce el servicio de restaurante y cafeteria</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma10" id="forma10">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Le fue informado sobre el horario de alimentación</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma11" id="forma11">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr> 
        </tr> 
<tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>EDUCACIÓN AL USUARIO Y PROMOCION DEL AUTOCUIDADO</strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>     
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Tiene conocimiento acerca de los aspectos concernientes a su registro, estancia, atención y cuidado</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma11" id="forma11">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Cuenta con todos los implementos de aseo personal  y cuidado de piel</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma12" id="forma12">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Es informado de forma adecuada sobre la preparación previa que debe cumplir para que le sean realizados los procedimientos ordenados por el equipo de salud </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma13" id="forma13">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Han sido evaluadas e informadas sus necesidades de prevención de enfermedades y la promoción de la salud</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma14" id="forma14">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="celda">Fue informado sobre las medidas de seguridad, incluidos uso de alarmas, timbres de llamado y conducta ante una posible evacuación.</td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma15" id="forma15">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr> 
<tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>TRAMITES ADMINISTRATIVOS</strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>
<tr>
        <td bgcolor="#FFFFFF" class="celda">Ha recibido información sobre los tramites administrativos como tarifas, copagos o cuotas moderadoras y documentación requerida para su ingreso y egreso. </td>
        <td bgcolor="#FFFFFF"><span class="celda">
          <select name="forma15" id="forma15">
            <option value="Normal">Cumple</option>
            <option value="Adicional">No Cumple</option>
            <option>No Aplica</option>
          </select>
        </span></td>
      </tr>
<tr>
  <td bgcolor="#FFFFFF" class="celda">El paciente y su familia conoce los horarios y restricciones de visita</td>
  <td bgcolor="#FFFFFF"><span class="celda">
    <select name="forma16" id="forma16">
      <option value="Normal">Cumple</option>
      <option value="Adicional">No Cumple</option>
      <option>No Aplica</option>
    </select>
  </span></td>
</tr>
<tr>
  <td bgcolor="#FFFFFF" class="celda">Le fue informado sobre la política de confidencialidad frente a la información del usuario y que su presencia en la organización no será divulgada sin su consentimiento.</td>
  <td bgcolor="#FFFFFF"><span class="celda">
    <select name="forma17" id="forma17">
      <option value="Normal">Cumple</option>
      <option value="Adicional">No Cumple</option>
      <option>No Aplica</option>
    </select>
  </span></td>
</tr>
<tr>
  <td bgcolor="#FFFFFF" class="celda">En caso de remision del paciente, se brinda información clara y completa al usuario y su familia sobre el proceso de remisión y los procedimientos administrativos a seguir para obtener el servicio donde se refiere al usuario</td>
  <td bgcolor="#FFFFFF"><span class="celda">
    <select name="forma18" id="forma18">
      <option value="Normal">Cumple</option>
      <option value="Adicional">No Cumple</option>
      <option>No Aplica</option>
    </select>
  </span></td>
</tr>
<tr>
  <td bgcolor="#FFFFFF" class="celda">Se evidencian acciones coordinadas entre los servicios e instituciones para establecer parámetros de oportunidad.</td>
  <td bgcolor="#FFFFFF"><span class="celda">
    <select name="forma19" id="forma19">
      <option value="Normal">Cumple</option>
      <option value="Adicional">No Cumple</option>
      <option>No Aplica</option>
    </select>
  </span></td>
</tr> 
<tr class="encabezadodetalle">
        <th width="379" bgcolor="#0099CC" scope="col"><strong>HABITACIONES</strong>          <p>&nbsp;</p></th>
        <th width="89" bgcolor="#0099CC" scope="col"><strong>SELECCION</strong></th>
      </tr>
 <tr>
  <td bgcolor="#FFFFFF" class="celda">Buenas condiciones de aseo</td>
  <td bgcolor="#FFFFFF"><span class="celda">
    <select name="forma19" id="forma19">
      <option value="Normal">Cumple</option>
      <option value="Adicional">No Cumple</option>
      <option>No Aplica</option>
    </select>
  </span></td>
</tr>
 <tr>
   <td bgcolor="#FFFFFF" class="celda">Se observa mantenimiento completo en paredes, pisos, techos y baños</td>
   <td bgcolor="#FFFFFF"><span class="celda">
     <select name="forma20" id="forma20">
       <option value="Normal">Cumple</option>
       <option value="Adicional">No Cumple</option>
       <option>No Aplica</option>
     </select>
   </span></td>
 </tr>
 <tr>
   <td bgcolor="#FFFFFF" class="celda">Canecas para la segregación de los residuos y conocen su uso</td>
   <td bgcolor="#FFFFFF"><span class="celda">
     <select name="forma21" id="forma21">
       <option value="Normal">Cumple</option>
       <option value="Adicional">No Cumple</option>
       <option>No Aplica</option>
     </select>
   </span></td>
 </tr>
 <tr>
   <td bgcolor="#FFFFFF" class="celda">Sistema de lavado y secado de manos adecuado</td>
   <td bgcolor="#FFFFFF"><span class="celda">
     <select name="forma22" id="forma22">
       <option value="Normal">Cumple</option>
       <option value="Adicional">No Cumple</option>
       <option>No Aplica</option>
     </select>
   </span></td>
 </tr> 
    </table>

      
         
</div>
    </table>
    <label><br />
      <br />
    </label>

  </div>

</form>
 <div align="center" class="titulopagina">  <p class="colortitulomenu"><strong>Regresar Al Menu Inicial</strong></p>
  <p class="colortitulomenu"><a href="index.php"><img src="img/pagina-de-inicio.png" width="100" height="80" alt="Inicio" /></a></p>
</div>

</body>
</html>
