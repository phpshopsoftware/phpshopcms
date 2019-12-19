<?php

/**
 * ���������� ���� �������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopPhoto extends PHPShopCore {

    /**
     * @var Int  ���-�� ���� � �����
     */
    var $ilim = 4;
    var $empty_index_action = true;

    /**
     * �����������
     */
    function __construct() {

        // ���-�� ���� �� ��������
        $num_row = 30;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['photo'];

        // �������
        $this->debug = false;

        // ������ �������
        $this->action = array("nav" => "CID");

        // ������ ��� ��������� ������� ������
        $this->navigationArray = 'CatalogPhoto';

        // �� ��� ������� ������
        $this->navigationBase = 'base.photo_categories';
        parent::__construct();

        $this->page = $GLOBALS['SysValue']['nav']['page'];
        if (strlen($this->page) == 0)
            $this->page = 1;

        $this->num_row = $num_row;
    }

    /**
     * ����� �� ���������, ��������
     */
    function index() {

        // ���������� ������ ������
        $this->parseTemplate($this->getValue('templates.error_page_forma'));
    }

    /**
     * ����� ������� ���������� ��� ������� ���������� ��������� CID
     */
    function CID() {

        // ID ���������
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopPhotoCategory = new PHPShopPhotoCategory($this->category);
        $this->category_name = $this->PHPShopPhotoCategory->getName();
        if (empty($this->category_name))
            $this->category_name = __('�����������');

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.photo_categories'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category, 'enabled' => "='1'"), false, array('limit' => 1));

        // ���� ����
        if (empty($row['id'])) {

            $this->ListPhoto();
        }
        // ���� ��������
        else {

            $this->ListCategory();
        }
    }

    /**
     * ����� ������ ����
     */
    function ListPhoto() {
        $disp = null;
        $i = 0;

        // ���� ��� ���������
        $this->objPath = '/photo/CID_' . $this->category . '_';

        // ������� ������
        $this->dataArray = parent::getListInfoItem(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'));
        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                $name_s = str_replace(".", "s.", $row['name']);
                $this->set('photoIcon', $name_s);
                $this->set('photoInfo', $row['info']);
                $this->set('photoImg', $row['name']);

                $disp.=ParseTemplateReturn('./phpshop/lib/templates/photo/photo_element_forma.tpl', true);
            }
        // ���� ���� �������� ��������
        if (empty($this->LoadItems['CatalogPhoto'][$this->category]))
            $content = $this->PHPShopPhotoCategory->getContent();
        elseif (!empty($this->LoadItems['CatalogPhoto'][$this->category]['content_enabled']))
            $content = $this->PHPShopPhotoCategory->getContent();

        $this->set('pageContent', $content.$disp);
        $this->set('pageTitle', $this->category_name);

        // ���������
        $this->setPaginator();

        // ����
        $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");

        // ��������� ������� ������
        $this->navigation($row['parent_to'], $this->category_name);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ������ ��������� ����
     */
    function ListCategory() {

        // ������� ������
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.photo_categories'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id'), array('parent_to' => '=' . $this->category), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], "/photo/CID_" . $row['id'] . ".html");
            }

        // ���� ���� �������� ��������
        if (!empty($this->LoadItems['CatalogPhoto'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopPhotoCategory->getContent();

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

}

?>