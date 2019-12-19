<?php

function addComment($data) {
    global $PHPShopGUI;

    // ��������� �������� � ������� actionStart
    $Tab3=$PHPShopGUI->setCheckbox('comment_enabled_new',1,'����������� �������������',$data['comment_enabled']);
    $Tab3.=$PHPShopGUI->setLine();
    $Tab3.=$PHPShopGUI->setCheckbox('rating_enabled_new',1,'������� �������',$data['rating_enabled']);

    // ���������� ��������
    $PHPShopGUI->addTab(array("�����������",$Tab3,true));
}

function updateComment(){
    if(empty($_POST['comment_enabled_new'])) $_POST['comment_enabled_new']=0;
    if(empty($_POST['rating_enabled_new'])) $_POST['rating_enabled_new']=0;
}

$addHandler=array(
        'actionStart'=>'addComment',
        'actionUpdate'=>'updateComment',
        'actionSave'=>'updateComment'
);

?>
