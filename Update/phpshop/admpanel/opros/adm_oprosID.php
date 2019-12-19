<?php

$TitlePage = __('Редактирование Опроса #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros_categories']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->addJSFiles('./opros/gui/opros.gui.js');

    $PHPShopGUI->setActionPanel($TitlePage, array('Удалить'), array('Сохранить', 'Сохранить и закрыть'));

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
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.opros.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.opros.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.opros.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros']);
    $action = $PHPShopOrm->delete(array('category' => '=' . $_POST['rowID']));
    return array('success' => $action);
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

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>