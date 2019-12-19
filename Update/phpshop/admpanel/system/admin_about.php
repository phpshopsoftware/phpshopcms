<?php

$TitlePage = __("О программе");


// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$version;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js'); 
    $PHPShopGUI->setActionPanel(__("О программе PHPShop"), false, false);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), 
            $PHPShopGUI->setField("Название программы", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/page/cmsfree.html" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> PHPShop CMS Free</a>').
            $PHPShopGUI->setField("Версия программы", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/docs/update.html" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> ' .  substr($version, 0, strlen($version)-1) .'</a>')  ,'in', false
    );


    $Tab1.=$PHPShopGUI->setCollapse(__('Лицензионное соглашение'), $PHPShopGUI->loadLib('tab_license', false, './system/'));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__,$License);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,false));


    // Футер
    $PHPShopGUI->Compile();
    return true;
}

?>