<?php

$TitlePage = __('Создание слайдера');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['slider']);


// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->setActionPanel(__("Создание Слайдера"), false, array('Сохранить и закрыть'));
    
    $data = array();

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField(__("Изображение"), $PHPShopGUI->setIcon($data['image'], "image_new", false)) .
            $PHPShopGUI->setField(__("Цель"), $PHPShopGUI->setInput("text", "link_new", $data['link'], "none", 300) . $PHPShopGUI->setHelp("Пример: /pages/info.html или http://google.com")).
            $PHPShopGUI->setField(__("Статус"),$PHPShopGUI->setRadio("enabled_new", 1, "Включить", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выключить", $data['enabled'])).
            $PHPShopGUI->setField(__("Приоритет"), $PHPShopGUI->setInputText(false, 'num_new', $data['num'], 100)) .
            $PHPShopGUI->setField(__("Описание"), $PHPShopGUI->setTextarea("alt_new", $data['alt']));


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.slider.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;
    
    $_POST['image_new'] = iconAdd();

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Добавление изображения 
function iconAdd() {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['image_new'];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST['image_new'])) {
        $file = $_POST['image_new'];
    }


    if (!empty($file)) {
        return $file;
    }
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');

?>