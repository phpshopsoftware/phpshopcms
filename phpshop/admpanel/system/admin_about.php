<?php

$TitlePage = __("� ���������");

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $version,$PHPShopBase;

    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel(__("� ��������� PHPShop"), false, false);


    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), $PHPShopGUI->setField("�������� ���������", '<a class="btn btn-sm btn-default" href="http://www.phpshopcms.ru" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> PHPShop CMS Free</a>') .
            $PHPShopGUI->setField("������ ���������", '<span class="btn btn-sm btn-default"><span class="glyphicon glyphicon-info-sign"></span> ' . substr($version, 0, strlen($version) - 1) . '</span>'), 'in', false
    );
    $Tab1 .=$PHPShopGUI->setField("������ PHP", phpversion(), false, false, false, 'text-right') .
            $PHPShopGUI->setField("������ MySQL", @mysqli_get_server_info($PHPShopBase->link_db), false, false, false, 'text-right') .
            $PHPShopGUI->setField("Max execution time", @ini_get('max_execution_time') . ' ���.', false, __('������������ ����� ������'), false, 'text-right') .
            $PHPShopGUI->setField("Memory limit", @ini_get('memory_limit'), false, __('���������� ������'), false, 'text-right');

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $License);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("������������ ����������", $PHPShopGUI->loadLib('tab_license', false, './system/'), true));


    // �����
    $PHPShopGUI->Compile();
    return true;
}

?>