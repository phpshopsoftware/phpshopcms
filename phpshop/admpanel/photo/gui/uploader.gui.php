<?php
session_start();
$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(array("base", "system"));

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Тема оформления
$theme = PHPShopSecurity::TotalClean($PHPShopSystem->getSerilizeParam('admoption.theme'));
if (!file_exists('./css/bootstrap-theme-' . $theme . '.css'))
    $theme = 'default';
?>


<!DOCTYPE html>
<html>
    <head>
        <title>damnUploader</title>

        <!-- Bootstrap -->
        <link id="bootstrap_theme" href="../../css/bootstrap-theme-<?php echo $theme; ?>.css" rel="stylesheet">
        <script src="../../js/jquery-1.11.0.min.js"></script>
        <script src="../../js/jquery.damnUploader.min.js"></script>
        <script src="./uploader.gui.js"></script>

    </head>
    <body>
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8 well well-sm">
                    <input type="file" class="auto-tip" id="file-input" name="my-file" data-title="You can select one or more files by system menu" multiple />
                </div>

                <div class="col-md-4 hidden">
                    <form class="form-inline" role="form" id="upload-form" method="post"  enctype="multipart/form-data" multi>
                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
                            <button id="clear-btn" class="btn btn-default"><span class="glyphicon glyphicon-stop"></span> Сброс</button>
                            <button id="send-btn" type="submit" class="btn btn-default"><span class="glyphicon glyphicon-play"></span> Отправить</button>
                            <input type="hidden" name="rowID" id ="productId" value="<?php echo intval($_GET['id']); ?>" />
                        </div>  
                    </form>
                </div>
            </div>

            <div class="row" >
                <div style="overflow-y:auto;max-height:450px">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Иконка</th>
                                <th>Имя файла</th>
                                <th>Размер</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="upload-rows"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>