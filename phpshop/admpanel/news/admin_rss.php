<?php

$TitlePage = __("RSS каналы");

function actionStart() {
    global $PHPShopInterface;
    
    $PHPShopInterface->setActionPanel(__("RSS каналы"), array('Удалить выбранные'),array('Добавить'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("URL загрузки", "50%"), array("Начало", "10%"), array("Завершение", "10%"), array("Последняя", "10%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['rssgraber']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'last_load desc'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow($row['id'], array('name' => $row['link'], 'link' => '?path='.$_GET['path'].'&id=' . $row['id'], 'align' => 'left'), PHPShopDate::get($row['start_date']), PHPShopDate::get($row['end_date']), PHPShopDate::get($row['last_load']), array('action' => array('edit', 'delete','id'=>$row['id']), 'align' => 'center'), array('status' => array('enable'=>$row['enabled'], 'align' => 'right','caption'=>array('Выкл', 'Вкл'))));
        }

    $PHPShopInterface->Compile();
}

?>