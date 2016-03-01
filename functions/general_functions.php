<?php
/**
 * Created by PhpStorm.
 * User: ReynaldoPG
 * Date: 02/02/2016
 * Time: 11:31 PM
 */

$path = __FILE__;
$path = str_replace("\\", "/", $path);
$site = explode('/', $path);

foreach ($site as $value) :
    if ($value != 'wp-content'):
        $siteConfig [] = $value;
    else:
        break;
    endif;
endforeach;
$wpConfig = implode('/', $siteConfig);

require_once($wpConfig . '/wp-config.php');
require_once($wpConfig . '/wp-includes/wp-db.php');
require_once($wpConfig . '/wp-load.php');

global $wpdb;
$home = site_url();
const FIELD = 'general';

if(isset($_GET['url'])){
    echo json_encode(array('home' => $home,'field'=> FIELD));
}

$general = new generalQuestionnaire($wpdb, $home);

class generalQuestionnaire
{
    protected $general;
    protected $home;
    protected $numberRegisters;

    function __construct($wpdb, $home)
    {
        $this->general = $wpdb;
        $this->home = $home;
        $this->numberRegister = array();
    }

    public function getDatas()
    {

        $wpdb = $this->general;
        $resultsDB = $wpdb->get_results("SELECT ocd.*, ocr.created_at, ocr.updated_at FROM empresa_cuestionario_datos ocd
INNER JOIN empresa_cuestionario_respuestas ocr ON ocd.id = ocr.id_datos
ORDER BY ocd.id DESC;");
        $resultsDB = json_decode(json_encode($resultsDB), true);

        return $resultsDB;
    }

    public function getData($id)
    {
        $wpdb = $this->general;
        $resultDB = $wpdb->get_row("SELECT ocd.*, ocr.* FROM empresa_cuestionario_datos ocd
                                    INNER JOIN empresa_cuestionario_respuestas ocr ON ocd.id = ocr.id_datos
                                    WHERE ocd.id = $id");
        $resultDB = json_decode(json_encode($resultDB), true);

        return $resultDB;
    }

    public function saveNewGeneralData($table, $values)
    {
        $wpdb = $this->general;
        $wpdb->insert(
            $table,
            $values,
            $this->numberRegister($values),
            array('%d')
        );
        return $wpdb->insert_id;
    }

    public function saveNewAnswers($tableAnswers, $valuesAnswers)
    {
        $wpdb = $this->general;
        $wpdb->insert(
            $tableAnswers,
            $valuesAnswers,
            $this->numberRegister($valuesAnswers),
            array('%d')
        );
    }

    public function saveGeneralData($table, $values, $id)
    {
        $wpdb = $this->general;
        $wpdb->update(
            $table,
            $values,
            array('id' => $id),
            $this->numberRegister($values),
            array('%d')
        );
    }

    public function saveAnswers($tableAnswers, $valuesAnswers, $id)
    {
        $wpdb = $this->general;
        $wpdb->update(
            $tableAnswers,
            $valuesAnswers,
            array('id_datos' => $id),
            $this->numberRegister($valuesAnswers),
            array('%d')
        );
    }

    public function changeText($value)
    {
        $val = explode('|', $value);
        return $val[0] . ', ' . $val[1];
    }

    public function getNameFile($data)
    {
        return $data['id_datos'] . '_' . strtolower(str_replace(' ', '', $data['contacto'])) . '_' . str_replace('-', '', explode(' ', $data['updated_at'])[0]);
    }

    public function directoryTmp($id)
    {
        $directory = ABSPATH . 'wp-content/plugins/cuestionario/download/' . $id;
        if (!is_dir($directory)) {
            umask(0);
            mkdir($directory, 0777, true);
        }
        return $directory;
    }

    public function deleteDirectory($dirname)
    {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    deleteDirectory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function numberRegister($values){
        $t = array();
        for ($i = 0; $i < count($values); $i++) {
            $t[] = '%s';
        }
        return $this->numberRegisters = $t;
    }
}