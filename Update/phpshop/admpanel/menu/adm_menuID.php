<?php

$TitlePage=__('Редактирование Текстового Блока #'.$_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['menu']);

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
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->setActionPanel(__("Редактирование Блока: ").$data['name'], array('Удалить') ,array('Сохранить','Сохранить и закрыть'));

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '350';
    $oFCKeditor->Value = $data['content'];

    $Select1 = setSelectChek($data['num']);

    $Select2[] = array("Слева", 0, $data['element']);
    $Select2[] = array("Справа", 1, $data['element']);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Название:", $PHPShopGUI->setInput("text", "name_new", $data['name'], "none", 500)) .
             $PHPShopGUI->setField("Статус:",$PHPShopGUI->setRadio("flag_new", 1, "Включить", $data['flag']) . $PHPShopGUI->setRadio("flag_new", 0, "Выключить", $data['flag'])) .
            $PHPShopGUI->setField("Позиция:", $PHPShopGUI->setSelect("num_new", $Select1,150)) .
            $PHPShopGUI->setField("Место:", $PHPShopGUI->setSelect("element_new", $Select2,150)) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Таргетинг:", $PHPShopGUI->setInput("text", "dir_new", $data['dir']) .
                    $PHPShopGUI->setHelp(__('* Пример: /page/,/news/. Можно указать несколько адресов через запятую.')));

    $Tab1.= $PHPShopGUI->setField("Содержание",$oFCKeditor->AddGUI());

        // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));



    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.menu.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.menu.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.menu.edit");

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

    //header('Location: ?path='.$_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (empty($_POST['flag_new']))
        $_POST['flag_new'] = 0;
    
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

?>