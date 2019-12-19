<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__) . "/obj.class.php");

/**
 * ��������� ���������
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopObj
 */
class PHPShopSystem extends PHPShopObj {

    /**
     * �����������
     */
    function __construct() {
        $this->objID = 1;
        $this->install = false;
        $this->cache = false;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name3'];
        parent::__construct();
    }

    /**
     * ����� ����� �����
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * ����� ���������������� �������� [param.val]
     * @param string $param
     * @return string
     */
    function getSerilizeParam($param) {
        $param = explode(".", $param);
        $val = parent::unserializeParam($param[0]);
        return $val[$param[1]];
    }

    /**
     * ��������� ���������������� �������� [param.val]
     * @param string $param ��� ����������
     * @param string $value �������� ����������
     * @return bool
     */
    function ifSerilizeParam($param, $value = false) {
        if (empty($value))
            $value = 1;
        if ($this->getSerilizeParam($param) == $value)
            return true;
    }

    /**
     * ����� �� ������ �� ��������� �� �����
     * @return int
     */
    function getDefaultValutaId() {
        return parent::getParam("dengi");
    }

    /**
     * ����� ����� ������ �� ���������
     * @return float
     */
    function getDefaultOrderValutaId() {
        return parent::getParam("kurs");
    }

    /**
     * ����� ����� ������ �� ��������
     * @param bool $order ������ � ������ (true)
     * @return float
     */
    function getDefaultValutaKurs($order = false) {
        if (!class_exists("phpshopvaluta"))
            parent::loadClass("phpshopvaluta");
        if ($order)
            $valuta_id = $this->getDefaultOrderValutaId();
        else
            $valuta_id = $this->getDefaultValutaId();
        $PV = new PHPShopValuta($valuta_id);

        return $PV->getKurs();
    }

    /**
     * ����� ISO ������ �� ��������
     * @param bool $order ������ � ������ (true)
     * @return string
     */
    function getDefaultValutaIso($order = false) {
        if (!class_exists("phpshopvaluta"))
            parent::loadClass("valuta");
        if ($order)
            $valuta_id = $this->getDefaultOrderValutaId();
        else
            $valuta_id = $this->getDefaultValutaId();
        $PV = new PHPShopValuta($valuta_id);

        return $PV->getIso();
    }

    /**
     * ����� ���� ������ �� ��������
     * @param bool $order ������ � ������ ������ � ������ ��� ������ (true)
     * @return string
     */
    function getDefaultValutaCode($order = false) {
        if (!class_exists("phpshopvaluta"))
            parent::loadClass("valuta");

        if ($order)
            $valuta_id = $this->getDefaultOrderValutaId();
        elseif (isset($_SESSION['valuta']))
            $valuta_id = $_SESSION['valuta'];
        else
            $valuta_id = $this->getDefaultValutaId();

        $PV = new PHPShopValuta($valuta_id);
        return $PV->getCode();
    }

    /**
     * ����� ���� ����� ��� ����������
     * @return string
     */
    function getLogo() {
        $logo = parent::getParam("logo");
        if (empty($logo))
            return "../../img/phpshop_logo.gif";
        else
            return $logo;
    }

    /**
     * ����� ������� �������� ������� �� ��
     * @return array
     */
    function getArray() {
        $array = $this->objRow;
        foreach ($array as $key => $v)
            if (is_string($key))
                $newArray[$key] = $v;
        return $newArray;
    }

    /**
     * ����� e-mail ��������������
     * @return string
     */
    function getEmail() {
        return parent::getParam('admin_mail');
    }

    /**
     * ��������� ����� SMTP
     * @param array $add �������������� ���������
     * @return array
     */
    function getMailOption($add = false) {

        if ($this->ifSerilizeParam('admoption.mail_smtp_enabled', 1)) {

            if ($this->ifSerilizeParam('admoption.mail_smtp_debug', 1))
                $mail_debug = 2;
            else
                $mail_debug = 0;

            $option = array(
                'smtp' => true,
                'host' => $this->getSerilizeParam('admoption.mail_smtp_host'),
                'port' => $this->getSerilizeParam('admoption.mail_smtp_port'),
                'debug' => $mail_debug,
                'auth' => $this->getSerilizeParam('admoption.mail_smtp_auth'),
                'user' => $this->getSerilizeParam('admoption.mail_smtp_user'),
                'password' => $this->getSerilizeParam('admoption.mail_smtp_pass'),
                'replyto' => $this->getSerilizeParam('admoption.mail_smtp_replyto')
            );
        }
        else
            $option = null;

        // �������������� ���������
        if (is_array($add)) {
            foreach ($add as $k => $v)
                $option[$k] = $v;
        }


        return $option;
    }

}

?>