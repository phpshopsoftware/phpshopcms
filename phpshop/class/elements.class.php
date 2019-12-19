<?php

/**
 * Родительский класс создания элементов
 * Примеры использования размещены в папке phpshop/inc/
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopElements {

    /**
     * @var string имя БД
     */
    var $objBase;
    var $objPath;

    /**
     * @var bool режим отладки
     */
    var $debug = false;
    var $cache = false;
    var $cache_format = array();

    /**
     * @var string результат работы парсера
     */
    var $Disp;

    /**
     * Конструктор
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
     * Добавление в переменную вывода через парсер
     * @param string $template имя шаблона для паисинга
     */
    function addToTemplate($template) {
        $this->Disp.=ParseTemplateReturn($template);
    }

    /**
     * Добавление в переменную вывода
     * @param sting $content контент
     */
    function add($content) {
        $this->Disp.=$content;
    }

    /**
     * Парсинг шаблона
     * @param string $template имя шаблона
     * @param bool $mod использование шаблона в модуле
     * @return string
     */
    function parseTemplate($template, $mod = false) {
        return ParseTemplateReturn($template, $mod);
    }

    /**
     * Создание системной переменной для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * Выдача системной переменной
     * @param string $param раздел.имя переменной
     * @return mixed
     */
    function getValue($param) {
        $param = explode(".", $param);
        return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * Выдача переменной из кэша
     * @param string $param раздел.имя переменной
     * @return string
     */
    function getValueCache($param) {
        return $this->LoadItems[$param];
    }

    /**
     * Иницилизация переменной по результату выполенния функции
     * @param string $method_name имя функции
     * @param bool $flag добавление данных в переменную
     */
    function init($method_name, $flag = false) {

        // Если переменная не определена модулем
        if (!empty($flag) and $this->isAction($method_name))
            $this->set($method_name, call_user_func(array(&$this, $method_name)), true);

        elseif (empty($this->SysValue['other'][$method_name])) {
            if ($this->isAction($method_name))
                $this->set($method_name, call_user_func(array(&$this, $method_name)));
            elseif ($this->isAction("index"))
                $this->set($method_name, call_user_func(array(&$this, 'index')));
            else
                $this->setError("index", "метод не существует");
        }
    }

    /**
     * Проверка экшена
     * @param string $method_name имя метода
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $method_name))
            return true;
    }

    /**
     * Сообщение об ошибке
     * @param string $name имя функции
     * @param string $action сообщение
     */
    function setError($name, $action) {
        echo '<p><span style="color:red">Ошибка обработчика события: </span> <strong>' . $name . '()</strong>
	 <br><em>' . $action . '</em></p>';
    }

    function __call($name, $arguments) {
        if ($name == __CLASS__) {
            self::__construct();
        }
    }

}

?>