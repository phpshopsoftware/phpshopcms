<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sticker.sticker_forms"));

// Функция записи
function actionInsert() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->insert($_POST);
    
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;

    // Выборка
    $data['name'] = 'Новый стикер';
    $data['enabled']=1;



    $Tab1 = $PHPShopGUI->setField('Название:', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));
    $Tab1.=$PHPShopGUI->setField('Маркер:', $PHPShopGUI->setInputText('@sticker_', 'path_new', $data['path'], 200, '@'));
    $Tab1.=$PHPShopGUI->setField('Опции:', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вывод на сайте', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('Таргетинг:', $PHPShopGUI->setInputText(false, 'dir_new', $data['dir']) . $PHPShopGUI->setHelp('Пример: /page/about.html,/page/company.html'));


    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('content_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];

    $Tab2 = $oFCKeditor->AddGUI();


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Содержание", $Tab2, 350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","Сохранить","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>