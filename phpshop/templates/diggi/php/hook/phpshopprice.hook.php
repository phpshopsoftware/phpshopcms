<?php

function template_pricecore_product_hook($obj, $category, $rout) {
    if ($rout == 'START') {
        $dis = null;

        // 404
        if (!PHPShopSecurity::true_num($category))
            return $obj->setError404();

        // дополнительные категории
        if (is_numeric($category))
            $str = " (category=$category or dop_cat LIKE '%#$category#%') and ";
        else
            $str = "";

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->sql = "select * from " . $PHPShopOrm->objBase . " where " . $str . " enabled='1' and parent_enabled='0' ORDER BY num LIMIT " . $obj->limit;
        $PHPShopOrm->debug = $obj->debug;
        $data = $PHPShopOrm->select();
        
        if (!empty($obj->category_name))
            $dis = $obj->tr(false, $obj->category_name);

        if ($obj->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId']))
            $user_price_activate = true;


        // Добавляем в дизайн ячейки с товарами
        if (is_array($data))
            foreach ($data as $row) {
                $name = '<a href="' . $obj->seourl($row) . '" class="list-group-item">' . $row['name'] ;
                if (empty($row['sklad']) and empty($user_price_activate))
                    $cart = '<button class="btn btn-default btn-xs  addToCartList" data-uid="' . $row['id'] . '" role="button">'.$obj->lang('product_sale').'</button>';
                if (empty($user_price_activate))
                    $price = $obj->price($row) . ' <span class="rubznak">' . $obj->currency().'</span>';
                else
                    $price = PHPShopText::a('../users/register.html', PHPShopText::img('images/shop/icon_user.gif', false, 'absMiddle'), $obj->lang('user_register_title'));

                
                

                $dis.=$name.'<b class="pull-right">'.$price.'</b></a>';
            }
 
        $obj->add($dis, true);
        return true;
    }
}

function template_pricecore_CAT_hook($obj, $data, $rout) {

    if ($rout == 'START') {

        $obj->category = $GLOBALS['SysValue']['nav']['page'];

        // Выбор каталога
        $obj->category_select();


        // Если выбрана опция вывести все
        if ($obj->category == 'ALL' or $GLOBALS['SysValue']['nav']['id'] == 'ALL') {

            if(is_array($obj->category_array))
            foreach ($obj->category_array as $key => $val) {
                $dis = '<div class="list-group"><span class="list-group-item list-group-item-success">' . $val . '</span>';
                $obj->add($dis, true);
                $obj->product($key);
            }
        } else {

            $obj->product($obj->category);
        }
        

        $obj->set('PageCategory', $obj->category);


        // Подключаем шаблон
        $obj->parseTemplate($obj->getValue('templates.price_page_list'));
        return true;
    }
}

function template_category_select_hook($obj) {
    $catdrop = '<li role="presentation"><a role="menuitem" tabindex="-1" href="/price/CAT_SORT_ALL.html">Все каталоги</a></li>';
    if (is_array($obj->category_array))
        foreach ($obj->category_array as $k => $v) {
            $catdrop.='<li role="presentation"><a role="menuitem" tabindex="-1" href="/price/CAT_SORT_' . $k . '.html">' . $v . '</a></li>';
        }
    $obj->set('searchPageCategoryDrop', $catdrop);
}

$addHandler = array
    (
    'product' => 'template_pricecore_product_hook',
    'CAT' => 'template_pricecore_CAT_hook',
    'category_select'=>'template_category_select_hook'
);
?>
