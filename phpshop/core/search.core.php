<?php

/**
 * ���������� ������
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopSearch extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        // �������
        $this->debug = false;

        // ������ �������
        $this->action = array("post" => "words", "nav" => "index");
        parent::__construct();

        $this->title = __('�����') . " - " . $this->PHPShopSystem->getValue("name");

        // ������� ������
        $this->target();
    }

    /**
     * ����� �� ���������, ����� �����
     */
    function index() {
        // ���������� ������
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    // ������� ������ ��������
    function searchpage() {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return $hook;

        $this->set('pageFrom', "page");
        $this->set('pageDomen', $_SERVER['SERVER_NAME'] . "/page/");
        $j = 0;
        $i = 0;

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('*'), array('content' => " LIKE '%" . $this->words . "%'", "enabled" => "!='0'"), array('order' => 'link'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                // ���������� ����������
                $this->set('productName', $row['name']);
                $this->set('pageWords', $this->words);
                $this->set('productKey', substr(strip_tags($row['content']), 0, 300) . "...");
                $this->set('pageLink', $row['link'] . ".html");
                $i++;
                $this->set('productNum', $i);

                if ($j == 0) {
                    $this->set('pageTitle', $this->PHPShopSystem->getParam('name') . ' / ' . __('��������'));
                    $this->set('pageNumN', __("���������") . " " . __('�������') . " - " . count($dataArray));
                } else {
                    $this->set('pageTitle', false);
                    $this->set('pageNumN', false);
                }

                $j++;

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // ���������� ������
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        $this->add('<p><br></p>', true);

        return $i;
    }

    // ������� ������ �������
    function searchnews() {
        global $PHPShopModules;

        $this->set('pageFrom', "news");
        $this->set('pageDomen', $_SERVER['SERVER_NAME'] . "/news/");
        $j = 0;

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name8'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->Option['where'] = " or ";
        $dataArray = $PHPShopOrm->select(array('*'), array('description' => " LIKE '%" . $this->words . "%'", 'title' => " LIKE '%" . $this->words . "%'",
            'content' => " LIKE '%" . $this->words . "%'"), array('order' => 'id desc'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                // ���������� ����������
                $this->set('productName', $row['title']);
                $this->set('pageWords', $this->words);
                $this->set('productKey', substr(strip_tags($row['description']), 0, 300) . "...");
                $this->set('pageLink', "ID_" . $row['id'] . ".html");
                $i++;
                $this->set('productNum', $i);

                if ($j == 0) {
                    $this->set('pageTitle', $this->PHPShopSystem->getParam('name') . ' / ' . __('�������'));
                    $this->set('pageNumN', __("���������") . ": " . __('�������') . " - " . count($dataArray));
                } else {
                    $this->set('pageTitle', false);
                    $this->set('pageNumN', false);
                }

                $j++;

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // ���������� ������
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        return $i;
    }

    /**
     * ����� ������� ���������� ������ ��� ������� ���������� $_POST[words]
     * ����� �� ��������� � ��������
     */
    function words() {

        // �������� �� ������ �������
        $this->words = PHPShopSecurity::TotalClean($_POST['words'], 4);

        switch ($_POST['target']) {

            case 'a':
                $i = $this->searchpage() + $this->searchnews();
                break;

            case 'b':
                $i = $this->searchpage();
                break;

            case 'c':
                $i = $this->searchnews();
                break;

            default:
                $i = $this->searchpage() + $this->searchnews();
        }

        $this->set('searchString', $this->words);

        // ���������� ������
        if ($i == 0) {
            $message = PHPShopText::h3(__('������ �� �������'));
            $message.=PHPShopText::div(__('���� �� �� ����� ������ ����������, ��������������') . ' ' .
                            PHPShopText::a('../map/', __('������ �����'), __('������ �����')), $align = "left", $style = "padding:5;border-style: dashed;border-width: 1px;border-color:#D3D3D3");
            $this->add($message, true);
        }

        // ����
        $this->title = __("�����") . " - " . $this->PHPShopSystem->getValue("name");

        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    function target() {
        if (isset($_POST['target'])) {
            $$_POST['target'] = 'selected';
        }
        else
            $a = 'selected';

        $value[] = array(__('�����'), 'a', $a);
        $value[] = array(__('��������'), 'b', $b);
        $value[] = array(__('�������'), 'c', $c);
        $this->set('searchTarget', PHPShopText::select('target', $value, 100));
    }

}

?>