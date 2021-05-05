<?php

$TitlePage = __('Создание баннера');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['banner']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data['flag'] = $data['enabled']= 1;
    $PHPShopGUI->setActionPanel(__("Создание Баннера"), false, array('Сохранить и закрыть'));

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Имя:", $PHPShopGUI->setInput("text", "name_new", $data['name'], false, 500)) .
            $PHPShopGUI->setField("Статус:", $PHPShopGUI->setRadio("enabled_new", 1, "Включить", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выключить", $data['enabled']));


    // Редактор 
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 2
    $Tab1.= $PHPShopGUI->setField("Содержание", $oFCKeditor->AddGUI());


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
   $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.banner.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>



