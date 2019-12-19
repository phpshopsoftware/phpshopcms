<?php

/**
 * Библиотека навигации
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopNav {

    /**
     * @var array массив данных навигации
     */
    var $objNav;

    /**
     * Конструктор
     */
    function __construct() {
        $url = parse_url("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

        // Вырезаем, если в папке
        $path_parts = pathinfo($_SERVER['PHP_SELF']);
        $root = $path_parts['dirname'] . "/";
        if ($root != "//")
            if ($root != "\/")
                $url = str_replace($path_parts['dirname'] . "/", "/", $url);

                 $Query = @$url["query"];
        $Path = explode("/", $url["path"]);
        if (is_array($Path)) {
            $File = @explode("_", $Path[2]);
            $Prifix = @explode(".", $File[1]);
            $Name = @explode(".", $File[0]);
            $Page = @explode(".", $File[2]);
        }   
 
        parse_str($Query, $output);
        $longpage = explode(".", str_replace("/page/", "", $url["path"]));

        // Заглушка для index
        if (empty($Path[1]) or strpos($Path[1], '.html'))
            $Path[1] = 'index';

        $this->objNav = array(
            "truepath" => $url["path"],
            "path" => $Path[1],
            "nav" => $File[0],
            "name" => $Name[0],
            "id" => $Prifix[0],
            "page" => $Page[0],
            "querystring" => @$url["query"],
            "query" => $output,
            "longname" => $longpage[0],
            "url" => $url["path"]);
        $GLOBALS['SysValue']['nav'] = $this->objNav;
    }

    /**
     * Выдача переменной навигации path
     * @return string
     */
    function getPath() {
        return $this->objNav['path'];
    }

    /**
     * Проверка условия на несоответсвие разделу
     * @param mixed $path массив разделов
     * @return bool
     */
    function notPath($path) {
        if (is_array($path)) {
            foreach ($path as $val) {
                if ($this->objNav['path'] == $val)
                    return false;
            }
            return true;
        }
        else if ($this->objNav['path'] != $path)
            return true;
    }

    /**
     * Выдача переменной навигации nav
     * @return string
     */
    function getNav() {
        return $this->objNav['nav'];
    }

    /**
     * Выдача переменной навигации name
     * @return string
     */
    function getName($mod_replace = '/') {
        if (is_int($mod_replace)) {
            return $this->objNav['name'];
        }
        else
            return str_replace($mod_replace, '', $this->objNav['longname']);
    }

    /**
     * Выдача переменной навигации id
     * @return string
     */
    function getId() {
        return $this->objNav['id'];
    }

    /**
     * Выдача переменной навигации page
     * @return string
     */
    function getPage() {
        if ($this->objNav['page'] > 0 or $this->objNav['page'] == 'ALL')
            return $this->objNav['page'];
        else
            return 1;
    }

    function isPageAll() {
        if (strtoupper($this->objNav['page']) == 'ALL')
            return true;
    }

    /**
     * Проверка на главную страницу
     * @return bool
     */
    function index() {
        if ($this->objNav['path'] == 'index')
            return true;
    }

}

?>