<?php

/**
 * ���������� �������� ��������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopSkinmarket extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        $this->empty_index_action = true;
        parent::__construct();

        // ��������� ������� ������
        $this->navigation(null, __('������� ��� �����'));
    }

    /**
     * ����� �� ���������
     */
    function index() {

        // ����
        $this->title = "������� �������� ��� PHPShop - " . $this->PHPShopSystem->getValue("name");

        // ���������� ���������
        $this->set('pageContent', '
            <!-- DESIGN CATALOG -->
            <div id="mgdcwidget-container" class="mgdcwidget-container"></div><script type="text/javascript">document.write(\'<script id="mgdcwidget-loader" src="//design.srv18.com/widget/index.php?id=9c78ae4de7f738ce19e1490cf24e97830a2ff711de59c22d6fc3d0d837cb3f78&\' + (new Date).getTime() + \'" type="text/javascript" charset="utf-8" async="async"><\/script>\');</script>
            <!-- /DESIGN CATALOG -->
            ');
        $this->set('pageTitle', '������� ��������');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

}

?>