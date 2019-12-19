<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/obj.class.php");
    require_once(dirname(__FILE__) . "/array.class.php");
}

/**
 * Категории товаров
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopObj
 */
class PHPShopCategory extends PHPShopObj {

    /**
     * Конструктор
     * @param int $objID ИД категории
     */
    function __construct($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name'];
        $this->cache = true;
        $this->debug = false;
        parent::__construct('id');
    }

    /**
     * Выдача имени категории
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Выдача описания категории
     * @return string
     */
    function getContent() {
        if(empty($this->cache))
        return parent::getParam("content");
        else {
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $data=$PHPShopOrm->select(array('content'), array('id' => '=' . intval($this->objID)), false, array('limit' => 1));
            return $data['content'];
        }
    }

    /**
     * Проверка на существование
     * @return bool
     */
    function init() {
        $id = parent::getParam("id");
        if (!empty($id))
            return true;
    }

}

/**
 * Страницы
 * Упрощенный доступ к страницам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPages extends PHPShopObj {

    /**
     * Конструктор
     * @param int $objID ИД страницы
     */
    function __construct($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];
        parent::__construct();
    }

    /**
     * Выдача имени страницы
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Выдача содержания
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }

}

/**
 * Категории страниц
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPageCategory extends PHPShopObj {

    /**
     * Конструктор
     * @param int $objID ИД категории
     */
    function __construct($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['page_categories'];
        $this->cache = true;
        $this->debug = false;
        parent::__construct('id');
    }

    /**
     * Выдача имени категории
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Выдача описания категории
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }

    /**
     * Проверка на существование
     * @return bool
     */
    function init() {
        $id = parent::getParam("id");
        if (!empty($id))
            return true;
    }

}

/**
 * Массив категории товаров
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopArray
 */
class PHPShopCategoryArray extends PHPShopArray {

    /**
     * Конструктор
     * @param string $sql SQL условие выборки
     */
    function __construct($sql = false) {
        $this->objSQL = $sql;
        $this->cache = false;
        $this->order = array('order' => 'num');
        $this->objBase = $GLOBALS['SysValue']['base']['categories'];
        parent::__construct("id", "name", "parent_to", "skin_enabled");
    }

}

/**
 * Категории фотогалереи
 * Упрощенный доступ к категориям фотогалереи 
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPhotoCategory extends PHPShopObj {

    /**
     * Конструктор
     * @param int $objID ИД категории
     */
    function __construct($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['photo_categories'];
        $this->cache = true;
        $this->debug = false;
        parent::__construct('id');
    }

    /**
     * Выдача имени категории
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Выдача описания категории
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }

    /**
     * Проверка на существование
     * @return bool
     */
    function init() {
        $id = parent::getParam("id");
        if (!empty($id))
            return true;
    }

}


/**
 * Массив категории фотогалереи
 * Упрощенный доступ к категориями фотогалереи
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopArray
 */
class PHPShopPhotoCategoryArray extends PHPShopArray {

    /**
     * Конструктор
     * @param string $sql SQL условие выборки
     */
    function __construct($sql = false) {
        $this->objSQL = $sql;
        $this->cache = false;
        $this->order = array('order' => 'num');
        $this->objBase = $GLOBALS['SysValue']['base']['photo_categories'];
        parent::__construct("id", "name", "parent_to", "link");
    }

}
?>