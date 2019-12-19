<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.lock.lock_system"));

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
    
    // Выборка
    $data = $PHPShopOrm->select();


    $e_value[] = array('Выкл', 1, $data['flag']);
    $e_value[] = array('Вкл', 2, $data['flag']);

    $Tab1=$PHPShopGUI->setField('Авторизация на сайте', $PHPShopGUI->setSelect('flag_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('Пользоваль',$PHPShopGUI->setInput('text.required', "login_new", $data['login'],false,200));
    $Tab1.=$PHPShopGUI->setField('Пароль',$PHPShopGUI->setInput("password.required", "password_new", $data['password'],false,200));

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("О Модуле", $Tab3,));

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