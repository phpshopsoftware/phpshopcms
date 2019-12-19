<?php

function news_index_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {
        $obj->SysValue['lang']['page_now'] = 'Pages';
        if(!empty($row['title_news_locale'])) $obj->set('newsZag',$row['title_news_locale']);
        if(!empty($row['description_news_locale'])) $obj->set('newsKratko',$row['description_news_locale']);

    }
}


function news_id_hook($obj,$row) {

    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['title_news_locale'])) {
            $obj->set('newsZag',$row['title_news_locale']);
            $row['title'] = $row['title_news_locale'];
        }
        if(!empty($row['description_news_locale'])) {
            $obj->set('newsKratko',$row['description_news_locale']);
            $row['description'] = $row['description_news_locale'];
        }
        if(!empty($row['content_news_locale'])) $obj->set('newsPodrob',$row['content_news_locale']);

    }
}


function newsmeta_hook($obj) {
    global $PHPShopSystem;
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default')
        $obj->set('pageTitl',"News - ".$PHPShopSystem->getValue('title'));
}

$addHandler=array(
        'index'=>'news_index_hook',
        'ID'=>'news_id_hook',
        'meta'=>'newsmeta_hook'
);


?>