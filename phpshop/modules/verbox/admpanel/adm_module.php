<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.verbox.verbox_system"));

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

    $PHPShopGUI->title = "Настройка модуля Verbox";

    // Выборка
    $data = $PHPShopOrm->select();
    
    // Редактор 
    $PHPShopGUI->setEditor('ace', true);
    $oFCKeditor = new Editor('code_new');
    $oFCKeditor->Height = '250';
    $oFCKeditor->Value = $data['code'];

    $Tab1 = $PHPShopGUI->setField('Код для вставки',  $oFCKeditor->AddGUI());

    $Info = '<h4>Для вставки данного модуля следуйте инструкции:</h4>
        <ol>
        <li>Зарегистрируйтесь на сайте <a href="https://admin.verbox.ru/r/phpshop" target="_blank">Verbox.ru</a></li>
	<li>Создать новый чат в меню <kbd>Мои сайты</kbd> &rarr; <kbd>Подключить сайт</kbd>.</li>
	<li>В меню <kbd>Установка</kbd> скопировать поле <code>Код для вставки на сайт</code> в одноменное поле настроек модуля.</li>
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