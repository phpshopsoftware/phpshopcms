<?php

/**
 * Элемент фото галерея
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopPhotoElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function __construct() {

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['table_name22'];
        parent::__construct();
    }

    /**
     * Вывод фото по таргетингу
     * @return string
     */
    function getPhotos() {
        global $PHPShopModules;
        $dis = null;
        $url = addslashes(substr($this->SysValue['nav']['url'], 1));
        if (empty($url))
            $url = '/';

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name22'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", "page" => " LIKE '%$url%'"), array('order' => 'num'), array("limit" => 100));

        if (is_array($data))
            foreach ($data as $row) {
            
                $this->set('photoTitle', $row['name']);
                $this->set('photoLink', '/photo/CID_'.$row['id']. '.html');
                
                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);
                
                $this->set('photoContent', $this->ListPhoto($row['id'], $row['num']));
                $dis.=$this->parseTemplate('./phpshop/lib/templates/photo/photo_list_forma.tpl', true);
            }

        return $dis;
    }

    /**
     * Вывод фото
     * @param int $cat ИД категории фото
     * @param int $num кол-во фото для вывода
     * @return string
     */
    function ListPhoto($cat, $num) {
        $dis = null;

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name23'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . intval($cat), 'enabled' => "='1'"), array('order' => 'num'), array('limit' => $num));
        if ($num == 1)
            $this->dataArray[] = $data;
        else
            $this->dataArray = $data;

        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                $name_s = str_replace(".", "s.", $row['name']);
                $this->set('photoIcon', $name_s);
                $this->set('photoInfo', $row['info']);
                $this->set('photoImg', $row['name']);

                $dis.=$this->parseTemplate('./phpshop/lib/templates/photo/photo_element_forma.tpl', true);
            }
        return $dis;
    }

    /**
     * Вывод случайного фото
     * @param int $cat ID каталога
     * @return string
     */
    function randPhoto($cat) {
        $dis = null;

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name23'));
        $PHPShopOrm->debug = $this->debug;

        $this->dataArray[] = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", "category" => "=" . intval($cat)), array('order' => 'RAND()'), array("limit" => 1));

        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                $name_s = str_replace(".", "s.", $row['name']);
                $this->set('photoIcon', $name_s);
                $this->set('photoInfo', $row['info']);
                $this->set('photoImg', $row['name']);

                $dis.=$this->parseTemplate('./phpshop/lib/templates/photo/photo_element_forma.tpl', true);
            }
        return $dis;
    }

    /**
     * Вывод категорий фото для навигации
     * @return string
     */
    function mainMenuPhoto() {
        global $PHPShopModules;

        $dis = null;
        $i = 0;

        $data = $this->PHPShopOrm->select(array('*'), array('parent_to' => '=0', 'enabled' => "='1'"), array('order' => 'num'), array("limit" => 100));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));

                // Глобальный массив для навигации хлебных крошек
                $this->LoadItems['CatalogPhoto'][$row['id']]['name'] = $row['name'];
                $this->LoadItems['CatalogPhoto'][$row['id']]['parent_to'] = $row['parent_to'];

                if (!empty($row['content']))
                    $this->LoadItems['CatalogPhoto'][$row['id']]['content_enabled'] = true;
                else
                    $this->LoadItems['CatalogPhoto'][$row['id']]['content_enabled'] = false;

                $this->set('catalogName', $row['name']);
                $this->set('catalogLink', '/photo/CID_' . $row['id'] . '.html');

                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);

                $this->set('menu', $this->parseTemplate($this->getValue('templates.catalog_photo_1_point')));

                $dis.=$this->parseTemplate($this->getValue('templates.catalog_photo_1'));
            }
        return $dis;
    }

}

/**
 * Элемент каталоги страниц
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCatalogElement extends PHPShopElements {

    /**
     * @var bool проверять на единичные каталоги
     */
    var $chek_page = true;

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['page_categories'];
        parent::__construct();
    }

    /**
     * Вывод навигации каталогов
     * @return string
     */
    function mainMenuPage() {
        $dis = '';
        $i = 0;

        $data = $this->PHPShopOrm->select(array('*'), array('parent_to' => '=0'), array('order' => 'num'), array("limit" => 100));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));

                // Глобальный массив для навигации хлебных крошек
                $this->LoadItems['CatalogPage'][$row['id']]['name'] = $row['name'];
                $this->LoadItems['CatalogPage'][$row['id']]['parent_to'] = $row['parent_to'];
                if (!empty($row['content']))
                    $this->LoadItems['CatalogPage'][$row['id']]['content_enabled'] = true;
                else
                    $this->LoadItems['CatalogPage'][$row['id']]['content_enabled'] = false;

                // Если есть страницы
                if ($this->chek($row['id'])) {

                    $link = $this->chek_page($row['id']);
                    if ($link and $this->chek_page) {
                        $this->set('catalogName', $row['name']);
                        $this->set('catalogId', $row['id']);
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
                    } else {

                        $this->set('catalogPodcatalog', $this->page($row['id']));
                        $this->set('catalogName', $row['name']);
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));
                    }
                } else {
                    $this->set('catalogPodcatalog', $this->podcatalog($row['id']));
                    $this->set('catalogName', $row['name']);
                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
                }

                $i++;
            }
        return $dis;
    }

    /**
     * Проверка подкатлогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('parent_to' => "=$id"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     * Проверка страниц
     * @param int $id ИД каталога
     * @return mixed
     */
    function chek_page($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page'));
        $PHPShopOrm->debug = false;
        $num = $PHPShopOrm->select(array('link'), array('category' => "=$id"), false, array('limit' => 5));
        if (is_array($num))
            if (count($num) == 1)
                return $num[0]['link'];
    }

    /**
     * Вывод страниц
     * @param Int $n ИД каталога
     * @return string
     */
    function page($n) {
        global $PHPShopModules, $dis;
        $dis = '';
        $n = intval($n);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . $n, 'enabled' => "='1'"), array('order' => 'num'), array("limit" => 100));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $n);
                $this->set('catalogUid', $row['id']);
                $this->set('catalogLink', $row['link']);
                $this->set('catalogName', $row['name']);

                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, array($row, &$dis));

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }

        return $dis;
    }

    /**
     * Вывод подкаталогов
     * @param Int $n ИД каталога
     * @return string
     */
    function podcatalog($n) {
        global $PHPShopModules;

        $dis = '';
        $i = 0;
        $n = intval($n);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $data = $PHPShopOrm->select(array('*'), array('parent_to' => '=' . $n), array('order' => 'num'), array("limit" => 100));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $n);
                $this->set('catalogI', $i);
                $this->set('catalogLink', 'CID_' . $row['id']);
                $this->set('catalogName', $row['name']);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogName', $row['name']);
                $i++;


                // Глобальный массив для навигации хлебных крошек
                $this->LoadItems['CatalogPage'][$row['id']]['name'] = $row['name'];
                $this->LoadItems['CatalogPage'][$row['id']]['parent_to'] = $row['parent_to'];
                if (!empty($row['content']))
                    $this->LoadItems['CatalogPage'][$row['id']]['content_enabled'] = true;
                else
                    $this->LoadItems['CatalogPage'][$row['id']]['content_enabled'] = false;

                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);


                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }
        return $dis;
    }

}

?>