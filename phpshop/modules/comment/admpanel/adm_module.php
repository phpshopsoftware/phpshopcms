<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.comment.comment_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['flag_new'])) $_POST['flag_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Select[]=array("Справа",1,$data['flag']);
    $Select[]=array("Слева",0,$data['flag']);
    
    $Tab1=$PHPShopGUI->setField("Расположение блока комментариев:",$PHPShopGUI->setSelect("flag_new",$Select,100,1));
    $Tab1.=$PHPShopGUI->setField("Вывод:", $PHPShopGUI->setCheckbox("enabled_new",1,"Вывод блока на сайте",$data['enabled']));

     $Info='
     Для произвольного размещения формы вывода последних комментариев отключите опцию вывода блока на сайте и используйте переменную <kbd>@lastcommentForma@</kbd>
     для вставки в свой шаблон в произвольное место.
';
    $Tab2=$PHPShopGUI->setInfo($Info);

    // Содержание закладки 2
    $Tab3=$PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,true),array("Описание",$Tab2),array("О Модуле",$Tab3),array("Комментарии", null,'?path=modules.dir.comment'));

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