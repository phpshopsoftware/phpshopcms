<?php

/**
 * Библиотека работы с массивами данных
 * <code>
 * // example:
 * class PHPShopCategoryArray extends PHPShopArray{
 * 	 function __construct(){
 * 	 $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
 * 	 parent::__construct("id","name","PID");
 * 	 }
  }
 * </code>
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopArray {

    /**
     * @var string имя БД
     */
    var $objBase;

    /**
     * Массив условий выборки
     * @var array 
     */
    var $objSQL = false;

    /**
     * Лимит 
     * @var int 
     */
    var $limit = 1000;

    /**
     * @var bool режим отладки
     */
    var $debug = false;
    var $cache = true;

    /**
     * @var int многомерный [1] одномерный масив [2] или [3] простой массив
     */
    var $objType = 1;

    /**
     * @var bool режим проверки ключей
     */
    var $checkKey = false;

    /**
     * Сортировка выборки
     * @var array
     */
    var $order = array();

    function __construct() {
        $this->objArg = func_get_args();
        $this->objArgNum = func_num_args();
        $this->setArray();
    }

    /**
     * Создание массива выбранных элементов из БД
     * @param mixed $param имя параметра через запятую
     */
    function setArray() {
        if (!$this->checkKey and $this->objArgNum > 0) {
            foreach ($this->objArg as $v) {
                $select[] = $v;
            }
        }
        else
            $select[] = "*";


        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;
        $data = $PHPShopOrm->select($select, $this->objSQL, $this->order, array('limit' => $this->limit));

        if (is_array($data))
            foreach ($data as $objRow) {
                switch ($this->objType) {
                    case(1):
                        foreach ($this->objArg as $val)
                            $_array[$val] = $objRow[$val];
                        $array[$objRow[$this->objArg[0]]] = $_array;
                        break;

                    case(2):
                        $array[$objRow[$this->objArg[0]]] = $objRow[$this->objArg[1]];
                        break;

                    case(3):
                        foreach ($this->objArg as $val)
                            $array[$val] = $objRow[$val];
                        break;
                }
            }
        $this->objArray = $array;
    }

    /**
     * Выдача общего массива
     * @return array
     */
    function getArray() {
        return $this->objArray;
    }

    /**
     * Добавить параметр
     * @param string $param имя параметра
     * @param mixed $value значение параметра
     */
    function setParam($param, $value) {
        if (strstr($param, '.')) {
            $param = explode(".", $param);
            $this->objArray[$param[0]][$param[1]] = $value;
        }
        else
            $this->objArray[$param] = $value;
    }

    /**
     * Выдача элемента массива
     * @param string $param имя параметра
     * @return string
     */
    function getParam($param) {
        if (strstr($param, '.')) {
            $param = explode(".", $param);
            if (isset($this->objArray[$param[0]][$param[1]]))
                return $this->objArray[$param[0]][$param[1]];
        }
        else
            return $this->objArray[$param];
    }

    /**
     * Преобразование в ключевой массив по первому параметру при указании метода
     * <code>
     * // example:
     * $PHPShopDeliveryArray = new PHPShopDeliveryArray();
     * $PHPShopDeliveryArray -> getKey('PID.name',true);
     * </code>
     * @param string $param имя параметра
     * @param bool $type при совпадении ключей создается многомерный массив, иначе берется FIFO
     * @return array
     */
    function getKey($param, $type = false) {
        $param = explode(".", $param);
        $array = $this->objArray;
        if (is_array($array))
            foreach ($array as $val)
                foreach ($val as $key => $v)
                    if ($key == $param[1]) {
                        if (empty($type)) {
                            $newArray[$val[$param[0]]] = $v;
                        } else {
                            if (empty($newArray[$val[$param[0]]]))
                                $newArray[$val[$param[0]]][] = $v;
                            else
                                $newArray[$val[$param[0]]][] = $v;
                        }
                    }
        return $newArray;
    }

    /**
     * Подсчет элементов  в массиве
     * @return int
     */
    function getNum() {
        return count($this->objArray);
    }

    function __call($name, $arguments) {
        if ($name == __CLASS__) {
            self::__construct();
        }
    }

}

?>