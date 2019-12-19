<?php
/**
 * Библиотека данных по страницам
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/obj.class.php");
    require_once(dirname(__FILE__)."/array.class.php");
}

/**
 * Массив категории страниц
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopPageCategoryArray extends PHPShopArray {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['page_categories'];
        $this->order=array('order'=>'num');
        parent::__construct("id","name","parent_to");
    }
}

?>