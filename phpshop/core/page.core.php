<?php

/**
 * ���������� �������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopPage extends PHPShopCore {

    /**
     * ����� �������� �������� � ����������
     * @var bool 
     */
    var $content_in_paginator = false;

    /**
     * �����������
     */
    function __construct() {

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];

        // �������
        $this->debug = false;

        // ������ �������
        $this->action = array("nav" => "CID");
        $this->empty_index_action = true;
        parent::__construct();
    }

    /**
     * ����� �� ���������, ����� ������ �� ��������
     * @return string
     */
    function index($link = false) {
       
        // ������������
        if (empty($link))
            $link = PHPShopSecurity::TotalClean($this->PHPShopNav->getName(true), 2);

        // ������� ������
        $row = parent::getFullInfoItem(array('*'), array('link' => "='$link'", 'enabled' => "!='0'"));

        // ���������� �������� �� �����
        if ($row['category'] == 2000)
            return $this->setError404();
        elseif (empty($row['id']))
            return $this->setError404();

        // ���������� ����������
        $this->set('pageContent', Parser($row['content']));
        $this->set('pageTitle', $row['name']);

        // ����
        if (empty($row['title']))
            $title = $row['name'];
        else
            $title = $row['title'];

        $this->title = $title . " - " . $this->PHPShopSystem->getValue("name");
        $this->description = $row['description'];
        $this->keywords = $row['keywords'];
        $this->lastmodified = $row['date'];

        // ��������� ������� ������
        $this->navigation($row['category'], $row['name']);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $row);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ������� ��������� ���������� ��� ������� ���������� ��������� CID
     */
    function CID() {

        // ID ���������
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category), false, array('limit' => 1));

        // ���� ��������
        if (empty($row['id'])) {

            $this->ListPage();
        }
        // ���� ��������
        else {

            $this->ListCategory();
        }
    }

    /**
     * ����� ������ �������
     * @return string
     */
    function ListPage() {
        $dis = null;

        // 404
        if (empty($this->category_name)) {
            return $this->setError404();
        }

        // ����� �������� ���������
        $this->page = $this->PHPShopNav->getPage();

        // ���� ��� ���������
        $this->objPath = "/page/CID_" . $this->category . '_';


        // ������� ������
        $dataArray = $this->PHPShopOrm->select(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'), array('limit' => 100));
        

        if (is_array($dataArray)) {

            if (count($dataArray) > 1)
                foreach ($dataArray as $row) {
                    $dis.=PHPShopText::li($row['name'], '/page/' . $row['link'] . '.html');

                }
            else {
                return $this->index($dataArray[0]['link']);
            }
        }



        //$disp = PHPShopText::h1($this->category_name);
        $disp = null;

        // ���� ���� �������� ��������
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled'])) {
            if ($this->page < 2)
                $disp.=$this->PHPShopCategory->getContent();
            elseif ($this->page > 1 and $this->content_in_paginator)
                $disp.=$this->PHPShopCategory->getContent();
        }



        // ������ �������
        $disp.=PHPShopText::ul($dis);

        // �������� ��������
        $this->set('pageContent', Parser($disp));

        // �������� ��������
        $this->set('pageTitle', $this->category_name);

        // ��������� @productPageNav@
        $this->setPaginator();

        // ����� �������� � ���������
        if ($this->page > 1) {
            $page_num = $this->page . ' - ';
        }
        else
            $page_num = null;

        if (!PHPShopParser::check($this->getValue('templates.page_page_list'), 'productPageNav'))
            $this->set('pageContent', $this->get('productPageNav'), true);

        // Title
        $this->title = $this->category_name . " - " . $page_num . $this->PHPShopSystem->getValue("name");

        // ��������� ������� ������
        $this->navigation($row['category'], $this->category_name);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ������ ���������
     */
    function ListCategory() {

        // ������� ������
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id'), array('parent_to' => '=' . $this->category), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], "/page/CID_" . $row['id'] . ".html");
            }

        $disp = PHPShopText::h1($this->category_name);

        // ���� ���� �������� ��������
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.=PHPShopText::ul($dis);

        $this->set('pageContent', Parser($disp));
        $this->set('pageTitle', $this->category_name);

        // ����
        $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");

        // ��������� ������� ������
        $this->navigation($this->category, $this->category_name);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ���������
     */
    function meta() {
        parent::meta();

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, false);
    }

}

?>