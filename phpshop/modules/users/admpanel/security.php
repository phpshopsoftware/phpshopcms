<?php

function addSecurity($data) {
    global $PHPShopGUI;

    // ��������� �������� � ������� actionStart
    $Tab3=$PHPShopGUI->setCheckbox('user_security_new',1,'��������� �����������',$data['user_security']);

    // ���������� ��������
    $PHPShopGUI->addTab(array("Users",$Tab3,450));
}

function updateSecurity() {
    if(empty($_POST['user_security_new'])) $_POST['user_security_new']=0;
}


$addHandler=array(
        'actionStart'=>'addSecurity',
        'actionUpdate'=>'updateSecurity'
);

?>
