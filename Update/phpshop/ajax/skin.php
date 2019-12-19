<?php

/**
 * Настройка шаблона из внешней части
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";


include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
//PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");

// Проверка прав админа
if (!empty($_SESSION['logPHPSHOP']) and PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme'])) {

    $PHPShopSystem = new PHPShopSystem();

    if ($GLOBALS['SysValue']['template_theme']['demo'] != 'true') {

        // Parse CSS
        if ($_POST['parser'] == 'css') {
            PHPShopObj::loadClass(array("parser", "file"));


            $css_file = '../templates/' . $_SESSION['skin'] . '/css/' . $_COOKIE[$_REQUEST['template'] . '_theme'] . '.css';
            $PHPShopCssParser = new PHPShopCssParser($css_file);
            $css_parse = $PHPShopCssParser->parse();

            //print_r($css_parse);

            $i = 0;
            if (is_array($css_parse))
                foreach ($css_parse as $key => $val) {

                    // Есть изменение CSS
                    if (is_array($_POST['color'][$i])) {
                        foreach ($_POST['color'][$i] as $color => $value)
                            if (!empty($value))
                                $PHPShopCssParser->setParam($key, $color, $value);
                    }

                    $i++;
                }

            // Запись изменений
            PHPShopFile::chmod($css_file);
            PHPShopFile::write($css_file, $PHPShopCssParser->compile());
        }


        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
        $admoption = unserialize($PHPShopSystem->getParam('admoption'));

        if (PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme2'])) {
            $admoption[$_REQUEST['template'] . '_theme2'] = $_COOKIE[$_REQUEST['template'] . '_theme2'];
            $status .= ",<br> " . $_COOKIE[$_REQUEST['template'] . '_theme2'] . '.css';
        }

        if (PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme3'])) {
            $admoption[$_REQUEST['template'] . '_theme3'] = $_COOKIE[$_REQUEST['template'] . '_theme3'];
            $status .= ",<br> " . $_COOKIE[$_REQUEST['template'] . '_theme3'] . '.css';
        }

        $admoption[$_REQUEST['template'] . '_theme'] = $_COOKIE[$_REQUEST['template'] . '_theme'];
        $admoption[$_REQUEST['template'] . '_fluid_theme'] = $_COOKIE[$_REQUEST['template'] . '_theme'];
        $update['admoption_new'] = serialize($admoption);
        $PHPShopOrm->update($update);

        $_RESULT = array(
            "status" => "Шаблон изменен на " . $_COOKIE[$_REQUEST['template'] . '_theme'] . '.css' . $status,
            "success" => 1
        );

        if ($_REQUEST['type'] == 'json') {
            $_RESULT['status'] = PHPShopString::win_utf8($_RESULT['status']);
            header("HTTP/1.1 200");
            header("Content-Type: application/json");
            echo json_encode($_RESULT);
        }
    }
}
?>