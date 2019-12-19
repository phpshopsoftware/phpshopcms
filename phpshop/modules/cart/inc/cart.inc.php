<?php

// ���������
$GLOBALS['_ADMIN']['dir'] = "./UserFiles/Files/";

// ��������� ����������
$url = parse_url("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
@$Query = $url["query"];
$QueryArray = parse_str($Query, $output);

// �������
class PHPShopCartElement extends PHPShopElements {

    function __construct() {
        $this->debug = false;
        parent::PHPShopElements();
    }

    // ����� �������
    function miniCart() {
        $sum = 0;
        $num = 0;
        if (!empty($_SESSION['CART']) and is_array($_SESSION['CART']))
            foreach ($_SESSION['CART'] as $key => $val) {

                $sum+=$val['price'] * $val['num'];
                $num+=$val['num'];
            }

        $dis = "� �������: <strong>" . $num . "</strong> ��.<br>";
        $dis.= "�����: <strong>" . $sum . "</strong> " . $GLOBALS['LoadItems']['modules']['cart']['valuta'] . "<br>";

        if ($num > 0)
            $dis.= '<p>
	  <form method="get" action="/order/">
	  <input type="submit" value="�������� �����" class="btn btn-primary">
	  </form></p>';
        else
            $dis.= '<p>
	  <form method="get" action="/price/">
	  <input type="submit" value="�����-����" class="btn btn-primary">
	  </form></p>';

        $this->set('leftMenuName', "�������");
        $this->set('leftMenuContent', $dis);

        return $this->parseTemplate($this->getValue('templates.right_menu'));
    }

}

// ������ ������ �� CSV �������
PHPShopObj::loadClass("readcsv");

class ProductCsv extends PHPShopReadCsv {

    var $CsvToArray;

    function ProductCsv($file) {
        $this->CsvContent = parent::readFile($file);
        parent::PHPShopReadCsv();
    }

    function CreatBase() {
        $CsvToArray = $this->CsvToArray;
        foreach ($CsvToArray as $items) {
            $PRODUCT[$items[0]]['art'] = $items[1];
            $PRODUCT[$items[0]]['name'] = $items[2];
            $PRODUCT[$items[0]]['price'] = $items[3];
            $PRODUCT[$items[0]]['catalog'] = trim($items[4]);
        }
        return $PRODUCT;
    }

}

// ������ ������ �� CSV ���������
class CatalogCsv extends PHPShopReadCsv {

    var $CsvToArray;

    function CatalogCsv($file) {
        $this->CsvContent = parent::readFile($file);
        parent::PHPShopReadCsv();
    }

    function CreatBase() {
        $CsvToArray = $this->CsvToArray;
        $CAT = array();
        if (is_array($CsvToArray))
            foreach ($CsvToArray as $items) {
                $CAT[$items[0]]['name'] = $items[1];
                $CAT[$items[0]]['id'] = $items[0];
            }
        return $CAT;
    }

}

// ���� �������
class ProductDisp {

    var $productID;
    var $productObj;

    function ProductDisp($productID) {
        $this->productID = $productID;
        $this->productObj = $GLOBALS['_PRODUCT'][$productID];
        $this->getForma();
    }

    function getId() {
        return $this->productID;
    }

    function getPrice() {
        return $this->productObj['price'];
    }

    function getName() {
        return $this->productObj['name'];
    }

    function getArt() {
        return $this->productObj['art'];
    }

    function getCart() {
        return '
	  <form method="get"> 
	  <input type="hidden" name="item" value="' . $this->productID . '">
	  <input type="submit" value="� �������"  height="10" title="�������� 1 ��.">
	  </form>';
    }

    function getForma() {
        global $PHPShopSystem;
        if (isset($this->productObj['name']))
            echo
            "������������: <strong>" . $this->getName() . "</strong><br>
	  �������: <strong>" . $this->getArt() . "</strong><br>
	  ���������: <strong>" . $this->getPrice() . " " . $GLOBALS['LoadItems']['modules']['cart']['valuta'] . "</strong><br>
	  " . $this->getCart();
    }

}

// ��������� ������
PHPShopObj::loadClass("array");

class PHPShopCartArray extends PHPShopArray {

    function PHPShopCartArray() {
        $this->objType = 3;
        $this->objBase = $GLOBALS['SysValue']['base']['cart']['cart_system'];
        parent::__construct("filedir", "catdir", "enabled", "email", "valuta", "enabled_market", "num", 'enabled_speed', 'enabled_search');
    }

}

function speed_check() {
    if (empty($GLOBALS['LoadItems']['modules']['cart']['enabled_speed']))
        return true;
    elseif ($GLOBALS['SysValue']['nav']['path'] == "price")
        return true;
    else
        return false;
}

// ��������� ������
$PHPShopCartArray = new PHPShopCartArray();
$GLOBALS['LoadItems']['modules']['cart'] = $PHPShopCartArray->getArray();

// ������ ���� �������
if (!empty($GLOBALS['LoadItems']['modules']['cart']['filedir']) and speed_check()) {
    $DB = $GLOBALS['_ADMIN']['dir'] . $GLOBALS['LoadItems']['modules']['cart']['filedir'];
    $ProductCsv = new ProductCsv($DB);
    $GLOBALS['_PRODUCT'] = $ProductCsv->CreatBase();
}

// ������ ���� ���������
if (!empty($GLOBALS['LoadItems']['modules']['cart']['catdir']) and speed_check()) {
    $DB2 = $GLOBALS['_ADMIN']['dir'] . $GLOBALS['LoadItems']['modules']['cart']['catdir'];
    $CatalogCsv = new CatalogCsv($DB2);
    $GLOBALS['_CATALOG'] = $CatalogCsv->CreatBase();
}


if (empty($output['num']))
    $output['num'] = 1;

// ������� �������
if (isset($output['item'])) {

    if (empty($_SESSION['CART'][$output['item']])) {
        $_SESSION['CART'][$output['item']] = $GLOBALS['_PRODUCT'][$output['item']];
        if (PHPShopSecurity::true_num($output['num']))
            $_SESSION['CART'][$output['item']]['num'] = $output['num'];
    }
    elseif (PHPShopSecurity::true_num($output['num']))
        $_SESSION['CART'][$output['item']]['num']+=$output['num'];
    else
        $_SESSION['CART'][$output['item']]['num'] = $_SESSION['CART'][$output['item']]['num'] + 1;
}



// ����� ���� �������
$PHPShopCartElement = new PHPShopCartElement();

// ������� ������� � ������
if ($PHPShopNav->getPath() != "order")
    if ($GLOBALS['LoadItems']['modules']['cart']['enabled']) {

        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopCartElement->miniCart();
    }
    else
        $PHPShopCartElement->init('miniCart');
?>