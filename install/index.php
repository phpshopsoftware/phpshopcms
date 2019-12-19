<?php
$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", false);

$version = null;
foreach (str_split($GLOBALS['SysValue']['upload']['version']) as $w)
    $version.=$w . '.';
$brand = 'PHPShop.CMS Free ' . substr($version, 0, 5);

$ok = '<span class="glyphicon glyphicon-ok text-success pull-right"></span>';
$error = '<span class="glyphicon glyphicon-remove text-danger pull-right"></span>';

// Apache
if (strstr($_SERVER['SERVER_SOFTWARE'], 'Apache'))
    $API = $ok;
else
    $API = $error;

// PHP
if (floatval(phpversion()) < 5.2)
    $php = $error;
else
    $php = $ok;

// MySQL
if (@mysqli_get_server_info($link_db)) {
    $mysqlversion = substr(@mysqli_get_server_info($link_db), 0, 1);
    if ($mysqlversion >= 4)
        $mysql = $ok;
    else
        $mysql = $error;
}
else
    $mysql = $ok;

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

// Поиск установочного *.sql
function getDump($file) {
    $fstat = explode(".", $file);
    if ($fstat[1] == "sql")
        return $file;
}

// Поиск обновояемых *.sql
function getDumpUpdate($dir) {
    global $value;
    if (is_dir('update/' . $dir)) {
        $value[] = array($dir, $dir, false);
    }
}

PHPShopObj::loadClass('file');
PHPShopObj::loadClass('text');
$value[] = array('Выбрать...', '', true);
$warning = $done = null;
PHPShopFile::searchFile('./update/', 'getDumpUpdate');

$update_select = PHPShopText::select('version_update', $value, 200, null, false, false);

// Обновление
if (!empty($_POST['version_update'])) {
    $PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
    $sql_file = 'update/' . $_POST['version_update'] . '/' . PHPShopFile::searchFile('update/' . $_POST['version_update'] . '/', 'getDump');

    if (file_exists($sql_file))
        $content = file_get_contents($sql_file);

    if (!empty($content)) {

        $sqlArray = explode(";\n", $content);
        if (count($sqlArray) < 5) {
            $sqlArray = explode(";\r\n", $content);
        }
        array_pop($sqlArray);
        while (list($key, $val) = each($sqlArray))
            if (!mysqli_query($link_db, $val))
                $result.='<div>' . mysqli_error($link_db) . '</div>';
    }

    $result = mysqli_error($link_db);

    if (empty($result)) {
        $done = '<div class="alert alert-success alert-dismissible" role="alert">
  <strong>Поздравляем Вас</strong> PHPShop успешно обновлен с версии ' . $_POST['version_update'] . ' до ' . $brand . '
</div> 
<div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-exclamation-sign"></span> Удаление установщика</h3>
                </div>
               <div class="panel-body">
               Необходимо удалить папку <kbd>/install</kbd> для безопасности вашего севрера.
               </div>
            </div>';
    }
    else
        $warning = '<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Ошибка!</strong> ' . $result . '
</div>';
}
// Установка базы
elseif (!empty($_POST['password'])) {

    $PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
    PHPShopObj::loadClass('orm');
    include($_classPath . "lib/phpass/passwordhash.php");

    if ($sql_file = PHPShopFile::searchFile('./', 'getDump'))
        $fp = file_get_contents($sql_file);

    if (!empty($fp)) {

        $content = str_replace("phpshop_", $_POST['prefix'], $fp);

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
                $result.='<div>' . mysqli_error($link_db) . '</div>';
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
            $content_adm = PHPShopParser::file('../phpshop/admpanel/tpl/changepass.mail.tpl', true);

            if (!empty($content_adm)) {
                $PHPShopMail->sendMailNow($content_adm);
            }
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
            </div>
            
 <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-exclamation-sign"></span> Удаление установщика</h3>
                </div>
               <div class="panel-body">
               Необходимо удалить папку <kbd>/install</kbd> для безопасности вашего севрера.
               </div>
            </div>
';
    } else {
        $warning = '<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Ошибка!</strong> ' . $result . '
</div>';
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
        <meta name="author" content="PHPShop Software">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="icon" href="/favicon.ico"> 
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

            .container .text-muted {
                margin: 20px 0;
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
            .modal-body{
                height: 500px;
            }
            #step-2{
                padding-top:30px;
            }
        </style>
    </head>
    <body role="document">
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <div class="container">

            <div class="page-header">
                <ul class="nav nav-pills pull-right hidden-sm hidden-xs">
                    <li role="presentation"><a href="#sys">Требования</a></li>
                    <li role="presentation"><a href="#inst">Установка</a></li>
                    <li role="presentation"><a href="#upd">Обновление</a></li>
                    <li role="presentation"><a href="#tran">Перенос</a></li>
                </ul>
                <h1><span class="glyphicon glyphicon-hdd"></span> Установка <?php echo $brand; ?></h1>
            </div>
            <ol class="breadcrumb">
                <li><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="/install/">Установка</a></li>
                <li class="active"><?php echo $brand; ?></li>
            </ol>
            <p class="lead">
                На этой странице вы найдет всю необходимую информацию для установки и настройки Интернет-магазина
            </p>

            <?php
            if (!empty($done)) {
                echo $done;
                $system = 'hide';
            } elseif (!empty($warning))
                echo $warning;
            else
                $system = null;
            ?>   
            <p class="<?php echo $system; ?>">   
                Ниже приведена инструкция для ручной установки PHPShop на виртуальный хостинг. Перед установкой рекомендуем ознакомиться со
                списком <a class="btn btn-info btn-xs" href="http://phpshop.ru/page/hosting-list.html" target="_blank" title="Хостинги"><span class="glyphicon glyphicon-share-alt"></span> рекомендуемых хостингов</a> на соответствие с системными требованиями PHPShop.</p>

            <p class="<?php echo $system; ?>">Если вы не хотите или по каким-то причинам не можете воспользоваться <strong>готовыми программами для установки</strong> <a href="http://wiki.phpshop.ru/index.php/PHPShop_EasyControl#PHPShop_Installer" class="btn btn-default btn-xs" target="_blank"><span class="glyphicon glyphicon-share-alt"></span> Windows Installer</a> и <a href="http://install.phpshop.ru" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-share-alt"></span> Web Installer</a>, то приведенная ниже информация, поможет вам выполнить установку в ручном режиме.</p>


            <div class="panel panel-info <?php echo $system; ?>" id="sys">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-signal"></span> Соответствие системным требованиям</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">Apache <?php echo $API ?>
                    <li class="list-group-item">MySQL<?php echo $mysql ?>
                    <li class="list-group-item">PHP<?php echo $php ?>
                    <li class="list-group-item">GD Support для PHP <?php echo $gd_support ?>
                    <li class="list-group-item">XML Parser для PHP <?php echo $xml_support ?>
                </ul>
            </div>


            <div class="panel panel-default <?php echo $system; ?>" id="inst">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-download-alt"></span> Установка в ручном режиме</h3>
                </div>
                <div class="panel-body">
                    <ol>
                        <li>Подключиться к своему серверу через FTP-клиент (FileZilla, CuteFTP, Total Commander и др.)
                        <li>Загрузить распакованный архив с PHPShop на FTP
                        <li>Создайте новую базу MySQL на своем сервере или узнайте пароли доступа к уже созданной базе у хост-провайдера.
                        <li>

                            Отредактируйте файл связи с базой MySQL <kbd>config.ini</kbd> в папке <code><?php echo $_SERVER['SERVER_NAME'] ?>/phpshop/inc/</code>. Изменить данные в кавычках " " на свои данные.

                            <pre>[connect]
host="localhost";   # имя хоста базы данных
user_db="user";     # имя пользователя
pass_db="mypas";    # пароль базы
dbase="mybase";     # имя базы</pre>

                        </li>
                        <li>
                            <p>Воспользуйтесь встроенным <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#install"><span class="glyphicon glyphicon-download-alt"></span> Установщиком базы данных</a></p>
                            <div class="alert alert-warning" role="alert"><b>Внимание!</b> Установщик базы запускать необходимо, в противном случае, не будет создан образ базы. </div>
                        </li>
                        <li>Для безопасности удалите папку <kbd>/install</kbd>
                        <li>Установите опцию <kbd>CMOD 775</kbd> (UNIX сервера) для папок:
                            <pre>
/license
/UserFiles/Image
/UserFiles/Files
/phpshop/admpanel/csv
/phpshop/admpanel/dumper/backup</pre>

                        <li>Для входа в <b>Административную панель</b> нажмите комбинацию клавиш <kbd>CTRL</kbd> + <kbd>F12</kbd>  или по ссылке  <a href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/phpshop/admpanel/">http://<?php echo $_SERVER['SERVER_NAME'] ?>/phpshop/admpanel/</a><br>
                            Пользователь и пароль задается при установке скрипта. При установке пользователь и пароль задается в ручном режиме. По желанию, регистрационные данные отсылаются на E-mail.

                </div>

            </div>

            <div class="panel panel-default <?php echo $system; ?>" id="upd">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-refresh"></span> Обновление в ручном режиме</h3>
                </div>
                <div class="panel-body">
                    <ol>
                        <li>Создать копию текущей базы данных через меню <kbd>База</kbd> - <kbd>Резервное копирование</kbd>
                        <li>Создать папку <code>/old</code> и перенести в нее все файлы из корневой директории с PHPShop (<em>www, httpdocs, docs, public_html</em>)
                        <li>Загрузить в очищенную корневую директорию файлы из архива новой версии
                        <li>Из старого конфигурационного файла <code>/old/phpshop/inc/config.ini</code> взять параметры подключения к базе данных (первые 5 строк) и вставить в новый конфигурационный файл <code>/phpshop/inc/config.ini</code>
                            <pre>[connect]
host="localhost";   # имя хоста базы данных
user_db="user";     # имя пользователя
pass_db="mypas";    # пароль базы
dbase="mybase";     # имя базы</pre>
                        <li>Запустить <a href="#" class="btn btn-success btn-xs update" target="_blank" data-toggle="modal" data-target="#install"><span class="glyphicon glyphicon-refresh"></span> Обновление базы данных</a>, выбрать предыдущую версию (которая была перед обновлением), если ее там нет, то обновлять базу не нужно. 
                        <li>Удалить папку <code>/install</code>
                        <li>Копировать папки <code>/old/UserFiles</code> со старыми изображениями в обновленный скрипт
                        <li>По необходимости копировать старый шаблон <code>/old/phpshop/templates/{имя-шаблона}</code>
                    </ol>
                </div>
            </div>

            <div class="panel panel-default <?php echo $system; ?>" id="tran">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-transfer"></span> Перенос файлов с другого сервера</h3>
                </div>
                <div class="panel-body">
                    <ol>
                        <li>Создать копию текущей базы данных на старом сервере через меню <kbd>База</kbd> - <kbd>Резервное копирование</kbd>
                        <li>Загрузить файлы переносимого скрипта из корневой веб-директории с PHPShop (<em>www, httpdocs, docs, public_html</em>) в корневую веб-директорию на новом сервере.  Для мгновенного переноса файлов с сервера на сервер можно воспользоваться утилитой <a href="http://phpshop.ru/loads/files/putty.exe" target="_blank">PyTTY</a> и  протоколом SSH. Команды оболочки после подключения на старом сервере (www заменяется на имя своей папки хранения веб-файлов):
                            <pre>tar cvf file.tar www/
gzip file.tar
cp file.tar.gz www/</pre>
                            Команды оболочки после подключения на новом сервере:

                            <pre>wget http://имя_домена/file.tar.gz
tar -zxf file.tar.gz
cp -rf file/ www/ </pre>

                        <li>Восстановить из архива скрипта папку <kbd>/install</kbd> и скопировать ее вместе с входящими в нее файлами на новый сервер.
                        <li>Прописать в файл конфигурации  <code>/phpshop/inc/config.ini</code> на новом сервере новые параметры доступа к базе данных MySQL.
                            <pre>[connect]
host="localhost";       # имя хоста
user_db="user";         # имя пользователя
pass_db="mypas";        # пароль базы
dbase="mybase";         # имя базы</pre>
                        <li>Запустить <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#install"><span class="glyphicon glyphicon-download-alt"></span> Установщик базы данных</a>. Выполнить установку баз с нуля, указать пароли доступа к панели управления (временные, после завершения пароли будут идентичны старому серверу). Будет установлена тестовая база временно.
                        <li>Удалить папку <code>/install</code>
                        <li>Авторизоваться в панели управления  <code>/phpshop/admpanel/</code>, используя новые временные пароли доступа.
                        <li>Восстановить резервную копию базы через меню <kbd>База</kbd> - <kbd>Резервное копирование</kbd>. 
                        <li>Теперь для входа в панель управления следует вводить пароли со старого сервера.
                    </ol>

                </div>
            </div>

            <div class="panel panel-warning <?php echo $system; ?>">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-alert"></span> Коды ошибок</h3>
                </div>
                <div class="panel-body">
                    <ul>
                        <li><b>101 Ошибка подключения к базе данных</b>
                            <ol>
                                <li>Проверь настройки подключения к базе данных: <em>host, user_db, pass_db, dbase</em>.
                                <li>В файле <code>phpshop/inc/config.ini</code> отредактировать переменные под вашу базу (заменить данные между кавычками).<br>
                                    <pre>[connect]
host="localhost";       # имя хоста
user_db="user";         # имя пользователя
pass_db="mypas";        # пароль базы
dbase="mybase";         # имя базы</pre>
                            </ol>
                        <li><b>102 Не установлены базы</b>
                            <ol><li>Запустить установку базы данных <code>/install/</code></ol>
                        <li><b>105 Ошибка существования папки /install</b>
                            <ol>
                                <li>Удалить папку <code>/install</code>
                            </ol>
                    </ul>
                </div>
            </div>


        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted text-center">
                    Перейти <a href="https://www.phpshopcms.ru" target="_blank" title="Разработчик"><span class="glyphicon glyphicon-home"></span>домой</a> или воспользоваться <a href="http://forum.phpshopcms.ru" target="_blank" title="Техническая поддержка"><span class="glyphicon glyphicon-user"></span>технической поддержкой</a>
                </p>
            </div>
        </footer>

        <!-- Modal  -->
        <div class="modal" id="install" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="form-horizontal" role="form"  method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Лицензия</h4>
                        </div>
                        <div class="modal-body">

                            <!-- Лицензия -->
                            <div id="step-1" style="overflow-y:scroll;height: 450px;" class="small"> 

                                <h4 class="title hide">Лицензия</h4>

                                <h4>ЛИЦЕНЗИОННОЕ СОГЛАШЕНИЕ НА ИСПОЛЬЗОВАНИЕ ПРОГРАММНОГО ПРОДУКТА "PHPShop"</h4>
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

                            <!-- Обновление -->
                            <div id="step-3" class="hide">
                                <h4 class="title hide">Обновление</h4>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Версия</label>
                                    <div class="col-sm-10">
                                        <?php echo $update_select; ?>
                                        <p></p>
                                        <p class="text-muted"><span class="glyphicon glyphicon-info-sign"></span> Необходимо выбрать свою текущую версию PHPShop (до обновления). Если вашей версии нет в списке, то выбрать версию выше и самую близкую к вашей в большую сторону. Например, ваша старая версия Enterprise 3.6.6.0.1, то следует выбрать в списке 3.6.7.1.3.</p><p class="text-muted">Префиксы <kbd>Start</kbd> и <kbd>CMS</kbd> обозначают одноименные сборки. Версия PHPShop 5 имеет одинаковую структуру данных у всех коммерческих версий Start, Enterprise и Pro 1C.</p><p class="text-muted">Для обновлении версий PHPShop 3 и ниже потребуется процедура восстановления пароля администратора по email.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Установка -->
                            <div id="step-2" class="hide">

                                <h4 class="title hide">Установка</h4>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Имя</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="user" required class="form-control" placeholder="Администратор">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Пользователь</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="login" required class="form-control" placeholder="admin">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">E-mail</label>
                                    <div class="col-sm-10">
                                        <input type="email" name="mail" required class="form-control" placeholder="mail@<?php echo $_SERVER['SERVER_NAME'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Пароль</label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password" required class="form-control" placeholder="Пароль">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Префикс <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="Префикс базы данных нужен для установки нескольких версий PHPShop в единую базу"></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="prefix" required class="form-control" value="phpshop_">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="send-welcome" checked value="1"> Отправить регистрационные данные на E-mail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-default" id="generator" data-password="<?php echo "P" . substr(md5(time()), 0, 6) ?>"><span class="glyphicon glyphicon-lock"></span> Генератор паролей</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">

                            <span class="pull-left"><label><input type="checkbox" id="licence-ok" checked> Я принимаю условия лицензионного соглашения</label>.</span>

                            <button type="button" class="btn btn-default btn-sm back hide"><span class="glyphicon glyphicon-arrow-left"></span> Назад</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отменить</button>
                            <button type="button" class="btn btn-primary btn-sm steps" data-step="1" name="install" value="1">Далее <span class="glyphicon glyphicon-arrow-right"></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Modal-->

        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script>

            $().ready(function() {

                // Обновление
                $('.update').on('click', function() {
                    $('#step-2 .title').text($('#step-3 .title').text());
                    $('#step-2').html($('#step-3').html());
                });

                // Согласие с лицензией
                $('#licence-ok').on('click', function() {
                    if (!this.checked) {
                        $('#install').modal('hide');
                        this.checked = true;
                    }
                });

                // Вперед
                $("body").on('click', '.steps', function() {
                    var step = new Number($(this).attr('data-step'));

                    switch ($(this).attr('data-step')) {

                        case "1":
                            $('#step-1').hide();
                            $('.back').removeClass('hide');
                            $('#step-2').removeClass('hide');
                            $('#step-2').show();
                            $('.modal-title').text($('#step-2 .title').text());
                            $('#licence-ok').closest('.pull-left').hide();
                            break;

                        case "2":
                            $(this).attr('type', 'submit');
                            break;
                    }

                    $(this).attr('data-step', step + 1);
                });

                // Назад
                $('.back').on('click', function() {
                    $('.steps').attr('data-step', 1);
                    $('.modal-title').text($('#step-1 .title').text());
                    $('#step-1').show();
                    $('#step-2').hide();
                    $('#licence-ok').closest('.pull-left').show();
                    $(this).addClass('hide');
                    $('.steps').attr('type', 'button');
                });

                // Генератор паролей
                $('#generator').on('click', function() {
                    var password = $(this).attr('data-password');
                    $('input[type=password]').val(password);
                    $(this).after('<div class="alert alert-success" role="alert">Ваш пароль: <b>' + password + '</b></div>');
                });

                // Подсказка
                $('[data-toggle="tooltip"]').tooltip({container: 'body'});

                $('select').addClass('form-control');

            });
        </script>

    </body>
</body>
</html>