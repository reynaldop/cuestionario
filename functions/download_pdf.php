<?php
/**
 * Created by PhpStorm.
 * User: ReynaldoPG
 * Date: 03/02/2016
 * Time: 06:56 AM
 */

$id = $_GET['id'];
require('../library/mpdf/mpdf.php');
require_once('general_functions.php');

$directory = $general->directoryTmp($id);
$data = $general->getData($id);
$name = $general->getNameFile($data);

$html = '<div>
<div style="color: orange; font-size: 20px;">Cuestionario</div><br/>
<div style="color: orange; font-size: 16px; text-align: right">Datos Generales.<hr></div>
<div>
<table>
<tr><td>Nombre de contacto:</td><td>'.$data['contacto'].'</td></tr>
<tr><td>Empresa:</td><td>'.$data['empresa'].'</td></tr>
<tr><td>Puesto:</td><td>'.$data['puesto'].'</td></tr>
<tr><td>Correo:</td><td>'.$data['correo'].'</td></tr>
<tr><td>Teléfono:</td><td>'.$data['telefono'].'</td></tr>
</table>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Introducción<hr></div>
<div>
1. ¿Pregunta uno?<br/>
'.$general->changeText($data['pregunta_uno']).'<br/><br/>
2. ¿Pregunta dos?<br/>
'.$data['pregunta_dos'].'<br/><br/>
3. ¿Pregunta tres?<br/>
'.$general->changeText($data['pregunta_tres']).'<br/><br/>
4. ¿Pregunta cuatro?<br/>
'.$general->changeText($data['pregunta_cuatro']).'<br/><br/>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Sección Uno<hr></div>
<div>
5. ¿Pregunta cinco?<br/>
'.$data['pregunta_cinco'].'<br/><br/>
6. ¿Pregunta seis?<br/>
'.$data['pregunta_seis'].'<br/><br/>
7. ¿Pregunta siete?<br/>
'.$data['pregunta_siete'].'<br/><br/>
8. ¿Pregunta ocho?<font style="color:red"> Nota: </font><br/>
'.$data['pregunta_ocho'].'<br/><br/>
9. ¿Pregunta nueve?<br/>
'.$data['pregunta_nueve'].'<br/><br/>
10. ¿Pregunta diez?<font style="color:red"> Nota:l .</font><br/>
'.$data['pregunta_diez'].'<br/><br/>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Sección Dos<hr></div>
<div>
11. ¿Pregunta once?<br/>
'.$data['pregunta_once'].'<br/><br/>
12. ¿Pregunta doce?<br/>
'.$data['pregunta_doce'].'<br/><br/>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Sección Tres<hr></div>
<div>
13. ¿Pregunta trece?<br/>
'.$data['pregunta_trece'].'<br/><br/>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Sección Custro<hr></div>
<div>
14. ¿Pregunta catorce?<br/>
'.$data['pregunta_catorce'].'<br/><br/>
15. ¿Pregunta quince?<br/>
'.$data['pregunta_quince'].'<br/><br/>
</div>
<div style="color: orange; font-size: 16px; text-align: right">Sección Cinco<hr></div>
<div>
16. ¿Pregunta dieciseis?<br/>
'.$general->changeText($data['pregunta_dieciseis']).'<br/><br/>
17. ¿Pregunta diecisiete?<br/>
'.$data['pregunta_diecisiete'].'<br/><br/>
</div>
</div>';
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
//$mpdf->Output();
$content = $mpdf->Output($directory.'/'.$name.'.pdf', 'F');