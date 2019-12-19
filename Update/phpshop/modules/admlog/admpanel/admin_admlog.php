<?php

$TitlePage = "Журнал событий";

function actionStart() {
    global $PHPShopInterface, $_classPath;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setCaption(array("Раздел", "50%"), array("Дата", "10%"), array("Имя", "10%"), array("IP", "10%"));

    $PHPShopModules = new PHPShopModules($_classPath . "modules/");
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 100));

    if (is_array($data))
        foreach ($data as $row) {


            $PHPShopInterface->setRow(array('name' => $row['title'], 'link' => '?path=modules.dir.admlog&id=' . $row['id'], 'align' => 'left'), PHPShopDate::get($row['date']), $row['user'], $row['ip']);
        }


    $PHPShopInterface->Compile();
}

?>