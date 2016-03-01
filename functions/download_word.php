<?php
/**
 * Created by PhpStorm.
 * User: ReynaldoPG
 * Date: 04/02/2016
 * Time: 22:09 PM
 */
$id = $_GET['id'];

require ('../library/word/PHPWord.php');
require_once('general_functions.php');

$directory = $general->directoryTmp($id);
$data = $general->getData($id);
$name = $general->getNameFile($data);

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('../library/word/general_cuestionario.docx');

$document->setValue('contacto', utf8_decode($data['contacto']));
$document->setValue('empresa', utf8_decode($data['empresa']));
$document->setValue('puesto', utf8_decode($data['puesto']));
$document->setValue('correo', utf8_decode($data['correo']));
$document->setValue('t', utf8_decode($data['telefono']));
$document->setValue('p1', utf8_decode($general->changeText($data['pregunta_uno'])));
$document->setValue('p2', utf8_decode($data['pregunta_dos']));
$document->setValue('p3', utf8_decode($general->changeText($data['pregunta_tres'])));
$document->setValue('p4', utf8_decode($general->changeText($data['pregunta_cuatro'])));
$document->setValue('p5', utf8_decode($data['pregunta_cinco']));
$document->setValue('p6', utf8_decode($data['pregunta_seis']));
$document->setValue('p7', utf8_decode($data['pregunta_siete']));
$document->setValue('p8', utf8_decode($data['pregunta_ocho']));
$document->setValue('p9', utf8_decode($data['pregunta_nueve']));
$document->setValue('p10', utf8_decode($data['pregunta_diez']));
$document->setValue('p11', utf8_decode($data['pregunta_once']));
$document->setValue('p12', utf8_decode($data['pregunta_doce']));
$document->setValue('p13', utf8_decode($data['pregunta_trece']));
$document->setValue('p14', utf8_decode($data['pregunta_catorce']));
$document->setValue('p15', utf8_decode($data['pregunta_quince']));
$document->setValue('p16', utf8_decode($general->changeText($data['pregunta_dieciseis'])));
$document->setValue('p17', utf8_decode($data['pregunta_diecisiete']));

$document->save($directory.'/'.$name.'.docx');