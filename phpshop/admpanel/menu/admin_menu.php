<?php

$TitlePage = __("��������� �����");

function actionStart() {
    global $PHPShopInterface;


        $PHPShopInterface->setActionPanel(__("��������� �����"), array('������� ���������'),array('��������'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("��������", "40%"), array("���������", "30%"), array("�����", "10%", array('align' => 'center')), array("", "10%"), array("������ &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['menu']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            if ($row['element'] == 0)
                $element = '<span class="glyphicon glyphicon-arrow-left"></span>';
            else
                $element = '<span class="glyphicon glyphicon-arrow-right"></span>';

            
             $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=menu&id=' . $row['id'], 'align' => 'left'), $row['num'], array('name' => $element, 'align' => 'center'), array('action' => array('edit', 'delete','id'=>$row['id']), 'align' => 'center'), array('status' => array('enable'=>$row['flag'], 'align' => 'right','caption'=>array('����', '���'))));
            
        }

    $PHPShopInterface->Compile();
}

?>
