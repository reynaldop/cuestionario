<?php
/*
Plugin Name: Cuestionario GeckoDesign
Description: Cuestionario construido con boostrap, jquery, php con descarga a pdf y word.
Version: 0.0.1
Author: Reynaldo Pizarro Glez.
Author URI: http://geckodesign.mx/
*/


function general_create_plugin_tables()
{
    global $wpdb;

    $table_datos = 'empresa_cuestionario_datos';

    $datos = "CREATE TABLE $table_datos (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contacto` varchar(255) NOT NULL,
  `empresa` varchar(255) NOT NULL,
  `puesto` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $table_respuestas = 'empresa_cuestionario_respuestas';

    $respuestas = "CREATE TABLE $table_respuestas (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_datos` int(9) NOT NULL,
  `pregunta_uno` text NOT NULL,
  `pregunta_dos` text NOT NULL,
  `pregunta_tres` text NOT NULL,
  `pregunta_cuatro` text NOT NULL,
  `pregunta_cinco` text NOT NULL,
  `pregunta_seis` text NOT NULL,
  `pregunta_siete` text NOT NULL,
  `pregunta_ocho` text NOT NULL,
  `pregunta_nueve` text NOT NULL,
  `pregunta_diez` text NOT NULL,
  `pregunta_once` text NOT NULL,
  `pregunta_doce` text NOT NULL,
  `pregunta_trece` text NOT NULL,
  `pregunta_catorce` text NOT NULL,
  `pregunta_quince` text NOT NULL,
  `pregunta_dieciseis` text NOT NULL,
  `pregunta_diecisiete` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($datos);
    dbDelta($respuestas);
}

// run the install scripts plugin activation
register_activation_hook(__FILE__, 'general_create_plugin_tables');

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages()
{
    // Add a new top-level menu (ill-advised):
    add_menu_page(__('Cuestionario', 'cuestionario-general'), __('Cuestionario', 'cuestionario-general'), 'manage_options', 'cuestionario', 'cuestionario_page');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('cuestionario', __('Nuevo', 'cuestionario-general'), __('Nuevo', 'cuestionario-general'), 'manage_options', 'cuestionario_nuevo', 'cuestionario_nuevo_page');
    //add_submenu_page('sedes', __('Nuevo', 'sedes-cervantino'), __('Nuevo', 'sedes-cervantino'), 'manage_options', 'sede_nuevo', 'sede_nuevo_page');
}

//Cuestionario
function cuestionario_page()
{
    include ('library/library.php');
    $general = new stdClass();
    global $wpdb;
    //Datos
    require_once('functions/general_functions.php');
    $resultsDB = $general->getDatas();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h4>Cuestionario empresa</h4>
                <input type="hidden" class="form-control general-font" id="general_site" value="<?= $general->getHome()?>">
            </div>
            <div class="col-md-10">
                <a href="?page=cuestionario_nuevo" class="pull-right"><span class="glyphicon glyphicon-plus"
                                                                            aria-hidden="true"></span>
                    Nuevo Cuestionario.</a>
                <table id="Table_1" class="display">
                    <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Cuestionario</th>
                        <th>Contacto</th>
                        <th>Empresa</th>
                        <th>Puesto</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Descargar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($resultsDB as $resultsdb): ?>
                        <tr id="renglon_<?= $resultsdb["id"] ?>">
                            <td width="7%" valign="top">
                                <a href="?page=cuestionario_nuevo&id=<?= $resultsdb["id"] ?>"><span
                                        class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> &nbsp; &nbsp;
                                <!--a href="?page=cuestionario_nuevo&id=<?= $resultsdb["id"] ?>&delete=true"
                                   class="eliminar" id="<?= $resultsdb["id"] ?>"><span
                                        class="glyphicon glyphicon-trash" aria-hidden="true"></span></a-->
                            </td>
                            <td valign="top"><?= $resultsdb["id"] ?></td>
                            <td valign="top"><?= $resultsdb["contacto"] ?></td>
                            <td valign="top"><?= $resultsdb["empresa"] ?></td>
                            <td valign="top"><?= $resultsdb["puesto"] ?></td>
                            <td valign="top"><?= $resultsdb["correo"] ?></td>
                            <td valign="top"><?= $resultsdb["telefono"] ?></td>
                            <td valign="top"><a href='#' rel='download_<?= $resultsdb["id"] ?>'>
                                    <img id="general_download_<?= $resultsdb['id'] ?>" src="../wp-content/plugins/cuestionario/img/download-icon.gif"/>
                                    <img id="general_download_pdf_<?= $resultsdb['id'] ?>" style="display: none;" src="../wp-content/plugins/cuestionario/img/pdf-icon.gif"/>
                                    <img id="general_download_word_<?= $resultsdb['id'] ?>" style="display: none;" src="../wp-content/plugins/cuestionario/img/word-icon.gif"/>
                                    <img id="general_download_loader_<?= $resultsdb['id'] ?>" style="display: none;" src="../wp-content/plugins/cuestionario/img/ajax-loader.gif"/>
                                </a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}

//Nuevo Cuestionario
function cuestionario_nuevo_page()
{
    include ('library/library.php');
    $general = new stdClass();
    require_once('functions/general_functions.php');

    $resultDB = array();
    $id = (isset($_GET['id'])) ? $_GET['id'] : null;

    if ($id):
        $resultDB = $general->getData($id);
    endif;
    ?>
    <style type="text/css">
        .content-quiestions{
            background-color: white;
            border-color: orange;
            border-style: double;
            font-size: 11px;
        }

        .general-legend{
            text-align: right;
            font-size: 15px;
            color: orange;
            font-weight: 600;
        }

        .general-font{
            font-size: 12px;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h3>Cuestionario</h3>
            </div>
            <br/>
            <div class="col-md-10">
                <form id="<?=FIELD ?>-form-1" class="form-horizontal" role="form">
                    <div id="<?=FIELD ?>-datos" class="content-quiestions col-lg-12">
                        <legend class="<?=FIELD ?>-legend">Datos Generales.
                        </legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_contacto" class="col-lg-2 control-label">Nombre del Contacto:</label>
                            <div class="col-lg-10">
                                <input type="hidden" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_id_datos" value="<?= $resultDB['id_datos']?>">
                                <input type="text" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_contacto"
                                       placeholder="Contacto" value="<?= $resultDB['contacto']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_empresa" class="col-lg-2 control-label">Empresa:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_empresa"
                                       placeholder="Empresa" value="<?= $resultDB['empresa']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_puesto" class="col-lg-2 control-label">Puesto:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_puesto"
                                       placeholder="Puesto" value="<?= $resultDB['puesto']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_correo" class="col-lg-2 control-label">Correo:</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_correo"
                                       placeholder="@" value="<?= $resultDB['correo']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_telefono" class="col-lg-2 control-label">Teléfono:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control <?=FIELD ?>-font" id="<?=FIELD ?>_telefono"
                                       placeholder="(55)" value="<?= $resultDB['telefono']?>">
                            </div>
                        </div>
                    </div>
                    <!--////////////////////////////////////////////////////////////////////////////////////-->
                    <div id="<?=FIELD ?>-preguntas" class="content-quiestions col-lg-12">
                        <div class="form-group">
                            <legend class="<?=FIELD ?>-legend">Introducción</legend>
                            <label for="<?=FIELD ?>_pregunta_uno" class="col-lg-12 control-label">
                                1. ¿Pregunta uno?
                            </label>
                            <div class="col-lg-1">a)<input type="radio" name="<?=FIELD ?>_pregunta_uno_r" id="<?=FIELD ?>_pregunta_uno_r" value="si" <?= trim(explode('|', $resultDB['pregunta_uno'])[0]) == 'si' ? 'checked' : '' ?>>Si</div>
                            <div class="col-lg-1">b)<input type="radio" name="<?=FIELD ?>_pregunta_uno_r" id="<?=FIELD ?>_pregunta_uno_r" value="no"<?= $resultDB['pregunta_uno'] == 'no' ? 'checked' : '' ?>>No</div>
                            <div class="col-lg-12"><label for="<?=FIELD ?>_pregunta_uno_1" class="col-lg-12 control-label">En caso de que la respuesta sea Si, favor de especificar de manera general que conoce.</label></div>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_uno" style="display: none"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_uno" id="<?=FIELD ?>_pregunta_uno" rows="3" placeholder="Pregunta 1"><?= ltrim(explode('|', $resultDB['pregunta_uno'])[1]) ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_dos" class="col-lg-12 control-label">
                                2. ¿Pregunta dos?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_dos"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_dos" id="<?=FIELD ?>_pregunta_dos" rows="3" placeholder="Pregunta 2"><?= $resultDB['pregunta_dos'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_tres" class="col-lg-12 control-label">
                                3. ¿Pregunta tres?
                            </label>
                            <div class="col-lg-1">a)<input type="radio" name="<?=FIELD ?>_pregunta_tres_r" id="<?=FIELD ?>_pregunta_tres_r" value="si" <?= $resultDB['pregunta_tres'] == 'si' ? 'checked' : '' ?>>Si</div>
                            <div class="col-lg-1">b)<input type="radio" name="<?=FIELD ?>_pregunta_tres_r" id="<?=FIELD ?>_pregunta_tres_r" value="no" <?= trim(explode('|', $resultDB['pregunta_tres'])[0]) == 'no' ? 'checked' : '' ?>>No</div>
                            <div class="col-lg-12"><label for="<?=FIELD ?>_pregunta_tres_1" class="col-lg-12 control-label">En caso de que la respuesta sea No, indicar que le hace falta para cubrir esa necesidad.</label></div>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_tres" style="display: none"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_tres" id="<?=FIELD ?>_pregunta_tres" rows="3" placeholder="Pregunta 3"><?= ltrim(explode('|', $resultDB['pregunta_tres'])[1]) ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_cuatro" class="col-lg-12 control-label">
                                4. ¿Pregunta cuatro?
                            </label>
                            <div class="col-lg-1">a)<input type="radio" name="<?=FIELD ?>_pregunta_cuatro_r" id="<?=FIELD ?>_pregunta_cuatro_r" value="si" <?= $resultDB['pregunta_cuatro'] == 'si' ? 'checked' : '' ?>>Si</div>
                            <div class="col-lg-1">b)<input type="radio" name="<?=FIELD ?>_pregunta_cuatro_r" id="<?=FIELD ?>_pregunta_cuatro_r" value="no" <?= trim(explode('|', $resultDB['pregunta_cuatro'])[0]) == 'no' ? 'checked' : '' ?>>No</div>
                            <div class="col-lg-12"><label for="<?=FIELD ?>_pregunta_cuatro_1" class="col-lg-12 control-label">En caso de que la respuesta sea No, indicar el ¿Por qué?</label></div>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_cuatro" style="display: none"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_cuatro" id="<?=FIELD ?>_pregunta_cuatro" rows="3" placeholder="Pregunta 4"><?= ltrim(explode('|', $resultDB['pregunta_cuatro'])[1]) ?></textarea></div>
                        </div>
                        <legend class="<?=FIELD ?>-legend">Sección Uno</legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_cinco" class="col-lg-12 control-label">
                                5. ¿Pregunta cinco?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_cinco"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_cinco" id="<?=FIELD ?>_pregunta_cinco" rows="3" placeholder="Pregunta 5"><?= $resultDB['pregunta_cinco'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_seis" class="col-lg-12 control-label">
                                6. ¿Pregunta seis?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_seis"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_seis" id="<?=FIELD ?>_pregunta_seis" rows="3" placeholder="Pregunta 6"><?= $resultDB['pregunta_seis'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_siete" class="col-lg-12 control-label">
                                7. ¿Pregunta siete?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_siete"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_siete" id="<?=FIELD ?>_pregunta_siete" rows="3" placeholder="Pregunta 7"><?= $resultDB['pregunta_siete'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_ocho" class="col-lg-12 control-label">
                                8. ¿Pregunta ocho? <font style="color: red">Nota: </font>
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_ocho"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_ocho" id="<?=FIELD ?>_pregunta_ocho" rows="3" placeholder="Pregunta 8"><?= $resultDB['pregunta_ocho'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_nueve" class="col-lg-12 control-label">
                                9. ¿Pregunta nueve?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_nueve"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_nueve" id="<?=FIELD ?>_pregunta_nueve" rows="3" placeholder="Pregunta 9"><?= $resultDB['pregunta_nueve'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_diez" class="col-lg-12 control-label">
                                10. ¿Pregunta diez?
                                <font color="red">Nota:</font>
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_diez"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_diez" id="<?=FIELD ?>_pregunta_diez" rows="3" placeholder="Pregunta 10"><?= $resultDB['pregunta_diez'] ?></textarea></div>
                        </div>
                        <legend class="<?=FIELD ?>-legend">Sección Dos</legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_once" class="col-lg-12 control-label">
                                11. ¿Pregunta once?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_once"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_once" id="<?=FIELD ?>_pregunta_once" rows="3" placeholder="Pregunta 11"><?= $resultDB['pregunta_once'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_doce" class="col-lg-12 control-label">
                                12. ¿Pregunta doce?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_doce"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_doce" id="<?=FIELD ?>_pregunta_doce" rows="3" placeholder="Pregunta 12"><?= $resultDB['pregunta_doce'] ?></textarea></div>
                        </div>
                        <legend class="<?=FIELD ?>-legend">Sección Tres</legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_trece" class="col-lg-12 control-label">
                                13. ¿Pregunta trece?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_trece"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_trece" id="<?=FIELD ?>_pregunta_trece" rows="3" placeholder="Pregunta 13"><?= $resultDB['pregunta_trece'] ?></textarea></div>
                        </div>
                        <legend class="<?=FIELD ?>-legend">Sección Cuatro</legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_catorce" class="col-lg-12 control-label">
                                14. ¿Pregunta catorce?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_catorce"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_catorce" id="<?=FIELD ?>_pregunta_catorce" rows="3" placeholder="Pregunta 14"><?= $resultDB['pregunta_catorce'] ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_quince" class="col-lg-12 control-label">
                                15. ¿Pregunta quince?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_quince"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_quince" id="<?=FIELD ?>_pregunta_quince" rows="3" placeholder="Pregunta 15"><?= $resultDB['pregunta_quince'] ?></textarea></div>
                        </div>
                        <legend class="<?=FIELD ?>-legend">Sección Cinco</legend>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_dieciseis" class="col-lg-12 control-label">
                                16. ¿Pregunta dieciseis?
                            </label>
                            <div class="col-lg-1">a)<input type="radio" name="<?=FIELD ?>_pregunta_dieciseis_r" id="<?=FIELD ?>_pregunta_dieciseis_r" value="si" <?= trim(explode('|', $resultDB['pregunta_dieciseis'])[0]) == 'si' ? 'checked' : '' ?>>Si</div>
                            <div class="col-lg-1">b)<input type="radio" name="<?=FIELD ?>_pregunta_dieciseis_r" id="<?=FIELD ?>_pregunta_dieciseis_r" value="no" <?= trim(explode('|', $resultDB['pregunta_dieciseis'])[0]) == 'no' ? 'checked' : '' ?>>No</div>
                            <div class="col-lg-12"><label for="<?=FIELD ?>_pregunta_uno_16" class="col-lg-12 control-label">Indicar a que versión en caso de que la respuesta sea positiva, en caso contrario ¿Por qué no?</label></div>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_dieciseis"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_dieciseis" id="<?=FIELD ?>_pregunta_dieciseis" rows="3" placeholder="Pregunta 16"><?= ltrim(explode('|', $resultDB['pregunta_dieciseis'])[1]) ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="<?=FIELD ?>_pregunta_diecisiete" class="col-lg-12 control-label">
                                17. ¿Pregunta diecisiete?
                            </label>
                            <div class="col-lg-12" id="<?=FIELD ?>_pregunta_diecisiete"><textarea class="form-control <?=FIELD ?>-font" name="<?=FIELD ?>_pregunta_diecisiete" id="<?=FIELD ?>_pregunta_diecisiete" rows="3" placeholder="Pregunta 17"><?= $resultDB['pregunta_diecisiete'] ?></textarea></div>
                        </div>
                        <div class="form-group" style="text-align: right">
                            <div class="col-lg-12">
                                <input class="btn btn-info" value="Guardar" name="submit" class="submit" id="<?=FIELD ?>-save" type="submit">
                                <img id="loading" style="display: none;"
                                     src="../wp-content/plugins/cuestionario/img/ajax-loader.gif"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
