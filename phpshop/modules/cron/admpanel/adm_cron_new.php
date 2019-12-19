<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));

// Функция обновления
function actionInsert() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    $work[] = array('Выбрать', '');
    $work[] = array('Бекап БД', 'phpshop/modules/cron/sample/dump.php');
    $work[] = array('Курсы валют', 'phpshop/modules/cron/sample/currency.php');
    $work[] = array('Снятие с продаж товаров', 'phpshop/modules/cron/sample/product.php');

    $Tab1 = $PHPShopGUI->setField("Название задачи:", $PHPShopGUI->setInput("text.requared", "name_new", 'Новая задача'));
    $Tab1.=$PHPShopGUI->setField("Запускаемый Файл:" , $PHPShopGUI->setInputArg(array('type'=>"text.requared", 'name'=>"path_new", 'size'=>'60%','float'=>'left','placeholder'=>'phpshop/modules/cron/sample/testcron.php')) . $PHPShopGUI->setSelect('work', $work, 200, 'left', false, false,false,false,false,false,'selectpicker', '$(\'input[name=path_new]\').val(this.value);'));
    $Tab1.=$PHPShopGUI->setField("Статус",$PHPShopGUI->setCheckbox("enabled_new", 1, "Включить", 1));
    $Tab1.=$PHPShopGUI->setField("Кол-во запусков в день",$PHPShopGUI->setSelect('execute_day_num_new', $PHPShopGUI->setSelectValue(false),70));



    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","Сохранить","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>