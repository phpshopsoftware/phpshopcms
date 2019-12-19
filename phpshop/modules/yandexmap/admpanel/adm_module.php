<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexmap.yandexmap_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;
    
    // Выборка
    $data = $PHPShopOrm->select();


    $Tab1=$PHPShopGUI->setTextarea('code_new', $data['code'], null, '98%', 300);
    $Tab3=$PHPShopGUI->setPay();
    $Info='<h4>Для вставки Яндекс.Карты следуйте инструкции</h4>
        <ol>
        <li> <a href="http://api.yandex.ru/maps/form.xml" target="_blank">Получите API ключ для своего сайта</a>
        <li> <a href="http://api.yandex.ru/maps/tools/constructor/" target="_blank">Создайте точки на карте</a>.Введите адрес улицы.
        Расставьте на карте точки и линии и подпишите их.
        <li> Получите код для вставки.
        <li> Скопируйте код и вставьте в закладку "Код кнопки" текущего окна настройки модуля.
        <li> Карта будет доступна по переменной <kbd>@yandexmap@</kbd>. Для вставки переменной <kbd>@yandexmap@</kbd> перейдите в редактор страницы, включите режим редактирования
        HTML кода страницы и вставьте в нужное место <kbd>@yandexmap@</kbd>.

</ol>';
    $Tab2=$PHPShopGUI->setInfo($Info);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Код кнопки",$Tab1),array("Описание",$Tab2),array("О Модуле",$Tab3));
    
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