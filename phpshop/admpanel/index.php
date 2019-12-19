<?php

session_start();
$_classPath = "../";
include($_classPath . "class/obj.class.php");
require_once $_classPath . '/lib/phpass/passwordhash.php';
PHPShopObj::loadClass(array("base", "system", "admgui", "orm", "security", "modules", "mail","lang"));

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopSystem = new PHPShopSystem();

// Locale
$_SESSION['lang'] = $PHPShopSystem->getSerilizeParam("admoption.lang");
$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'admin'));


$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
$PHPShopOrm->debug = false;
PHPShopParser::set('logo', $PHPShopSystem->getLogo());
PHPShopParser::set('serverPath', $_SERVER['SERVER_NAME']);

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Редактор GUI
$PHPShopGUI = new PHPShopGUI();

// Проверка черного списка
if ($PHPShopBase->getNumRows('black_list', 'where ip="' . PHPShopSecurity::TotalClean($_SERVER['REMOTE_ADDR']) . '"')) {
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    if (file_exists('../../404.html'))
        include_once('../../404.html');
    exit();
}

function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

// Выбор шаблона панели управления
function GetAdminSkinList($skin) {
    global $PHPShopGUI;
    $dir = "./css/";
    $id = 0;

    $color = array(
        'default' => '#178ACC',
        'cyborg' => '#000',
        'flatly' => '#D9230F',
        'spacelab' => '#46709D',
        'slate' => '#4E5D6C',
        'yeti' => '#008CBA',
        'simplex' => '#DF691A',
        'sardbirds' => '#45B3AF',
        'wordless' => '#468966',
        'wildspot' => '#564267',
        'loving' => '#FFCAEA',
        'retro' => '#BBBBBB',
        'cake' => '#E3D2BA',
        'dark' => '#3E444C'
    );

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/^bootstrap-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
                    $icon = $color[$match[1]];

                    $file = str_replace(array('.css', 'bootstrap-theme-'), '', $file);

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.')) {
                        
                        if($file == 'default')
                            $name = 'тема';
                        else $name=$file;
                        
                        $value[] = array($file, $file, $sel, 'data-content="<span class=\'glyphicon glyphicon-picture\' style=\'color:' . $icon . '\'></span> ' . $name . '"');
                        $id++;
                    }
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('theme', $value, 100, null, false, false, false, 1, false, 'theme');
}

// Экшен выхода
function actionLogout() {
    global $notification;
    $notification = __('Пользователь') . ' ' . $_SESSION['logPHPSHOP'] . ' ' . __('выполнил выход');
    session_destroy();
}

// Экшен генерация хеша на смену пароля
function actionHash() {
    global $PHPShopOrm, $notification, $PHPShopSystem;
  
    if (PHPShopSecurity::true_param($_POST['actionHash']) and PHPShopSecurity::true_login($_POST['log'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
        $data = $PHPShopOrm->select(array('password', 'mail', 'id', 'login'), array('login' => '="' . $_POST['log'] . '"'), false, array('limit' => 1));

        if (is_array($data)) {

            $hash = md5($data['id'] . $_POST['log'] . $data['mail'] . $data['password'] . time());
            $PHPShopOrm->clean();
            $PHPShopOrm->update(array('hash_new' => $hash), array('id' => '=' . $data['id']));

            PHPShopParser::set('hash', $hash);
            PHPShopParser::set('login', $data['login']);
            new PHPShopMail($data['mail'], $PHPShopSystem->getEmail(), __('Доступ к PHPShop'), PHPShopParser::file('tpl/hash.mail.tpl', true), true);

            $notification = __('Письмо с инструкциями выслано на') . ' ' . $data['mail'];
        }
    }
}

// Экшен геренация пароля
function actionUpdate() {
    global $PHPShopOrm, $notification, $PHPShopSystem;


    $hash = mysqli_real_escape_string($PHPShopOrm->link_db, stripslashes($_GET['newPassGen']));

    if (PHPShopSecurity::true_param($_GET['newPassGen'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
        $data = $PHPShopOrm->select(array('password', 'mail', 'id', 'login'), array('hash' => '="' . $hash . '"'), false, array('limit' => 1));
        if (is_array($data)) {

            // генерируем новый пароль для администратора
            $newPass = generatePassword();

            // кодируем новый пароль 
            $hasher = new PasswordHash(8, false);
            $password = $hasher->HashPassword($newPass);

            $PHPShopOrm->update(array('password_new' => $password, 'hash_new' => ''), array('id' => '=' . $data['id']));

            PHPShopParser::set('login', $data['login']);
            PHPShopParser::set('password', $newPass);
            new PHPShopMail($data['mail'], $PHPShopSystem->getEmail(), __('Доступ к PHPShop'), PHPShopParser::file('tpl/pass.mail.tpl', true), true);

            $notification = __('Письмо с новым паролем выслано на') . ' ' . $data['mail'];
        }
    }
}

// Экшен входа
function actionEnter() {
    global $PHPShopOrm, $PHPShopModules;

    $hasher = new PasswordHash(8, false);
    $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'"), false, array('limit' => 30));
    if (is_array($data)) {
        foreach ($data as $row) {

            if ($row['login'] == $_POST['log'] and $hasher->CheckPassword($_POST['pas'], $row['password'])) {

                $_SESSION['logPHPSHOP'] = $_POST['log'];
                $_SESSION['pasPHPSHOP'] = $_POST['pas'];
                $_SESSION['idPHPSHOP'] = $row['id'];

                // Запрос модуля на закладку
                $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

                if (isset($_SESSION['return']))
                    $return = '?' . $_SESSION['return'];
                else
                    $return = null;

                // Запись в журнал авторизации
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jurnal']);
                $PHPShopOrm->insert(array('user' => $_POST['log'], 'datas' => time(), 'flag' => 0, 'ip' => PHPShopSecurity::TotalClean($_SERVER['REMOTE_ADDR'])), '');

                // Смена цветовой темы
                $theme = PHPShopSecurity::TotalClean($_POST['theme']);
                if (!file_exists('./css/bootstrap-theme-' . $theme . '.css'))
                    $theme = 'default';
                else
                    $_SESSION['admin_theme'] = $theme;

                header('Location: ./admin.php' . $return);
                return true;
            }
        }
    }
    // Запись в журнал авторизации
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jurnal']);
    $PHPShopOrm->insert(array('user' => PHPShopSecurity::TotalClean($_POST['log']), 'datas' => time(), 'flag' => 1, 'ip' => PHPShopSecurity::TotalClean($_SERVER['REMOTE_ADDR'])), '');

    PHPShopParser::set('error', 'has-error');
}

function actionStart() {
    global $PHPShopSystem, $PHPShopBase, $notification;

    if (!empty($_SESSION['logPHPSHOP']) and empty($_SESSION['return'])) {
        header('Location: ./admin.php');
    }

    // Тема офомления
    if (empty($_SESSION['admin_theme']))
        $theme = PHPShopSecurity::TotalClean($PHPShopSystem->getSerilizeParam('admoption.theme'));
    elseif(!file_exists('./css/bootstrap-theme-' . $theme . '.css'))
        $theme = $_SESSION['admin_theme'];
    else 
        $theme = 'default';

    // Демо-режим
    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
        PHPShopParser::set('user', 'demo');
        PHPShopParser::set('password', 'demouser');
        PHPShopParser::set('readonly', 'readonly');
        PHPShopParser::set('disabled', 'disabled');
        PHPShopParser::set('hide', 'hide');
        PHPShopParser::set('themeSelect', GetAdminSkinList($theme));
    } else {
        PHPShopParser::set('autofocus', 'autofocus');
    }

    PHPShopParser::set('title', 'PHPShop - ' . __('Авторизация'));
    PHPShopParser::set('version', $PHPShopBase->getParam('upload.version'));
    PHPShopParser::set('theme', $theme);
    PHPShopParser::set('notification', $notification);
    PHPShopParser::set('code', $GLOBALS['PHPShopLang']->code);
    PHPShopParser::set('charset', $GLOBALS['PHPShopLang']->charset);
    PHPShopParser::set('lang', $_SESSION['lang']);
    PHPShopParser::file('tpl/signin.tpl');
}

// Смена пароля
$_REQUEST['actionList']['newPassGen'] = 'actionUpdate';
$_REQUEST['actionList']['logout'] = 'actionLogout';


// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_GET['logout'], 'actionStart');
?>