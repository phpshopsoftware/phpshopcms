<?php

  
$TitlePage = "Блог";

function actionStart() {
    global $PHPShopInterface,$PHPShopModules;
        $PHPShopInterface->setCaption(array(null, "5%"), array("Заголовок", "70%"), array("", "10%"), array("ID", "10%", array('align' => 'left')), array("Дата", "20%", array('align' => 'center')));


    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
        
         $PHPShopInterface->setRow($row['id'], array('name' => $row['title'], 'link' => '?path=modules.dir.blog&id=' . $row['id'], 'align' => 'left'), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('name' => $row['id'], 'align' => 'left'), $row['date']);

        }
    $PHPShopInterface->Compile();
}

?>