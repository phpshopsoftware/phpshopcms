<?php

/**
 * ���������� ������ ��������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopIndex extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];
        $this->debug = false;

        // ������ ������� ��������
        $this->template = 'templates.index';
        parent::__construct();
    }


    /**
     * ����� �� ���������
     */
    function index() {
        global $PHPShopModules;

        //  ������ �� ������ /blabla.html ��� ����������� ������ SeoUrl
        $true_url = array('/', '');
        if (!in_array($this->PHPShopNav->objNav['url'], $true_url)) {
            return $this->setError404();
        }

        // ������� ������
        $row = parent::getFullInfoItem(array('*'), array('category' => "=2000", 'enabled' => "='1'"));

        // ���������� ����������
        $this->set('mainContent', Parser($row['content']));
        $this->set('mainContentTitle', Parser($row['name']));

        // �������� ������
        $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);
    }

}

?>