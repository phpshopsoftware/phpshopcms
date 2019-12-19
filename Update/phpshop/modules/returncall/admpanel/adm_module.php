<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.returncall.returncall_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

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

    // Вывод
    $e_value[] = array('не выводить', 0, $data['enabled']);
    $e_value[] = array('слева', 1, $data['enabled']);
    $e_value[] = array('справа', 2, $data['enabled']);

    // Тип вывода
    $w_value[] = array('форма', 0, $data['windows']);
    $w_value[] = array('всплывающее окно', 1, $data['windows']);

    // Captcha
    $c_value[] = array('да', 1, $data['captcha_enabled']);
    $c_value[] = array('нет', 2, $data['captcha_enabled']);


    $Tab1 = $PHPShopGUI->setField('Заголовок', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField('Сообщение', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));
    $Tab1.=$PHPShopGUI->setField('Место вывода', $PHPShopGUI->setSelect('enabled_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('Тип вывода', $PHPShopGUI->setSelect('windows_new', $w_value, 200));
    $Tab1.=$PHPShopGUI->setField('Captcha', $PHPShopGUI->setSelect('captcha_enabled_new', $c_value, 200));

    $info = 'Для произвольной вставки элемента следует выбрать парамет вывода "Не выводить" и в ручном режиме вставить переменную
        <kbd>@returncall@</kbd> в свой шаблон.
        <p>Для персонализации формы вывода отредактируйте шаблоны <code>phpshop/modules/returncall/templates/</code></p>
        <p>Для включения защитной каптчи используйте <kbd>@returncall_captcha@</kbd> в форме обратного звонка <code>
        phpshop/modules/returncall/templates/returncall_forma.tpl</code></p>';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($data['serial'], false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Инструкция", $Tab2), array("О Модуле", $Tab3),array("Обзор заявок", null,'?path=modules.dir.returncall'));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>