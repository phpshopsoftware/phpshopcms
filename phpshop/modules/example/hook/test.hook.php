<?php

// Имя исполняемого файла и входящие переменные
function hook_message($fun,$row=false) {

    $content=null;
    if(!empty($row)) {
        ob_start();
        echo '<p>';
        print_r($row);
        echo '</p>';
        $content=ob_get_clean();
        $content=PHPShopText::message(PHPShopText::h3('Var dump').$content);
    }

    return PHPShopText::message('Example Hook -> '.$fun.'() -> '.__FILE__.$content);
}



function rating_hook($obj,$row) {

    // Переназначаем переменную
    $obj->set('ratingfull',hook_message(__FUNCTION__,$row=false));

    // Прерываем дальнейшее выполнение перехватыемой функции
    return true;
}

function image_gallery_hook($obj,$row) {
    $obj->set('productFotoList',hook_message(__FUNCTION__,$row=false));
    return true;
}

function option_select_hook($obj,$row) {
    $obj->set('productFotoList',hook_message(__FUNCTION__,$row=false));
    return true;
}

function product_uid_hook($obj,$row,$rout) {

    /* Полный перехват подробного описания товара
    if($rout == 'START')
        return true;
    */

    if($rout == 'MIDDLE') {
        $obj->set('productDes',hook_message(__FUNCTION__,$row=false));
        $obj->set('productName','Hook Name');
    }
}

function odnotip_test_hook($obj,$row,$rout) {
    // Обработка в начале функции
    if($rout == 'START') {

        $obj->set('specMainTitle','Hook');
        $obj->set('specMainIcon',hook_message(__FUNCTION__,$row=false));
        return true;
    }

}

function parent_hook($obj,$row,$rout) {

    // Обработка в начале функции
    if($rout == 'END') {
        $obj->set('productParentList',hook_message(__FUNCTION__,$row=false));
    }

}

function set_meta_hook($obj) {
    $obj->title='Hook -> '.__FUNCTION__.'() -> '.__FILE__;
    return true;

}

function article_hook($obj,$row,$rout) {

    // Обработка в начале функции
    if($rout == 'START') {
        $obj->set('pagetemaDisp',hook_message(__FUNCTION__,$row=false));
        return true;
    }
}

function sort_table_hook($obj,$row) {
    $obj->set('vendorDisp',hook_message(__FUNCTION__,$row=false));
    return true;
}

function product_grid_hook($obj,$row) {
    $obj->set('productName',__FUNCTION__.'()');
}

function setpaginator_hook($obj) {
    $obj->set('productPageNav',hook_message(__FUNCTION__,$row=false));
    return true;
}

function price_hook() {
    return 'Hook';
}

function checkstore_hook($obj,$row,$rout) {
    if($rout == 'END') {
        $obj->set('productValutaName','h.');
        $obj->set('productSklad',$obj->lang('product_on_sklad')." hook ".$obj->lang('product_on_sklad_i'));

    }
}

$addHandler=array
(
        'index'=>'index_hook',
        'rating'=>'rating_hook',
        'image_gallery'=>'image_gallery_hook',
        'option_select'=>'option_select_hook',
        'uid'=>'product_uid_hook',
        'odnotip'=>'odnotip_test_hook',
        'set_meta'=>'set_meta_hook',
        'parent'=>'parent_hook',
        'article'=>'article_hook',
        'sort_table'=>'sort_table_hook',
        'product_grid'=>'product_grid_hook',
        'setpaginator'=>'setpaginator_hook',
        'price'=>'price_hook',
        'checkstore'=>'checkstore_hook'
);


?>