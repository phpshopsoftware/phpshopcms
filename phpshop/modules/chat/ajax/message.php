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

// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

require_once "../lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

function smile($string) {

    $Smile = array(
        ':-D' => '<img src="templates/smiley/grin.gif" alt="�������" border="0">',
        ':\)' => '<img src="templates/smiley/smile3.gif" alt="���������" border="0">',
        ':\(' => '<img src="templates/smiley/sad.gif" alt="��������" border="0">',
        ':shock:' => '<img src="templates/smiley/shok.gif" alt="� ����" border="0">',
        ':cool:' => '<img src="templates/smiley/cool.gif" alt="�������������" border="0">',
        ':blush:' => '<img src="templates/smiley/blush2.gif" alt="����������" border="0">',
        ':dance:' => '<img src="templates/smiley/dance.gif" alt="�������" border="0">',
        ':rad:' => '<img src="templates/smiley/happy.gif" alt="��������" border="0">',
        ':lol:' => '<img src="templates/smiley/lol.gif" alt="��� ������" border="0">',
        ':huh:' => '<img src="templates/smiley/huh.gif" alt="� ��������������" border="0">',
        ':rolly:' => '<img src="templates/smiley/rolleyes.gif" alt="����������" border="0">',
        ':thuf:' => '<img src="templates/smiley/threaten.gif" alt="����" border="0">',
        ':tongue:' => '<img src="templates/smiley/tongue.gif" alt="���������� ����" border="0">',
        ':smart:' => '<img src="templates/smiley/umnik2.gif" alt="��������" border="0">',
        ':wacko:' => '<img src="templates/smiley/wacko.gif" alt="���������" border="0">',
        ':yes:' => '<img src="templates/smiley/yes.gif" alt="�����������" border="0">',
        ':yahoo:' => '<img src="templates/smiley/yu.gif" alt="���������" border="0">',
        ':sorry:' => '<img src="templates/smiley/sorry.gif" alt="��������" border="0">',
        ':nono:' => '<img src="templates/smiley/nono.gif" alt="��� ���" border="0">',
        ':dash:' => '<img src="templates/smiley/dash.gif" alt="������ �� ������" border="0">',
        ':dry:' => '<img src="templates/smiley/dry.gif" alt="������������" border="0">',
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

    // �������� ����������� ���������
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_system"));
    $PHPShopOrm->debug = false;
    $data_system = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));


    // �������� ������ ����
    if (!empty($_REQUEST['close'])) {

        // ����� ��������� System
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
        $insert['user_session_new'] = $_SESSION['mod_chat_user_session'];
        $insert['content_new'] = htmlspecialchars('������������ ' . $_SESSION['mod_chat_user_name'] . ' ������� ���', ENT_COMPAT, 'windows-1251');
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


    // ������ �������
    if (!empty($_REQUEST['addtext']) and $data_system['operator'] == 1) {
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
        $insert['user_session_new'] = $_SESSION['mod_chat_user_session'];
        $insert['content_new'] = htmlspecialchars($_REQUEST['addtext'], ENT_COMPAT, 'windows-1251');
        $insert['name_new'] = $_SESSION['mod_chat_user_name'];
        $insert['status_new'] = 1;
        $insert['date_new'] = time();
        $PHPShopOrm->insert($insert);
    }


    // ������ ���������
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_jurnal"));
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('user_session' => "='" . $_SESSION['mod_chat_user_session'] . "'",
        'date' => '> "' . $_REQUEST['time'].'"'), array('order' => 'id'), array('limit' => 100));
    $content = null;
    $time = $_REQUEST['time'];
    if (is_array($data)) {
        foreach ($data as $row) {

            // ������
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
