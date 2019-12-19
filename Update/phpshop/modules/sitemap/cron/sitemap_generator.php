<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

function sitemaptime($nowtime) {
    return PHPShopDate::dataV($nowtime, false, true);
}


// Библиотека
$title = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$title.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";

// Страницы
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
$data = $PHPShopOrm->select(array('*'),array('enabled'=>"!='0'",'category'=>'!=2000'),array('order'=>'date DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_pages.= '<url>' . "\n";

        // Проверка модуля Seourl
        if($PHPShopModules->getParam("base.seourl.seourl_system") != "")
            $stat_pages.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/'.$row['link'].'.html</loc>' . "\n";
        else $stat_pages.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/page/'.$row['link'].'.html</loc>' . "\n";

        $stat_pages.= '<lastmod>'.sitemaptime($row['date']).'</lastmod>' . "\n";
        $stat_pages.= '<changefreq>daily</changefreq>' . "\n";
        $stat_pages.= '<priority>1.0</priority>' . "\n";
        $stat_pages.= '</url>' . "\n";
    }

// Каталоги
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
$data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_cat.= '<url>' . "\n";

        // Проверка модуля Seourl
        if($PHPShopModules->getParam("base.seourl.seourl_system") != "")
            $stat_cat.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/cat/'.$row['seoname'].'.html</loc>' . "\n";
        else $stat_cat.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/page/CID_'.intval($row['id']).'.html</loc>' . "\n";

        $stat_cat.= '<changefreq>daily</changefreq>' . "\n";
        $stat_cat.= '<priority>1.0</priority>' . "\n";
        $stat_cat.= '</url>' . "\n";
    }

// Новости
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
$data = $PHPShopOrm->select(array('*'),false,array('order'=>'date DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_news.= '<url>' . "\n";

        // Проверка модуля Seourl
        if($PHPShopModules->getParam("base.seourl.seourl_system") != "")
            $stat_news.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/news/ID_'.strip_tags($row['seo_name']).'.html</loc>' . "\n";
        else  $stat_news.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/news/ID_'.intval($row['id']).'.html</loc>' . "\n";

        $stat_news.= '<lastmod>'.sitemaptime(PHPShopDate::GetUnixTime($row['date'])).'</lastmod>' . "\n";
        $stat_news.= '<changefreq>daily</changefreq>' . "\n";
        $stat_news.= '<priority>1.0</priority>' . "\n";
        $stat_news.= '</url>' . "\n";
    }

$sitemap=$title.$stat_pages.$stat_cat.$stat_news.'</urlset>';


// Запись в файл
fwrite(fopen('../../../../UserFiles/File/sitemap.xml',"w+"), $sitemap);

echo "Sitemap.xml done!";
?>