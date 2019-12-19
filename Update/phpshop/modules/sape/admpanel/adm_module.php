<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sape.sape_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name;

// Выборка
    $data = $PHPShopOrm->select();

    $PHPShopGUI->action_button['Скачать'] = array(
        'name' => 'Скачать файлы Sape',
        'action' => '../modules/sape/code/',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Скачать', 'Сохранить и закрыть'));

    $Select[] = array("Слева", 0, $data['flag']);
    $Select[] = array("Справа", 1, $data['flag']);

// Создаем объекты для формы
    $Tab1 = $PHPShopGUI->setField("Sape ID", $PHPShopGUI->setInput("text", "sape_user_new", $data['sape_user']));
    $Tab1.=$PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text", "title_new", $data['title']));
    $Tab1.= $PHPShopGUI->setField("Расположение:", $PHPShopGUI->setSelect("flag_new", $Select, 100));
    $Tab1.= $PHPShopGUI->setField("Количество ссылок:", $PHPShopGUI->setInput("text", "num_new", $data['num'], false, 100));
    $Tab1.= $PHPShopGUI->setField("Статус:", $PHPShopGUI->setCheckbox("enabled_new", 1, "Вывод блока на сайте", $data['enabled']));


// Содержание закладки 2
    $Info = 'Для работы модуля требуется загрузить в корневую директорию форума папку 4cb48833f491686a2500f80310e072da.
Папку переименуйте в свой уникальный SAPE USER номер и проставьте для права на запись CHMOD 777.
Файлы доступны по <a href="../modules/sape/code/" taget="_blank">ссылке</a>.
<p>    
При включении опции "Вывод блока на сайте" Sape ссылки будут автоматически добавлены в левый или правый текстовый блок  в конец списка.
</p>  <p>  
Для произвольного включения формы вывода ссылок нужно снять галочку "Вывод блока на сайте" и в вставить переменную @sape@
в нужное место шаблонов <code>main/index.tpl и main/shop.tpl</code>.</p>  
<p>  
Для размещения в текстовых блоках черех админ-панель следует отключить визуальный редактор через настройку системы, создать новый тестовый блок, отключить опцию "вывод блока на сайте" и вставить код:</p>  

<ul>
<li>Вариант 1: <pre>
@php echo $GLOBALS["SysValue"]["other"]["sape"]; php@</pre></li>

<li>Вариант 2:<pre>
@php
if (defined("_SAPE_USER")) {
$PHPShopSapeElement = new PHPShopSapeElement();
$PHPShopSapeElement->links(4);
} else echo "<b>Вывод ссылок не работает!</b><br>Модуль Sape не установлен!";
// где 4 - кол-во ссылок для вывода
php@
</pre>
</li>
</ul>
';
   $Tab2 = $PHPShopGUI->setInfo($Info) ;

// Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("Описание", $Tab2, 270), array("О Модуле", $Tab3, 270));

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