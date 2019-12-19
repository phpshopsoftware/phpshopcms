<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules,$TitlePage, $select_name;
    
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);

    $PHPShopInterface->setCaption(array("", "1%"), array("Заголовок", "40%"), array("Дата", "10%"), array("Пользователь", "30%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.messageboard.messageboard_log"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
        
            if(!empty($row['mail'])) $row['name']='<a href="mailto:'.$row['mail'].'">'.$row['name'].'</a>';
        
            $PHPShopInterface->setRow($row['id'], array('name' => $row['title'], 'link' => '?path=modules.dir.messageboard&id=' . $row['id'], 'align' => 'left'), PHPShopDate::dataV($data['date'], false),$row['name'], array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }


    $PHPShopInterface->Compile();
}

?>