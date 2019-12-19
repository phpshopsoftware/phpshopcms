<?php

$TitlePage = __('Редактирование RSS #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['rssgraber']);

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->field_col = 2;
    
        // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->setActionPanel(__("Редактирование RSS"), array('Удалить'), array('Сохранить', 'Сохранить и закрыть'));

    $Tab1 = $PHPShopGUI->setField("URL:", $PHPShopGUI->setInputText(null, "link_new", $data['link'])) .
            $PHPShopGUI->setField("Дата начала:", $PHPShopGUI->setInputDate("start_date_new", PHPShopDate::get($data['start_date']))) .
            $PHPShopGUI->setField("Дата завершения:", $PHPShopGUI->setInputDate("end_date_new", PHPShopDate::get($data['end_date']))) .
            $PHPShopGUI->setField("Забирать новости:", $PHPShopGUI->setInputText(null, "day_num_new", $data['day_num'], 100, 'в день')) .
            $PHPShopGUI->setField("Новостей в заборе:", $PHPShopGUI->setInputText(null, "news_num_new", $data['news_num'], 100, 'за раз')) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']) . '&nbsp;&nbsp;');


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.news.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.news.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.news.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    if (!empty($_POST['start_date_new']))
        $_POST['start_date_new'] = PHPShopDate::GetUnixTime($_POST['start_date_new']);
    else
        $_POST['start_date_new'] = time();

    if (!empty($_POST['end_date_new']))
        $_POST['end_date_new'] = PHPShopDate::GetUnixTime($_POST['end_date_new']);
    else
        $_POST['end_date_new'] = time();
    
        // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('enabled_new');

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    
    return array("success" =>  $action);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>