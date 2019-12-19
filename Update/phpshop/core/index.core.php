<?php

/**
 * Обработчик первой страницы
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopIndex extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];
        $this->debug = false;

        // Шаблон главной страницы
        $this->template = 'templates.index';
        parent::__construct();
    }


    /**
     * Экшен по умолчанию
     */
    function index() {
        global $PHPShopModules;

        //  Защита от ссылок /blabla.html без включенного модуля SeoUrl
        $true_url = array('/', '');
        if (!in_array($this->PHPShopNav->objNav['url'], $true_url)) {
            return $this->setError404();
        }

        // Выборка данных
        $row = parent::getFullInfoItem(array('*'), array('category' => "=2000", 'enabled' => "='1'"));

        // Определяем переменные
        $this->set('mainContent', Parser($row['content']));
        $this->set('mainContentTitle', Parser($row['name']));

        // Перехват модуля
        $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);
    }

}

?>