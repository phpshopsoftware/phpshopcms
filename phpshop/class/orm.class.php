<?php

/**
 * Библиотека запросов к БД на основе объектов типа доступа
 * @author PHPShop Software
 * @version 1.8
 * @package PHPShopClass
 */
class PHPShopOrm {

    /**
     * имя базы
     * @var string 
     */
    var $Base;

    /**
     * режим отладки
     * @var bool
     */
    var $debug = false;

    /**
     * вывод ошибок mysql
     * @var bool 
     */
    var $mysql_error = true;

    /**
     * комментарий для отладчика
     * @var bool 
     */
    var $comment = false;

    /**
     * проверка установки
     * @var bool 
     */
    var $install = true;

    /**
     * кэширование данных
     * @var bool 
     */
    var $cache = false;

    /**
     * массив неиспользуемых ключей в кеше для удаления
     * @var array 
     */
    var $cache_format = array();
    var $cache_sort = 'id';

    /**
     * лимит элементов в кеше
     * @var integer 
     */
    var $cache_limit = 100;
    var $_SQL;
    var $_DATA;
    var $link_db;

    /**
     * Конструктор
     * @param string $Base имя таблицы
     */
    function __construct($Base = false) {
        global $PHPShopBase;

        $this->objBase = $Base;
        $this->Option['where'] = " and ";
        $this->nWhere = 1;
        $this->nSelect = 1;
        $this->sql = false;
        $this->Items = &$GLOBALS['Cache'];

        if ($PHPShopBase)
            $this->link_db = $PHPShopBase->link_db;
    }

    /**
     * Выдача массива из кэша
     */
    function cache_get($params, $orm_array = false) {
        $param = explode(".", str_replace('"', '', $params));
        if ($this->cache_check($param)) {
            if (is_array($orm_array)) {

                if (empty($param[1]))
                    $param[1] = '?';

                $this->comment = 'Кэширование - ' . $param[0] . '.' . $param[1] . '.' . $this->cache_sort . '.' . $orm_array['class_name'] . '.' . $orm_array['function_name'];
                $result = $this->select_native($orm_array['select'], $orm_array['where'], $orm_array['order'], $orm_array['option']);

                if (is_array($result)) {
                    if ($orm_array['option']['limit'] > 1) {
                        foreach ($result as $row)
                            $this->cache_set($this->objBase . '.' . @$row['id'], $row);
                    }
                    else
                        $this->cache_set($this->objBase . '.' . @$result['id'], $result);
                }
            }
        }
        else {

            if ($orm_array['select'][0] == '*')
                $result = $this->Items[$param[0]][$param[1]];
            else {
                $select = explode(",", $orm_array['select'][0]);
                foreach ($select as $val)
                    $result[$val] = trim($this->Items[$param[0]][$param[1]][$val]);
            }
        }
        return $result;
    }

    /**
     * Проверка на наличие записи
     */
    function cache_check($param) {

        if (!is_array(@$this->Items[$param[0]][$param[1]])) {
            return true;
        }
        return false;
    }

    /**
     * Добавления элемента в массив
     */
    function cache_set($param, $value) {
        $param = explode(".", $param);

        // Проверка на лимит кэша
        if (count(@$this->Items[$param[0]]) < $this->cache_limit) {
            $this->Items[$param[0]][$param[1]] = $value;

            // Форматирование массива
            if (is_array($this->cache_format))
                foreach ($this->cache_format as $val)
                    $this->Items[$param[0]][$param[1]][$val] = null;
        }
    }

    /**
     * Выборка из БД SELECT по заданный параметрам с проверкой кэша
     * @param array $select массив ячеек для выборки
     * @param array $where массив параметра whree
     * @param array $order массив параметра order by
     * @param array $option массив параметра дополнительных опций [limit]
     * @param string $class_name имя класс для отладки
     * @param string $function_name имя метода для отладки
     * @return array
     */
    function select_cache($select, $where = false, $order = false, $option = false, $class_name = false, $function_name = false) {
        $memory_name = null;
        if (!empty($where['id'])) {
            $memory_name = $this->objBase . '.' . str_replace('=', '', $where[$this->cache_sort]);
        }

        $result = $this->cache_get($memory_name, array('select' => $select, 'where' => $where,
            'order' => $order, 'option' => $option, 'class_name' => $class_name, 'function_name' => $function_name));
        return $result;
    }

    /**
     * Выборка из БД SELECT по заданный параметрам
     * <code>
     * // example:
     * $PHPShopOrm= new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->select(array('id','name'),array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1));
     * </code>
     * @param array $select массив ячеек для выборки
     * @param array $where массив параметра whree
     * @param array $order массив параметра order by
     * @param array $option массив параметра дополнительных опций [limit]
     * @param string $class_name имя класс для отладки
     * @param string $function_name имя метода для отладки
     * @return array
     */
    function select($select = array('*'), $where = false, $order = false, $option = false, $class_name = false, $function_name = false) {

        if ($this->cache) {
            $result = $this->select_cache($select, $where, $order, $option, $class_name, $function_name);
            $this->clean();
        } else {
            $result = $this->select_native($select, $where, $order, $option, $class_name, $function_name);
            $this->clean();
        }

        return $result;
    }

    /**
     * Выборка из БД SELECT по заданный параметрам
     * @param array $select массив ячеек для выборки
     * @param array $where массив параметра whree
     * @param array $order массив параметра order by
     * @param array $option массив параметра дополнительных опций [limit]
     * @return array
     */
    function select_native($select = array('*'), $where = false, $order = false, $option = false, $class_name = false, $function_name = false) {

        // Выборка по параметрам SELECT
        if (is_array($select)) {
            $this->_SQL.='select ';
            foreach ($select as $value) {
                $this->_SQL.=$value;
                if ($this->nSelect < count($select))
                    $this->_SQL.=',';
                $this->nSelect++;
            }
        }

        $this->_SQL.=' from ' . $this->objBase;

        // Выборка по параметрам WHERE
        if (!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach ($where as $pole => $value) {
                $this->_SQL.=$pole . $value;
                if ($this->nWhere < count($where))
                    $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Сортировка
        if (!empty($order) and is_array($order))
            foreach ($order as $pole => $value) {
                $this->_SQL.=' ' . $pole . ' by ' . $value;
                if (!empty($option['order']))
                    $this->_SQL.=' ' . $option['order'] . ' ';
            }

        // Опции LIMIT
        if (!empty($option['limit']))
            $this->_SQL.=' limit ' . $option['limit'];

        // Целиковый запрос
        if (!empty($this->sql)) {
            $option['limit'] = 1000;
            $this->_SQL = $this->sql;
        }


        // Трассировка
        if ($this->debug) {
            if (empty($this->cache) and !empty($class_name))
                $this->comment = $class_name . '.' . $function_name;
            $this->setError("SQL Запрос: ", $this->_SQL);
        }

        // Возвращаем данные в виде массива
        if ($this->install) {
            if ($this->mysql_error)
                $result = mysqli_query($this->link_db, $this->_SQL) or die($this->setError("SQL Ошибка для [" . $this->_SQL . "] ", mysqli_error($this->link_db), true));
            else
                $result = mysqli_query($this->link_db, $this->_SQL);
        }
        else
            $result = mysqli_query($this->link_db, $this->_SQL) or die(PHPShopBase::errorConnect(102));

        if ($result) {
            $num = mysqli_num_rows($result);
            $this->numrows = $num;
            while ($row = mysqli_fetch_assoc($result))
                if ($num > 1 or $option['limit'] > 1 or strlen($option['limit']) > 1)
                    $this->_DATA[] = $row;
                else
                    $this->_DATA = $row;
        }


        // Счетчик запросов
        @$GLOBALS['SysValue']['sql']['num']++;

        // Проверка на большой массив, убирается чистка на слеши для экономии памяти
        if ($num > 1000)
            return $this->_DATA;
        elseif (!empty($this->_DATA))
            return stripslashes_deep($this->_DATA);
    }

    /**
     * Вывод сообщения об ошибке
     * @param string $name имя функции
     * @param string $action ошибка
     */
    function setError($name, $action, $stylesheet = false) {

        if ($this->comment)
            $comment = '<br>Комментарий: ' . $this->comment;
        else
            $comment = null;

        if (!class_exists('PHPShopGUI') or !empty($stylesheet))
            $error = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">';
        else
            $error = null;

        $error.='<div class="alert alert-info alert-dismissible" id="debug-message" role="alert" style="margin:10px">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><span class="glyphicon glyphicon-alert"></span> ' . $name . '</strong> ' . $action . '
</div>';
        echo $error;
    }

    /**
     * Распаковка массива в глобальные переменные
     */
    function var_export() {
        foreach ($this->_DATA as $var)
            foreach ($var as $name => $value)
                $GLOBALS[$$name] = $value;
    }

    /**
     * Обновление БД update
     * <code>
     * // example:
     * $PHPShopOrm= new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->update($_POST,array('id'=>'=10'));
     * </code>
     * @param array $value массив значений
     * @param array $where массив параметра whree
     * @param string $prefix префикс полей в форме [_new]
     * @return mixed
     */
    function update($value, $where = false, $prefix = '_new') {

        $this->_SQL = 'update ' . $this->objBase . ' set ';
        $_KEY = $this->findKey();

        foreach ($_KEY as $key => $v)
            if (isset($value[$key . $prefix])) {
                $this->_SQL.="`" . $key . "`='" . @addslashes($value[$key . $prefix]) . "',";
            }
        $this->_SQL = substr($this->_SQL, 0, strlen($this->_SQL) - 1);

        // Выборка по параметрам WHERE
        if (!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach ($where as $pole => $value) {
                $this->_SQL.=$pole . $value;
                if ($this->nWhere < count($where))
                    $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Целиковый запрос
        if (!empty($this->sql))
            $this->_SQL = $this->sql;

        // Трассировка
        if ($this->debug)
            $this->setError("SQL Запрос: ", $this->_SQL);

        // Выполнение
        if (mysqli_query($this->link_db, $this->_SQL)) {
            $this->clean();
            return true;
        }
        else
            return mysqli_error($this->link_db);
    }

    /**
     * Анализатор БД на наличие ячеек с заданным именем (PHP 7)
     * @return array
     */
    function findKey() {
        $result = mysqli_query($this->link_db, 'show fields from ' . $this->objBase);
        while ($row = mysqli_fetch_array($result)) {
            $return[$row['Field']] = null;
        }
        return $return;
    }

    /**
     * Удаление из БД delete
     * <code>
     * // example:
     * $PHPShopOrm = new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->delete(array('id'=>'=10'));
     * </code>
     * @param array $where массив параметра whree
     * @return mixed
     */
    function delete($where) {
        $this->_SQL = 'delete from ' . $this->objBase;

        // Выборка по параметрам WHERE
        if (!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach ($where as $pole => $value) {
                $this->_SQL.=$pole . $value;
                if ($this->nWhere < count($where))
                    $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Целиковый запрос
        if (!empty($this->sql))
            $this->_SQL = $this->sql;

        // Трассировка
        if ($this->debug)
            $this->setError("SQL Запрос: ", $this->_SQL);

        // Выполнение
        if (mysqli_query($this->link_db, $this->_SQL))
            return true;
        else
            return mysqli_error($this->link_db);
    }

    /**
     * Универсальный запрос к БД
     * // example:
     * $PHPShopOrm = new PHPShopOrm();
     * $PHPShopOrm->query('select id,name from phpshop_categories where id=1 order by id DESC limit 1');
     * </code>
     * @param string $sql запро к БД в формате SQL
     * @return mixed
     */
    function query($sql) {
        $this->_SQL = $sql;

        // Трассировка
        if ($this->debug)
            $this->setError("SQL Запрос: ", $this->_SQL);

        if ($this->mysql_error)
            $result = mysqli_query($this->link_db, $this->_SQL) or die($this->setError("SQL Ошибка для [" . $this->_SQL . "] ", mysqli_error($this->link_db) . ""));
        else
            $result = mysqli_query($this->link_db, $this->_SQL);

        // Счетчик запросов
        $GLOBALS['SysValue']['sql']['num']++;

        return $result;
    }

    /**
     * Вывод отладочной информации
     * @param mixed $var данные для вывода
     */
    function trace($var) {
        echo '<pre>';
        print_r($var);
        echo "</pre>";
    }

    /**
     * Вставка данных в БД insert
     * <code>
     * // example:
     * $PHPShopOrm = new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->insert(array('name_new'=>'Hi Test2'));
     * </code>
     * @param array $value массив значений
     * @param string $prefix префикс полей в форме [_new]
     * @return mixed
     */
    function insert($value, $prefix = '_new') {
        $this->_SQL = 'insert into ' . $this->objBase . ' set ';
        $_KEY = $this->findKey();

        foreach ($_KEY as $key => $v)
            if (isset($value[$key . $prefix])) {
                $this->_SQL.="`" . $key . "`='" . @addslashes($value[$key . $prefix]) . "',";
            }
        $this->_SQL = substr($this->_SQL, 0, strlen($this->_SQL) - 1);

        // Целиковый запрос
        if (!empty($this->sql))
            $this->_SQL = $this->sql;

        // Трассировка
        if ($this->debug)
            $this->setError("SQL Запрос: ", $this->_SQL);

        // Выполнение
        if (mysqli_query($this->link_db, $this->_SQL))
            return mysqli_insert_id($this->link_db);
        else
            return mysqli_error($this->link_db);
    }

    /**
     * Очистка данных
     */
    function clean() {
        $this->nWhere = 1;
        $this->nSelect = 1;
        $this->_SQL = '';
        unset($this->_DATA);
    }

    /**
     * Корректировка пустых значений
     */
    function updateZeroVars() {
        $Arg = func_get_args();
        foreach ($Arg as $value) {

            if (strpos($value, '.')) {
                $param = explode(".", $value);
                if (empty($_POST[$param[0]][$param[1]]))
                    $_POST[$param[0]][$param[1]] = 0;
            }
            else if (empty($_POST[$value]))
                $_POST[$value] = 0;
        }
    }

}

/**
 * Убираем слеши в массиве
 * @param array $value массив данных
 * @return array
 */
function stripslashes_deep($value) {
    $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
    return $value;
}

/*
  Пример выполнения ORM:

  1. Выборка
  $PHPShopOrm->select(array('id','name'),array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1));
  или
  $PHPShopOrm->sql='select id,name from phpshop_categories where id=1 order by id DESC limit 1';
  $PHPShopOrm->select();

  2. Обновление
  $PHPShopOrm->update($_REQUEST,array('id'=>'=10'));

  3. Вставка
  $PHPShopOrm->insert(array('name_new'=>'Hi Test2'));

  4. Удаление
  $PHPShopOrm->delete(array('id'=>'=10'));
 */
?>