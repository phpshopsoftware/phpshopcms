<?php

$TitlePage = __('Добавление адреса в рассылку');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;


    // Передача данных
    $data = $_GET['data'];

    $PHPShopGUI->setActionPanel(__("Добавление адреса в рассылку"), false, array('Сохранить и закрыть'));

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput('text.required', "mail_new", $_GET['mail'], false, '200'));

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

    $_POST['date_new']=date("d-m-y");
    $action = $PHPShopOrm->insert($_POST);
    

    header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>