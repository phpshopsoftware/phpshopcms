<?php

$TitlePage = __("������ ����������");
PHPShopObj::loadClass('update');

$PHPShopUpdate = new PHPShopUpdate();


$License = @parse_ini_file_true(PHPShopFile::searchFile("../../license/", 'getLicense'), 1);

define("UPDATE_PATH", "http://www.phpshop.ru/update/updatecms5.php?from=" . $_SERVER['SERVER_NAME'] . "&version=" . $GLOBALS['SysValue']['upload']['version']);

// ������� ����������
function actionUpdate() {
    global $PHPShopUpdate, $update_result, $TitlePage;

    $TitlePage.=' - ' . __('��������� ����������');

    // �������� ����������
    $PHPShopUpdate->checkUpdate();


    // �������� ��������/�������� �������
    if ($PHPShopUpdate->isReady()) {

        // ����� ��
        //$PHPShopUpdate->checkBD();

        // ���������� � FTP
        $PHPShopUpdate->ftpConnect();

        // ������ ����� ����������
        $PHPShopUpdate->map();

        // ����� ������ ��� ��������������
        $PHPShopUpdate->backupFiles();

        // ���������� ������
        if ($PHPShopUpdate->installFiles() and !$PHPShopUpdate->base_update_enabled) {
            $TitlePage.=' - ' . __('���������');
        }

        // ���������� config.ini
        $PHPShopUpdate->installConfig();

        // ������� ��������� ������ /temp/
        $PHPShopUpdate->cleanTemp();

        // ���������� ��
        $PHPShopUpdate->installBD();
    }


    $update_result = true;
    unset($_SESSION['update_check']);
}

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $result_message, $PHPShopUpdate, $update_result, $help;

    // �������� ����������
    $PHPShopUpdate->checkUpdate();

    foreach (str_split($PHPShopUpdate->version) as $w)
        $version.=$w . '.';
    $version = substr($version, 0, strlen($version) - 1);

    if ($PHPShopUpdate->update_status == 'active' and empty($update_result)) {

        if (is_array($PHPShopUpdate->content)) {
            $result_content = '<ul class="list-group">';

            foreach ($PHPShopUpdate->content as $text)
                $result_content.='<li class="list-group-item">' . $text . '</li>';

            $result_content.='</ul>';

            $PHPShopGUI->action_button['����������'] = array(
                'name' => '���������� ����������',
                'class' => $PHPShopUpdate->btn_class,
                'type' => 'button',
                'icon' => 'glyphicon glyphicon-cloud-download'
            );
        }

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('cloud-download') . __('�������� ����� ������') . ' PHPShop ' . $version, $result_content, 'panel-primary', false);
    } elseif ($PHPShopUpdate->update_status == 'no_update') {

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('ok-sign text-success') . __('�� ����������� ��������� ������') . ' PHPShop ' . $version, __('���������� �� ���������'), 'panel-default');
        unset($_SESSION['update_check']);
    } elseif ($PHPShopUpdate->update_status == 'passive') {

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('cloud-download') . __('�������� ����� ������') . ' PHPShop ' . $version, __('��� ��������� ���������� ���������� �������� ����������� ���������'), 'panel-danger');

        $PHPShopGUI->action_button['����������'] = array(
            'name' => '������ ����������',
            'class' => 'btn btn-primary btn-sm navbar-btn btn-action-panel-blank',
            'action' => 'http://phpshop.ru/order/',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-ruble'
        );
    }

    $PHPShopGUI->action_button['������'] = array(
        'name' => '������ ����������',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'action' => 'http://phpshop.ru/docs/update.html',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-gift'
    );

    // �������� ���
    $result_message.=$PHPShopGUI->setProgress(__('�������� ��������� ����� ������...'), 'hide');


    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./update/gui/update.gui.js');

    $PHPShopGUI->_CODE = $result_message;
    $PHPShopGUI->_CODE.=$PHPShopUpdate->getLog();

    $PHPShopGUI->setActionPanel($TitlePage, false, array('������', '����������'));


    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);


    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.update.view");
    $PHPShopGUI->setFooter($ContentFooter);

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