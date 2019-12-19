<?php

/**
 * Преобразование строки
 * @param string $str строка
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
    $str=str_replace("«", "", $str);
    $str=str_replace("»", "", $str);
    $str=str_replace("ь", "", $str);
    $str=str_replace("ъ", "", $str);

    $_Array=array(
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"zh",
            "з"=>"z",
            "и"=>"i",
            "й"=>"i",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"h",
            "ц"=>"c",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sh",
            "ы"=>"y",
            "э"=>"e",
            "ю"=>"uy",
            "я"=>"ya",
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "E"=>"e",
            "Ё"=>"e",
            "Ж"=>"gh",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"i",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"h",
            "Ц"=>"c",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sh",
            "Э"=>"e",
            "Ю"=>"uy",
            "Я"=>"ya",
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

// Последний номер
function getLastId() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id'),false,array('order'=>'id DESC'),array('limit'=>1));
    return ($data['id']+1);
}


function addSeoUrl($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    if(empty($data['seo_name'])) $data['seo_name']=setLatin($data['title']);
    $Tab3=$PHPShopGUI->setField("SEO ссылка:",$PHPShopGUI->setInputText(false,"seo_name_new",'',500,false,"left"),"none");

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