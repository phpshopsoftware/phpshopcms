<?php

/**
 * ����� ������ ���������� � ��������������� � ������� �������� �������.
 */
function phpshopshopcore_product_grid_nt_hook($obj, $dataArray) {
    
    // ���������������
    if (!empty($dataArray['spec']))
        $obj->set('specIcon', ParseTemplateReturn('product/specIcon.tpl'));
    else
        $obj->set('specIcon', '');

    // �������
    if (!empty($dataArray['newtip']))
        $obj->set('newtipIcon', ParseTemplateReturn('product/newtipIcon.tpl'));
    else
        $obj->set('newtipIcon', '');
    
}


$addHandler = array
    (
    'product_grid' => 'phpshopshopcore_product_grid_nt_hook',
);
?>