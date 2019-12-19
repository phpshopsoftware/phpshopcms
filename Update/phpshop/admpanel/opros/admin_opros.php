<?php

$TitlePage = __("������");

function actionStart() {
    global $PHPShopInterface,$TitlePage;

    $PHPShopInterface->setActionPanel($TitlePage, array('������� ���������'), array('��������'));
    
    $PHPShopInterface->setCaption(array(null, "3%"), array("���������", "40%"), array("����������", "40%"), array("", "10%"), array("������ &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));


    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros_categories']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'name DESC'), array("limit" => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=opros&id=' . $row['id'], 'align' => 'left'), $row['dir'], array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['flag'], 'align' => 'right', 'caption' => array('����', '���'))));
        }

    $PHPShopInterface->Compile();
}

?>