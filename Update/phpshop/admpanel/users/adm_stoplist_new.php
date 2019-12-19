<?php

$TitlePage = __('Добавление в черный список');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['black_list']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;


    // Передача данных
    $data = $_GET['data'];

    $PHPShopGUI->setActionPanel(__("Добавление в черный список"), false, array('Сохранить и закрыть'));

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("IP", $PHPShopGUI->setInput('text.required', "ip_new", $_GET['ip'], false, '200'));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.users.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if ($_POST['ip_new'] != $_SERVER['REMOTE_ADDR']) {
        $action = $PHPShopOrm->insert($_POST);
    }

    header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>