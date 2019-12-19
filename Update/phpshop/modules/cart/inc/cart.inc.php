<?php

// Настройки
$GLOBALS['_ADMIN']['dir'] = "./UserFiles/Files/";

// Парсируем переменные
$url = parse_url("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
@$Query = $url["query"];
$QueryArray = parse_str($Query, $output);

// Корзина
class PHPShopCartElement extends PHPShopElements {

    function __construct() {
        $this->debug = false;
        parent::PHPShopElements();
    }

    // Вывод корзины
    function miniCart() {
        $sum = 0;
        $num = 0;
        if (!empty($_SESSION['CART']) and is_array($_SESSION['CART']))
            foreach ($_SESSION['CART'] as $key => $val) {

                $sum+=$val['price'] * $val['num'];
                $num+=$val['num'];
            }

        $dis = "В корзине: <strong>" . $num . "</strong> шт.<br>";
        $dis.= "Сумма: <strong>" . $sum . "</strong> " . $GLOBALS['LoadItems']['modules']['cart']['valuta'] . "<br>";

        if ($num > 0)
            $dis.= '<p>
	  <form method="get" action="/order/">
	  <input type="submit" value="Оформить заказ" class="btn btn-primary">
	  </form></p>';
        else
            $dis.= '<p>
	  <form method="get" action="/price/">
	  <input type="submit" value="Прайс-лист" class="btn btn-primary">
	  </form></p>';

        $this->set('leftMenuName', "Корзина");
        $this->set('leftMenuContent', $dis);

        return $this->parseTemplate($this->getValue('templates.right_menu'));
    }

}

// Чтение данных из CSV товаров
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

// Чтение данных из CSV каталогов
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

// База товаров
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
	  <input type="submit" value="В корзину"  height="10" title="Добавить 1 шт.">
	  </form>';
    }

    function getForma() {
        global $PHPShopSystem;
        if (isset($this->productObj['name']))
            echo
            "Наименование: <strong>" . $this->getName() . "</strong><br>
	  Артикул: <strong>" . $this->getArt() . "</strong><br>
	  Стоимость: <strong>" . $this->getPrice() . " " . $GLOBALS['LoadItems']['modules']['cart']['valuta'] . "</strong><br>
	  " . $this->getCart();
    }

}

// Настройки модуля
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

// Настройки модуля
$PHPShopCartArray = new PHPShopCartArray();
$GLOBALS['LoadItems']['modules']['cart'] = $PHPShopCartArray->getArray();

// Читаем базу товаров
if (!empty($GLOBALS['LoadItems']['modules']['cart']['filedir']) and speed_check()) {
    $DB = $GLOBALS['_ADMIN']['dir'] . $GLOBALS['LoadItems']['modules']['cart']['filedir'];
    $ProductCsv = new ProductCsv($DB);
    $GLOBALS['_PRODUCT'] = $ProductCsv->CreatBase();
}

// Читаем базу каталогов
if (!empty($GLOBALS['LoadItems']['modules']['cart']['catdir']) and speed_check()) {
    $DB2 = $GLOBALS['_ADMIN']['dir'] . $GLOBALS['LoadItems']['modules']['cart']['catdir'];
    $CatalogCsv = new CatalogCsv($DB2);
    $GLOBALS['_CATALOG'] = $CatalogCsv->CreatBase();
}


if (empty($output['num']))
    $output['num'] = 1;

// Считаем корзину
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



// Вывод Мини корзины
$PHPShopCartElement = new PHPShopCartElement();

// Убираем корзину в заказе
if ($PHPShopNav->getPath() != "order")
    if ($GLOBALS['LoadItems']['modules']['cart']['enabled']) {

        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopCartElement->miniCart();
    }
    else
        $PHPShopCartElement->init('miniCart');
?>