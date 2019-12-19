<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules;

    $PHPShopInterface->setCaption(array("", "1%"), array("��������", "30%"), array("������", "20%"), array("���������", "40%"), array("", "10%"), array("������ &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sticker.sticker_forms"));
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $row) {


            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=modules.dir.sticker&id=' . $row['id'], 'align' => 'left'), $row['path'], $row['dir'], array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('����', '���'))));
        }
    $PHPShopInterface->Compile();
}

?>