<?php

if (!array_key_exists('HTTP_REFERER', $_SERVER))
    exit('No direct script access allowed');

/**
 * jQuery File Tree PHP Connector
 *
 * Version 1.1.0
 *
 * @author - Cory S.N. LaViska A Beautiful Site (http://abeautifulsite.net/)
 * @author - Dave Rogers - https://github.com/daverogers/jQueryFileTree
 *
 * History:
 *
 * 1.1.1 - SECURITY: forcing root to prevent users from determining system's file structure (per DaveBrad)
 * 1.1.0 - adding multiSelect (checkbox) support (08/22/2014)
 * 1.0.2 - fixes undefined 'dir' error - by itsyash (06/09/2014)
 * 1.0.1 - updated to work with foreign characters in directory/file names (12 April 2008)
 * 1.0.0 - released (24 March 2008)
 *
 * Output a list of files for jQuery File Tree
 */
/**
 * filesystem root - USER needs to set this!
 * -> prevents debug users from exploring system's directory structure
 * ex: $root = $_SERVER['DOCUMENT_ROOT'];
 */
//$root = null;
$root = $_SERVER['DOCUMENT_ROOT'];
if (!$root)
    exit("ERROR: Root filesystem directory not set in jqueryFileTree.php");

// Проверка пути
if (!strstr($_POST['dir'], '/UserFiles/Files') or strstr($_POST['dir'], '..'))
    $_POST['dir'] = '/UserFiles/Files/';

$postDir = rawurldecode($root . (isset($_POST['dir']) ? $_POST['dir'] : null ));

// set checkbox if multiSelect set to true
$checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
$onlyFolders = ( isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
$onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;

if (file_exists($postDir)) {

    // Локализация
    $lang = @parse_ini_file_true('lang.ini', 1);

    $files = scandir($postDir);
    $returnDir = substr($postDir, strlen($root));

    natcasesort($files);

    if (count($files) > 2) { // The 2 accounts for . and ..
        echo "<ul class='jqueryFileTree'>";

        foreach ($files as $file) {
            $htmlRel = htmlentities($returnDir . $file, ENT_QUOTES);
            $htmlName = htmlentities($file);
            $ext = preg_replace('/^.*\./', '', $file);
            
            // Локализация
            if(!empty($lang[$htmlRel])){
                $htmlName=$lang[$htmlRel]['name'];
                
                if(!empty($lang[$htmlRel]['size']))
                $htmlName.=' ('.$lang[$htmlRel]['size'].' Mb)';
            }

            if (file_exists($postDir . $file) && $file != '.' && $file != '..' && $file != '.tmb' && $file != '.quarantine' && $file != 'sitemap.xml') {
                if (is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders))
                    echo "<li class='directory collapsed'>{$checkbox}<a rel='" . $htmlRel . "/'>" . $htmlName . "</a></li>";
                else if (!$onlyFolders || $onlyFiles)
                    echo "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
            }
        }

        echo "</ul>";
    }
}
?>
