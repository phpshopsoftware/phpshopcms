<?php

/**
 * Элемент вывода случайного товарав перменную @showcase@
 */
class AddToTemplate extends PHPShopProductElements {

    var $debug = false;

    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::__construct();
    }

    function showcase() {

        // Проверка на индекс
        if ($this->PHPShopNav->index()) {

            // Шаблон ячейки
            $template = 'product_showcase';
            $this->SysValue['templates']['product_showcase'] = 'element/' . $template . '.tpl';

            // Количество ячеек для вывода товара
            $cell = 1;

            // Кол-во товаров на странице
            $limit = 1;

            // Случаные товары
            //$where['id']=$this->setramdom($limit);
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";

            $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $limit));

            // Добавляем в дизайн ячейки с товарами
            $this->product_grid($this->dataArray, $cell, $template, $line = false);

            // Собираем и возвращаем таблицу с товарами
            $this->set('showcase', $this->compile());
        }
    }

}

/**
 * Элемент вывода модуля visualcart в метку @visualcart@ не зависимо от натсроек модуля.
 */
class AddVisualCartModuleToTemplate extends PHPShopProductElements {

    var $debug = false;

    function __construct($par = false) {
        // если включён модуль визуальной корзины выводим ее в переменную
        if ($par instanceof AddToTemplateVisualCart)
            $GLOBALS['SysValue']['other']['visualcart'] = $par->cart;
        parent::__construct();
    }

}

// Добавляем в шаблон элемент вывода случайного товара в индекс
$AddToTemplate = new AddToTemplate();
$AddToTemplate->showcase();

// визуальная корзина
new AddVisualCartModuleToTemplate(@$AddToTemplateVisualCart);

// выводим сообщение для старых IE.
function getBrowser($agent = false) {

    if (empty($agent))
        $agent = $_SERVER["HTTP_USER_AGENT"];

    preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // регулярное выражение, которое позволяет отпределить 90% браузеров
    list(, $browser, $version) = $browser_info;
    if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera))
        return 'Opera ' . $opera[1];
    if ($browser == 'MSIE') {
        preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
        if ($ie)
            return $ie[1] . ' based on IE ' . $version;
        return 'IE ' . $version;
    }
    if ($browser == 'Firefox') {
        preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
        if ($ff)
            return $ff[1] . ' ' . $ff[2];
    }
    if ($browser == 'Opera' && $version == '9.80')
        return 'Opera ' . substr($agent, -5);
    if ($browser == 'Version')
        return 'Safari ' . $version;
    if (!$browser && strpos($agent, 'Gecko'))
        return 'Browser based on Gecko';
    return $browser . ' ' . $version;
}

$browser = null;
$getBrowser = getBrowser();
if (strstr($getBrowser, 'IE 5'))
    $browser = 'ie5';
elseif (strstr($getBrowser, 'IE 6'))
    $browser = 'ie6';
elseif (strstr($getBrowser, 'IE 7'))
    $browser = 'ie7';
if (!empty($browser) and empty($_GET['SERVER_NAME'])) {
    $GLOBALS['SysValue']['other']['oldBrowserMessage'] = ParseTemplateReturn('main/old_browser_mess.tpl');
}


?>