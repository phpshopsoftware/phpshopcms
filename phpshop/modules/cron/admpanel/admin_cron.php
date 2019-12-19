<?php

$TitlePage = "Обзор задач Cron";

function actionStart() {
    global $PHPShopInterface, $PHPShopModules;


    $PHPShopInterface->setCaption(array(null, "1%"), array("Задача", "30%"), array("Исполняемый файл", "40%"), array("Дата", "10%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));


    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=' . $_GET['path'] . '&id=' . $row['id'], 'align' => 'left'), $row['path'], PHPShopDate::get($row['date']), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }

    $PHPShopInterface->Compile();
}

?>