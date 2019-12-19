<?php

$TitlePage = __('Создание RSS канала');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['rssgraber']);

function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    // Выборка
    $data['start_date'] = time();
    $data['end_date'] = time() + 10000000;
    $data['enabled'] = 1;
    $data['day_num'] = 1;
    $data['news_num'] = 3;

    $PHPShopGUI->field_col = 2;

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->setActionPanel(__("Создание RSS"), false, array('Сохранить и закрыть'));

    $Tab1 = $PHPShopGUI->setField("URL:", $PHPShopGUI->setInputArg(array('type' => 'text.required', 'name' => "link_new", 'value' => $data['link'], 'placeholder' => 'http://www.phpshop.ru/rss/'))) .
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
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.news.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    if (!empty($_POST['start_date_new']))
        $_POST['start_date_new'] = PHPShopDate::GetUnixTime($_POST['start_date_new']);
    else
        $_POST['start_date_new'] = time();

    if (!empty($_POST['end_date_new']))
        $_POST['end_date_new'] = PHPShopDate::GetUnixTime($_POST['end_date_new']);
    else
        $_POST['end_date_new'] = time();

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
