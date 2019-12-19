<?php

$TitlePage="Журнал выполнения задач Cron";

function actionStart() {
    global $PHPShopInterface,$PHPShopModules,$TitlePage,$select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Дата","10%"),array("Задача","20%"),array("Исполняемый файл","40%"),array("Статус","10%"));

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_log"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {

            $PHPShopInterface->setRow(PHPShopDate::dataV($row['date']),$row['name'],$row['path'],$row['status']);
        }

    $PHPShopInterface->Compile();
}
?>