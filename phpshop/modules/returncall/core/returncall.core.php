<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopReturncall extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['returncall']['returncall_jurnal'];

        // �������
        $this->debug = false;

        // ���������
        $this->system();

        // ������ �������
        $this->action = array(
            'post' => 'returncall_mod_send',
            'name' => 'done',
            'nav' => 'index'
        );


        parent::__construct();

        // ������� ������
        $this->navigation(null, __('�������� ������'));

        // ����
        $this->title = $this->system['title'] . " - " . $this->PHPShopSystem->getValue("name");
    }

    /**
     * ���������
     */
    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['returncall']['returncall_system']);
        $this->system = $PHPShopOrm->select();
    }

    /**
     * ��������� �� ������� ������
     */
    function done() {
        $message = $this->system['title_end'];
        if (empty($message))
            $message = $GLOBALS['SysValue']['lang']['returncall_done'];
        $this->set('pageTitle', $this->system['title']);
        $this->set('retuncallDone', $message);
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['returncall']['returncall_done'], true, false, true));
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� �� ���������, ����� ����� ������
     */
    function index($message = false) {

        // ������ ��������
        if ($message)
            $this->set('pageTitle', $message);
        else
            $message = $this->system['title'];

        // �������� ������
        if ($this->system['captcha_enabled'] == 1) {
            $PHPShopRecaptchaElement = new PHPShopRecaptchaElement();
            $this->set('returncall_captcha', $PHPShopRecaptchaElement->captcha('returncall'));
        }

        // ���������� ������
        $this->set('pageTitle', $message);
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['returncall']['returncall_forma'], true, false, true));
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
     * ����� ������ ��� ��������� $_POST[returncall_mod_send]
     */
    function returncall_mod_send() {

        $error = false;
        
         preg_match_all('/http:?/', $_POST['returncall_mod_message'], $url, PREG_SET_ORDER);

        // �������� ������
        if ($this->system['captcha_enabled'] == 1) {

            if (!$this->security())
                $error = true;
        }

        if (PHPShopSecurity::true_param($_POST['returncall_mod_name'], $_POST['returncall_mod_tel'], empty($error)) and count($url)==0) {
            $this->write();
            header('Location: ./done.html');
            exit();
        } else {
            $message = $GLOBALS['SysValue']['lang']['returncall_error'];
        }
        $this->index($message);
    }

    /**
     * ������ � ����
     */
    function write() {

        // ���������� ���������� �������� �����
        PHPShopObj::loadClass("mail");
        $insert = array();
        $insert['name_new'] = PHPShopSecurity::TotalClean($_POST['returncall_mod_name'], 2);
        $insert['tel_new'] = PHPShopSecurity::TotalClean($_POST['returncall_mod_tel'], 2);
        $insert['date_new'] = time();
        $insert['time_start_new'] = $_POST['returncall_mod_time_start'];
        $insert['time_end_new'] = $_POST['returncall_mod_time_end'];
        $insert['message_new'] = PHPShopSecurity::TotalClean($_POST['returncall_mod_message'], 2);
        $insert['ip_new'] = $_SERVER['REMOTE_ADDR'];

        // ������ � ����
        $this->PHPShopOrm->insert($insert);

        $zag = $this->PHPShopSystem->getValue('name') . " - �������� ������ - " . PHPShopDate::dataV();


        $message = "
������� �������!
---------------

� ����� " . $this->PHPShopSystem->getValue('name') . " ������ ������ �� �������� ������

������ � ������������:
----------------------

���:                " . $insert['name_new'] . "
�������:             " . $insert['tel_new'] . "
����� ������:       " . $insert['time_start_new'] . "  " . $insert['time_end_new'] . "
���������:          " . $insert['message_new'] . "
����:               " . PHPShopDate::dataV($insert['date_new']) . "
IP:                 " . $_SERVER['REMOTE_ADDR'] . "
REFERER:            " . $_SERVER['HTTP_REFERER'] . " 
---------------

� ���������,
http://" . $_SERVER['SERVER_NAME'];

        new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $zag, $message);
    }

}

?>