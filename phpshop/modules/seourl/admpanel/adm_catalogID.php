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

       $_Array=array(
            "�"=>"a",
            "�"=>"b",
            "�"=>"v",
            "�"=>"g",
            "�"=>"d",
            "�"=>"e",
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
            "�"=>"i",
            "�"=>"yi",
            "�"=>"i",
            "�"=>"a",
            "�"=>"u",
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
            "�"=>"a",
            "�"=>"u",
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

function addSeoUrl($data) {
    global $PHPShopGUI;
    
    // ��������� �������� � ������� actionStart
    if(empty($data['seoname'])) $data['seoname']=setLatin($data['name']);
    $Tab3=$PHPShopGUI->setField("SEO ������:",$PHPShopGUI->setInput("text","seoname_new",$data['seoname'],"left",300),"none");
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