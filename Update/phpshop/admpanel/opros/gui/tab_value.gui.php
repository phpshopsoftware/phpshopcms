<?php

/**
 * �������� �������������
 */
function tab_value($data) {
    global $PHPShopInterface;
    
     $PHPShopInterface->action_title['remove'] = '�������';
     $PHPShopInterface->action_title['value-edit'] = '�������������';


    $PHPShopInterface->dropdown_action_form=false;
    $PHPShopInterface->checkbox_action=false;
    $PHPShopInterface->path='opros.value';
    $PHPShopInterface->setCaption(array("���������", "7%"), array("������� ������", "55%"),array("������", "10%"),array(null, "10%"),array(null, "3%"));

    // ������� � �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('category'=>"=".$data['id']), array('order' => 'num,name DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow( array('name' => $row['num'], 'editable' => 'num_value','id'=>$row['id']), array('name' => $row['name'], 'editable' => 'name_value','id'=>$row['id']),  array('name' => $row['total'], 'editable' => 'total_value','id'=>$row['id']),array('action' => array('remove', 'id' => $row['id']), 'align' => 'center'),'<span class="glyphicon glyphicon-remove remove hide" data-toggle="tooltip" data-id="'.$row['id'].'" data-placement="top" title="�������"></span>');
        }
        
     
    $PHPShopInterface->setRow( array('name' => '<input style="width:100%" class="form-control input-sm" name="num_value" value="">'), array('name' => '<input style="width:100%" data-id="" placeholder="��������" name="name_value" class="form-control input-sm editable-add" value="">'), array('name' => '<input style="width:100%" data-id="" name="total_value" class="form-control input-sm" value="">'),' ',' ');
    $disp = '<table class="table table-hover value-list">' . $PHPShopInterface->getContent() . '</table>';
    
    return $disp;
}

?>