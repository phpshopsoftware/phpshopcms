<?php

$TitlePage = __("������");

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->setActionPanel(__("������"), array('������� ���������'), array('��������'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("���������", "40%"), array("������������", "20%"), array("����", "10%", array('align' => 'center')), array("�����", "10%", array('align' => 'center')), array("", "10%"), array("������", "10%", array('align' => 'right')));


    // ������� � �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where=array(), array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
        
            if(!empty($row['mail'])) $row['name']=' <a href="mailto:'.$row['mail'].'" title="���������">'.$row['name'].'</a>';
            
            if(!empty($row['answer'])) $otvet='<span class="glyphicon glyphicon-ok"></span>';
            else $otvet='<span class="glyphicon glyphicon-remove"></span>';
            
            $PHPShopInterface->setRow($row['id'], array('name' => $row['title'], 'link' => '?path=gbook&id=' . $row['id'], 'align' => 'left'), $row['name'], array('name' => PHPShopDate::get($row['date']), 'align' => 'center'), array('name' => $otvet, 'align' => 'center'), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('����', '���'))));
        }


    $PHPShopInterface->Compile();
}

?>