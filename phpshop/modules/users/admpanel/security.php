<?php

function addSecurity($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    $Tab3=$PHPShopGUI->setCheckbox('user_security_new',1,'Требуется авторизация',$data['user_security']);

    // Содержание закладки
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
