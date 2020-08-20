<?php
/*
  +-------------------------------------------------------+
  |  PHPShop Self Installer                               |
  |  Copyright © PHPShop, 2004-2020                       |
  |  Все права защищены. ИП Туренко Д.Л.                  |
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

// Поиск установочного *.sql
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

    // Загрузка
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

    // Изменние конфига
    if ($next2) {

        $_classPath = "phpshop/";
        include($_classPath . "class/obj.class.php");
        PHPShopObj::loadClass("base");

        $iniPath = 'phpshop/inc/config.ini';
        $config['connect'] = array('host' => $_POST['dbserver'], 'user_db' => $_POST['dbuser'], 'pass_db' => $_POST['dbpassword'], 'dbase' => $_POST['dbname']);
        $SysValue = parse_ini_file_true($iniPath, 1);

        // Новый config.ini
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

    // Установка завершена
    if ($next3) {

        $PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true);
        PHPShopObj::loadClass('orm');
        PHPShopObj::loadClass('lang');
        include($_classPath . "lib/phpass/passwordhash.php");

        // Язык
        $GLOBALS['PHPShopLang'] = new PHPShopLang();

        if ($sql_file = PHPShopFile::searchFile('install/', 'getDump'))
            $fp = file_get_contents('install/' . $sql_file);

        if (!empty($fp)) {

            $content = $fp;

            // Подстановка почты администратора
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

            // Отправка почты
            if (!empty($_POST['send-welcome'])) {

                PHPShopObj::loadClass("parser");
                PHPShopObj::loadClass("mail");
                PHPShopObj::loadClass("system");

                PHPShopParser::set('user_name', $_POST['user']);
                PHPShopParser::set('login', $_POST['login']);
                PHPShopParser::set('password', $_POST['password']);

                $PHPShopSystem = new PHPShopSystem();

                $PHPShopMail = new PHPShopMail($_POST['mail'], $_POST['mail'], "Пароль администратора " . $_SERVER['SERVER_NAME'], '', true, true);
                $content_adm = PHPShopParser::file('phpshop/admpanel/tpl/changepass.mail.tpl', true);

                if (!empty($content_adm)) {
                    $PHPShopMail->sendMailNow($content_adm);
                }
            }

            if (!rename("install", "_install" . md5(time()))) {
                $rename = '<div class="panel panel-warning">
              <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-exclamation-sign"></span> Удаление установщика</h3>
              </div>
              <div class="panel-body">
              Необходимо удалить папку <kbd>/install</kbd> и установочный файл <kbd>install.php</kbd> для безопасности вашего сервера.
              </div>
              </div>';
            }

            $done = '
            <p>Поздравляем Вас, PHPShop успешно установлен на ваш сервер. Для перехода в панель управления воспользуйтесь <a href="../phpshop/admpanel/" class="btn btn-primary btn-xs" target="_blank"><span class="glyphicon glyphicon-share-alt"></span>Ссылкой</a></p>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-ok"></span> Установка завершена</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">Имя: ' . $_POST['user'] . '</li>
                    <li class="list-group-item">Логин: ' . $_POST['login'] . '</li>
                    <li class="list-group-item">Пароль: ' . $_POST['password'] . '</li>
                    <li class="list-group-item">E-mail: ' . $_POST['mail'] . '</li>
                    <li class="list-group-item">Управление: <a href="http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/" target="_blank">http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/</a></li>
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
        <title>Установка <?php echo $brand; ?></title>
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
                    <li role="presentation"><a href="#p2">Настройка</a></li>
                    <li role="presentation"><a href="#p4">Лицензия</a></li>
                </ul>
                <h2><span class="glyphicon glyphicon-hdd"></span> Установка <?php echo $brand; ?></h2>
            </div>
            <ol class="breadcrumb">
                <li><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="https://www.phpshopcms.ru"><?php echo $brand; ?></a></li>
                <li class="active">Установка</li>
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
                    <h3 class="panel-title"><span class="glyphicon glyphicon-signal"></span> Соответствие системным требованиям</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item <?php echo $api_style ?>">Apache <?php echo $API ?>
                    <li class="list-group-item <?php echo $mysql_style ?>">MySQL <?php echo $mysql ?>
                    <li class="list-group-item">PHP<?php echo $php ?>
                    <li class="list-group-item <?php echo $zip_style ?>">ZipArchive для PHP <?php echo $zip_support ?>
                    <li class="list-group-item">GD Support для PHP <?php echo $gd_support ?>
                    <li class="list-group-item">XML Parser для PHP <?php echo $xml_support ?>
                </ul>
            </div>

            <form class="form-horizontal" role="form"  method="post" enctype="multipart/form-data">

                <div class="panel panel-info <?php echo $system; ?>" id="p2">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Настройка пользователя</h3>
                    </div>

                    <div id="step" >
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Имя</label>
                            <div class="col-sm-9">
                                <input type="text" name="user" required class="form-control" placeholder="Администратор" value="Администратор">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Пользователь</label>
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
                            <label class="col-sm-3 control-label">Пароль</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" required  class="form-control" placeholder="Пароль">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send-welcome" checked value="1"> Отправить регистрационные данные на E-mail
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="button" class="btn btn-default" id="generator" data-password="<?php echo "P" . substr(md5(time()), 0, 6) ?>"><span class="glyphicon glyphicon-lock"></span> Генератор паролей</button>
                                <div id="password-message">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info  <?php echo $system; ?>" id="p3">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog"></span> Настройка подключения к базе данных MySQL</h3>
                    </div>

                    <div id="step" >
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label ">Адрес сервера</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbserver" required class="form-control" placeholder="localhost" value="localhost">
                            </div>
                        </div>

                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">Имя базы данных</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbname" required class="form-control" placeholder="sitename_base" value="<?php echo $_POST['dbname'] ?>">
                            </div>
                        </div>
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">Имя пользователя базы данных</label>
                            <div class="col-sm-9">
                                <input type="text" name="dbuser" required class="form-control" placeholder="sitename_user" value="<?php echo $_POST['dbuser'] ?>">
                            </div>
                        </div>
                        <div class="form-group <?php echo $mysql_error_label; ?>">
                            <label class="col-sm-3 control-label">Пароль базы данных</label>
                            <div class="col-sm-9">
                                <input type="password" name="dbpassword"  required class="form-control" placeholder="dbpassword" value="<?php echo $_POST['dbpassword'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info <?php echo $system; ?>" id="p4">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Лицензия</h3>
                    </div>
                    <div style="overflow-y:scroll;height: 450px;padding:10px" class="small"> 

                         <h5>ЛИЦЕНЗИОННОЕ СОГЛАШЕНИЕ НА ИСПОЛЬЗОВАНИЕ ПРОГРАММНОГО ПРОДУКТА "PHPShop"</h5>
                                <p>Настоящее лицензионное соглашение заключается между пользователем программного продукта "PHPShop" (далее Пользователь) и ИП Туренко Д.Л. (далее Автор). Перед использованием продукта внимательно ознакомьтесь с условиями данного соглашения. Если вы не согласны с условиями данного соглашения, вы не можете использовать данный продукт. Установка и использование продукта означает ваше полное согласие со всеми пунктами настоящего соглашения. Соглашение относится ко всем версиям и модификациям программного продукта PHPShop.
                                <p>Основные термины настоящего соглашения: ЭКЗЕМПЛЯР ПРОГРАММЫ - копия продукта "PHPShop CMS Free", 	включающая в себя код программы управления сайтом, воспроизведенный в файлах или на бумаге, включая электронную или распечатанную документацию;
                                <p>Лицензионное соглашение вступает в силу с момента установки продукта локально или на сервер и действует на протяжении всего срока использования продукта.
                                <p>1.<b>	Предмет лицензионного соглашения</b>
                                    <br>1.1.	Предметом настоящего лицензионного соглашения является право использования неограниченного количества экземпляров программного продукта (в дальнейшем "ЭКЗЕМПЛЯР ПРОГРАММЫ", "программа" или "продукт") "PHPShop CMS Free", предоставляемое Пользователю Автором, в порядке и на условиях, установленных настоящим соглашением.
                                    <br>1.2.	Все положения настоящего соглашения распространяются как на весь продукт в целом, так и на его отдельные компоненты.
                                    <br>1.3.	Данное Соглашение дает право Пользователю на использование неограниченного количества копий Продукта PHPShop CMS Free.
                                    <br>1.4.	Лицензионное соглашение не предоставляет право собственности на продукт "PHPShop" и его компоненты, а только право использования ЭКЗЕМПЛЯРА ПРОГРАММЫ и его компонентов в соответствии с условиями, которые обозначены в пункте 3 настоящего соглашения.
                                <p><b>2.	Авторские права </b>
 <br>2.1. Все авторские права на Продукт, включая документацию и исходный текст, принадлежат Автору, на основании свидетельств о государственной регистрации программы для ЭВМ "PHPShop" №2006614274. Свидетельство о государственной регистрации товарного знака "PHPShop" №455381.
                                    <br>2.2. Продукт в целом или по отдельности является объектом авторского права и защищен Законом РФ "О правовой охране программ для электронных вычислительных машин и баз данных" от 23 сентября 1992 года, Законом РФ "Об авторском праве и смежных правах" от 9 июля 1993 года, а также международными Договорами.
                                    <br>2.3. В случае нарушения авторских прав предусматривается ответственность в соответствии с действующим законодательством РФ.
                                <p>3.<b> 	Условия использования продукта и ограничения </b>
                                    <br>3.1.	Пользователь имеет право на использование неограниченного количества копий Продукта.
                                    <br>3.2.	Пользователь может изменять, добавлять или удалять любые файлы ЭКЗЕМПЛЯРА ПРОГРАММЫ "PHPShop CMS Free" в соответствии с Законодательством РФ об авторском праве.
                                    <br>3.3.	Запрещается любое использование Продукта, противоречащее действующему законодательству РФ.

                                <p>4.<b>	Ответственность сторон</b>
                                    <br>4.1.	Любое коммерческое распространение Продукта без предварительного согласия Автора является нарушением данного Соглашения и влечет ответственность согласно действующему законодательству.
                                    <br>4.2.	За нарушение условий настоящего соглашения наступает ответственность, предусмотренная законодательством РФ.
                                    <br>4.3.	Продукт поставляется на условиях "КАК ЕСТЬ" ("AS IS") без предоставления гарантий производительности, сохранности данных, а также иных явно выраженных или предполагаемых гарантий. Автор не несет какой-либо ответственности за причинение или возможность причинения вреда Вам, Вашей информации или Вашему бизнесу вследствие использования или невозможности использования Продукта.
                                    <br>4.4.	Автор не несет ответственность, связанную с привлечением Вас к административной или уголовной ответственности за использование Продукта в противозаконных целях (включая, но не ограничиваясь, продажей через Интернет магазин объектов, изъятых из оборота или добытых преступным путем, предназначенных для разжигания межрасовой или межнациональной вражды; и т.д.).
                                    <br>4.5.	Автор не несет ответственности за работоспособность Продукта в случае внесения Вами каких бы то ни было изменений.

                                <p><b>5. Условия технической поддержки</b>

                                    <br> 5.1. Устанавливая Продукт, Пользователь может получить бесплатную техническую поддержку от других пользователей Продукта по добровольному согласию Пользователей. Бесплатные консультации проводятся в специальном разделе сайта службы бесплатной технической поддержки Продукта <a target="_blank" href="http://forum.phpshopcms.ru/">forum.phpshopcms.ru</a>.
                                    <br> 5.2. Пользователь может получить <b>платную техническую поддержку от разработчика</b>. Платные консультации проводятся в специальном разделе сайта службы платной технической поддержки <a target="_blank" href="https://help.phpshop.ru/">help.phpshop.ru</a> по рабочим дням (за исключением выходных и нерабочих праздничных дней Российской Федерации) с 10 до 18 часов московского времени. 

                                <p>6.<b>	Изменение и расторжение соглашения</b>
                                    <br>6.1.	В случае невыполнения пользователем одного из вышеуказанных положений, Автор имеет право в одностороннем порядке расторгнуть настоящее соглашение, уведомив об этом пользователя.
                                    <br>6.2.	При расторжении соглашения Пользователь обязан прекратить использование продукта и удалить программу PHPShop CMS Free полностью.
                                    <br>6.3.	В случае если компетентный суд признает какие-либо положения настоящего соглашения недействительными, Соглашение продолжает действовать в остальной части.
                                <p>Настоящее лицензионное соглашение также распространяется на все обновления, если только при обновлении программного продукта пользователю не предлагается ознакомиться и принять новое лицензионное соглашение или дополнения к действующему соглашению.
                                </p>

                    </div>
                </div>   
        </div>

        <footer class="footer <?php echo $system . ' ' . $ready; ?>">
            <div class="container text-center" style="padding-top: 15px;">

                <span class="pull-left"><label><input type="checkbox" id="licence-ok" checked> Я принимаю условия лицензионного соглашения</label>.</span>
                <button type="submit" class="btn btn-info pull-right" name="install" id="install" value="1">Установить <span class="glyphicon glyphicon-arrow-right"></span></button>
            </div>
        </footer>
    </form>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>        
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>

        $().ready(function () {

            // Ошибка MySQL
            $('[data-toggle="error"]').on('click', function (event) {
                event.preventDefault();
                alert($('.list-group-item-danger').text());
            });


            // Согласие с лицензией
            $('#licence-ok').on('click', function () {
                if (!this.checked) {
                    $('#install').attr('disabled', 'disabled');

                } else
                    $('#install').removeAttr('disabled');
            });


            // Генератор паролей
            $('#generator').on('click', function () {
                var password = $(this).attr('data-password');
                $('input[type=password]').val(password);
                $('#password-message').html('<div class="alert alert-success" role="alert">Ваш пароль: <b>' + password + '</b></div>');
            });

        });
    </script>
</body>
</body>
</html>