<?php

$TitlePage = __('Редактирование записи #' . intval($_GET['id']));

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm;

    // Выбор даты
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('description_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['description'];

    // Содержание закладки 1
    $Tab1 = '<hr>' . $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInputDate("date_new", $data['date'])) .
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("Анонс:", $oFCKeditor->AddGUI());

    // Редактор 2
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Подробно", $Tab2));


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// Обработка событий 
$PHPShopGUI->getAction();


// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>