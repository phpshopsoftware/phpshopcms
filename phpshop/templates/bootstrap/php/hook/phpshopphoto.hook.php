<?php



function ListCategory_template_hook($obj, $dataArray) {
    $disp=null;

    // Если есть описание каталога
    if (!empty($obj->LoadItems['CatalogPhoto'][$obj->category]['content_enabled']))
        $disp.= '<div class="col-xs-12">' . $obj->PHPShopPhotoCategory->getContent() . '</div>';

    $disp.=
            '<div class="col-xs-12">';

    if (is_array($dataArray))
        foreach ($dataArray as $val)
            $disp.='<a href="/photo/CID_' . $val['id'] . '.html">
        <div class="panel panel-default">
            <div class="panel-body">
                ' . $val['name'] . '
            </div>
        </div>
    </a>';


    $disp.='</div>';

    $obj->set('pageContent', $disp);
}

$addHandler = array
    (
    'ListCategory' => 'ListCategory_template_hook'
);
?>