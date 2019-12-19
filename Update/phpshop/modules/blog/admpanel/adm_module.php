<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['enabled_menu_new']))
        $_POST['enabled_menu_new'] = 0;
    if (empty($_POST['flag_new']))
        $_POST['flag_new'] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    if ($data['flag'] == 0)
        $s0 = "selected";
    else
        $s1 = "selected";

    $Select[] = array("Справа", 1, $s1);
    $Select[] = array("Слева", 0, $s0);

    $Tab1 = '<hr>'.$PHPShopGUI->setField("Статус", $PHPShopGUI->setCheckbox("enabled_new", 1, "Вывод блока на сайте", $data['enabled']) . '<br>' .
            $PHPShopGUI->setCheckbox("enabled_menu_new", 1, "Добавить в топ-меню ссылку", $data['enabled_menu']));
    $Tab1.= $PHPShopGUI->setField("Расположение блока:", $PHPShopGUI->setSelect("flag_new", $Select, 100, 1));
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField("Кол-во на странице:", $PHPShopGUI->setInputText(false, 'num_new', $data['num'], 100));
    $Info = '
     Для произвольного размещения формы вывода последних записей блога отключите опцию вывода блока на сайте и используйте переменную <kbd>@lastblogForma@</kbd>
     для вставки в свой шаблон в произвольное место.';

    $Tab2 = $PHPShopGUI->setInfo($Info, 250, '97%');

    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("Описание", $Tab2, 270), array("О Модуле", $Tab3, 270),array("Обзор записей блога", 0,'?path=modules.dir.blog'));

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