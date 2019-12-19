<?php

function template_CID_Product($obj, $data, $rout) {
    if ($rout == 'START') {

        // Фасетный фильтр
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
            //default: $obj->set('fSetAactive', 'active');
        }
    }
}

/**
 * Вывод подтипов в подробном описании 
 */
function template_parent($obj, $dataArray, $rout) {

    if ($rout == 'END') {
        
        if (count($obj->select_value > 0)) {
            $obj->set('parentList', '');
            
            foreach ($obj->select_value as $value) {
                $obj->set('parentName', $value[0]);
                $obj->set('parentId', $value[1]);


                $obj->set('parentCheckedId', $value[1]);


                $disp = ParseTemplateReturn("product/product_odnotip_product_parent_one.tpl");
                $obj->set('parentList', $disp, true);
            }
            $obj->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
        }
    }
}

function template_UID($obj, $dataArray, $rout) {
    if ($rout == 'MIDDLE') {
        if ($obj->get('optionsDisp') != '' and $obj->get('parentList') == '') {
            //$obj->set('ComStart','<!--');
            $obj->set('ComStartCart', '<!--');
            $obj->set('ComEndCart', '-->');
            //$obj->set('ComEnd','-->');
            $obj->set('optionsDisp', ParseTemplateReturn("product/product_option_product.tpl"));
        }

        //$obj->set('brandUidDescription',str_replace('href','href="#" data-url',$GLOBALS['SysValue']['other']['brandUidDescription']));
    }
}

/**
 * Шаблон вывода характеристик
 */
function sorttemplatehook($value, $n, $title, $vendor) {
    $disp = null;

    if (is_array($value)) {
        foreach ($value as $p) {

            $text = $p[0];
            $checked = null;
            if (is_array($vendor)) {
                foreach ($vendor as $v) {
                    if (is_array($v))
                        foreach ($v as $s)
                            if ($s == $p[1])
                                $checked = 'checked';
                }
            }

            // Определение цвета
            if ($text[0] == '#')
                $text = '<div class="filter-color" style="background:' . $text . '"></div>';

            $disp.= '<div class="checkbox">
  <label>
    <input type="checkbox" value="1" name="' . $n . '-' . $p[1] . '" ' . $checked . ' data-url="v[' . $n . ']=' . $p[1] . '"  data-name="' . $n . '-' . $p[1] . '">
    <span class="filter-item"  title="' . $p[0] . '">' . $text . '</span>
  </label>
</div>';
        }
    }
    return '<h4>' . $title . '</h4>' . $disp;
}

/**
 *  Фотогалерея
 */
function template_image_gallery($obj, $array) {

    $bxslider = $bxsliderbig = $bxpager = null;
    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.foto'));
    $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $array['id']), array('order' => 'num'), array('limit' => 100));
    $i = 0;
    $s = 1;

    if (is_array($data)) {

        // Сортировка
        foreach ($data as $k => $v) {

            if ($v['name'] == $array['pic_big'])
                $sort_data[0] = $v;
            else
                $sort_data[$s] = $v;

            $s++;
        }

        ksort($sort_data);

        foreach ($sort_data as $k => $row) {
            $name = $row['name'];
            $name_s = str_replace(".", "s.", $name);
            $name_bigstr = str_replace(".", "_big.", $name);

            // Подбор исходного изображения
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $name_bigstr))
                $name_bigstr = $name;

            $bxslider.= '<div><a class href="#"><img src="' . $name . '" /></a></div>';
            $bxsliderbig.= '<li><a class href=\'#\'><img src=\'' . $name_bigstr . '\'></a></li>';
            $bxpager.='<a data-slide-index=\'' . $i . '\' href=\'\'><img class=\'img-thumbnail\'  src=\'' . $name_s . '\'></a>';
            $i++;
        }


        if ($i < 2)
            $bxpager = null;


        $obj->set('productFotoList', '<img class="bxslider-pre" alt="' . $array['name'] . '" src="' . $array['pic_big'] . '" /><div class="bxslider hide">' . $bxslider . '</div><div class="bx-pager">' . $bxpager . '</div>');
        $obj->set('productFotoListBig', '<ul class="bxsliderbig" data-content="' . $bxsliderbig . '" data-page="' . $bxpager . '"></ul><div class="bx-pager-big">' . $bxpager . '</div>');
        return true;
    }
}

$addHandler = array
    (
    'CID_Product' => 'template_CID_Product',
    'parent' => 'template_parent',
    'UID' => 'template_UID',
    'image_gallery' => 'template_image_gallery'
);
?>