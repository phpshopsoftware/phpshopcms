<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__)."/obj.class.php");

/**
 * Системные настройки
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopObj
 */
class PHPShopSystem extends PHPShopObj {

    /**
     * Конструктор
     */
    function __construct() {
        $this->objID=1;
        $this->install=false;
        $this->cache=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name3'];
        parent::__construct();
    }

    /**
     * Вывод имени сайта
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Вывод сериализованного значения [param.val]
     * @param string $param
     * @return string
     */
    function getSerilizeParam($param) {
        $param=explode(".",$param);
        $val=parent::unserializeParam($param[0]);
        return $val[$param[1]];
    }

    /**
     * Сравнение сериализованного значения [param.val]
     * @param string $param имя переменной
     * @param string $value значение переменной
     * @return bool
     */
    function ifSerilizeParam($param,$value=false) {
        if(empty($value)) $value=1;
        if($this->getSerilizeParam($param) == $value) return true;
    }

    /**
     * Вывод ИД валюты по умолчанию на сайте
     * @return int
     */
    function getDefaultValutaId() {
        return parent::getParam("dengi");
    }

    /**
     * Вывод курса валюты по умолчанию
     * @return float
     */
    function getDefaultOrderValutaId() {
        return parent::getParam("kurs");
    }

    /**
     * Вывод курса валюты по умочанию
     * @param bool $order валюта в заказе (true)
     * @return float
     */
    function getDefaultValutaKurs($order=false) {
        if(!class_exists("phpshopvaluta")) parent::loadClass("phpshopvaluta");
        if($order) $valuta_id = $this->getDefaultOrderValutaId();
        else $valuta_id = $this->getDefaultValutaId();
        $PV = new PHPShopValuta($valuta_id);

        return $PV->getKurs();
    }

    /**
     * Вывод ISO валюты по умочанию
     * @param bool $order валюта в заказе (true)
     * @return string
     */
    function getDefaultValutaIso($order=false) {
        if(!class_exists("phpshopvaluta")) parent::loadClass("valuta");
        if($order) $valuta_id = $this->getDefaultOrderValutaId();
        else $valuta_id = $this->getDefaultValutaId();
        $PV = new PHPShopValuta($valuta_id);

        return $PV->getIso();
    }

    /**
     * Вывод кода валюты по умочанию
     * @param bool $order валюта в заказе только с курсом для заказа (true)
     * @return string
     */
    function getDefaultValutaCode($order=false) {
        if(!class_exists("phpshopvaluta")) parent::loadClass("valuta");

        if($order) $valuta_id = $this->getDefaultOrderValutaId();
        elseif(isset($_SESSION['valuta'])) $valuta_id=$_SESSION['valuta'];
        else $valuta_id = $this->getDefaultValutaId();

        $PV = new PHPShopValuta($valuta_id);
        return $PV->getCode();
    }

    /**
     * Вывод лого сайта для документов
     * @return string
     */
    function getLogo() {
        $logo = parent::getParam("logo");
        if(empty($logo)) return "../../img/phpshop_logo.gif";
        else return $logo;
    }

    /**
     * Вывод массива настроек системы из БД
     * @return array
     */
    function getArray() {
        $array = $this->objRow;
        foreach($array as $key=>$v)
            if(is_string($key)) $newArray[$key]=$v;
        return $newArray;
    }

    /**
     * Вывод e-mail администратора
     * @return string
     */
    function getEmail(){
        return parent::getParam('admin_mail');
    }
}
?>