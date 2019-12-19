<?php

$TitlePage = __("Новости");

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->setActionPanel(__("Новости"), array('Удалить выбранные'), array('Добавить'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("Заголовок", "70%"), array("", "10%"), array("ID", "10%", array('align' => 'left')), array("Дата", "20%", array('align' => 'center')));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow($row['id'], array('name' => $row['title'], 'link' => '?path=news&id=' . $row['id'], 'align' => 'left'), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('name' => $row['id'], 'align' => 'left'), array('name'=>$row['date'],'order'=>$row['id']));
        }
    $PHPShopInterface->Compile();
}

?>