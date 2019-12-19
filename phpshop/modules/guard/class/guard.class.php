<?php

/**
 * PHPShop Guard
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopInc
 */
class Guard {

    var $version = '1.7';
    var $none_chek_temlates = array('example');
    var $none_chek_dir = array('UserFiles', 'install', '1cManager', 'backup', 'files', 'csv', 'editors', 'Packs', 'doc',
        '_dev_', '.hg', 'awstats');
    var $src = array('php', 'js');
    var $update_url = 'http://www.phpshop.ru/update/guard/update2.php';
    var $update_enabled = true;
    var $backup_path = 'UserFiles/Files/';
    var $license_path = 'license/';
    var $none_chek_file = array('example');

    // Конструктор
    function __construct($dir_global) {
        global $PHPShopSystem, $_classPath;
        $this->classPath = $_classPath;
        $this->dir_global = $dir_global;

        // Включаем таймер
        $time = explode(' ', microtime());
        $this->start_time = $time[1] + $time[0];

        $this->PHPShopSystem = $PHPShopSystem;
        $this->SysValue = $GLOBALS['SysValue'];
        $this->date = date("U");
        $this->sec = md5($this->date);

        // Сообщение о заражении
        $this->stop_message = $this->SysValue['lang']['guard_stop_message'];

        // Дизайн
        $this->my_template = $this->PHPShopSystem->getParam('skin');

        // Настройка
        $this->system();

        // Заглушка
        if (!empty($this->system['stop']))
            $this->alert();
    }

    // Запуск
    function start() {

        if ($this->system['enabled'] and empty($this->system['used'])) {

            // Принудительная проверка обновлений сигнатур
            //if($this->date-$this->system['last_update']>86400) $this->update();

            if ($this->date - $this->system['last_chek'] > (86400 / $this->system['chek_day_num'])) {

                // Пишем лог
                $this->log('start');

                // Проверяем файлы
                $this->file($this->dir_global);

                // Первый бекап
                if ($this->log_id == 1) {
                    $this->create();
                    $this->first_start = true;
                }

                // Сравниваем
                $this->chek();

                // Сигнатуры
                $this->signature();

                // Пишем лог
                $this->log('end');
            }
        }
    }

    // Настройка защитника
    function system() {
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
        $this->system = $PHPShopOrm->select();
    }

    // Срок действия тех. поддержки
    function license() {
        if (@$dh = opendir($this->license_path)) {
            while (($file = readdir($dh)) !== false) {
                $fstat = explode(".", $file);
                if ($fstat[1] == "lic")
                    $license = $this->license_path . $file;
            }
            closedir($dh);
        }
        $license = @parse_ini_file_true($license, 1);
        $this->support = $license['License']['SupportExpires'];
    }

    // Обновление сигнатур
    function update() {

        // Если разрешена проверка обновлений
        if ($this->update_enabled) {

            PHPShopObj::loadClass("xml");

            // Проверка лицензии
            $this->license();

            // if($this->support > time()) {

            $this->update_url.="?from=" . $_SERVER['SERVER_NAME'] . "&version=" . $this->SysValue['upload']['version'] . "&support=" . $this->support;
            if (function_exists("xml_parser_create")) {
                if (@$db = readDatabase($this->update_url, "virus")) {

                    // Очищаем
                    if (is_array($db)) {

                        if ($db[0]['status'] != 'passive') {

                            $PHPShopOrm = new PHPShopOrm();
                            $PHPShopOrm->query('TRUNCATE TABLE ' . $this->SysValue['base']['guard']['guard_signature']);
                            $virus_name = null;

                            // Вставляем новые сигнатуры
                            foreach ($db as $key => $val) {
                                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_signature']);
                                $PHPShopOrm->insert(array('virus_name_new' => $val['name'], 'virus_signature_new' => $val['signature']));
                                $virus_name.=$val['name'] . '
';
                            }

                            // Обновляем дату обновления сигнатур
                            $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                            $PHPShopOrm->update(array('last_update_new' => $this->date), array('id' => '=1'));

                            $this->update_result_virus = substr($virus_name, 0, -2);
                            $this->update_result = 1;
                        }
                        else
                            $this->update_result = 0;
                    }
                    else
                        $this->update_result = 2;
                }


                // Кол-во сигнатур
                $this->signature_num = count($db);
            }
            //}
        }
    }

    // Закрываем сайт при заражении
    function alert() {
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data = $PHPShopOrm->select(array('infected_files'), false, array('order' => 'id DESC'), array('limit' => 1));
        if ($data['infected_files'] > 0)
            $this->message($this->stop_message);
    }

    // Проверка сигнатур вирусов
    function signature() {
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_signature']);
        $data = $PHPShopOrm->select(array('virus_name', 'virus_signature'), false, false, array('limit' => 1000));
        $pattern = '/(';
        if (is_array($data)) {
            foreach ($data as $row)
                $pattern.=$row['virus_signature'] . '|';
            $pattern = substr($pattern, 0, -1);
            $pattern.=')/i';

            if (is_array($this->update) and is_array($this->new))
                $warning_list = array_merge($this->update, $this->new);
            elseif (is_array($this->update))
                $warning_list = $this->update;
            else
                $warning_list = $this->new;

            if (is_array($warning_list))
                foreach ($warning_list as $val) {
                    $virus_str = 'Найдено: ';
                    if (!in_array($val, $this->none_chek_file)) {
                        $content = file_get_contents($val);
                        if (preg_match($pattern, $content, $match_vurus)) {

                            // Строк вхождения
                            foreach ($match_vurus as $virus_name)
                                $virus_str.=$virus_name . ', ';

                            $this->infected[] = $val . ' (' . substr($virus_str, 0, -2) . ')';
                        }
                    }
                }
        }
    }

    // Проверка текущего шаблона
    function template_chek() {
        if (is_array($this->none_chek_temlates))
            foreach ($this->none_chek_temlates as $val)
                if ($val != $this->my_template)
                    $this->temlates[] = $val;
    }

    // Проверка режима
    function mode($file, $ext) {
        if (empty($this->system['mode'])) {
            if ($ext == "php") {
                if ($file == "index.php")
                    return true;
                else
                    return false;
            }
            else
                return true;
        }
        else
            return true;
    }

    // Выбор файла
    function file($dir) {

        $this->template_chek();
        $none = array_merge($this->temlates, $this->none_chek_dir);

        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && !in_array($file, $none)) {

                    // Проверка расширения
                    $ext = pathinfo($file);

                    if (is_dir($dir . "/" . $file))
                        $this->file($dir . "/" . $file);
                    else if (in_array($ext['extension'], $this->src) and $this->mode($file, $ext['extension']) and file_get_contents($dir . "/" . $file)) {
                        $this->base[md5_file($dir . "/" . $file)] = $dir . "/" . $file;
                    }
                }
            }
            closedir($dh);
        }
        return null;
    }

    // Сообщение
    function message($content, $caption = 'PHPShop Guard') {
        $message = '<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . $caption . '</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
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
                max-width: 680px;
                padding: 0 15px;
            }
            .container .text-muted {
                margin: 20px 0;
            }
            a .glyphicon{
                padding-right: 3px;
            }
        </style>
    </head>
    <body role="document">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <div class="container">
            <div class="page-header">
                <h1>' . $caption . ' ' . $this->version . '</h1>
            </div>
            <p class="lead">
            ' . $content . '
            </p>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted">' . date('r') . '</p>
            </div>
        </footer>
        <script>
        if(window.opener)
        window.opener.location.reload();
        </script>
    </body>
    </body>
</html>';
        exit($message);
    }

    function backup() {
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data = $PHPShopOrm->select(array('backup'), array('backup' => "!=''"), array('order' => 'id DESC'), array('limit' => 1));
        return $data['backup'];
    }

    // Запись лога в базу
    function log($action = 'start') {

        switch ($action) {

            case "start":

                // Блокируем загрузки
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new' => '1'), array('id' => '=1'));

                // Пишем лог
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $this->log_id = $PHPShopOrm->insert(array('date_new' => $this->date));
                break;

            case "end":

                // Имя последнего бекапа
                $last_backup = $this->backup();
                if (empty($last_backup))
                    $last_backup = date("d-m-Y-H-i", $this->date) . '-' . $this->sec;

                // Бекап
                if (count($this->infected) == 0)
                    if (count($this->changes) > 0 or count($this->new) > 0) {
                        $this->zip();
                        $backup = date("d-m-Y-H-i", $this->date) . '-' . $this->sec;
                    }

                // Если первый запуск
                if ($this->first_start) {
                    $this->zip();
                    $backup = date("d-m-Y-H-i", $this->date) . '-' . $this->sec;
                }


                // Сообщение администратору
                if (count($this->infected) > 0 or count($this->changes) > 0)
                    if ($this->system['mail_enabled'])
                        $this->mail($last_backup);

                // Выкл таймер
                $this->timer();

                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $PHPShopOrm->update(array('infected_files_new' => count($this->infected), 'new_files_new' => count($this->new),
                    'change_files_new' => count($this->changes), 'time_new' => $this->timer, 'backup_new' => $backup), array('id' => '=' . $this->log_id));

                // Снимаем блокировку загрузки
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new' => '0', 'last_chek_new' => $this->date), array('id' => '=1'));

                break;

            case "end_admin":
                $this->zip();
                $backup = date("d-m-Y-H-i", $this->date) . '-' . $this->sec;

                // Выкл таймер
                $this->timer();
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $PHPShopOrm->update(array('infected_files_new' => count($this->infected), 'new_files_new' => count($this->new),
                    'change_files_new' => count($this->changes), 'time_new' => $this->timer, 'backup_new' => $backup), array('id' => '=' . $this->log_id));

                // Снимаем блокировку загрузки
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new' => '0', 'last_crc_new' => $this->last_crc, 'last_chek_new' => $this->date), array('id' => '=1'));
                break;
        }
    }

    // Трассировка
    function trace($obj, $caption = false) {
        echo "<h4>$caption</h4>";
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }

    // Проверка измененных файлов
    function chek() {
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
        $data = $PHPShopOrm->select(array('crc_name', 'crc_file'), false, false, array('limit' => 1000));

        if (is_array($data))
            foreach ($data as $val)
                $this->log[$val['crc_file']] = $val['crc_name'];

        if (is_array($this->base))
            foreach ($this->base as $name => $file)
                if (@!array_key_exists($name, $this->log))
                    $this->update[$name] = $file;

        // Разделение на новые и измененные
        if (is_array($this->update))
            foreach ($this->update as $name => $file) {
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
                $data = $PHPShopOrm->select(array('id'), array('crc_name' => '="' . md5($file) . '"'), false, array('limit' => 1));
                if (is_array($data))
                    $this->changes[$name] = $file;
                else
                    $this->new[$name] = $file;
            }
    }

    // Создание списка файлов в базе
    function create() {

        // Очищаем
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->query('TRUNCATE TABLE ' . $this->SysValue['base']['guard']['guard_crc']);

        if (is_array($this->base))
            foreach ($this->base as $key => $val) {

                // Новая запись
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
                $this->crc_num = $PHPShopOrm->insert(array('log_id_new' => $this->log_id, 'date_new' => $this->date, 'crc_name_new' => md5($val),
                    'crc_file_new' => $key, 'path_file_new' => $val));
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
            }

        $this->last_crc = $this->date;
        //$this->crc_num = mysql_insert_id();
        // Заносим в БД дату последней проверки
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
        $PHPShopOrm->update(array('last_crc_new' => $this->date));
    }

    // ZIP
    function zip($dir = false, $fname = false) {

        // Библиотека ZIP
        include_once($_SERVER['DOCUMENT_ROOT'] . $this->SysValue['dir']['dir'] . "/phpshop/lib/zip/pclzip.lib.php");

        if (empty($dir))
            $dir = $this->base;

        $zip_files = '';
        $name = $fname . date("d-m-Y-H-i", $this->date) . '-' . $this->sec;
        if (is_array($dir))
            foreach ($dir as $file)
                $zip_files.=$file . ',';

        // Имя архива
        $archive_name = $this->backup_path . $name . '.zip';

        $archive = new PclZip($archive_name);
        $v_list = $archive->create($zip_files, PCLZIP_OPT_REMOVE_PATH, "../../../");
        if ($v_list == 0) {
            die("Error : " . $archive->errorInfo(true));
        }
        return $archive_name;
    }

    // Скорость
    function timer() {

        // Выключаем таймер
        $time = explode(' ', microtime());
        $seconds = ($time[1] + $time[0] - $this->start_time);
        $seconds = substr($seconds, 0, 6);
        $this->timer = $seconds;
    }

    // Проверка прав
    function admin($password) {
        if (!preg_match("/^([\d]{2}-[\d]{2}-[\d]{4}-[\d]{2}-[\d]{2}-[a-z0-9]{32})$/i", $password))
            return false;
        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data = $PHPShopOrm->select(array('date'), array('backup' => '="' . $password . '"'));
        if (is_array($data)) {
            $today = date("U");
            if (($today - $data['date']) < 86400)
                return true;
        }
    }

    function mail($last_backup) {
        PHPShopObj::loadClass("mail");
        $zag = 'Возможное заражение вирусом сайта ' . $this->PHPShopSystem->getParam('name');
        $content = 'Внимание!
---------------

Мониторинг сайта ' . $_SERVER['SERVER_NAME'] . ' сообщает об изменении в структуры файлов:

* Измененных файлов - ' . count($this->changes) . '
* Новых файлов - ' . count($this->new) . '
* Предположение на зараженние файлов - ' . count($this->infected) . '
    ';

        // Предупреждение о вирусной базе через 30 дней
        if (($this->date - $this->system['last_update']) > (86400 * 30))
            $content.='

!!!!!!!!!
---
ВНИМАНИЕ:

Антивирусная база PHPShop Guard устарела, с даты последнего обновления сигнатур прошло более 30 дней. Для защиты вашего сайта необходимо обновить сигнатуры web-вирусов в настройках модуля Gurad!
---
!!!!!!!!!

';


        if (is_array($this->new)) {
            $content.='
Анализ показал наличие новых файлов:

';
            foreach ($this->new as $key => $infected)
                $content.='* ' . str_replace('../', '', $infected) . '
';
        }

        if (is_array($this->changes)) {
            $content.='
Анализ показал изменение содержимого в файлах:

';
            foreach ($this->changes as $key => $infected)
                $content.='* ' . str_replace('../', '', $infected) . '
';
        }

        if (is_array($this->changes))
            $content
                    .='
Измененные файлы могут быть причиной заражения их вирусами или внесения в файлы изменений службой поддержки или техническим специалистом для доработки сайта.
Если Вы уверены, что изменение файлов было спонтанным, то переходите к инструкции по борьбе с вирусами, представленной чуть ниже.

';
        if (is_array($this->infected)) {
            $content.='Предварительный анализ сигнатур показал наличие предположительных вирусов в файлах:

';

            foreach ($this->infected as $key => $infected)
                $content.='* ' . str_replace('../', '', $infected) . '
';
        }
        if (is_array($this->changes))
            $content.='

Для борьбы с вирусом следуйте инструкции:

* Проверить свой компьютер антивирусом
* Поменять пароль FTP сайта
* Воспользоваться бэкапом и заменить файлы на FTP.

Рекомендуются к использованию
бэкап: http://' . $_SERVER['SERVER_NAME'] . '/UserFiles/File/' . $last_backup . '.zip

Если изменения файлов было санкционированным, то перейдите по ссылке для создания нового образа файлов:
http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/guard/admin.php?do=create&backup=' . $last_backup . '
';
        // Отправить в карантин
        /*
          $this->license();
          if (($this->date) < ($this->support) and is_array($this->changes))
          $content.='
          Если изменения файлов было несанкционированным и есть подозрение, что произошло заражение вирусом, то перейдите по ссылке
          для анализа измененных файлов из карантина службой поддержки PHPShop Guard:
          http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/guard/admin.php?do=quarantine&backup=' . $last_backup . '
          '; */

        $content.='
---
' . date('r') . ' / -- guardian system v. ' . $this->version;

        // E-mail для отправки
        if (empty($this->system['mail']))
            $this->system['mail'] = $this->PHPShopSystem->getEmail();

        new PHPShopMail($this->system['mail'], $this->system['mail'], $zag, $content);
    }

}

?>