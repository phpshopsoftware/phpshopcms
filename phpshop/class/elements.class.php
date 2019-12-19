<?php

/**
 * ������������ ����� �������� ���������
 * ������� ������������� ��������� � ����� phpshop/inc/
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopElements {

    /**
     * @var string ��� ��
     */
    var $objBase;
    var $objPath;

    /**
     * @var bool ����� �������
     */
    var $debug = false;
    var $cache = false;
    var $cache_format = array();

    /**
     * @var string ��������� ������ �������
     */
    var $Disp;

    /**
     * �����������
     * @global obj $PHPShopSystem
     * @global obj $PHPShopNav
     * @global obj $PHPShopModules
     */
    function __construct() {
        global $PHPShopSystem, $PHPShopNav, $PHPShopModules;

        if ($this->objBase) {
            $this->PHPShopOrm = new PHPShopOrm($this->objBase);

            $this->PHPShopOrm->cache_format = $this->cache_format;
            $this->PHPShopOrm->cache = $this->cache;
            $this->PHPShopOrm->debug = $this->debug;
        }
        $this->SysValue = &$GLOBALS['SysValue'];
        $this->PHPShopSystem = &$PHPShopSystem;
        $this->PHPShopNav = &$PHPShopNav;
        $this->LoadItems = &$GLOBALS['LoadItems'];
        $this->PHPShopModules = &$PHPShopModules;
    }

    /**
     * ���������� � ���������� ������ ����� ������
     * @param string $template ��� ������� ��� ��������
     */
    function addToTemplate($template) {
        $this->Disp.=ParseTemplateReturn($template);
    }

    /**
     * ���������� � ���������� ������
     * @param sting $content �������
     */
    function add($content) {
        $this->Disp.=$content;
    }

    /**
     * ������� �������
     * @param string $template ��� �������
     * @param bool $mod ������������� ������� � ������
     * @return string
     */
    function parseTemplate($template, $mod = false) {
        return ParseTemplateReturn($template, $mod);
    }

    /**
     * �������� ��������� ���������� ��� ��������
     * @param string $name ���
     * @param mixed $value ��������
     * @param bool $flag [1] - ��������, [0] - ����������
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * ������ ��������� ����������
     * @param string $param ������.��� ����������
     * @return mixed
     */
    function getValue($param) {
        $param = explode(".", $param);
        return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * ������ ���������� �� ����
     * @param string $param ������.��� ����������
     * @return string
     */
    function getValueCache($param) {
        return $this->LoadItems[$param];
    }

    /**
     * ������������ ���������� �� ���������� ���������� �������
     * @param string $method_name ��� �������
     * @param bool $flag ���������� ������ � ����������
     */
    function init($method_name, $flag = false) {

        // ���� ���������� �� ���������� �������
        if (!empty($flag) and $this->isAction($method_name))
            $this->set($method_name, call_user_func(array(&$this, $method_name)), true);

        elseif (empty($this->SysValue['other'][$method_name])) {
            if ($this->isAction($method_name))
                $this->set($method_name, call_user_func(array(&$this, $method_name)));
            elseif ($this->isAction("index"))
                $this->set($method_name, call_user_func(array(&$this, 'index')));
            else
                $this->setError("index", "����� �� ����������");
        }
    }

    /**
     * �������� ������
     * @param string $method_name ��� ������
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $method_name))
            return true;
    }

    /**
     * ��������� �� ������
     * @param string $name ��� �������
     * @param string $action ���������
     */
    function setError($name, $action) {
        echo '<p><span style="color:red">������ ����������� �������: </span> <strong>' . $name . '()</strong>
	 <br><em>' . $action . '</em></p>';
    }

    function __call($name, $arguments) {
        if ($name == __CLASS__) {
            self::__construct();
        }
    }

}

?>