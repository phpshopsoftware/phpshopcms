<?php

/**
 * Библиотека проверки безопасности
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopSecurity {

    /**
     * Проверка на пустые данные
     * @return bool
     */
    static function true_param() {
        $Arg = func_get_args();
        foreach ($Arg as $val) {
            if (empty($val))
                return false;
        }
        return true;
    }

    /**
     * Проверка расширения файла
     * @param string $sFileName имя файла
     * @return mixed
     */
    static function getExt($sFileName) {
        $sTmp = $sFileName;
        while ($sTmp != "") {
            $sTmp = strstr($sTmp, ".");
            if ($sTmp != "") {
                $sTmp = substr($sTmp, 1);
                $sExt = $sTmp;
            }
        }
        $pos = stristr($sFileName, "php");
        if ($pos === false)
            return strtolower($sExt);
    }

    /**
     * Очистка строк от [/]['][\]
     * @param string $str текст для проверки
     * @return string
     */
    static function CleanStr($str) {
        $str = str_replace("\/", "|", $str);
        //$str = str_replace("\"", "", $str);
        $str = str_replace("\\", "", $str);
        return str_replace("'", "", $str);
    }

    /**
     * Очистка тегов и переводов строк  [\r\n\t]
     * @param string $str текст для анализа
     * @return string
     */
    static function CleanOut($str) {
        $str = stripslashes($str);
        $str = preg_replace('([\r\n\t;])', '', $str);
        $str = @html_entity_decode($str,null,'windows-1251');
        
        return $str;
    }

    /**
     * Проверка почтового адреса
     * @param string $email адрес
     * @return bool
     */
    static function true_email($email) {
        if (strlen($email) > 100)
            return FALSE;
        return preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i", trim($email));
    }

    /**
     * Проверка логина
     * @param string $login логин
     * @return bool
     */
    static function true_login($login) {
        return preg_match("/^[a-zA-Z0-9_\.]{2,20}$/", trim($login));
    }
    
    
    /**
     * Проверка имени шаблона
     * @param string $login логин
     * @return bool
     */
    static function true_skin($skin) {
        return preg_match("/^[a-zA-Z0-9\-_\.\/]{2,30}$/", trim($skin));
    }

    /**
     * Проверка номера зааказа
     * @param string $num номер заказа
     * @return bool
     */
    static function true_order($num) {
        return preg_match("/^[0-9-]{4,20}$/", $num);
    }

    /**
     * Проверка номера
     * @param int $num номер
     * @return bool
     */
    static function true_num($num) {
        return preg_match("/^[0-9]{1,20}$/", $num);
    }

    /**
     * Проверка пароля
     * @param string $passw пароль
     * @return bool
     */
    static function true_passw($passw) {
        return preg_match("/^[a-zA-Z0-9_]{4,20}$/", trim($passw));
    }

    /**
     * Универсальная проверка
     * @param string $str строка
     * @param int $flag параметр [1]-корзину,[2]-преобразует все в код html,[3]-почту,[4]-ввод с формы,[5]-цифры
     * @return mixed
     */
    static function TotalClean($str, $flag = 2) {

        switch ($flag) {
            case 1:
                if (!preg_match("/([0-9])/", $str))
                    $str = "0";
                return abs($str);
                break;

            case 2:
                return htmlspecialchars(stripslashes($str),ENT_QUOTES,'windows-1251');
                break;

            case 3:
                if (!preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i", $str))
                    $str = "";
                return $str;
                break;

            case 4:
                if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $str))
                    $str = "";
                return htmlspecialchars(stripslashes($str),ENT_QUOTES,'windows-1251');
                break;

            case 5:
                if (preg_match("/[^(0-9)|(\-)|(\.]/", $str))
                    $str = 0;
                return $str;
                break;
        }
    }

    /**
     * Проверка Request переменных на запрещенные команды
     * @param string $search
     */
    static function RequestSearch($search) {
        $pathinfo = pathinfo($_SERVER['PHP_SELF']);
        $f = $pathinfo['basename'];
        if (empty($_SESSION['theme']))
            $_SESSION['theme'] = 'classic';
        $com = array("union", "select", "insert", "update", "delete");
        $mes = '<h3>Внимание</h3>Работа скрипта ' . $_SERVER['PHP_SELF'] . ' прервана из-за использования запрещенной команды';
        $mes2 = "<br>Удалите все вхождения этой команды в водимой информации.";
        foreach ($com as $v)
            if (@preg_match("/" . $v . "/i", $search))
                exit($mes . ' <b style="color:red">' . $v . '</b>' . $mes2);
    }

    /**
     * Проверка дял поиска
     * @param string $search
     * @return strong
     */
    static function true_search($search) {
        $count = strlen($search);
        $search = strtolower($search);
        $i = 0;
        while ($i < ($count / 7)) {
            $search = str_replace("'", "", $search);
            $search = str_replace("\\", "", $search);
            $search = str_replace("union", "", $search);
            $search = str_replace("select", "", $search);
            $search = str_replace("insert", "", $search);
            $search = str_replace("delete", "", $search);
            $search = str_replace(")", "", $search);
            $search = str_replace("(", "", $search);
            $i++;
        }

        if (!empty($search))
            return trim($search);
    }

}

?>