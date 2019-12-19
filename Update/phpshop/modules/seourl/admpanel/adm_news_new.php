<?php

/**
 * �������������� ������
 * @param string $str ������
 */
function setLatin($str) {
    $str=strtolower($str);
    $str=str_replace("/", "", $str);
    $str=str_replace("\\", "", $str);
    $str=str_replace("(", "", $str);
    $str=str_replace(")", "", $str);
    $str=str_replace(":", "", $str);
    $str=str_replace(" ", "-", $str);
    $str=str_replace("\"", "", $str);
    $str=str_replace(".", "", $str);
    $str=str_replace("�", "", $str);
    $str=str_replace("�", "", $str);
    $str=str_replace("�", "", $str);
    $str=str_replace("�", "", $str);

    $_Array=array(
            "�"=>"a",
            "�"=>"b",
            "�"=>"v",
            "�"=>"g",
            "�"=>"d",
            "�"=>"e",
            "�"=>"e",
            "�"=>"zh",
            "�"=>"z",
            "�"=>"i",
            "�"=>"i",
            "�"=>"k",
            "�"=>"l",
            "�"=>"m",
            "�"=>"n",
            "�"=>"o",
            "�"=>"p",
            "�"=>"r",
            "�"=>"s",
            "�"=>"t",
            "�"=>"u",
            "�"=>"f",
            "�"=>"h",
            "�"=>"c",
            "�"=>"ch",
            "�"=>"sh",
            "�"=>"sh",
            "�"=>"y",
            "�"=>"e",
            "�"=>"uy",
            "�"=>"ya",
            "�"=>"a",
            "�"=>"b",
            "�"=>"v",
            "�"=>"g",
            "�"=>"d",
            "E"=>"e",
            "�"=>"e",
            "�"=>"gh",
            "�"=>"z",
            "�"=>"i",
            "�"=>"i",
            "�"=>"k",
            "�"=>"l",
            "�"=>"m",
            "�"=>"n",
            "�"=>"o",
            "�"=>"p",
            "�"=>"r",
            "�"=>"s",
            "�"=>"t",
            "�"=>"u",
            "�"=>"f",
            "�"=>"h",
            "�"=>"c",
            "�"=>"ch",
            "�"=>"sh",
            "�"=>"sh",
            "�"=>"e",
            "�"=>"uy",
            "�"=>"ya",
            "."=>"",
            ","=>"",
            "$"=>"i",
            "%"=>"i",
            "&"=>"and");

    $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

    foreach($chars as $val)
        if(empty($_Array[$val])) @$new_str.=$val;
        else $new_str.=$_Array[$val];

    return $new_str;
}

// ��������� �����
function getLastId() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id'),false,array('order'=>'id DESC'),array('limit'=>1));
    return ($data['id']+1);
}


function addSeoUrl($data) {
    global $PHPShopGUI;

    // ��������� �������� � ������� actionStart
    if(empty($data['seo_name'])) $data['seo_name']=setLatin($data['title']);
    $Tab3=$PHPShopGUI->setField("SEO ������:",$PHPShopGUI->setInputText(false,"seo_name_new",'',500,false,"left"),"none");

    if(empty($data['seo_title'])) $data['seo_title']=$data['title'];
    $Tab3.=$PHPShopGUI->setField("Title:",$PHPShopGUI->setInputText(false,'seo_title_new','','100%',false,"none",false,'50px'));

    if(empty($data['seo_desc'])) $data['seo_desc']=strip_tags($data['description']);
    $Tab3.=$PHPShopGUI->setField("Description:",$PHPShopGUI->setInputText(false,'seo_desc_new','','100%',false,"none",false,'50px'));
    
    $Tab3.=$PHPShopGUI->setField("Keywords:",$PHPShopGUI->setInputText(false,'seo_key_new','','100%',false,"none",false,'50px'));
    $PHPShopGUI->addTab(array("SEO",$Tab3,350));
}

function insertSeoUrl($data) {
    global $PHPShopOrm;

    if(empty($_POST['seo_name_new'])) $_POST['seo_name_new']=setLatin($data['title_new']);

}

$addHandler=array(
        'actionStart'=>'addSeoUrl',
        'actionDelete'=>false,
        'actionUpdate'=>false,
        'actionInser'=>'insertSeoUrl'
);

?>