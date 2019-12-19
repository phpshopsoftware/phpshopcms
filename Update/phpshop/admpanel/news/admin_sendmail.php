<?php

$TitlePage = __("Рассылки");

function actionStart() {
    global $PHPShopInterface;
    
        $PHPShopInterface->action_button['Подписчики'] = array(
        'name' => 'Подписчики',
        'action' => 'news.mail',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-envelope'
    );
    
    $PHPShopInterface->setActionPanel(__("Рассылки"), array('Удалить выбранные'),array('Добавить','Подписчики'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("Тема", "70%"), array("", "10%"), array("Дата рассылки &nbsp;&nbsp;&nbsp;", "20%", array('align' => 'right')));

    // SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
        
            if(!empty($row['date']))
                $date=PHPShopDate::get($row['date'],true);
            else $date=' - ';
        
            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path='.$_GET['path'].'&id=' . $row['id'], 'align' => 'left') , array('action' => array('edit', 'delete','id'=>$row['id']), 'align' => 'center'), array('name'=>$date, 'align' => 'right','order'=>$row['date']));
        }

    $PHPShopInterface->Compile();
}

?>