<?php

function searchpage_localsearch_hook($obj) {

    // Поддержка PHP 5.4
    if (!empty($obj) and is_array($obj))
        $obj = &$obj[0];

    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.table_name11'));
    $PHPShopOrm->debug = $obj->debug;
    $dataArray = $PHPShopOrm->select(array('*'), array('content_locale' => " LIKE '%" . $obj->words . "%'", "enabled" => "!='0'"), array('order' => 'link'), array('limit' => 100));
    if (is_array($dataArray))
        foreach ($dataArray as $row) {

            // Определяем переменные
            $obj->set('productName', $row['name_locale']);
            $obj->set('pageWords', $this->words);
            $obj->set('productKey', substr(strip_tags($row['content_locale']), 0, 300) . "...");
            $obj->set('pageLink', $row['link'] . ".html");
            $i++;
            $obj->set('productNum', $i);

            if ($j == 0) {
                $obj->set('pageTitle', $obj->PHPShopSystem->getParam('name') . ' / ' . __('Страницы'));
                $obj->set('pageNumN', __("Результат") . " " . __('страниц') . " - " . count($dataArray));
            } else {
                $obj->set('pageTitle', false);
                $obj->set('pageNumN', false);
            }

            $j++;

            // Подключаем шаблон
            $obj->addToTemplate($obj->getValue('templates.main_search_forma'));
        }
}

function searchnews_localsearch_hook($obj) {

    // Поддержка PHP 5.4
    if (!empty($obj) and is_array($obj))
        $obj = &$obj[0];

    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.table_name8'));
    $PHPShopOrm->debug = $obj->debug;
    $PHPShopOrm->Option['where'] = " or ";
    $dataArray = $PHPShopOrm->select(array('*'), array('description_news_locale' => " LIKE '%" . $obj->words . "%'", 'title_news_locale' => " LIKE '%" . $obj->words . "%'",
        'content_news_locale' => " LIKE '%" . $obj->words . "%'"), array('order' => 'id desc'), array('limit' => 100));
    if (is_array($dataArray))
        foreach ($dataArray as $row) {

            // Определяем переменные
            $obj->set('productName', $row['title_news_locale']);
            $obj->set('pageWords', $obj->words);
            $obj->set('productKey', substr(strip_tags($row['description_news_locale']), 0, 300) . "...");
            $obj->set('pageLink', "ID_" . $row['id'] . ".html");
            $i++;
            $obj->set('productNum', $i);

            if ($j == 0) {
                $obj->set('pageTitle', $obj->PHPShopSystem->getParam('name') . ' / ' . __('Новости'));
                $obj->set('pageNumN', __("Результат") . ": " . __('страниц') . " - " . count($dataArray));
            } else {
                $obj->set('pageTitle', false);
                $obj->set('pageNumN', false);
            }

            $j++;

            // Подключаем шаблон
            $obj->addToTemplate($obj->getValue('templates.main_search_forma'));
        }
}

function searchpage_locale_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE')
        if (isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

            if (!empty($row['content_locale']))
                $obj->set('productKey', substr(strip_tags($row['content_locale']), 0, 300) . "...");
            if (!empty($row['name_locale']))
                $obj->set('productName', $row['name_locale']);
        }

    if ($rout == 'END')
        searchpage_localsearch_hook(array(&$obj));
}

function searchnews_locale_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE')
        if (isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

            if (!empty($row['content_news_locale']))
                $obj->set('productKey', substr(strip_tags($row['content_news_locale']), 0, 300) . "...");
            if (!empty($row['title_news_locale']))
                $obj->set('productName', $row['title_news_locale']);
        }

    if ($rout == 'END')
        searchnews_localsearch_hook(array(&$obj));
}

$addHandler = array(
    'searchpage' => 'searchpage_locale_hook',
    'searchnews' => 'searchnews_locale_hook'
);
?>
