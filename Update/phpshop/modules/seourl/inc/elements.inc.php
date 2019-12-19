<?php

/**
 * Преобразование строки
 * @param string $str строка
 */
function setRssLatin($str) {
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

function add_news_hook($obj,$row){
    $row['seo_name_new']=setRssLatin($row['title_new']);
    $row['seo_title_new']=$row['title_new'];
    $row['seo_desc_new']=strip_tags($row['description_new']);
}



function podcatalog_hook($obj,$row) {

    $obj->set('catalogLink','../cat/'.$row['seoname']);

}

function page_hook($obj,$array) {

    $obj->set('catalogLink',''.$array[0]['link']);
    $array[1]=str_replace('/page', '', $array[1]);

}

function topmenu_hook($obj,$row) {

    $obj->set('topMenuLink','../'.$row['link']);

}

function news_hook($obj,$row) {

    $obj->set('newsId',$row['seo_name']);

}

function searchpage_hook($obj,$row,$rout) {
    if($rout == 'MIDDLE')
        $GLOBALS['SysValue']['other']['pageFrom']='..';

}

function searchnews_hook($obj,$row,$rout) {
    if($rout == 'MIDDLE')
        $GLOBALS['SysValue']['other']['pageLink']='ID_'.$row['seo_name'].'.html';

}

$addHandler=array(
        'topMenu'=>'topmenu_hook',
        'podcatalog'=>'podcatalog_hook',
        'page'=>'page_hook',
        'index'=>'news_hook',
        'add_news'=>'add_news_hook',
        'searchnews'=>'searchnews_hook',
    'searchpage'=>'searchpage_hook'
);

?>