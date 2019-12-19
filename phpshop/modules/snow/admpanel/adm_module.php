<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.snow.snow_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;
    
       // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles('./css/bootstrap-colorpicker.min.css');
    $PHPShopGUI->addJSFiles('./js/bootstrap-colorpicker.min.js');

    // Выборка
    $data = $PHPShopOrm->select();


    $e_value[] = array('JQuery Snow 2.0', 1, $data['flag']);
    $e_value[] = array('Snow 1.0', 2, $data['flag']);

    $Tab1=$PHPShopGUI->setField('Тип подключения', $PHPShopGUI->setSelect('flag_new', $e_value, 200) . $PHPShopGUI->setHelp('JQuery Snow требует подключения отдельно библиотеки <a href="http://jquery.com/" target="_blank">JQuery</a>. Подходит для новых шаблонов Bootstrap, White_brick и подобных.'));
    $Tab1.=$PHPShopGUI->setField('Цвет снег',$PHPShopGUI->setInputColor('color_new',$data['color']));

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("О Модуле", $Tab3,));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>