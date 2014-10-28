<?php
error_reporting(0);
ini_set('display_errors', 0);

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
$config = require '../app/config/config.php';

$cacheDir = $config->application->cacheDir;
$file = $_GET['file'];

$filePath = $cacheDir.$file;

if (file_exists($filePath)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $filePath);
    if (strpos($mimeType, 'image') !== false) {
        header('Content-type: '.$mimeType);
        readfile($filePath);
    }
}

