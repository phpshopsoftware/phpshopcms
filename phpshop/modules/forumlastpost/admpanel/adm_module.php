<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.forumlastpost.ipboard_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;


    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['connect_new']))
        $_POST['connect_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    if ($data['flag'] == 1)
        $s2 = "selected";
    else
        $s1 = "selected";


    $Select[] = array("Слева", 0, $s1);
    $Select[] = array("Справа", 1, $s2);

    if ($data['connect'] == 1)
        $c2 = "selected";
    else
        $c1 = "selected";

    $Select_connect[] = array("Socket", 0, $c1);
    $Select_connect[] = array("IFRAME", 1, $c2);

    $Tab1= $PHPShopGUI->setField('URL форума', $PHPShopGUI->setInputText(false,"path_new", $data['path'],false,'/lastpost.php'));
        $Tab1.= $PHPShopGUI->setField("Режим подключения:", $PHPShopGUI->setSelect("connect_new", $Select_connect, 150, 1));
    $Tab1.=$PHPShopGUI->setField("Ширина:",$PHPShopGUI->setInput("text", "width_new", $data['width'],false,100).$PHPShopGUI->setHelp('Для режима IFRAME'));
    $Tab1.=$PHPShopGUI->setField("Высота: ", $PHPShopGUI->setInput("text", "height_new", $data['height'],false,100).$PHPShopGUI->setHelp('Для режима IFRAME'));
    $Tab1.=$PHPShopGUI->setField("Заголовок: ",$PHPShopGUI->setInput("text", "title_new", $data['title']));
    $Tab1.= $PHPShopGUI->setField("Расположение:", $PHPShopGUI->setSelect("flag_new", $Select, 200, 1));
    $Tab1.= $PHPShopGUI->setField("Сообщений из топиков:", $PHPShopGUI->setInput("text", "num_new", $data['num'], $float = "left", $size = 70));
    $Tab1. $PHPShopGUI->setField("Статус",$PHPShopGUI->setCheckbox("enabled_new", 1, "Вывод блока на сайте", $data['enabled']));


    $Info = 'Для работы модуля требуется загрузить в корневую директорию форума файл lastpost.php и иконки оформления.<br>
    
Файл доступен по ссылке: <a target="_blank" href="http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/forumlastpost/code/">http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/forumlastpost/code/</a>
    <p>
При включении опции "<b>Вывод блока на сайте</b>" информация о последних сообщений с форума будет автоматически добавлена в левый или правый
текстовый блок автоматически в конец списка.</p>

Для произвольного включения формы вывода сообщений, нужно снять галочку "<b>Вывод блока на сайте</b>" и в вставить переменную <kbd>@forumlastpost@</kbd>
в нужное место шаблонов <mark>main/index.tpl</mark> и <mark>main/shop.tpl</mark>.
';
    $Tab2 = $PHPShopGUI->setInfo($Info);
    $Tab3 = $PHPShopGUI->setPay();

    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Инструкция", $Tab2), array("О Модуле", $Tab3));
    
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>