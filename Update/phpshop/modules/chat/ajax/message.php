<?php

session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("text");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

require_once "../lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

function smile($string) {

    $Smile = array(
        ':-D' => '<img src="templates/smiley/grin.gif" alt="Смеется" border="0">',
        ':\)' => '<img src="templates/smiley/smile3.gif" alt="Улыбается" border="0">',
        ':\(' => '<img src="templates/smiley/sad.gif" alt="Грустный" border="0">',
        ':shock:' => '<img src="templates/smiley/shok.gif" alt="В шоке" border="0">',
        ':cool:' => '<img src="templates/smiley/cool.gif" alt="Самоуверенный" border="0">',
        ':blush:' => '<img src="templates/smiley/blush2.gif" alt="Стесняется" border="0">',
        ':dance:' => '<img src="templates/smiley/dance.gif" alt="Танцует" border="0">',
        ':rad:' => '<img src="templates/smiley/happy.gif" alt="Счастлив" border="0">',
        ':lol:' => '<img src="templates/smiley/lol.gif" alt="Под столом" border="0">',
        ':huh:' => '<img src="templates/smiley/huh.gif" alt="В замешательстве" border="0">',
        ':rolly:' => '<img src="templates/smiley/rolleyes.gif" alt="Загадочный" border="0">',
        ':thuf:' => '<img src="templates/smiley/threaten.gif" alt="Злой" border="0">',
        ':tongue:' => '<img src="templates/smiley/tongue.gif" alt="Показывает язык" border="0">',
        ':smart:' => '<img src="templates/smiley/umnik2.gif" alt="Умничает" border="0">',
        ':wacko:' => '<img src="templates/smiley/wacko.gif" alt="Запутался" border="0">',
        ':yes:' => '<img src="templates/smiley/yes.gif" alt="Соглашается" border="0">',
        ':yahoo:' => '<img src="templates/smiley/yu.gif" alt="Радостный" border="0">',
        ':sorry:' => '<img src="templates/smiley/sorry.gif" alt="Сожалеет" border="0">',
        ':nono:' => '<img src="templates/smiley/nono.gif" alt="Нет Нет" border="0">',
        ':dash:' => '<img src="templates/smiley/dash.gif" alt="Бьется об стенку" border="0">',
        ':dry:' => '<img src="templates/smiley/dry.gif" alt="Скептический" border="0">',
    );

    foreach ($Smile as $key => $val)
        $string = str_replace($key, $val, $string);

    return $string;
}

function get_file_link($matches) {
    $path_parts = pathinfo($matches[0]);
    $url = parse_url($matches[0]);

    if ($url['scheme'] == 'file')
        return '<a href="' . chr(47) . $GLOBALS['SysValue']['dir']['dir'] . 'UserFiles/Image/' . $_SESSION['chat_dir'] . $path_parts['basename'] . '" target="_blank">' . $path_parts['basename'] . '</a>';
    else if ($url['host'] != $_SERVER['SERVER_NAME'])
        return '<a href="' . $matches[0] . '" target="_blank">' . $matches[0] . '</a>';
    else
        return '<a href="http://' . $url['host'] . chr(47) . $GLOBALS['SysValue']['dir']['dir'] . 'UserFiles/Image/' . $_SESSION['chat_dir'] . $path_parts['basename'] . '" target="_blank">' . $path_parts['basename'] . '</a>';
}

function check_content($content) {

    $str = smile($content);
    $str = preg_replace_callback('/((www|http:\/\/|file:\/\/)[^ ]+)/', 'get_file_link', $str);

    return $str;
}

if (!empty($_SESSION['mod_chat_user_session'])) {

    // Проверка присутствия оператора
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_system"));
    $PHPShopOrm->debug = false;
    $data_system = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));


    // Зыкрытие сессии чата
    if (!empty($_REQUEST['close'])) {

        // Новое сообщение System
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
        $insert['user_session_new'] = $_SESSION['mod_chat_user_session'];
        $insert['content_new'] = htmlspecialchars('Пользователь ' . $_SESSION['mod_chat_user_name'] . ' покинул чат', ENT_COMPAT, 'windows-1251');
        $insert['status_new'] = 1;
        $insert['name_new'] = 'System';
        $insert['date_new'] = time();
        $PHPShopOrm->insert($insert);

        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_users"));
        $PHPShopOrm->debug = true;
        $update['status_new'] = 2;
        $PHPShopOrm->update($update, array('user_session' => "='" . $_SESSION['mod_chat_user_session'] . "'"));


        unset($_SESSION['mod_chat_user_session']);
        unset($_SESSION['mod_chat_user_name']);
        $_RESULT = array('close' => true);
        exit();
    }


    // Запись вопроса
    if (!empty($_REQUEST['addtext']) and $data_system['operator'] == 1) {
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
        $insert['user_session_new'] = $_SESSION['mod_chat_user_session'];
        $insert['content_new'] = htmlspecialchars($_REQUEST['addtext'], ENT_COMPAT, 'windows-1251');
        $insert['name_new'] = $_SESSION['mod_chat_user_name'];
        $insert['status_new'] = 1;
        $insert['date_new'] = time();
        $PHPShopOrm->insert($insert);
    }


    // Список сообщений
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('user_session' => "='" . $_SESSION['mod_chat_user_session'] . "'",
        'date' => '> "' . $_REQUEST['time'].'"'), array('order' => 'id'), array('limit' => 100));
    $content = null;
    $time = $_REQUEST['time'];
    if (is_array($data)) {
        foreach ($data as $row) {

            // Иконка
            if ($_SESSION['mod_chat_user_name'] == $row['name']) {
                //$icon = 'user.png';
                $name = '<h4>' . $row['name'] . '</h4>';
                $div_class = 'text_user panel panel-body';
            } else {
                //$icon = 'admin.png';
                if (!empty($row['avatar']))
                    $icon = '<img src="' . $row['avatar'] . '" alt="" onerror="imgerror(this)" class="img-thumbnail avatar">';
                $name = '<h4 class="pull-right">' . $row['name'] . $icon . '</h4>';
                $div_class = 'text_admin panel panel-body';
            }

            //$name = PHPShopText::img('./templates/' . $icon, 3, 'absmiddle') . $name;
            $content.=PHPShopText::div(check_content($row['content']) . $name, "left", false, false, $div_class);
            $time = $row['date'];
        }
    }
}

$_RESULT = array(
    "message" => $content,
    "time" => $time
);
?>
