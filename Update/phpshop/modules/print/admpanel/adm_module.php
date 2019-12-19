<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.print.print_system"));


// Функция обновления
function actionUpdate() {
     header('Location: ?path=modules&install=check');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    // Содержание закладки 1
    $Info='
<p>
Модуль создает печатную форму страницы по адресу /print/ссылка.html
В шаблон страницы нужно добавить ссылку на печатную форму <a href="/print/ссылка.html">Печатная форма</a>
</p>
<p>
Для автоматического прописывания ссылки добавьте php код в шаблон <mark>page/page_page_list.tpl</mark>
</p>
<pre>
@php
if(class_exists("PHPShopPrintForma")){
$PHPShopPrintForma=new PHPShopPrintForma();
$PHPShopPrintForma->forma();
}
php@
</pre>
';
    $Tab1=$PHPShopGUI->setInfo($Info);

    // Содержание закладки 2
    $Tab2=$PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Настройка",$Tab1),array("О Модуле",$Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

?>