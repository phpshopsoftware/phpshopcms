<?php

/**
 * Библиотека парсинга данных
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 */
class PHPShopParser {

    /**
     * Проверка шаблона на присутствие переменной
     * @param string $path путь к файлу шаблона
     * @param string $value переменная шабонизатора
     * @return boolean 
     */
    static function check($path, $value) {
        $string = null;
        $path = $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $path;
        if (file_exists($path))
            $string = @file_get_contents($path);
        else
            echo "Error Tmp File: $path";
        if (stristr($string, '@' . $value . '@'))
            return true;
    }

    /**
     * Проверка  папки шаблона на присутствие в ней файла шаблона
     * @param string $path путь к файлу шаблона
     * @return boolean 
     */
    static function checkFile($path, $mod = false) {
        if (!$mod)
            $path = $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $path;
        if (file_exists($path))
            return true;
        else
            return false;
    }

    static function replacedir($string) {
        $replaces = array(
            "/images\//i" => $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/java\//i" => "/java/",
            "/css\//i" => "/css/",
            "/phpshop\//i" => "/phpshop/",
            "/\/id\//i" => $GLOBALS['SysValue']['dir']['dir'] . "/id/",
        );
        return $string = preg_replace(array_keys($replaces), array_values($replaces), $string);
    }

    /**
     * Обработка файла шаблона, вставка переменнных
     * @param string $path путь к файлу шаблона
     * @param bool $return режим вывода информации или возврата информации
     * @param bool $replace режим замены 
     * @param bool $check_template поиск файла в шаблоне
     * @return string
     */
    static function file($path, $return = false, $replace = true, $check_template = false) {

        $string = null;

        // Поиск шаблона модуля в основном шаблоне
        if ($check_template) {

            $path_template = str_replace('./phpshop', $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'], $path);
            if (is_file($path_template))
                $path = $path_template;
        }



        if (is_file($path))
            $string = @file_get_contents($path);
        else
            echo "Error Tpl File: $path";

        $replaces = array(
            "/images\//i" => $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/java\//i" => "/java/",
            "/phpshop\//i" => "/phpshop/",
        );

        $string = preg_replace_callback("/(@php)(.*)(php@)/sU", "phpshopparserevalstr", $string);
        //$string = preg_replace_callback("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $string);
        $string = preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'PHPShopParser::SysValueReturn', $string);

        if (!empty($replace))
            $string = preg_replace(array_keys($replaces), array_values($replaces), $string);

        if (!empty($return))
            return $string;
        else
            echo $string;
    }

    /**
     * Создание системной переменной для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    static function set($name, $value, $flag = false) {
        if ($flag)
            $GLOBALS['SysValue']['other'][$name].=$value;
        else
            $GLOBALS['SysValue']['other'][$name] = $value;
    }

    /**
     * Выдача системной переменной
     * @param string $name
     * @return string
     */
    static function get($name) {
        return $GLOBALS['SysValue']['other'][$name];
    }

    static function SysValueReturn($m) {
        global $SysValue;
        return $SysValue["other"][$m[1]];
    }

}

// Обработка php тегов
function phpshopparserevalstr($str) {
    ob_start();
    if (eval(stripslashes($str[2])) !== NULL) {
        echo ('<center style="color:red"><br><br><b>PHPShop Template Code: В шаблоне обнаружена ошибка выполнения php</b><br>');
        echo ('Код содержащий ошибки:');
        echo ('<pre>');
        echo ($str[2]);
        echo ('</pre></center>');
        return ob_get_clean();
    }
    return ob_get_clean();
}

/**
 * Библиотека парсинга CSS
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopCssParser {

    var $file;
    var $css_array;

    function __construct($file) {
        $this->file = $file;
    }

    function parse() {
        if (file_exists($this->file)) {
            $css = file_get_contents($this->file);
            preg_match_all('/(?ims)([a-z0-9\s\.\:#_\-@,>]+)\{([^\}]*)\}/', $css, $arr);
            $result = array();
            foreach ($arr[0] as $i => $x) {
                $selector = trim($arr[1][$i]);
                $rules = explode(';', trim($arr[2][$i]));
                $rules_arr = array();
                foreach ($rules as $strRule) {
                    if (!empty($strRule)) {
                        $rule = explode(":", $strRule);
                        $rules_arr[trim($rule[0])] = trim($rule[1]);
                    }
                }

                $result[$selector] = $rules_arr;
            }

            $this->css_array = $result;
        }
        return $this->css_array;
    }

    function getParam($element, $param) {
        return $this->css_array[$element][$param];
    }

    function setParam($element, $param, $value, $add = ' !important') {
        
        switch ($param) {

            // Фильтр
            case "filter":
                $filters = array('filter', '-webkit-filter', '-ms-filter', '-o-filter', '-moz-filter');
                foreach ($filters as $set) {
                    $this->css_array[$element][$set] = 'hue-rotate(' . $value . 'deg)'.$add;
                }
                $this->css_array[$element]['-editor-filter'] = $value;

                break;

            // Остальное
            default: $this->css_array[$element][$param] = $value.$add;
        }
    }

    function compile() {
        $css = null;
        if (is_array($this->css_array))
            foreach ($this->css_array as $k => $v) {
                $css.='
' . $k . '{
';
                if (is_array($v))
                    foreach ($v as $name => $rule)
                        $css.=$name . ':' . $rule . ';
';

                $css.='}';
            }
        return $css;
    }

}