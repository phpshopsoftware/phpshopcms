<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (!empty($_POST['last_execute_new']))
        $_POST['used_new'] = 0;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;


    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}


// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));


    $work[] = array('Выбрать', '');
    $work[] = array('Бекап БД', 'phpshop/modules/cron/sample/dump.php');
    $work[] = array('Курсы валют', 'phpshop/modules/cron/sample/currency.php');
    $work[] = array('Снятие с продаж товаров', 'phpshop/modules/cron/sample/product.php');

    $Tab1 = $PHPShopGUI->setField("Название задачи:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));
    $Tab1.=$PHPShopGUI->setField("Запускаемый Файл:" , $PHPShopGUI->setInputArg(array('type'=>"text.requared", 'name'=>"path_new", 'size'=>'60%','float'=>'left','value'=>$data['path'])) . $PHPShopGUI->setSelect('work', $work, 200, 'left', false, false,false,false,false,false,'selectpicker', '$(\'input[name=path_new]\').val(this.value);'));
    $Tab1.=$PHPShopGUI->setField("Статус", $PHPShopGUI->setCheckbox("enabled_new", 1, "Включить", 1));
    $Tab1.=$PHPShopGUI->setField("Кол-во запусков в день", $PHPShopGUI->setSelect('execute_day_num_new', $PHPShopGUI->setSelectValue($data['execute_day_num']), 70));


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart');
?>