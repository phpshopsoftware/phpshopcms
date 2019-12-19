<?php

$TitlePage = __("������ ��������������");
PHPShopObj::loadClass('update');

$PHPShopRestore = new PHPShopRestore();

// ������� ����������
function actionRestore() {
    global $PHPShopRestore, $TitlePage;

    $TitlePage.=' - ' . __('�������������� ������');

    // �������� ����������
    $PHPShopRestore->checkRestore($_REQUEST['version']);

    // �������� ��������/�������� �������
    if ($PHPShopRestore->isReady()) {

        // ����� ������ ��� ��������������
        $PHPShopRestore->restoreFiles();

        // ���������� config.ini
        $PHPShopRestore->restoreConfig();

        // ���������� ��
        $PHPShopRestore->restoreBD();
    }

    unset($_SESSION['update_check']);
    return true;
}

function getFileInfo($file) {
    global $PHPShopInterface;
    static $i;

    $i++;
    $stat = stat("../../backup/backups/" . $file . '/files.zip');
    $stat_bd = stat("../../backup/backups/" . $file . '/base.sql.gz');

    foreach (str_split($file) as $w)
        $version.=$w . '.';
    $version = __('������') . ' ' . substr($version, 0, strlen($version) - 1);


    if ($GLOBALS['SysValue']['upload']['version'] > $file)
        $menu = array('restore', 'log', 'id' => $file);
    else {
        $menu = array('log', 'id' => $file);
        $version = '<span class="text-danger">' . $version . '</span>';
    }


    $PHPShopInterface->setRow(array('name' => $version, 'align' => 'left'), PHPShopDate::get($stat['mtime'], true), array('name' => number_format($stat['size'], 0, ',', ' ') . ' ' . __('����')), array('action' => $menu, 'align' => 'right'), array('name' => number_format($stat_bd['size'], 0, ',', ' ') . ' ' . __('����'), 'align' => 'right'));
}

// ��������� ���
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopModules, $PHPShopGUI, $PHPShopRestore, $help;

    $PHPShopGUI->addJSFiles('./update/gui/update.gui.js');
   
    $PHPShopGUI->action_button['������'] = array(
        'name' => '������ ����������',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'action' => 'http://phpshop.ru/docs/update.html',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-gift'
    );
    
     $PHPShopGUI->setActionPanel($TitlePage, false, array('������'));

    if (!empty($_GET['version']))
        $restore_result = actionRestore();

    if (empty($restore_result)) {
        $PHPShopInterface->action_title['load'] = '�������';
        $PHPShopInterface->action_title['restore'] = '������������';
        $PHPShopInterface->action_title['restorebd'] = '������������ ���� ������';
        $PHPShopInterface->action_title['deletefile'] = '������� ����';
        $PHPShopInterface->action_title['log'] = '������';
        $PHPShopInterface->checkbox_action = false;


        $PHPShopInterface->setCaption(array("��� �����", "35%"), array("����", "15%"), array("������ ������", "15%"), array("", "7%", array('align' => 'right')), array("������ ��", "15%", array('align' => 'right')));
        PHPShopFile::searchFile("../../backup/backups/", 'getFileInfo');


        $PHPShopGUI->_CODE.='<table class="table table-hover" id="data">' . $PHPShopInterface->getContent() . '</table>';
    } else {
        $PHPShopGUI->_CODE.=$PHPShopRestore->getLog();
    }

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "version", "���������", "right", 80, "", "but", "actionRestore");
    $PHPShopGUI->setFooter($ContentFooter);

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    // �����
    $sidebarleft[] = array('title' => '������', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './update/'));
    $sidebarleft[] = array('title' => '���������', 'content' => $help);
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);
    $PHPShopGUI->Compile(2);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();
?>