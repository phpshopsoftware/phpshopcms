<?php

function template_v($obj, $data, $rout) {
    if ($rout == 'START') {

        // —ортировка по характеристикам сохран€ем значени€
        if (is_array($_GET['v'])) {
            foreach ($_GET['v'] as $k => $v)
                $productVendor.='v[' . intval($k) . ']=' . intval($v) . '&';
            $productVendor = substr($productVendor, 0, strlen($productVendor) - 1);
        }
        if ($productVendor)
            $obj->set('productVendor', $productVendor);

        if (empty($_GET['gridChange']))
            $obj->cell = 3;

        $obj->sort_template = 'sorttemplatehook';

        switch ($_GET['gridChange']) {
            case 1:
                $obj->set('gridSetAactive', 'active');
                break;
            case 2:
                $obj->set('gridSetBactive', 'active');
                break;
            default: $obj->set('gridSetBactive', 'active');
        }


        switch ($_GET['s']) {
            case 1:
                $obj->set('sSetAactive', 'active');
                break;
            case 2:
                $obj->set('sSetBactive', 'active');
                break;
            default: $obj->set('sSetCactive', 'active');
        }


        switch ($_GET['f']) {
            case 1:
                $obj->set('fSetAactive', 'active');
                break;
            case 2:
                $obj->set('fSetBactive', 'active');
                break;
            //default: $obj->set('fSetCactive', 'active');
        }
    }
}

$addHandler = array
    (
    'v' => 'template_v'
);
?>
