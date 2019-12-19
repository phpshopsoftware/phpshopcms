<?php

function template_index_cloud_hook($obj, $array) {
    $disp = null;

    if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system']))
        $seourl = true;
    else
        $seourl = false;

    foreach ($array as $key => $val) {
        if ($seourl)
            $disp.="<a href='/" . $val . ".html' class='btn btn-default btn-xs'>$key</a>";
        else
            $disp.="<a href='/page/" . $val . ".html' class='btn btn-default btn-xs'>$key</a>";
    }

    $obj->set('leftMenuContent', '<div class="product-tags">' . $disp . '</div>');
}

$addHandler = array
    (
    'index' => 'template_index_cloud_hook',
);
?>