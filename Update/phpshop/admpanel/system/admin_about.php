<?php

$TitlePage = __("� ���������");


// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$version;

    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js'); 
    $PHPShopGUI->setActionPanel(__("� ��������� PHPShop"), false, false);


    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), 
            $PHPShopGUI->setField("�������� ���������", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/page/cmsfree.html" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> PHPShop CMS Free</a>').
            $PHPShopGUI->setField("������ ���������", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/docs/update.html" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> ' .  substr($version, 0, strlen($version)-1) .'</a>')  ,'in', false
    );


    $Tab1.=$PHPShopGUI->setCollapse(__('������������ ����������'), $PHPShopGUI->loadLib('tab_license', false, './system/'));


    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__,$License);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,false));


    // �����
    $PHPShopGUI->Compile();
    return true;
}

?>