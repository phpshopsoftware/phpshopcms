<?php

$TitlePage = __("����������");

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->action_select['������������� ���������'] = array(
        'name' => '������������� ��������� IP',
        'action' => 'add-blacklist-select',
        'class' => 'disabled'
    );
    
        $PHPShopInterface->action_button['�������� E-mail'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="�������� E-mail"'
    );


    $PHPShopInterface->action_title['add-blacklist'] = '������������� IP';

    $PHPShopInterface->setActionPanel(__("����������"), array('������� ���������'), array('�������� E-mail'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("�����", "60%"),array("���� ����������", "20%"), array("", "20%"));

    // ������� � �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['mail'], 'link' => '?path=news.mail&id=' . $row['id'], 'align' => 'left'),array('name' => $row['date']), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'));
        }
    $PHPShopInterface->Compile();
}

?>