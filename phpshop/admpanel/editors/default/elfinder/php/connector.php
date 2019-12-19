<?php

session_start();
if (empty($_SESSION['idPHPSHOP']))
    exit('Неавторизованный запрос');
error_reporting(0); // Set E_ALL for debuging

// Снятие ограничение для больших папок
if(function_exists('set_time_limit'))
    set_time_limit(0);

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderConnector.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinder.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 * */
function access($attr, $path, $data, $volume) {
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            : null;                                    // else elFinder decide it itself
}

// Что показывать
switch ($_GET['path']) {

    case 'image':
        $alias = 'Image';
        $path = 'Image/' . $_SESSION['imageResultPath'];
        break;

    case 'file':
        $alias = 'Files';
        $path = 'Files/';
        break;

    default:
        $path = '';
}

$opts = array(
    'debug' => false,
    'roots' => array(
        array(
            'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
            'path' => $_SERVER['DOCUMENT_ROOT'] . $_SESSION['imageResultDir'] . "/UserFiles/" . $path, // path to files (REQUIRED)
            'URL' => $_SESSION['imageResultDir'] . "/UserFiles/" . $path, // URL to files (REQUIRED)
            'accessControl' => 'access', // disable and hide dot starting files (OPTIONAL)
            'uploadAllow' => array('image/png', 'image/jpeg', 'image/gif', "application/x-shockwave-flash", "application/zip", "application/rar", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf", "application/x-rar","video/mp4","application/mp4","image/svg+xml"),
            'uploadDeny' => array('all'),
            'uploadOrder' => 'deny,allow',
            'checkSubfolders' => false,
            'tmbCrop' => false,
        ),
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

