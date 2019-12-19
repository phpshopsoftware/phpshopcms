<?php

$TitlePage = __('Создание cсылки');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);

// Заполняем выбор
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected";
        else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->setActionPanel(__("Создание Ссылки"), false, array('Сохранить и закрыть'));

    $Select1 = setSelectChek(1);

    // Содержание закладки 1
    $Tab1 =
            $PHPShopGUI->setField("Ресурс:", $PHPShopGUI->setTextarea("name_new", 'Новая ссылка')) .
            $PHPShopGUI->setField("Приоритет:", $PHPShopGUI->setSelect("num_new", $Select1, 70, 1)) .
            $PHPShopGUI->setField("Статус:", $PHPShopGUI->setRadio("enabled_new", 1, "Включить", 1) . $PHPShopGUI->setRadio("enabled_new", 0, "Выключить", 1)) .
            $PHPShopGUI->setField("Ссылка:", $PHPShopGUI->setInput("text", "link_new", '')) .
            $PHPShopGUI->setField("Описание:", $PHPShopGUI->setTextarea("content_new", ''));


    $Tab1.=$PHPShopGUI->setField("Код кнопки:", $PHPShopGUI->setTextarea("image_new", ''));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.links.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (!empty($_POST['otsiv_new']))
        $_POST['flag_new'] = 1;
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
}

// Обработка событий 
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>