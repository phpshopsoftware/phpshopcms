<?php

/**
 * ���������� ������������ html ������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopDoc extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        $this->setMeta();
        $this->empty_index_action = true;
        parent::__construct();
    }

    /**
     * ������� ����������� �����
     * @global array $SysValue ���������
     * @param string $pages ��� ����� ��� ����������
     * @return string
     */
    function OpenHTML($pages) {
        global $SysValue;
        $dir = "pageHTML/";
        $pages = $pages . ".php";
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if ($file == $pages) {
                $urlfile = fopen("$dir$file", "r");
                $text = fread($urlfile, 1000000);
                return $text;
            }
        }
        return false;
    }

    /**
     * ����� �� ���������
     */
    function index() {

        // ������ ����
        $dis = $this->OpenHTML($this->SysValue['nav']['name']);

        // 404 ������ ��� ���������� �����
        if (empty($dis))
            return $this->setError404();

        // ����
        $this->title = $this->meta[$this->SysValue['nav']['name']] . ' - ' . $this->PHPShopSystem->getValue("name");
        $this->description = $this->meta_description[$this->SysValue['nav']['name']];
        $this->keywords = $this->meta_keywords[$this->SysValue['nav']['name']];

        // ���������� ���������
        $this->set('pageContent', $dis);
        $this->set('pageTitle', $this->meta[$this->SysValue['nav']['name']]);

        // ��������� ������� ������
        $this->navigation(null, $this->get('pageTitle'));


        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    // ��������� ������
    function setMeta() {
        $this->meta = array(
            'license' => '������������ ����������',
            'design' => '�������������� �������',
            'test' => '����������� HTML ������',
            'phpshop-response' => 'PHPShop ������',
            'laboratornoe-oborudovanie-avtoritet-otziv' => '������������ ������������ ���������'
        );

        $this->meta_description = array(
            'license' => '������������ ����������',
            'design' => '�������������� �������',
            'test' => '����������� HTML ������',
            'phpshop-response' => 'PHPShop ������ �������������',
            'laboratornoe-oborudovanie-avtoritet-otziv' => '������������ ������������ ���������, ������.'
        );

        $this->meta_keywords = array(
            'license' => '������������ ����������',
            'design' => '�������������� �������',
            'test' => '����������� HTML ������',
            'phpshop-response' => 'phpshop ������',
            'laboratornoe-oborudovanie-avtoritet-otziv' => '������������ ������, ������������, ������, ��������� ������������ ������������, bioscorp.ru'
        );
    }

}

?>
