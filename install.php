<?php
/*
  +-------------------------------------------------------+
  |  PHPShop Self Installer                               |
  |  Copyright � PHPShop, 2004-2020                       |
  |  ��� ����� ��������. �� ������� �.�.                  |
  |  https://www.phpshop.ru/page/license.html             |
  +-------------------------------------------------------+
 */

//  UTF-8 Default Charset Fix
if (stristr(ini_get("default_charset"), "utf") and function_exists('ini_set')) {
    ini_set("default_charset", "cp1251");

    if (stristr(ini_get("default_charset"), "utf"))
        exit('The encoding mbstring.internal_encoding = "' . ini_get("default_charset") . '" is not supported, only mbstring.internal_encoding = "CP1251"');
}

if (function_exists('set_time_limit'))
    set_time_limit(0);

$brand = 'PHPShop CMS Free 5';
$ok = '<span class="glyphicon glyphicon-ok text-info pull-right"></span>';
$error = '<span class="glyphicon glyphicon-remove text-danger pull-right"></span>';
$alert = 'list-group-item-danger';
$mysql = '<span class="glyphicon glyphicon-hourglass text-warning pull-right"></span>';
$ready = null;


// Apache
if (strstr($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
    $API = $ok;
    $api_style = null;
} else {
    $API = $error;
    $api_style = $alert;
    $ready = 'hide';
}

// PHP
if (floatval(phpversion()) < 5.3) {
    $php = $error;
    $ready = 'hide';
} else
    $php = $ok;

// GD Support
$GD = gd_info();
if (!empty($GD['GD Version']))
    $gd_support = $ok;
else
    $gd_support = $error;

// XML Support
if (function_exists("simplexml_load_string"))
    $xml_support = $ok;
else
    $xml_support = $error;

// Zip Support
if (class_exists("ZipArchive"))
    $zip_support = $ok;
else {
    $zip_support = $error;
    $zip_style = $alert;
    $ready = 'hide';
}

// ����� ������������� *.sql
function getDump($file) {
    $fstat = explode(".", $file);
    if ($fstat[1] == "sql")
        return $file;
}

if (isset($_POST['install'])) {

    $link_db = @mysqli_connect($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpassword']) or $mysql_error = mysqli_connect_error();
    @mysqli_select_db($link_db, $_POST['dbname']) or $mysql_error .= @mysqli_error($link_db);
    @mysqli_query($link_db, "SET NAMES 'cp1251'");
    @mysqli_query($link_db, "SET SESSION sql_mode=''");

    if ($link_db and empty($mysql_error)) {
        $mysql = $ok;
        $mysql_style = null;
        $next = true;
    } else {
        $mysql = $error;
        $mysql_style = $alert;
        $next = false;
        $mysql_error_label = 'has-error';
    }

    // ��������
    if ($next) {
        $file = $path = 'cmsfree5.zip';
        $url = 'http://install.phpshop.ru/load/' . $file;
        $next2 = true;

        if (!file_exists($file)) {
            $fp = fopen($path, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            $data = curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

            $zip = new ZipArchive;
            $res = $zip->open($file);
            if ($res === TRUE) {
                $zip->extractTo($path);
                $zip->close();
                $next2 = true;
            } else {
                $next2 = false;
            }
        }
    }

    // �������� �������
    if ($next2) {

        $_classPath = "phpshop/";
        include($_classPath . "class/obj.class.php");
        PHPShopObj::loadClass("base");

        $iniPath = 'phpshop/inc/config.ini';
        $config['connect'] = array('host' => $_POST['dbserver'], 'user_db' => $_POST['dbuser'], 'pass_db' => $_POST['dbpassword'], 'dbase' => $_POST['dbname']);
        $SysValue = parse_ini_file_true($iniPath, 1);

        // ����� config.ini
        if (is_array($config)) {
            foreach ($config as $k => $v) {
                if (is_array($config[$k])) {
                    foreach ($config[$k] as $key => $value) {
                        $SysValue[$k][$key] = $value;
                    }
                }
            }
        }

        $s = null;

        if (is_array($SysValue))
            foreach ($SysValue as $k => $v) {

                $s .= "[$k]\n";
                foreach ($v as $key => $val) {
                    if (!is_array($val))
                        $s .= "$key = \"$val\";\n";
                }

                $s .= "\n";
            }

        if (!empty($s)) {
            if ($f = @fopen("phpshop/inc/config.ini", "w")) {

                if (!empty($s) and strstr($s, 'phpshop')) {
                    fwrite($f, $s);
                    $next3 = true;
                }

                fclose($f);
            } else
                $next3 = false;
        } else
            $next3 = false;
    }

    // ��������� ���������
    if ($next3) {

        $PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true);
        PHPShopObj::loadClass('orm');
        PHPShopObj::loadClass('lang');
        include($_classPath . "lib/phpass/passwordhash.php");

        // ����
        $GLOBALS['PHPShopLang'] = new PHPShopLang();

        if ($sql_file = PHPShopFile::searchFile('install/', 'getDump'))
            $fp = file_get_contents('install/' . $sql_file);

        if (!empty($fp)) {

            $content = $fp;

            // ����������� ����� ��������������
            if (!empty($_POST['send-welcome'])) {
                $content = str_replace("admin@localhost", $_POST['mail'], $content);
            }

            $sqlArray = explode(";\n", $content);
            if (count($sqlArray) < 5) {
                $sqlArray = explode(";\r\n", $content);
            }
            array_pop($sqlArray);
            $result = null;
            foreach ($sqlArray as $val) {
                if (!mysqli_query($link_db, $val))
                    $result .= '<div>' . mysqli_error($link_db) . '</div>';
            }
        }

        if (empty($result)) {

            $hasher = new PasswordHash(8, false);
            $PHPShopOrm = new PHPShopOrm($PHPShopBase->getParam('base.users'));

            $insert = array(
                'status' => 'a:24:{s:5:"gbook";s:5:"1-1-1";s:4:"news";s:5:"1-1-1";s:5:"order";s:7:"1-1-1-1";s:5:"users";s:7:"1-1-1-1";s:9:"shopusers";s:5:"1-1-1";s:7:"catalog";s:11:"1-1-1-0-0-0";s:6:"report";s:5:"1-1-1";s:4:"page";s:5:"1-1-1";s:4:"menu";s:5:"1-1-1";s:6:"banner";s:5:"1-1-1";s:6:"slider";s:5:"1-1-1";s:5:"links";s:5:"1-1-1";s:3:"csv";s:5:"1-1-1";s:5:"opros";s:5:"1-1-1";s:6:"rating";s:5:"1-1-1";s:8:"exchange";s:5:"1-1-0";s:6:"system";s:3:"1-1";s:8:"discount";s:5:"1-1-1";s:6:"valuta";s:5:"1-1-1";s:8:"delivery";s:5:"1-1-1";s:7:"servers";s:5:"1-1-1";s:10:"rsschanels";s:5:"0-0-0";s:6:"update";i:1;s:7:"modules";s:9:"1-1-1-0-0";}',
                'login' => $_POST['login'],
                'password' => $hasher->HashPassword($_POST['password']),
                'mail' => $_POST['mail'],
                'enabled' => 1,
                'name' => $_POST['user']
            );

            $PHPShopOrm->insert($insert, '');

            // �������� �����
            if (!empty($_POST['send-welcome'])) {

                PHPShopObj::loadClass("parser");
                PHPShopObj::loadClass("mail");
                PHPShopObj::loadClass("system");

                PHPShopParser::set('user_name', $_POST['user']);
                PHPShopParser::set('login', $_POST['login']);
                PHPShopParser::set('password', $_POST['password']);

                $PHPShopSystem = new PHPShopSystem();

                $PHPShopMail = new PHPShopMail($_POST['mail'], $_POST['mail'], "������ �������������� " . $_SERVER['SERVER_NAME'], '', true, true);
                $content_adm = PHPShopParser::file('phpshop/admpanel/tpl/changepass.mail.tpl', true);

                if (!empty($content_adm)) {
                    $PHPShopMail->sendMailNow($content_adm);
                }
            }

            if (!rename("install", "_install" . md5(time()))) {
                $rename = '<div class="panel panel-warning">
              <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-exclamation-sign"></span> �������� �����������</h3>
              </div>
              <div class="panel-body">
              ���������� ������� ����� <kbd>/install</kbd> � ������������ ���� <kbd>install.php</kbd> ��� ������������ ������ �������.
              </div>
              </div>';
            }

            $done = '
            <p>����������� ���, PHPShop ������� ���������� �� ��� ������. ��� �������� � ������ ���������� �������������� <a href="../phpshop/admpanel/" class="btn btn-primary btn-xs" target="_blank"><span class="glyphicon glyphicon-share-alt"></span>�������</a></p>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-ok"></span> ��������� ���������</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">���: ' . $_POST['user'] . '</li>
                    <li class="list-group-item">�����: ' . $_POST['login'] . '</li>
                    <li class="list-group-item">������: ' . $_POST['password'] . '</li>
                    <li class="list-group-item">E-mail: ' . $_POST['mail'] . '</li>
                    <li class="list-group-item">����������: <a href="http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/" target="_blank">http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/</a></li>
                </ul>
            </div>' . $rename;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>��������� <?php echo $brand; ?></title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <style>
            html {
                position: relative;
                min-height: 100%;
            }
            body {
                margin-bottom: 60px;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 60px;
                background-color: #f5f5f5;
            }
            .container {
                width: auto;
                max-width: 800px;
                padding: 0 15px;
            }

            .container .text-muted {
                padding-top: 20px;
            }
            a .glyphicon{
                padding-right: 3px;
            }

            .panel{
                margin-top:20px;
            }
            pre,.alert {
                margin-top:10px;
            }
            #step{
                padding:30px;
            }
        </style>
    </head>
    <body role="document">

        <div class="container">
            <div class="page-header">
                <ul class="nav nav-pills pull-right hidden-sm hidden-xs">
                    <li role="presentation"><a href="#p2">���������</a></li>
                    <li role="presentation"><a href="#p4">��������</a></li>
                </ul>
                <h2><span class="glyphicon glyphicon-hdd"></span> ��������� <?php echo $brand; ?></h2>
            </div>
            <ol class="breadcrumb">
                <li><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="https://www.phpshopcms.ru"><?php echo $brand; ?></a></li>
                <li class="active">���������</li>
            </ol>

            <?php
            if (!empty($done)) {
                echo $done;
                $system = 'hide';
            } elseif (!empty($warning))
                echo $warning;
            else
                $system = null;
            ?>   

            <div class="panel panel-info <?php echo $system; ?>" id="p1">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-signal"></span> ������������ ��������� �����������</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item <?php echo $api_style ?>">Apache <?php echo $API ?>
                    <li class="list-group-item <?php echo $mysql_style ?>">MySQL <?php echo $mysql ?>
                    <li class="list-group-item">PHP<?php echo $php ?>
                    <li class="list-group-item <?php echo $zip_style ?>">ZipArchive ��� PHP <?php echo $zip_support ?>
                    <li class="list-group-item">GD Support ��� PHP <?php echo $gd_support ?>
                    <li class="list-group-item">XML Parser ��� PHP <?php echo $xml_support ?>
                </ul>
            </div>

            <form class="form-horizontal" role="form"  method="post" enctype="multipart/form-data">

                <div class="panel panel-info <?php echo $system; ?>" id="p2">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> ��������� ������������</h3>
                    </div>

                    <div id="step" >
                        <div class="form-group">
                            <label class="col-sm-3 control-label">���</label>
                            <div class="col-sm-9">
                                <input type="text" name="user" required class="form-control" placeholder="�������������" value="�������������">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">������������</label>
                            <div class="col-sm-9">
                                <input type="text" name="login" required class="form-control" placeholder="admin" value="admin">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">E-mail</label>
                            <div class="col-sm-9">
                                <input type="email" name="mail" required class="form-control" placeholder="mail@<?php echo $_SERVER['SERVER_NAME'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">������</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" required  class="form-control" placeholder="������">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send-welcome" checked value="1"> ��������� ��������������� ������ �� E-mail
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="button" class="btn btn-default" id="generator" data-password="<?php echo "P" . substr(md5(time()), 0, 6) ?>"><span class="glyphicon glyphicon-lock"></span> ��������� �������</button>
                                <div id="password-message">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info  <?php echo $system; ?>" id="p3">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog"></span> ��������� ����������� � ���� ������ MySQL</h3>
                    </div>

                    <div id="step" >
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label ">����� �������</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbserver" required class="form-control" placeholder="localhost" value="localhost">
                            </div>
                        </div>

                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">��� ���� ������</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbname" required class="form-control" placeholder="sitename_base" value="<?php echo $_POST['dbname'] ?>">
                            </div>
                        </div>
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">��� ������������ ���� ������</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbuser" required class="form-control" placeholder="sitename_user" value="<?php echo $_POST['dbuser'] ?>">
                            </div>
                        </div>
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">������ ���� ������</label>
                            <div class="col-sm-9">
                                <input type="password" name="dbpassword"  required class="form-control" placeholder="dbpassword" value="<?php echo $_POST['dbpassword'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info <?php echo $system; ?>" id="p4">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> ��������</h3>
                    </div>
                    <div style="overflow-y:scroll;height: 450px;padding:10px" class="small"> 

                         <h5>������������ ���������� �� ������������� ������������ �������� "PHPShop"</h5>
                                <p>��������� ������������ ���������� ����������� ����� ������������� ������������ �������� "PHPShop" (����� ������������) � �� ������� �.�. (����� �����). ����� �������������� �������� ����������� ������������ � ��������� ������� ����������. ���� �� �� �������� � ��������� ������� ����������, �� �� ������ ������������ ������ �������. ��������� � ������������� �������� �������� ���� ������ �������� �� ����� �������� ���������� ����������. ���������� ��������� �� ���� ������� � ������������ ������������ �������� PHPShop.
                                <p>�������� ������� ���������� ����������: ��������� ��������� - ����� �������� "PHPShop CMS Free", 	���������� � ���� ��� ��������� ���������� ������, ���������������� � ������ ��� �� ������, ������� ����������� ��� ������������� ������������;
                                <p>������������ ���������� �������� � ���� � ������� ��������� �������� �������� ��� �� ������ � ��������� �� ���������� ����� ����� ������������� ��������.
                                <p>1.<b>	������� ������������� ����������</b>
                                    <br>1.1.	��������� ���������� ������������� ���������� �������� ����� ������������� ��������������� ���������� ����������� ������������ �������� (� ���������� "��������� ���������", "���������" ��� "�������") "PHPShop CMS Free", ��������������� ������������ �������, � ������� � �� ��������, ������������� ��������� �����������.
                                    <br>1.2.	��� ��������� ���������� ���������� ���������������� ��� �� ���� ������� � �����, ��� � �� ��� ��������� ����������.
                                    <br>1.3.	������ ���������� ���� ����� ������������ �� ������������� ��������������� ���������� ����� �������� PHPShop CMS Free.
                                    <br>1.4.	������������ ���������� �� ������������� ����� ������������� �� ������� "PHPShop" � ��� ����������, � ������ ����� ������������� ���������� ��������� � ��� ����������� � ������������ � ���������, ������� ���������� � ������ 3 ���������� ����������.
                                <p><b>2.	��������� ����� </b>
 <br>2.1. ��� ��������� ����� �� �������, ������� ������������ � �������� �����, ����������� ������, �� ��������� ������������ � ��������������� ����������� ��������� ��� ��� "PHPShop" �2006614274. ������������� � ��������������� ����������� ��������� ����� "PHPShop" �455381.
                                    <br>2.2. ������� � ����� ��� �� ����������� �������� �������� ���������� ����� � ������� ������� �� "� �������� ������ �������� ��� ����������� �������������� ����� � ��� ������" �� 23 �������� 1992 ����, ������� �� "�� ��������� ����� � ������� ������" �� 9 ���� 1993 ����, � ����� �������������� ����������.
                                    <br>2.3. � ������ ��������� ��������� ���� ����������������� ��������������� � ������������ � ����������� ����������������� ��.
                                <p>3.<b> 	������� ������������� �������� � ����������� </b>
                                    <br>3.1.	������������ ����� ����� �� ������������� ��������������� ���������� ����� ��������.
                                    <br>3.2.	������������ ����� ��������, ��������� ��� ������� ����� ����� ���������� ��������� "PHPShop CMS Free" � ������������ � ����������������� �� �� ��������� �����.
                                    <br>3.3.	����������� ����� ������������� ��������, �������������� ������������ ���������������� ��.

                                <p>4.<b>	��������������� ������</b>
                                    <br>4.1.	����� ������������ ��������������� �������� ��� ���������������� �������� ������ �������� ���������� ������� ���������� � ������ ��������������� �������� ������������ ����������������.
                                    <br>4.2.	�� ��������� ������� ���������� ���������� ��������� ���������������, ��������������� ����������������� ��.
                                    <br>4.3.	������� ������������ �� �������� "��� ����" ("AS IS") ��� �������������� �������� ������������������, ����������� ������, � ����� ���� ���� ���������� ��� �������������� ��������. ����� �� ����� �����-���� ��������������� �� ���������� ��� ����������� ���������� ����� ���, ����� ���������� ��� ������ ������� ���������� ������������� ��� ������������� ������������� ��������.
                                    <br>4.4.	����� �� ����� ���������������, ��������� � ������������ ��� � ���������������� ��� ��������� ��������������� �� ������������� �������� � ��������������� ����� (�������, �� �� �������������, �������� ����� �������� ������� ��������, ������� �� ������� ��� ������� ���������� �����, ��������������� ��� ���������� ���������� ��� ��������������� ������; � �.�.).
                                    <br>4.5.	����� �� ����� ��������������� �� ����������������� �������� � ������ �������� ���� ����� �� �� �� ���� ���������.

                                <p><b>5. ������� ����������� ���������</b>

                                    <br> 5.1. ������������ �������, ������������ ����� �������� ���������� ����������� ��������� �� ������ ������������� �������� �� ������������� �������� �������������. ���������� ������������ ���������� � ����������� ������� ����� ������ ���������� ����������� ��������� �������� <a target="_blank" href="http://forum.phpshopcms.ru/">forum.phpshopcms.ru</a>.
                                    <br> 5.2. ������������ ����� �������� <b>������� ����������� ��������� �� ������������</b>. ������� ������������ ���������� � ����������� ������� ����� ������ ������� ����������� ��������� <a target="_blank" href="https://help.phpshop.ru/">help.phpshop.ru</a> �� ������� ���� (�� ����������� �������� � ��������� ����������� ���� ���������� ���������) � 10 �� 18 ����� ����������� �������. 

                                <p>6.<b>	��������� � ����������� ����������</b>
                                    <br>6.1.	� ������ ������������ ������������� ������ �� ������������� ���������, ����� ����� ����� � ������������� ������� ����������� ��������� ����������, �������� �� ���� ������������.
                                    <br>6.2.	��� ����������� ���������� ������������ ������ ���������� ������������� �������� � ������� ��������� PHPShop CMS Free ���������.
                                    <br>6.3.	� ������ ���� ������������ ��� �������� �����-���� ��������� ���������� ���������� �����������������, ���������� ���������� ����������� � ��������� �����.
                                <p>��������� ������������ ���������� ����� ���������������� �� ��� ����������, ���� ������ ��� ���������� ������������ �������� ������������ �� ������������ ������������ � ������� ����� ������������ ���������� ��� ���������� � ������������ ����������.
                                </p>

                    </div>
                </div>   
        </div>

        <footer class="footer <?php echo $system . ' ' . $ready; ?>">
            <div class="container text-center" style="padding-top: 15px;">

                <span class="pull-left"><label><input type="checkbox" id="licence-ok" checked> � �������� ������� ������������� ����������</label>.</span>
                <button type="submit" class="btn btn-info pull-right" name="install" id="install" value="1">���������� <span class="glyphicon glyphicon-arrow-right"></span></button>
            </div>
        </footer>
    </form>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>        
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>

        $().ready(function () {

            // ������ MySQL
            $('[data-toggle="error"]').on('click', function (event) {
                event.preventDefault();
                alert($('.list-group-item-danger').text());
            });


            // �������� � ���������
            $('#licence-ok').on('click', function () {
                if (!this.checked) {
                    $('#install').attr('disabled', 'disabled');

                } else
                    $('#install').removeAttr('disabled');
            });


            // ��������� �������
            $('#generator').on('click', function () {
                var password = $(this).attr('data-password');
                $('input[type=password]').val(password);
                $('#password-message').html('<div class="alert alert-success" role="alert">��� ������: <b>' + password + '</b></div>');
            });

        });
    </script>
</body>
</body>
</html>