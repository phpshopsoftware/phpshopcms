<?php


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$select_name;
    
    $PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id']).'</span>', $select_name, null);

    // Содержание закладки 2
    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("О Модуле", $Tab2),array("Обзор Задач", null,'?path=modules.dir.cron'),array("Журнал выполнения", null,'?path=modules.dir.cron.log'));

    return true;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>