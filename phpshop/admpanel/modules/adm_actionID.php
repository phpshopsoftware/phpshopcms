<?php

if (empty($_REQUEST['action']))
    $_REQUEST['action'] = 'off';
else
    $_REQUEST['action'] = 'on';

$_POST['moduleAction'] = $_REQUEST['action'];
$_POST['moduleId'] = strtolower($_REQUEST['id']);

$modPath = "../modules/";

// Информация по модулю
function GetModuleInfo($name) {
    global $modPath;
    $path = $modPath . $name . "/install/module.xml";
    return xml2array($path, false, true);
}

if (!empty($PHPShopBase) and $PHPShopBase->Rule->CheckedRules('modules', 'edit')) {
    switch ($_POST['moduleAction']) {
        case("off"):

            // Удаление базы
            if (is_array($PHPShopModules->ModValue['base'][$_POST['moduleId']]))
                foreach ($PHPShopModules->ModValue['base'][$_POST['moduleId']] as $val)
                    if (!empty($val))
                        mysqli_query($PHPShopBase->link_db, "DROP TABLE " . $val);

            // Удаление полей
            if (is_array($PHPShopModules->ModValue['field'][$_POST['moduleId']]))
                foreach ($PHPShopModules->ModValue['field'][$_POST['moduleId']] as $key => $val)
                    if (!empty($val))
                        mysqli_query($PHPShopBase->link_db, "ALTER TABLE `" . $val . "` DROP `" . $key . "` ");


            // Удаляем дополнительные БД
            $modulePath = $modPath . $_POST['moduleId'] . "/uninstall/module.sql";
            if (file_exists($modulePath)) {
                $moduleSQLFile = file_get_contents($modulePath);
                $SQLArray = explode(";", $moduleSQLFile);
                foreach ($SQLArray as $val)
                    if (!empty($val))
                        $result = mysqli_query($PHPShopBase->link_db, $val);
            }

            $sql = "delete from " . $GLOBALS['SysValue']['base']['modules'] . " where path='" . $_POST['moduleId'] . "'";
            $result = mysqli_query($PHPShopBase->link_db, $sql);
            $date = null;
            break;

        case("on"):

            $num = $PHPShopBase->getNumRows('modules', false);

            if ($num < $_SESSION['mod_limit']) {

                // Информация по модулю
                $Info = GetModuleInfo($_POST['moduleId']);

                if (empty($Info['trial']))
                    $date_end = 0;
                else
                    $date_end = time() + 2592000;

                if (empty($Info['key']))
                    $key = null;
                else
                    $key = $Info['key'];

                $sql = "INSERT INTO " . $GLOBALS['SysValue']['base']['modules'] . "  VALUES ('" . $_POST['moduleId'] . "','" . $Info ['name'] . "'," . time() . ")";
                $result = mysqli_query($PHPShopBase->link_db, $sql);

                if (!empty($date_end))
                    $sql = "INSERT INTO " . $GLOBALS['SysValue']['base']['modules_key'] . "  VALUES ('" . $_POST['moduleId'] . "'," . $date_end . ",'" . $key . "','" . md5($_POST['moduleId'] . $date_end . $_SERVER['SERVER_NAME'] . $key) . "')";
                mysqli_query($PHPShopBase->link_db, $sql);

                // Устанавливаем БД модуля
                $modulePath = $modPath . $_POST['moduleId'] . "/install/module.sql";
                if (file_exists($modulePath)) {
                    $moduleSQLFile = file_get_contents($modulePath);
                    $SQLArray = explode(";", $moduleSQLFile);
                    foreach ($SQLArray as $val)
                        if (!empty($val))
                            $result = mysqli_query($PHPShopBase->link_db, $val);
                }

                $date = date("d-m-Y");
            }
            else
                exit(json_encode(array("succes" => false)));

            break;
    }


    // Дата установки
    exit(json_encode(array("date" => $date, 'success' => true)));
}
?>