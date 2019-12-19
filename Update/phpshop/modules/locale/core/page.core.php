<?php

function index_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['content_locale'])) $obj->set('pageContent',Parser($row['content_locale']));
        if(!empty($row['name_locale'])) {
            $obj->set('pageTitle',$row['name_locale']);
            $obj->navigation(false,$row['name_locale']);
        }
    }
}

function get_category($category) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('name_cat_locale,content_cat_locale'),array('id'=>'='.$category));

    return $data;
}

function listpage_hook($obj,$data) {
    $dis='';

    // Описание
    $get_category = get_category($obj->category);
    $category_name = $get_category['name_cat_locale'];
    $category_content = $get_category['content_cat_locale'];
    $obj->set('pageTitle',$get_category['name_cat_locale']);

    if(is_array($obj->dataArray))
        foreach($obj->dataArray as $row) {
            if(!empty($row['name_locale'])) $name=$row['name_locale'];
            else $name=$row['name'];
            $dis.="<li><a href=\"/page/".$row['link'].".html\" title=\"".$name."\">".$name."</a></li>";
        }

    $disp="<h1>".$category_name."</h1>";

    // Если есть описание каталога
    if(!empty($obj->LoadItems['CatalogPage'][$obj->category]['content_enabled']))
        $disp.=$category_content;

    $disp.="<ul>$dis</ul>";

    // Навигация хлебные крошки
    if(!empty($obj->LoadItems['CatalogPage'][$obj->category]['parent_to']))
        $obj->navigation($obj->LoadItems['CatalogPage'][$obj->category]['parent_to'],$category_name);
    else $obj->navigation($data['category'],$category_name);

    $obj->set('pageContent',$disp);
}

function pagemeta_hook($obj) {
    global $PHPShopSystem;
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default')
        $obj->set('pageTitl',$obj->get('pageTitle')." - ".$PHPShopSystem->getValue('title'));
}

$addHandler=array(
        'index'=>'index_hook',
        'ListPage'=>'listpage_hook',
        'meta'=>'pagemeta_hook'
);

?>