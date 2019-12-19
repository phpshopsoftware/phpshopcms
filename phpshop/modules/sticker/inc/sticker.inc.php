<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopStickerElement {

    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['sticker']['sticker_forms'];
    }

    function init() {
        $url = $_SERVER['REQUEST_URI'];
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url = $url['path'];

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), array("enabled" => "='1'"), false, array("limit" => 100));

        if (is_array($data)) {
            foreach ($data as $row) {
                
                $row['content']=str_replace('&#43;', '+',$row['content']);

                // Если несколько страниц
                if (strpos($row['dir'], ',')) {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if ($dir == $url) {
                            $GLOBALS['SysValue']['other']['sticker_' . $row['path']] = Parser($row['content']);
                        }
                }
                // Если одна страница
                elseif ($row['dir'] == $url) {
                    $GLOBALS['SysValue']['other']['sticker_' . $row['path']] = Parser($row['content']);
                }
                // Если нет привязки
                elseif (empty($row['dir'])) {
                    $GLOBALS['SysValue']['other']['sticker_' . $row['path']] = Parser($row['content']);
                }
            }
        }
    }

    function forma($path) {

        $path = PHPShopSecurity::TotalClean($path, 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), array('path' => "='" . $path . "'", 'enabled' => "='1'"), false, array('limit' => 1));

        if (is_array($data)) {
            return Parser(str_replace('&#43;', '+',$data['content']));
        }
        else
            return 'Стикер не найден в базе';
    }

}

$PHPShopStickerElement = new PHPShopStickerElement();
$PHPShopStickerElement->init();
?>