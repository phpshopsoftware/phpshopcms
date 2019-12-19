<?php
$TitlePage = __('Создание Новости');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);

function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$PHPShopSystem;

    // Выборка
    $data['date'] = PHPShopDate::get();
    $data['title'] = __('Новость за ') . $data['date'];

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js','./js/jquery.waypoints.min.js','./news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
    $PHPShopGUI->setActionPanel(__("Создание Новости"), false, array('Сохранить и закрыть'));

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('description_new');
    $oFCKeditor->Height = '270';
    $oFCKeditor->Value = $data['description'];

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInputDate("date_new", $data['date'])) .
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("Анонс:", $oFCKeditor->AddGUI());


    // Редактор 2
    $oFCKeditor2 = new Editor('content_new');
    $oFCKeditor2->Height = '550';
    $oFCKeditor2->Value = $data['content'];

    $Tab1.=$PHPShopGUI->setField("Подробно:", $oFCKeditor2->AddGUI());

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);
    
        // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.news.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datau_new'] = time();

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');


?>
