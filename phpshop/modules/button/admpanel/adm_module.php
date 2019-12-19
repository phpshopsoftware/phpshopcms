<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.button.button_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    switch ($data['enabled']) {
        case 0: $s0 = 'selected';
            break;
        case 1: $s1 = 'selected';
            break;
        case 2: $s2 = 'selected';
            break;
        case 3: $s3 = 'selected';
            break;
    }

    $value[] = array('счетчики', 0, $s0);
    $value[] = array('подвал', 1, $s1);
    $value[] = array('слева', 2, $s2);
    $value[] = array('справа', 3, $s3);


    $info = 'Для произвольной вставки элемента, следует выбрать параметр вывода "Счетчики" и вставить переменную
        <kbd>@button@</kbd> в свой шаблон в нужное вам место.';
    $Tab1 = $PHPShopGUI->setInfo($info, 200, '96%');


    $Tab1.=$PHPShopGUI->setField('Расположение блока', $PHPShopGUI->setSelect('enabled_new', $value));

    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("О Модуле", $Tab3),array("Обзор Кнопок", null,'?path=modules.dir.button'));

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