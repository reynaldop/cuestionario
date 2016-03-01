<?php
/**
 * Created by PhpStorm.
 * User: ReynaldoPG
 * Date: 31/01/2016
 * Time: 11:51 PM
 */

date_default_timezone_set('America/Mexico_City');
require_once('../functions/general_functions.php');
$valuesAnswers = array();
$valuesDatas=array();
foreach($_POST as $key => $post){
    if(explode('pregunta_',explode(FIELD.'_', $key)[1])[0]){
        $valuesDatas[explode('pregunta_',explode(FIELD.'_', $key)[1])[0]] = $post;
    }else{
        $valuesAnswers[explode(FIELD.'_', $key)[1]] = $post;
    }

}
$id = $valuesDatas[key($valuesDatas)];
$tableDatas = 'empresa_cuestionario_datos';
$tableAnswers = 'empresa_cuestionario_respuestas';
unset($valuesDatas[key($valuesDatas)]);
if($id){
    $general->saveGeneralData($tableDatas, $valuesDatas, $id);
    $general->saveAnswers($tableAnswers, $valuesAnswers, $id);

}else{
    $idInsert = $general->saveNewGeneralData($tableDatas, $valuesDatas);
    $id = array(
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
        'id_datos'=>$idInsert
    );
    $valuesAnswers = array_merge($id, $valuesAnswers);
    $general->saveNewAnswers($tableAnswers, $valuesAnswers);

}

$r = array('regreo');
header('Content-type: application/json; charset=utf-8');
echo json_encode($r);
