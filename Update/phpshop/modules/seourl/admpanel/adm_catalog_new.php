<?php

// Последний номер
function getLastId() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('id'),false,array('order'=>'id DESC'),array('limit'=>1));
    return ($data['id']+1);
}


function addSeoUrl($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    $Tab3=$PHPShopGUI->setField("SEO ссылка:",$PHPShopGUI->setInput("text","seoname_new","katalog-".getLastId(),"left",300),"none");
    $Tab3.=$PHPShopGUI->setField("Title:",$PHPShopGUI->setTextarea('seotitle_new',$data['seotitle']),"none");
    $Tab3.=$PHPShopGUI->setField("Description:",$PHPShopGUI->setTextarea('seodesc_new',$data['seodesc']),"none");
    $Tab3.=$PHPShopGUI->setField("Keywords:",$PHPShopGUI->setTextarea('seokey_new',$data['seokey']),"none");
    $PHPShopGUI->addTab(array("SEO",$Tab3,450));
}

$addHandler=array(
        'actionStart'=>'addSeoUrl',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>