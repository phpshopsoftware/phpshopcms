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

       $_Array=array(
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"gh",
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
            "ъ"=>"i",
            "ы"=>"yi",
            "ь"=>"i",
            "э"=>"a",
            "ю"=>"u",
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
            "Э"=>"a",
            "Ю"=>"u",
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

function addSeoUrl($data) {
    global $PHPShopGUI;
    
    // Добавляем значения в функцию actionStart
    if(empty($data['seoname'])) $data['seoname']=setLatin($data['name']);
    $Tab3=$PHPShopGUI->setField("SEO ссылка:",$PHPShopGUI->setInput("text","seoname_new",$data['seoname'],"left",300),"none");
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