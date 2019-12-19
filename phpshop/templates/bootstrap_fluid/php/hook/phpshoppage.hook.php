<?php

function ListPage_template_hook($obj, $dataArray) {
    $disp=null;

    // Если есть описание каталога
    if (!empty($obj->LoadItems['CatalogPage'][$obj->category]['content_enabled'])) {
        if ($obj->page < 2)
            $disp.= '<div class="col-xs-12">' . $obj->PHPShopCategory->getContent() . '</div>';
        elseif ($obj->page > 1 and $obj->content_in_paginator)
            $disp.= '<div class="col-xs-12">' . $this->PHPShopCategory->getContent() . '</div>';
    }


    $disp.=
            '<div class="col-xs-12">';

    if (is_array($dataArray))
        foreach ($dataArray as $val)
            $disp.='<a href="/page/' . $val['link'] . '.html">
        <div class="panel panel-default">
            <div class="panel-body">
                ' . $val['name'] . '
            </div>
        </div>
    </a>';


    $disp.='</div>';

    $obj->set('pageContent', $disp);
}

function ListCategory_template_hook($obj, $dataArray) {
    $disp=null;

    // Если есть описание каталога
    if (!empty($obj->LoadItems['CatalogPage'][$obj->category]['content_enabled']))
        $disp.= '<div class="col-xs-12">' . $obj->PHPShopCategory->getContent() . '</div>';

    $disp.=
            '<div class="col-xs-12">';

    if (is_array($dataArray))
        foreach ($dataArray as $val)
            $disp.='<a href="/page/CID_' . $val['id'] . '.html">
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
    'ListCategory' => 'ListCategory_template_hook',
    'ListPage' => 'ListPage_template_hook'
);
?>