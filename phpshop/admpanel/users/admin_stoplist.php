<?php

$TitlePage = __("������ ������ IP");

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->action_select['������������� ���������'] = array(
        'name' => '������������� ��������� IP',
        'action' => 'add-blacklist-select',
        'class' => 'disabled'
    );
    
        $PHPShopInterface->action_button['�������� IP'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="�������� IP"'
    );


    $PHPShopInterface->action_button['������ �����������'] = array(
        'name' => '������ �����������',
        'action' => 'users.jurnal',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-eye-open'
    );

    $PHPShopInterface->action_title['add-blacklist'] = '������������� IP';

    $PHPShopInterface->setActionPanel(__("������ ������ IP"), array('������� ���������'), array('�������� IP','������ �����������'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("IP", "40%"),array("���� ����������", "20%"), array("", "20%"), array('Whois',"10%",array('align'=>'right','sort'=>'none')));

    // ������� � �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['black_list']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['flag']))
                $status = '<span class="glyphicon glyphicon-ok"></span>';
            else
                $status = '<span class="glyphicon glyphicon-remove" style="color:red"></span>';

            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['ip'], 'link' => '?path=users.stoplist&id=' . $row['id'], 'align' => 'left'),array('name' => PHPShopDate::get($row['datas'], true)), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('name' => '<span class="glyphicon glyphicon-question-sign"></span>', 'link' => 'https://www.nic.ru/whois/?query=' . $row['ip'],'target'=>'_blank','align'=>'right'));
        }
    $PHPShopInterface->Compile();
}

?>