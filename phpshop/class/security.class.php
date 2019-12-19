<?php

/**
 * ���������� �������� ������������
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopSecurity {

    /**
     * �������� �� ������ ������
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
     * �������� ���������� �����
     * @param string $sFileName ��� �����
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
     * ������� ����� �� [/]['][\]
     * @param string $str ����� ��� ��������
     * @return string
     */
    static function CleanStr($str) {
        $str = str_replace("\/", "|", $str);
        //$str = str_replace("\"", "", $str);
        $str = str_replace("\\", "", $str);
        return str_replace("'", "", $str);
    }

    /**
     * ������� ����� � ��������� �����  [\r\n\t]
     * @param string $str ����� ��� �������
     * @return string
     */
    static function CleanOut($str) {
        $str = stripslashes($str);
        $str = preg_replace('([\r\n\t;])', '', $str);
        $str = @html_entity_decode($str,null,'windows-1251');
        
        return $str;
    }

    /**
     * �������� ��������� ������
     * @param string $email �����
     * @return bool
     */
    static function true_email($email) {
        if (strlen($email) > 100)
            return FALSE;
        return preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i", trim($email));
    }

    /**
     * �������� ������
     * @param string $login �����
     * @return bool
     */
    static function true_login($login) {
        return preg_match("/^[a-zA-Z0-9_\.]{2,20}$/", trim($login));
    }
    
    
    /**
     * �������� ����� �������
     * @param string $login �����
     * @return bool
     */
    static function true_skin($skin) {
        return preg_match("/^[a-zA-Z0-9\-_\.\/]{2,30}$/", trim($skin));
    }

    /**
     * �������� ������ �������
     * @param string $num ����� ������
     * @return bool
     */
    static function true_order($num) {
        return preg_match("/^[0-9-]{4,20}$/", $num);
    }

    /**
     * �������� ������
     * @param int $num �����
     * @return bool
     */
    static function true_num($num) {
        return preg_match("/^[0-9]{1,20}$/", $num);
    }

    /**
     * �������� ������
     * @param string $passw ������
     * @return bool
     */
    static function true_passw($passw) {
        return preg_match("/^[a-zA-Z0-9_]{4,20}$/", trim($passw));
    }

    /**
     * ������������� ��������
     * @param string $str ������
     * @param int $flag �������� [1]-�������,[2]-����������� ��� � ��� html,[3]-�����,[4]-���� � �����,[5]-�����
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
     * �������� Request ���������� �� ����������� �������
     * @param string $search
     */
    static function RequestSearch($search) {
        $pathinfo = pathinfo($_SERVER['PHP_SELF']);
        $f = $pathinfo['basename'];
        if (empty($_SESSION['theme']))
            $_SESSION['theme'] = 'classic';
        $com = array("union", "select", "insert", "update", "delete");
        $mes = '<h3>��������</h3>������ ������� ' . $_SERVER['PHP_SELF'] . ' �������� ��-�� ������������� ����������� �������';
        $mes2 = "<br>������� ��� ��������� ���� ������� � ������� ����������.";
        foreach ($com as $v)
            if (@preg_match("/" . $v . "/i", $search))
                exit($mes . ' <b style="color:red">' . $v . '</b>' . $mes2);
    }

    /**
     * �������� ��� ������
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