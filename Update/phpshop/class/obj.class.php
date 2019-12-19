<?php

if (!defined("OBJENABLED"))
    define("OBJENABLED", dirname(__FILE__));

/**
 * Родительский класс Объекта
 * @author PHPShop Software
 * @version 1.7
 * @package PHPShopClass
 */
class PHPShopObj {

    /**
     * ИД объекта в БД
     * @var int 
     */
    var $objID;

    /**
     * имя БД
     * @var string 
     */
    var $objBase;

    /**
     * массив данных
     * @var array 
     */
    var $objRow;

    /**
     * режим отладки
     * @var bool 
     */
    var $debug = false;

    /**
     * проверка установки
     * @var bool 
     */
    var $install = true;

    /**
     * Режим кэширования
     * @var bool
     */
    var $cache = false;

    /**
     * Форматирование кэша
     * @var array
     */
    var $cache_format = array();

    /**
     * Конструктор
     * @param string $var поле выборки, по умолчанию id
     * @param array $import_data массив импорта данных
     */
    function __construct($var = 'id', $import_data = null) {
        if (is_array($import_data))
            $this->objRow = $import_data;
        else
            $this->setRow($var);
    }

    /**
     * Запрос к БД
     * @param string var поле выборки, по умолчанию id
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
     * Сравнение параметра из массива
     * @param string $paramName имя переменной
     * @param string $paramValue значение переменной
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
     * Добавить параметр
     * @param string $param имя параметра
     * @param mixed $value значение параметра
     */
    function setParam($param, $value) {
        $this->objRow[$param] = $value;
    }

    /**
     * Выдача параметра из массива по ключу
     * @param string $paramName ключ
     * @return mixed
     */
    function getParam($paramName) {
        if (!empty($this->objRow[$paramName]))
            return $this->objRow[$paramName];
    }

    /**
     * Выдача параметра из массива по ключу, копия функции getParam($paramName)
     * @param string $paramName ключ
     * @return mixed
     */
    function getValue($paramName) {
        if (!empty($this->objRow[$paramName]))
            return $this->objRow[$paramName];
    }

    /**
     * Выдача массива значений целиком
     * @return array
     */
    function getArray() {
        return $this->objRow;
    }

    /**
     * Загрузка класса
     * @param mixed $class_name имя класса (массив с именами) согласно config.ini
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
                echo "Нет файла " . $class_path;
        }
    }

    /**
     * Выдача десериализованного значения
     * @param string $paramName имя параметра
     * @return string
     */
    function unserializeParam($paramName) {
        return unserialize($this->getParam($paramName));
    }

    /**
     * Загрузка класса роутера ядра для наследования
     * @param string $class_name имя класса, согласно config.ini
     */
    function importCore($class_name) {
        global $_classPath;
        $class_path = $_classPath . '/core/' . $class_name . ".core.php";
        if (file_exists($class_path))
            require_once($class_path);
        else
            echo "Нет файла " . $class_path;
    }

}

/**
 *  Автозагрузчик библиотек phpshop/class
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