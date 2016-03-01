<?php
/**
 * Created by PhpStorm.
 * User: ReynaldoPG
 * Date: 02/02/2016
 * Time: 11:24 PM
 */

date_default_timezone_set('America/Mexico_City');
require('general_functions.php');

$directory = $general->directoryTmp($_GET['id']);

if($_GET['del']){
	$t = $general->deleteDirectory($directory);
	echo 'ok';
}


$source_dir = $directory.'/';
$zip_file = $directory.'.zip';

// Initialize archive object
$zip = new ZipArchive();
$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source_dir),
    RecursiveIteratorIterator::LEAVES_ONLY
);
foreach ($files as $name => $file)
{
    if (!$file->isDir())
    {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($source_dir) + 0);
        $zip->addFile($filePath, $relativePath);
    }
}
$zip->close();

$general->deleteDirectory($source_dir);
echo '/wp-content/plugins/cuestionario/download/'.$_GET['id'].'.zip';