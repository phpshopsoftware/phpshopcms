<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.envybox.envybox_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "Настройка модуля Envybox";

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Код сайта для интеграции', $PHPShopGUI->setInputText(false, 'widget_id_new', $data['widget_id'], 300,false, false, false,'679f0a2e11f4ab299aa741fb8d211539'));

    $Info = '<h4>Для вставки данного модуля следуйте инструкции:</h4>
        <ol>
        <li>Зарегистрируйтесь на сайте <a href="http://envbx.ru/url/ab802d/" target="_blank">Envbx.ru</a></li>
	<li>Создать новый чат в меню <kbd>Сайты</kbd> &rarr; <kbd>Добавить сайт</kbd>.</li>
	<li>Выбрать опцию <kbd>Получить код</kbd> и скопировать поле <kbd>Код сайта для интеграции</kbd> в одноменное поле настроек модуля.</li>
		</ol>';
    $Tab2 = $PHPShopGUI->setInfo($Info, '200px', '100%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

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