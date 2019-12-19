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
                $zag = 'Guard [' . $_SERVER['SERVER_NAME'] . '] - ����� ��� �������';
                $content = '������� �������!
---------------

��������-������ "' . $PHPShopSystem->getName() . '" �������� ����� ��� �������:

* �������� - ' . $_SERVER['SERVER_NAME'] . '
* ���������� ������ - ' . count($Guard->changes) . '
* ������ ��� �������� ������ �� ���������: http://' . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . $quarantine_name;

                PHPShopObj::loadClass("mail");
                $PHPShopMail = new PHPShopMail('guard@phpshop.ru', $PHPShopSystem->getParam('adminmail2'), $zag, $content);
                $Guard->message('����� �������� ������ ��������� PHPShop Guard.');
            }
        }
        else
            exit('������ ����������!');

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
            $Guard->message('�������� ���� ���������. � ���� ' . $Guard->crc_num . ' ������.');
        }
        else
            exit('������ ����������!');
        break;

    case "update":
               
        $PHPShopBase->chekAdmin();

        $Guard->update();
        
        switch ($Guard->update_result) {

            case 0:
                $message = '������ ����������� ��������� ����������,
���������� ��� �������� ������� ����������.
��������� ������ ����������� ���������.';
                $message = '���������� �� ����������.';
                
                break;

            case 1:
                $message = '���������� �������� ������� ���������. 
���� �������� ��������� ��������� �������: 
'.$Guard->update_result_virus;
                break;

            case 2:
                $message = '������ ����������� � ������� PHPShop.ru';
                break;
        }

        // ��������� ���� ���������� ��������
        $PHPShopOrm = new PHPShopOrm($SysValue['base']['guard']['guard_system']);
        $PHPShopOrm->update(array('last_update_new' => time()), array('id' => '=1'));
        
        $Guard->message('<pre>' . $message . '</pre>');
        break;

    case "chek":

        $PHPShopBase->chekAdmin();

        // ����� ���
        $Guard->log('start');

        // ��������� �����
        $Guard->file($Guard->dir_global);

        // ����������
        $Guard->chek();

        // ���������
        $Guard->signature();


        // ����� ���
        $Guard->log('end_admin');


        // ��������� ��������������

        $Guard->mail($Guard->backup());

        $message = '<pre>
* ���������� ������ - ' . count($Guard->changes) . '
* ����� ������ - ' . count($Guard->new) . '
* ������������� �� ���������� ������ - ' . count($Guard->infected) . '

������ ����� � ���������� �� ���������� ���������
���������� �� ������ ' . $PHPShopSystem->getParam('adminmail2') . '
    </pre>';
        $Guard->message($message);

        break;
}

header('Location: /error/');
exit();
?>
