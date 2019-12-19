<?php

$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);

function output_handler($xml) {
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . strlen($xml));
    header('Content-type: text/xml; charset=windows-1251');
    return $xml;
}

$data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 15));

ob_start("output_handler");
echo '<?xml version="1.0" encoding="windows-1251" ?>' . "\n";
echo '<rss version="2.0">' . "\n";
echo '        <channel>' . "\n";
echo '                <title>RSS Новости - ' . $PHPShopSystem->getParam('title') . '</title>' . "\n";
echo '                <description>RSS Новости от ' . $PHPShopSystem->getParam('company') . '</description>' . "\n";
echo '                <link>http://' . $_SERVER['SERVER_NAME'] . '</link>' . "\n";
echo '                <language>ru</language>' . "\n";
echo '                <generator>PHPShop</generator>' . "\n";

foreach ($data as $row) {
    echo '                <item>' . "\n";
    echo '                        <title>' . trim($row['title']) . '</title>' . "\n";

    // Проверка модуля Seourl
    if (!empty($row['seo_name']))
        echo '                        <link>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . $row["seo_name"] . '.html</link>' . "\n";
    else
        echo '                        <link>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . $row["id"] . '.html</link>' . "\n";
    echo '                        <pubDate>' . $row['date'] . '</pubDate>' . "\n";
    echo '                        <description><![CDATA[' . trim($row['description']) . ']]></description>' . "\n";
    echo '                        <author>' . $PHPShopSystem->getName() . '</author>' . "\n";
    echo '                </item>' . "\n";
}
echo '        </channel>' . "\n";
echo '</rss>';
?>