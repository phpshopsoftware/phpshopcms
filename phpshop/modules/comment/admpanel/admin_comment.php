<?php

$TitlePage="�����������";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,530";
    $PHPShopInterface->link="../modules/comment/admpanel/adm_commentID.php";
    $PHPShopInterface->setCaption(array("����","7%"),array("������������","10%"),array("�����������","60%"),array("��������","10%"));

    // ��������� ������
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.comment.comment_log"));
    $PHPShopOrm->debug=false;
    $data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>300));
    /*
    $result = $PHPShopOrm->query('SELECT a.*, b.login FROM '.$GLOBALS['SysValue']['base']['comment']['comment_log'].' AS a
            JOIN '.$GLOBALS['SysValue']['base']['users']['users_base'].' AS b ON a.user_id = b.id order by a.id desc limit 300');
    */
    if(is_array($data))
        foreach($data as $row) {

            extract($row);
            $PHPShopInterface->setRow($id,PHPShopDate::dataV($date),$user,$content,'/page/'.$page.'.html');
        }

    $PHPShopInterface->Compile();
}
?>