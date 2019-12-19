<?php

function addComment($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    $Tab3=$PHPShopGUI->setCheckbox('comment_enabled_new',1,'Комментарии пользователей',$data['comment_enabled']);
    $Tab3.=$PHPShopGUI->setLine();
    $Tab3.=$PHPShopGUI->setCheckbox('rating_enabled_new',1,'Рейтинг страниц',$data['rating_enabled']);

    // Содержание закладки
    $PHPShopGUI->addTab(array("Комментарии",$Tab3,true));
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
