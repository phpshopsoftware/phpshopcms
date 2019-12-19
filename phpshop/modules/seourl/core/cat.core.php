<?php

class PHPShopCat extends PHPShopCore {

    /**
     * Вывод описания каталога в пагинаторе
     * @var bool 
     */
    var $content_in_paginator = false;

    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];
        $this->objPath = "/cat/";
        $this->debug = false;
        $this->action = array("nav" => "index");

        // Библиотека офорфмления текста
        PHPShopObj::loadClass("text");

        parent::PHPShopCore();
    }

    // Высчитываем PID каталога
    function getPid($name) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id'), array('seoname' => "='$name'"), false, array('limit' => 1));
        return $row['id'];
    }

    // Высчитываем seourl каталога
    function getSeourl($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('seoname'), array('id' => "=" . $id), false, array('limit' => 1));
        return $row['seoname'];
    }

    function index() {

        // Получаем ссылку
        $this->name = str_replace('cat', '', $this->PHPShopNav->getName());
        $name = PHPShopSecurity::TotalClean($this->name, 2);


        // ID категории
        $this->category = $this->getPid($name);

        if (!$this->category)
            return $this->setError404();

        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category), false, array('limit' => 1));

        // Если страницы
        if (empty($row['id'])) {

            $this->ListPage();
        }
        // Если каталоги
        else {

            $this->ListCategory();
        }
    }

    function ListPage() {
        $dis = null;

        // 404
        if (empty($this->category_name))
            return $this->setError404();

        // Номер страница навигации
        if (isset($_GET['p']))
            $this->page = $_GET['p'];

        $this->navigationFileType = null;

        // Путь для навигации
        $this->objPath = "/cat/" . $this->name . '.html?p=';

        // Выборка данных
        $this->dataArray = parent::getListInfoItem(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'));
        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], "/" . $row['link'] . ".html");
            }

        //$disp = PHPShopText::h1($this->category_name);
        // Если есть описание каталога
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled'])) {
            if ($this->page < 2)
                $disp.=$this->PHPShopCategory->getContent();
            elseif ($this->page > 1 and $this->content_in_paginator)
                $disp.=$this->PHPShopCategory->getContent();
        }

        $disp.=PHPShopText::ul($dis);

        $this->set('pageContent', Parser($disp));
        $this->set('pageTitle', $this->category_name);

        // Номер страницы в заголовке
        if ($_GET['p'] > 1)
            $page_num = $this->page . ' - ';
        else
            $page_num = null;

        // Title
        if ($this->PHPShopCategory->getValue('seotitle') != '')
            $this->title = $this->PHPShopCategory->getValue('seotitle');
        else
            $this->title = $this->category_name . " - " . $page_num . $this->PHPShopSystem->getValue("name");

        // Description
        $this->description = $this->PHPShopCategory->getValue('seodesc');

        // Keywords
        $this->keywords = $this->PHPShopCategory->getValue('seokey');

        // Навигация хлебные крошки
        $this->navigation($row['category'], $this->category_name);

        // Пагинатор @productPageNav@
        $this->setPaginator();
        if (!PHPShopParser::check($this->getValue('templates.page_page_list'), 'productPageNav'))
            $this->set('pageContent', $this->get('productPageNav'), true);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    function ListCategory() {

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id', 'seoname', 'seotitle', 'seodesc', 'seokey'), array('parent_to' => '=' . $this->category), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], $row['link'] . ".html");
            }

        // $disp = PHPShopText::h1($this->category_name);
        // Если есть описание каталога
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.=PHPShopText::ul($dis);


        $this->set('pageContent', Parser($disp));
        $this->set('pageTitle', $this->category_name);

        // Title
        if ($this->PHPShopCategory->getValue('seotitle') != '')
            $this->title = $this->PHPShopCategory->getValue('seotitle');
        else
            $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");

        // Description
        $this->description = $this->PHPShopCategory->getValue('seodesc');

        // Keywords
        $this->keywords = $this->PHPShopCategory->getValue('seokey');

        // Навигация хлебные крошки
        $this->navigation($this->category, $this->category_name);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    function navigation($id, $name) {
        $dis = '';
        $spliter = ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home = ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        $arrayPath = $this->getNavigationPath($id);

        if (is_array($arrayPath)) {
            $arrayPath = array_reverse($arrayPath);

            array_pop($arrayPath);

            foreach ($arrayPath as $v) {
                $dis.= $spliter . '<A href="/' . $this->PHPShopNav->getPath() . '/' . $this->getSeourl($v['id']) . '.html">' . $v['name'] . '</a>';
            }
        }

        $dis = $home . $dis . $spliter . '<b>' . $name . '</b>';
        $this->set('breadCrumbs', $dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId', $id);
    }

}

?>