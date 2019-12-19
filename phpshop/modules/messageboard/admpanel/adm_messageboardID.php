<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.messageboard.messageboard_log"));

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));


    $Tab1 = $PHPShopGUI->setField("Дата", $PHPShopGUI->setInputDate("date_new", PHPShopDate::dataV($data['date'], false)));
    $Tab1.=$PHPShopGUI->setField("Статус", $PHPShopGUI->setCheckbox('enabled_new', '1', 'Вывод на сайте', $data['enabled']));

    $Tab1.=$PHPShopGUI->setField("Пользователь", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));
    $Tab1.=$PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput("text", "mail_new", $data['mail']));
    $Tab1.=$PHPShopGUI->setField("Телефон", $PHPShopGUI->setInput("text", "tel_new", $data['tel']));

    $Tab1.=$PHPShopGUI->setField("Тема:", $PHPShopGUI->setTextarea("title_new", $data['title']));
    $Tab1.=$PHPShopGUI->setField("Содержание:", $PHPShopGUI->setTextarea("content_new", $data['content'], "", '100%', 200));

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

// Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $_POST['date_new'] = PHPShopDate::GetUnixTime($_POST['date_new']);
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
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
    return array("success" =>  $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>