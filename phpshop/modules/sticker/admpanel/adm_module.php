<?php

// Функция обновления
function actionUpdate() {
   header('Location: ?path=modules&install=check');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;


    $Info = '<p>Для вывода стикера в шаблоне используйте переменную <kbd>@sticker_маркер@</kbd>. 
        Маркер указывается в одноименном поле карточки редактирования стикера. 
        Имя маркера обязательно должно быть на латинском языке.
        </p> 
         <p>
         Для интеграции стикера в ручном режиме включите следующий код в содержание страницы или текстового блока:
        <p>
        <pre>
@php
$PHPShopStickerElement = new PHPShopStickerElement();
echo $PHPShopStickerElement->forma("маркер стикера");
php@
        </pre>
         </p>';

    $Tab2 = $PHPShopGUI->setInfo($Info);


    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция", $Tab2), array("О Модуле", $Tab3),array("Обзор стикеров", null,'?path=modules.dir.sticker'));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>