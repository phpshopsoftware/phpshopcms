<?php

$TitlePage = __("О программе");

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $version,$PHPShopBase;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel(__("О программе PHPShop"), false, false);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField("Название программы", '<a class="btn btn-sm btn-default" href="http://www.phpshopcms.ru" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> PHPShop CMS Free</a>') .
            $PHPShopGUI->setField("Версия программы", '<span class="btn btn-sm btn-default"><span class="glyphicon glyphicon-info-sign"></span> ' . substr($version, 0, strlen($version) - 1) . '</span>'), 'in', false
    );
    $Tab1 .=$PHPShopGUI->setField("Версия PHP", phpversion(), false, false, false, 'text-right') .
            $PHPShopGUI->setField("Версия MySQL", @mysqli_get_server_info($PHPShopBase->link_db), false, false, false, 'text-right') .
            $PHPShopGUI->setField("Max execution time", @ini_get('max_execution_time') . ' сек.', false, __('Максимальное время работы'), false, 'text-right') .
            $PHPShopGUI->setField("Memory limit", @ini_get('memory_limit'), false, __('Выделяемая память'), false, 'text-right');

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $License);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("Лицензионное соглашение", $PHPShopGUI->loadLib('tab_license', false, './system/'), true));


    // Футер
    $PHPShopGUI->Compile();
    return true;
}

?>