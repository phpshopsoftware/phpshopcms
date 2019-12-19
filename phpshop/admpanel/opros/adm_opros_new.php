<?php

$TitlePage = __('Создание опроса');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros_categories']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage;

    // Выборка
    $data['id'] = getLastID();
    $data['flag']=1;

    $PHPShopGUI->addJSFiles('./opros/gui/opros.gui.js');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Создать и редактировать', 'Сохранить и закрыть'));

    // Содержание закладки 
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField(__("Заголовок"), $PHPShopGUI->setInput("text.requared", "name_new", $data['name'])) .
            $PHPShopGUI->setField(__("Таргетинг"), $PHPShopGUI->setTextarea("dir_new", $data['dir']) . $PHPShopGUI->setHelp("Пример: page/,news/. Можно указать несколько адресов через запятую.")) .
            $PHPShopGUI->setField(__("Статус"), $PHPShopGUI->setRadio("flag_new", 1, "Включить", $data['flag']) . $PHPShopGUI->setRadio("flag_new", 0, "Выключить", $data['flag'])));

    // Варианты
    $Tab1.=$PHPShopGUI->setCollapse(__('Значения'), $PHPShopGUI->setField(null, $PHPShopGUI->loadLib('tab_value', $data)));


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.opros.create") . $PHPShopGUI->setInput("hidden", "rowID", $data['id']);

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ID новой записи в таблице
 * @return integer 
 */
function getLastID() {
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SHOW TABLE STATUS LIKE "' . $GLOBALS['SysValue']['base']['opros_categories'] . '"';
    $data = $PHPShopOrm->select();
    if (is_array($data)) {
        return $data[0]['Auto_increment'];
    }
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;


    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->insert($_POST, '_new');

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $_POST['rowID']);
    else
        header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>