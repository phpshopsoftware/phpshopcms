<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

/**
 * ������� ����� ����� � ���
 */
class AddToTemplateChatElement extends PHPShopElements {

    var $debug = false;

    /**
     * �����������
     */
    function __construct() {
        parent::__construct();
        $this->option();
    }

    /**
     * ���������
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['chat']['chat_system']);
        $PHPShopOrm->debug = $this->debug;
        $this->option = $PHPShopOrm->select();
    }

    /**
     * ����� �����
     */
    function display() {

        $forma = PHPShopParser::file($GLOBALS['SysValue']['templates']['chat']['chat_forma'], true, false, true);
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $this->option['title']);

        // ���������� ������
        if (empty($this->option['windows']))
            $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
        else {
            if (empty($this->option['enabled']))
                $dis = PHPShopParser::file($GLOBALS['SysValue']['templates']['chat']['chat_forma_window'], true, false, true);
            else {
                $this->set('leftMenuContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['chat']['chat_forma_window'], true, false, true));
                $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
            }
        }

        // ��������� ���������� �������
        //if ($this->option['operator'] == 1)
            switch ($this->option['enabled']) {

                case 1:
                    $this->set('leftMenu', $dis, true);
                    break;

                case 2:
                    $this->set('rightMenu', $dis, true);
                    break;

                default: $this->set('chat', $dis);
            }
    }

}

// ��������� � ������ �������
$AddToTemplateChatElement = new AddToTemplateChatElement();
$AddToTemplateChatElement->display();
?>