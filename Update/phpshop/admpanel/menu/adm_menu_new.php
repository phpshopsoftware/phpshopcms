<?php
$TitlePage = __('Создание Текстового Блока');
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
    $data['flag'] = 1;
    $data['name'] = __('Новый блок');

    $PHPShopGUI->setActionPanel(__("Создание нового текстового блока"), false ,array('Сохранить и закрыть'));

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
 $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.menu.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
}

// Обработка событий 
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

?>