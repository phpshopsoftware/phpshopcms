<?php

/**
 * Библиотека парсинга данных
 * @author PHPShop Software
 * @version 1.8
 * @package PHPShopParser
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
            "/(\"|\'|=)images\//i" => "\\1" . $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
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
            "/(\"|\'|=)images\//i" => "\\1" . $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
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
        echo ('<div class="alert alert-danger"><h4>В шаблоне обнаружена ошибка выполнения PHP</h4>');
        echo ('Код содержащий ошибки:');
        echo ('<pre>');
        echo ($str[2]);
        echo ('</pre></div>');
        return ob_get_clean();
    }
    return ob_get_clean();
}

/**
 * Библиотека парсинга CSS
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopParser
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
                    $this->css_array[$element][$set] = 'hue-rotate(' . $value . 'deg)' . $add;
                }
                $this->css_array[$element]['-editor-filter'] = $value;

                break;

            // Остальное
            default: $this->css_array[$element][$param] = $value . $add;
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

/**
 * Парсер главного шаблона и вывод результата на экран
 * @package PHPShopParser
 * @param string $TemplateName имя файла шаблона
 * @return string
 */
function ParseTemplate($TemplateName) {
    global $SysValue;

    $file = tmpGetFile($SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);
    $string = Parser($file);

    // Реальный путь
    $path_parts = pathinfo($_SERVER['PHP_SELF']);
    if (getenv("COMSPEC"))
        $dirSlesh = "\\";
    else
        $dirSlesh = "/";
    $root = $path_parts['dirname'] . "/";
    if ($path_parts['dirname'] != $dirSlesh) {
        $replaces = array(
            "/(\"|\'|=)images\//i" => "\\1" . $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/\/favicon.ico/i" => $root . "favicon.ico",
            "/java\//i" => $root . "java/",
            "/css\//i" => "/css/",
            "/phpshop\//i" => $root . "phpshop/",
            "/\/order\//i" => $root . "order/",
            "/\/done\//i" => $root . "done/",
            "/\/print\//i" => $root . "print/",
            "/\/links\//i" => $root . "links/",
            "/\/files\//i" => $root . "files/",
            "/\/opros\//i" => $root . "opros/",
            "/\/page\//i" => $root . "page/",
            "/\/news\//i" => $root . "news/",
            "/\/gbook\//i" => $root . "gbook/",
            "/\/users\//i" => $root . "users/",
            "/\/clients\//i" => $root . "clients/",
            "/\/price\//i" => $root . "price/",
            "/\/pricemail\//i" => $root . "pricemail/",
            "/\/compare\//i" => $root . "compare/",
            "/\/wishlist\//i" => $root . "wishlist/",
            "/\/shop\/CID/i" => $root . "shop/CID",
            "/\/shop\/UID/i" => $root . "shop/UID",
            "/\/search\//i" => $root . "search/",
            "/\"\/\"/i" => $root,
            "/\/notice\//i" => $root . "notice/",
            "/\/map\//i" => $root . "map/",
            "/\/success\//i" => $root . "success/",
            "/\/fail\//i" => $root . "fail/",
            "/\/rss\//i" => $root . "rss/",
            "/\/newtip\//i" => $root . "newtip/",
            "/\/spec\//i" => $root . "spec/",
            "/\/forma\//i" => $root . "forma/",
            "/\/newprice\//i" => $root . "newprice/",
        );
    } else {
        $replaces = array(
            "/(\"|\'|=)images\//i" => "\\1" . $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/java\//i" => "/java/",
            "/css\//i" => "/css/",
            "/phpshop\//i" => "/phpshop/",
        );
    }
    echo preg_replace(array_keys($replaces), array_values($replaces), $string);
}

/**
 * Парсер дополнительного шаблона и возврат результата
 * @package PHPShopParser
 * @param string $TemplateName имя файла шаблона
 * @param bool $mod шаблон для модуля
 * @return string
 */
function ParseTemplateReturn($TemplateName, $mod = false, $debug = false) {
    global $SysValue;

    if ($mod)
        $file = tmpGetFile($TemplateName);
    else
        $file = tmpGetFile($SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);
    $dis = Parser($file);

    $add = ' id="data-source" data-toggle="tooltip" data-placement="auto" data-source="' . $TemplateName . '" title="Показать [Ctrl + &crarr;]" ';


    if ($debug and !empty($_COOKIE['debug_template'])) {
        if (strstr($dis, '<li') or strstr($dis, 'class="product-col"'))
            $result = str_replace(array('<li', 'class="product-col"'), array('<li' . $add, 'class="product-col"' . $add), $dis);
        else
            $result = '<div ' . $add . '>' . $dis . '</div>';
    }
    else
        $result = $dis;

    return $result;
}

// Обработка PHP тегов
function evalstr($str) {
    ob_start();
    if (parser_function_guard == 'true') {
        if (!allowedFunctions($str[2]))
            return ob_get_clean();
    }
    if (eval(stripslashes($str[2])) !== NULL) {
        echo ('<div class="alert alert-danger"><h4>В шаблоне обнаружена ошибка выполнения PHP</h4>');
        echo ('Код содержащий ошибки:');
        echo ('<pre>');
        echo ($str[2]);
        echo ('</pre></div>');
        return ob_get_clean();
    }
    return ob_get_clean();
}

// Обработка PHP тегов, список разрешенных функций
function allowedFunctions($str) {
    $Functions = array(
        'if',
        'else',
        'switch',
        'for',
        'foreach',
        'echo',
        'print',
        'print_r',
        'array',
        'isset',
        'empty',
        'chr',
        'str_replace',
        'empty'
    );

    $allowFunctions = array_merge($Functions, explode(',', parser_function_allowed));
    preg_match_all('/\s*([A-Za-z0-9_$]+)\s*\(/isU', $str, $findedFunctions);
    $remElements = array_diff($findedFunctions[1], $allowFunctions);

    $denyFunctions = explode(',', parser_function_deny);
    foreach ($denyFunctions as $deny)
        if (stristr($str, $deny))
            $remElements[] = $deny;

    if (count($remElements) > 0) {
        echo ('<div class="alert alert-warning"><h4>В шаблоне обнаружена запрещенная функция</h4>');
        echo ('Список найденных запрещенных функций:');
        echo ('<pre>');
        foreach ($remElements as $remElement) {
            echo ($remElement . '()');
        }
        echo ('</pre>');
        echo ('Список разрешенных функций (добавить свою функцию можно в phpshop/inc/config.ini секция [function]):');
        echo ('<pre>');
        foreach ($allowFunctions as $allowFunction) {
            echo ($allowFunction . '()<br>');
        }
        echo ('</pre></div>');
        return false;
    } else {
        return true;
    }
}

// Обертка парсера
function SysValueReturn($m) {
    global $SysValue;
    return $SysValue["other"][$m[1]];
}

/**
 * Парсер PHP тегов в тексте
 * @package PHPShopParser
 * @param string $string текст
 * @param string $debug имя файла шаблона для отладки
 * @return string
 */
function Parser($string,$debug=false) {
    
    $dis = @preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'SysValueReturn', @preg_replace_callback("/(@php)(.*)(php@)/sU", "evalstr", str_replace('&#43;', '+', $string)));
    
    $add = ' id="data-source" data-toggle="tooltip" data-placement="auto" data-source="' . $debug . '" title="Показать [Ctrl + &crarr;]" ';

    if ($debug and !empty($_COOKIE['debug_template'])) {
        if (strstr($dis, '<li') or strstr($dis, 'class="product-col"'))
            $result = str_replace(array('<li', 'class="product-col"'), array('<li' . $add, 'class="product-col"' . $add), $dis);
        else
            $result = '<div ' . $add . '>' . $dis . '</div>';
    }
    else
        $result = $dis;


    return $result;
}

/**
 * Чтение файла шаблона
 * @param string $path имя файла шаблона
 * @return mixed
 */
function tmpGetFile($path) {
    if (strpos($path, '.tpl')) {
        $file = @file_get_contents($path);
        if (!$file)
            return false;
        return $file;
    }
    else
        return false;
}

?>