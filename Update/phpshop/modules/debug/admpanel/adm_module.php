<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.debug.debug_system"));

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
    global $PHPShopGUI, $PHPShopOrm,$select_name;

    $PHPShopGUI->action_button['Панель'] = array(
        'name' => 'Панель отладки',
        'action' => '../../dev/',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-blackboard'
    );

    $PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id'] . '</span>'), $select_name, array('Сохранить и закрыть', 'Панель'));

    // Выборка
    $data = $PHPShopOrm->select();

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField('Авторизация', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Права администратора для доступа в раздел /dev/', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('Гостевой ключ', $PHPShopGUI->setInputText(null, 'text_new', $data['text'], 200));

    // Содержание закладки 2
    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("О Модуле", $Tab2));

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