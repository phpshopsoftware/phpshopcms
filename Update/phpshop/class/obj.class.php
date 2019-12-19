<?php

if (!defined("OBJENABLED"))
    define("OBJENABLED", dirname(__FILE__));

/**
 * ������������ ����� �������
 * @author PHPShop Software
 * @version 1.7
 * @package PHPShopClass
 */
class PHPShopObj {

    /**
     * �� ������� � ��
     * @var int 
     */
    var $objID;

    /**
     * ��� ��
     * @var string 
     */
    var $objBase;

    /**
     * ������ ������
     * @var array 
     */
    var $objRow;

    /**
     * ����� �������
     * @var bool 
     */
    var $debug = false;

    /**
     * �������� ���������
     * @var bool 
     */
    var $install = true;

    /**
     * ����� �����������
     * @var bool
     */
    var $cache = false;

    /**
     * �������������� ����
     * @var array
     */
    var $cache_format = array();

    /**
     * �����������
     * @param string $var ���� �������, �� ��������� id
     * @param array $import_data ������ ������� ������
     */
    function __construct($var = 'id', $import_data = null) {
        if (is_array($import_data))
            $this->objRow = $import_data;
        else
            $this->setRow($var);
    }

    /**
     * ������ � ��
     * @param string var ���� �������, �� ��������� id
     */
    function setRow($var) {
        $this->loadClass("orm");
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->install = $this->install;
        $this->objRow = $PHPShopOrm->select(array('*'), array($var => '="' . $this->objID . '"'), false, array('limit' => 1));
    }

    /**
     * ��������� ��������� �� �������
     * @param string $paramName ��� ����������
     * @param string $paramValue �������� ����������
     * @return bool
     */
    function ifValue($paramName, $paramValue = false) {
        if (empty($paramValue))
            $paramValue = 1;
        if (!empty($this->objRow[$paramName]))
            if ($this->objRow[$paramName] == $paramValue)
                return true;
    }

    /**
     * �������� ��������
     * @param string $param ��� ���������
     * @param mixed $value �������� ���������
     */
    function setParam($param, $value) {
        $this->objRow[$param] = $value;
    }

    /**
     * ������ ��������� �� ������� �� �����
     * @param string $paramName ����
     * @return mixed
     */
    function getParam($paramName) {
        if (!empty($this->objRow[$paramName]))
            return $this->objRow[$paramName];
    }

    /**
     * ������ ��������� �� ������� �� �����, ����� ������� getParam($paramName)
     * @param string $paramName ����
     * @return mixed
     */
    function getValue($paramName) {
        if (!empty($this->objRow[$paramName]))
            return $this->objRow[$paramName];
    }

    /**
     * ������ ������� �������� �������
     * @return array
     */
    function getArray() {
        return $this->objRow;
    }

    /**
     * �������� ������
     * @param mixed $class_name ��� ������ (������ � �������) �������� config.ini
     */
    static function loadClass($class) {

        if (!is_array($class)) {
            $class_name[] = $class;
        }
        else
            $class_name = $class;

        foreach ($class_name as $name) {
            $class_path = OBJENABLED . "/" . $name . ".class.php";
            if (file_exists($class_path))
                require_once($class_path);
            else
                echo "��� ����� " . $class_path;
        }
    }

    /**
     * ������ ������������������ ��������
     * @param string $paramName ��� ���������
     * @return string
     */
    function unserializeParam($paramName) {
        return unserialize($this->getParam($paramName));
    }

    /**
     * �������� ������ ������� ���� ��� ������������
     * @param string $class_name ��� ������, �������� config.ini
     */
    function importCore($class_name) {
        global $_classPath;
        $class_path = $_classPath . '/core/' . $class_name . ".core.php";
        if (file_exists($class_path))
            require_once($class_path);
        else
            echo "��� ����� " . $class_path;
    }

}

/**
 *  ������������� ��������� phpshop/class
 */
function PHPShopAutoLoadClass($class_name) {
    global $_classPath;
    if (preg_match("/^[a-zA-Z0-9_\.]{2,20}$/", $class_name)) {
        $class_path = $_classPath . "class/" . strtolower(str_replace('PHPShop', '', $class_name)) . ".class.php";
        if (file_exists($class_path))
            require_once($class_path);
    }
}

if (function_exists('spl_autoload_register')) {
    spl_autoload_register('PHPShopAutoLoadClass');
}
?>