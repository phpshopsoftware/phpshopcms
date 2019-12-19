<?php

/**
 * Вывод иконок распродажи и спецпредложений в кратком описании товаров.
 */
function phpshopproductelements_product_grid_nt_hook($obj, $dataArray) {
    
    
    // Спецпредложения
    if($dataArray['spec'])
        $obj->set('specIcon', ParseTemplateReturn('product/specIcon.tpl'));
    else
        $obj->set('specIcon', '');
    
    // Новинки
    if($dataArray['newtip'])
        $obj->set('newtipIcon', ParseTemplateReturn('product/newtipIcon.tpl'));
    else
        $obj->set('newtipIcon', '');
    
}

/**
 * Добавление в список каталогов спецпредложения товаров в 3 ячейки, лимит 3
 */
$addHandler = array
    (
    'product_grid' => 'phpshopproductelements_product_grid_nt_hook',
);
?>