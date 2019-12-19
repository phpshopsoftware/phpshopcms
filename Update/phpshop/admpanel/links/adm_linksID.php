<?php

$TitlePage=__('Редактирование Ссылки #'.$_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);

// Заполняем выбор
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected"; else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->setActionPanel(__("Редактирование Ссылки: ".$data['name']), array('Удалить'),array('Сохранить','Сохранить и закрыть'));
    
    $Select1 = setSelectChek(1);

    // Содержание закладки 1
    $Tab1 =
            $PHPShopGUI->setField("Ресурс:", $PHPShopGUI->setInputText(null,"name_new", $data['name'])) .
            $PHPShopGUI->setField("Приоритет:", $PHPShopGUI->setSelect("num_new", $Select1, 70, 1)) .
            $PHPShopGUI->setField("Статус:", $PHPShopGUI->setRadio("enabled_new", 1, "Включить", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выключить", $data['enabled'])) .
            $PHPShopGUI->setField("Ссылка:", $PHPShopGUI->setInput("text", "link_new", $data['link'])) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Описание:", $PHPShopGUI->setTextarea("content_new", $data['content']));


    $Tab1.=$PHPShopGUI->setField("Код кнопки:", $PHPShopGUI->setTextarea("image_new", $data['image']));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.links.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.links.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.links.edit");

// Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path='.$_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;
    
    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// Обработка событий 
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

?>