<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->action_button['���������'] = array(
        'name' => '��������� �����',
        'action' => '../modules/guard/admin.php?do=chek',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-import'
    );

    $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('���������'));
    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setCaption(array("����", "20%"), array("������ ��������", "20%"), array("����� ������", "20%"), array("���������� ������", "20%"), array('����� (������)', '20%', array('align' => 'right')));


    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.guard.guard_log"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow(PHPShopDate::dataV($row['date']), $row['change_files'], $row['new_files'], $row['infected_files'], array("name" => $row['time'], 'align' => 'right'));
        }

    $PHPShopInterface->Compile();
}

?>