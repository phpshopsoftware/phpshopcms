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
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("xml");

$_REQUEST['template']=$_SESSION['skin'];

// Проверка прав админа
if (!empty($_SESSION['logPHPSHOP']) and PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme'])) {

    $PHPShopSystem = new PHPShopSystem();

    if ($GLOBALS['SysValue']['template_theme']['demo'] != 'true') {

        // Parse CSS
        if ($_POST['parser'] == 'css') {
            PHPShopObj::loadClass(array("parser", "file"));


            $option = xml2array('../templates/'. $_SESSION['skin'] . '/editor/style.xml', false, true);
            $optionIndex = array_values($option['element']);
            $css_file = '../templates/' . $_SESSION['skin'] . '/css/' . $_COOKIE[$_REQUEST['template'] . '_theme'] . '.css';
            $PHPShopCssParser = new PHPShopCssParser($css_file);
            $css_parse = $PHPShopCssParser->parse();

            $i = 0;
            if (is_array($css_parse))
                foreach ($css_parse as $key => $val) {

                    // Есть изменение CSS
                    if (is_array($_POST['color'][$i])) {
                        foreach ($_POST['color'][$i] as $color => $value)
                            if (isset($value)){
                                
                                // Изображение
                                if($optionIndex[$i]['var']['type'] == 'image')
                                    $value='url('.$value.') no-repeat center';
                                
                                if($optionIndex[$i]['var']['important'] == 'true')
                                    $add=' !important';
                                else $add=null;
                                
                                $PHPShopCssParser->setParam($key, $color, $value,$add);
                            }
                    }

                    $i++;
                }

            // Запись изменений
            PHPShopFile::chmod($css_file);
            PHPShopFile::write($css_file, $PHPShopCssParser->compile());
        }


        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
        $PHPShopOrm->debug=false;
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