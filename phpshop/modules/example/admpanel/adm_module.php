<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.example.example_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;

    // Выборка
    $data = $PHPShopOrm->select();

    // Содержание закладки 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $editor = new Editor('example_new');
    $editor->Height = 300;
    $editor->Value = $data['example'];
    $Tab1 = $editor->AddGUI();
    
    $Tab1.=$PHPShopGUI->setHelp('Результат выполнения страницы в <a href="/example/" target="_blank">/example/</a>');

    $Tab1.=$PHPShopGUI->setCollapse('Документация',$PHPShopGUI->setLink('http://doc.phpshop.ru', 'PhpDoc', _blank, false, false, 'btn btn-default btn-sm') . ' '.$PHPShopGUI->setLink('http://wiki.phpshop.ru/index.php/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F:%D0%9E%D1%81%D0%BD%D0%BE%D0%B2%D0%BD%D0%BE%D0%B5_API', 'Wiki', _blank, false, false, 'btn btn-default btn-sm'). ' '.$PHPShopGUI->setLink('http://faq.phpshop.ru', 'FAQ', '_blank', false, false, 'btn btn-default btn-sm'). ' '.$PHPShopGUI->setLink('http://getbootstrap.com', 'Bootstrap', '_blank', false, false, 'btn btn-default btn-sm'). ' '.$PHPShopGUI->setLink('http://jquery.com', 'jQuery', '_blank', false, false, 'btn btn-default btn-sm'));

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
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>


