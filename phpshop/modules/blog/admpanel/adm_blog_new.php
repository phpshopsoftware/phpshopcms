<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;
    
     // Выбор даты
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js','./news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
    $data['date']=time();
    $data['title']='Новая запись в блог';

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('description_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->ToolbarSet = 'Normal';

    // Содержание закладки 1
    $Tab1 = '<hr>'.$PHPShopGUI->setField("Дата:", $PHPShopGUI->setInputDate("date_new", PHPShopDate::get($data['date']))).
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text.required", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("Анонс:", $oFCKeditor->AddGUI());

    // Редактор 2
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->ToolbarSet = 'Normal';

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Подробно", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","Сохранить","right",false,false,false,"actionInsert.modules.create");

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

// Обработка событий 
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>