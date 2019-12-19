<?php

$TitlePage = __("Ссылки");

function actionStart() {
    global $PHPShopInterface;
    
    $PHPShopInterface->setActionPanel(__("Ссылки"), array('Удалить выбранные'),array('Добавить'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("Название", "30%"), array("Ссылка", "20%"), array("Приоритет", "10%", array('align' => 'center')), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'num desc'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=links&id=' . $row['id'], 'align' => 'left'), $row['link'], array('name' => $row['num'], 'align' => 'center'), array('action' => array('edit', 'delete','id'=>$row['id']), 'align' => 'center'), array('status' => array('enable'=>$row['enabled'], 'align' => 'right','caption'=>array('Выкл', 'Вкл'))));
        }

    $PHPShopInterface->Compile();
}

?>
