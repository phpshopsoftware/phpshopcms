<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
include("class/guard.class.php");
include($_classPath . "lib/zip/pclzip.lib.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("modules");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopSystem = new PHPShopSystem();

$PHPShopModules = new PHPShopModules('../');
$PHPShopModules->checkInstall('guard');

$Guard = new Guard("../../../");
$Guard->backup_path = '../../../UserFiles/Files/';
$Guard->license_path = '../../../license/';

switch ($_GET['do']) {

    case "quarantine":
        if ($Guard->admin($_GET['backup'])) {
            $Guard->backup_path = '../../../UserFiles/Files/';
            $Guard->file($Guard->dir_global);
            $Guard->chek();

            if (count($Guard->changes) > 0) {

                $quarantine_name = str_replace('../../../', '/', $Guard->zip($Guard->changes, $fname = '!!!quarantine!!!'));
                $zag = 'Guard [' . $_SERVER['SERVER_NAME'] . '] - файлы для анализа';
                $content = 'Доброго времени!
---------------

Интернет-ресурс "' . $PHPShopSystem->getName() . '" передает файлы для анализа:

* Источник - ' . $_SERVER['SERVER_NAME'] . '
* Измененных файлов - ' . count($Guard->changes) . '
* Ссылка для загрузки файлов из карантина: http://' . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . $quarantine_name;

                PHPShopObj::loadClass("mail");
                $PHPShopMail = new PHPShopMail('guard@phpshop.ru', $PHPShopSystem->getEmail(), $zag, $content);
                $Guard->message('Файлы переданы службе поддержки PHPShop Guard.');
            }
        }
        else
            exit('Ссылка просрочена!');

        break;

    case "create":

        if ($Guard->admin($_GET['backup']))
            $create_enabled = true;
        else{
            $PHPShopBase->chekAdmin();
            $create_enabled = true;
        }

        if ($create_enabled) {
            $Guard->log('start');
            $Guard->file($Guard->dir_global);
            $Guard->create();
            $Guard->changes = $Guard->base;
            $Guard->log('end_admin');
            $Guard->message('Файловая база обновлена. В базе ' . $Guard->crc_num . ' файлов.');
        }
        else
            exit('Ссылка просрочена!');
        break;

    case "update":
               
        $PHPShopBase->chekAdmin();

        $Guard->update();
        
        switch ($Guard->update_result) {

            case 0:
                $message = 'Период технической поддержки закочнился,
обновление баз сигнатур вирусов невозможно.
Требуется оплата технической поддержки.';
                $message = 'Обновлений не обнаружено.';
                
                break;

            case 1:
                $message = 'Обновление сигнатур успешно выполнено. 
База содержит следующие сигнатуры вирусов: 
'.$Guard->update_result_virus;
                break;

            case 2:
                $message = 'Ошибка подключения к серверу PHPShop.ru';
                break;
        }

        // Обновляем дату обновления сигнатур
        $PHPShopOrm = new PHPShopOrm($SysValue['base']['guard']['guard_system']);
        $PHPShopOrm->update(array('last_update_new' => time()), array('id' => '=1'));
        
        $Guard->message('<pre>' . $message . '</pre>');
        break;

    case "chek":

        $PHPShopBase->chekAdmin();

        // Пишем лог
        $Guard->log('start');

        // Проверяем файлы
        $Guard->file($Guard->dir_global);

        // Сравниваем
        $Guard->chek();

        // Сигнатуры
        $Guard->signature();


        // Пишем лог
        $Guard->log('end_admin');


        // Сообщение администратору

        $Guard->mail($Guard->backup());

        $message = '<pre>
* Измененных файлов - ' . count($Guard->changes) . '
* Новых файлов - ' . count($Guard->new) . '
* Предположение на зараженние файлов - ' . count($Guard->infected) . '

Полный отчет и инструкции по дальнейшим действиям
направлены по адресу ' . $PHPShopSystem->getEmail() . '
    </pre>';
        $Guard->message($message);

        break;
}

header('Location: /error/');
exit();
?>
