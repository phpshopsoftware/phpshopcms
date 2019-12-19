<?php

/**
 * ���������� ����� ��������� � �����
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopForma extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        $this->debug = false;

        // ������ �������
        $this->action = array("post" => "message", "nav" => "index");
        parent::__construct();

        // ��������� ������� ������
        $this->navigation(false, __('����� �����'));
    }

    /**
     * ����� �� ���������, ����� ����� �����
     */
    function index() {

        // ����
        $this->title = __("����� �����") . " - " . $this->PHPShopSystem->getValue("name");

        // ���������� ����������
        $this->set('pageTitle', __('����� �����'));

        // ���������� ������
        $this->addToTemplate("page/page_forma_list.tpl");
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * �������� �����
     * @param array $option ��������� �������� [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;

        return $PHPShopRecaptchaElement->security($option);
    }

    /**
     * ����� �������� ����� ��� ��������� $_POST[message]
     */
    function message() {
        
        preg_match_all('/http:?/', $_POST['message'], $url, PREG_SET_ORDER);
        
        if ($this->security()) {
            $this->send();
        } else {
            $this->set('Error', __("������ �����, ��������� ������� ����� �����"));
        }
    }

    /**
     * ��������� ���������
     */
    function send() {

        // ���������� ���������� �������� �����
        PHPShopObj::loadClass("mail");

        // ��������� ������������� �����
        if (PHPShopSecurity::true_param($_POST['nameP'], $_POST['subject'], $_POST['message'], $_POST['mail'])) {

            $zag = $_POST['subject'] . " - " . $this->PHPShopSystem->getValue('name');
            $message = "��� ������ ��������� � ����� " . $this->PHPShopSystem->getValue('name') . "

������ � ������������:
----------------------
";
            unset($_POST['g-recaptcha-response']);

            // ���������� �� ���������
            foreach ($_POST as $k => $val) {
                $message.=$val . "
";
                unset($_POST[$k]);
            }


            $message.="
����: " . date("d-m-y H:s a") . "
IP: " . $_SERVER['REMOTE_ADDR'] ;

            new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $zag, $message, false, false, array('replyto' => $_POST['mail']));

            $this->set('Error', __("��������� ������� ����������"));
        }
        else
            $this->set('Error', __("������ ���������� ������������ �����"));
    }

}

?>