<?php

/**
 * ����� ������ ���������� � ��������������� � ������� �������� �������.
 */
function phpshopproductelements_product_grid_nt_hook($obj, $dataArray) {
    
    
    // ���������������
    if($dataArray['spec'])
        $obj->set('specIcon', ParseTemplateReturn('product/specIcon.tpl'));
    else
        $obj->set('specIcon', '');
    
    // �������
    if($dataArray['newtip'])
        $obj->set('newtipIcon', ParseTemplateReturn('product/newtipIcon.tpl'));
    else
        $obj->set('newtipIcon', '');
    
}

/**
 * ���������� � ������ ��������� ��������������� ������� � 3 ������, ����� 3
 */
$addHandler = array
    (
    'product_grid' => 'phpshopproductelements_product_grid_nt_hook',
);
?>