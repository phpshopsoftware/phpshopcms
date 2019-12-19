<?php

session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("orm");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('filemanager');

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FileManager</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="./jQueryFileTree.min.css">
    </head>
    <body role="document">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="./jQueryFileTree.min.js"></script>
        
        <div class="container-fluid">
            <div class="page-header <?php if(empty($_GET['full'])) echo 'hide' ?>">
                <h2>Файловый менеджер</h2>
            </div>
            <div class="filemanager"></div>
        </div>
        <script>
            $(document).ready(function() {
                $('.filemanager').fileTree({
                    root: '/UserFiles/Files/',
                    script: './jqueryFileTree.php',
                    expandSpeed: 500,
                    collapseSpeed: 500,
                    multiFolder: true
                }, function(file) {
                   window.open(file);
                });
            });
        </script>
    </body>
</body>
</html>
