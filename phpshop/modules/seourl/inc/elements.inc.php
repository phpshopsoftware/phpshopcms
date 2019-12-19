<?php

/**
 * �������������� ������
 * @param string $str ������
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