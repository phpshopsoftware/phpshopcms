<?php

/**
 * ������� ������ ���������� ������� ��������� @showcase@
 */
class AddToTemplate extends PHPShopProductElements {

    var $debug = false;

    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::__construct();
    }

    function showcase() {

        // �������� �� ������
        if ($this->PHPShopNav->index()) {

            // ������ ������
            $template = 'product_showcase';
            $this->SysValue['templates']['product_showcase'] = 'element/' . $template . '.tpl';

            // ���������� ����� ��� ������ ������
            $cell = 1;

            // ���-�� ������� �� ��������
            $limit = 1;

            // �������� ������
            //$where['id']=$this->setramdom($limit);
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";

            $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $limit));

            // ��������� � ������ ������ � ��������
            $this->product_grid($this->dataArray, $cell, $template, $line = false);

            // �������� � ���������� ������� � ��������
            $this->set('showcase', $this->compile());
        }
    }

}

/**
 * ������� ������ ������ visualcart � ����� @visualcart@ �� �������� �� �������� ������.
 */
class AddVisualCartModuleToTemplate extends PHPShopProductElements {

    var $debug = false;

    function __construct($par = false) {
        // ���� ������� ������ ���������� ������� ������� �� � ����������
        if ($par instanceof AddToTemplateVisualCart)
            $GLOBALS['SysValue']['other']['visualcart'] = $par->cart;
        parent::__construct();
    }

}

// ��������� � ������ ������� ������ ���������� ������ � ������
$AddToTemplate = new AddToTemplate();
$AddToTemplate->showcase();

// ���������� �������
new AddVisualCartModuleToTemplate(@$AddToTemplateVisualCart);

// ������� ��������� ��� ������ IE.
function getBrowser($agent = false) {

    if (empty($agent))
        $agent = $_SERVER["HTTP_USER_AGENT"];

    preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // ���������� ���������, ������� ��������� ����������� 90% ���������
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