<?php

/**
 * ���������� �������������� �����
 * @author PHPShop Software
 * @version 1.7
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopString {

    /**
     * ��������� Win 1251 � UTF8
     * @param string $in_text
     * @return string
     */
    static function win_utf8($in_text) {
        if (function_exists('iconv')) {
            $output = iconv("windows-1251", "utf-8", $in_text);
        } else {

            $output = null;
            $other[1025] = "�";
            $other[1105] = "�";
            $other[1028] = "�";
            $other[1108] = "�";
            $other[1030] = "I";
            $other[1110] = "i";
            $other[1031] = "�";
            $other[1111] = "�";

            for ($i = 0; $i < strlen($in_text); $i++) {
                if (ord($in_text{$i}) > 191) {
                    $output.="&#" . (ord($in_text{$i}) + 848) . ";";
                } else {
                    if (array_search($in_text{$i}, $other) === false) {
                        $output.=$in_text{$i};
                    } else {
                        $output.="&#" . array_search($in_text{$i}, $other) . ";";
                    }
                }
            }
        }

        return $output;
    }

    /**
     * ����������� utf8 � win1251
     * @param string $s ������
     * @return string
     */
    static function utf8_win1251($s) {
        $s = strtr($s, array("\xD0\xB0" => "�", "\xD0\x90" => "�", "\xD0\xB1" => "�", "\xD0\x91" => "�", "\xD0\xB2" => "�", "\xD0\x92" => "�", "\xD0\xB3" => "�", "\xD0\x93" => "�", "\xD0\xB4" => "�", "\xD0\x94" => "�", "\xD0\xB5" => "�", "\xD0\x95" => "�", "\xD1\x91" => "�", "\xD0\x81" => "�", "\xD0\xB6" => "�", "\xD0\x96" => "�", "\xD0\xB7" => "�", "\xD0\x97" => "�", "\xD0\xB8" => "�", "\xD0\x98" => "�", "\xD0\xB9" => "�", "\xD0\x99" => "�", "\xD0\xBA" => "�", "\xD0\x9A" => "�", "\xD0\xBB" => "�", "\xD0\x9B" => "�", "\xD0\xBC" => "�", "\xD0\x9C" => "�", "\xD0\xBD" => "�", "\xD0\x9D" => "�", "\xD0\xBE" => "�", "\xD0\x9E" => "�", "\xD0\xBF" => "�", "\xD0\x9F" => "�", "\xD1\x80" => "�", "\xD0\xA0" => "�", "\xD1\x81" => "�", "\xD0\xA1" => "�", "\xD1\x82" => "�", "\xD0\xA2" => "�", "\xD1\x83" => "�", "\xD0\xA3" => "�", "\xD1\x84" => "�", "\xD0\xA4" => "�", "\xD1\x85" => "�", "\xD0\xA5" => "�", "\xD1\x86" => "�", "\xD0\xA6" => "�", "\xD1\x87" => "�", "\xD0\xA7" => "�", "\xD1\x88" => "�", "\xD0\xA8" => "�", "\xD1\x89" => "�", "\xD0\xA9" => "�", "\xD1\x8A" => "�", "\xD0\xAA" => "�", "\xD1\x8B" => "�", "\xD0\xAB" => "�", "\xD1\x8C" => "�", "\xD0\xAC" => "�", "\xD1\x8D" => "�", "\xD0\xAD" => "�", "\xD1\x8E" => "�", "\xD0\xAE" => "�", "\xD1\x8F" => "�", "\xD0\xAF" => "�"));
        return $s;
    }

    /**
     * ������� � ��������
     * @param string $str
     * @return string
     */
    static function toLatin($str) {
        $str = strtolower($str);
        $str = str_replace("&nbsp;", "", $str);
        $str = str_replace("/", "", $str);
        $str = str_replace("\\", "", $str);
        $str = str_replace("(", "", $str);
        $str = str_replace(")", "", $str);
        $str = str_replace(":", "", $str);
        $str = str_replace("-", "", $str);
        $str = str_replace(" ", "-", $str);
        $str = str_replace("!", "", $str);
        $str = str_replace("|", "_", $str);
        $str = str_replace(".", "_", $str);
        $str = str_replace("�", "N", $str);
        $str = str_replace("?", "", $str);
        $str = str_replace("&nbsp", "_", $str);
        $str = str_replace("&amp;", '_', $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace(",", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);
        $str = str_replace("�", "", $str);

        $new_str = '';
        $_Array = array(" " => "_", "�" => "a", "�" => "b", "�" => "v", "�" => "g", "�" => "d", "�" => "e", "�" => "e", "�" => "zh", "�" => "z", "�" => "i", "�" => "y", "�" => "k", "�" => "l", "�" => "m", "�" => "n", "�" => "o", "�" => "p", "�" => "r", "�" => "s", "�" => "t", "�" => "u", "�" => "f", "�" => "h", "�" => "c", "�" => "ch", "�" => "sh", "�" => "sch", "�" => "i", "�" => "y", "�" => "i", "�" => "e", "�" => "u", "�" => "ya", "�" => "a", "�" => "b", "�" => "v", "�" => "g", "�" => "d", "�" => "e", "�" => "e", "�" => "zh", "�" => "z", "�" => "i", "�" => "y", "�" => "k", "�" => "l", "�" => "m", "�" => "n", "�" => "o", "�" => "p", "�" => "r", "�" => "s", "�" => "t", "�" => "Y", "�" => "u", "�" => "f", "�" => "h", "�" => "c", "�" => "ch", "�" => "sh", "�" => "sch", "�" => "e", "�" => "u", "�" => "ya", "." => "_", "$" => "i", "%" => "i", "&" => "_and_");

        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $val)
            if (empty($_Array[$val]))
                $new_str.=$val;
            else
                $new_str.=$_Array[$val];

        return preg_replace('([^a-z0-9_\.-])', '', $new_str);
    }

    // �������� �� ����� � ������� &
    static function mySubstr($str, $a) {
        $str = htmlspecialchars(strip_tags(trim($str)));
        $len = strlen($str);

        if ($len < $a)
            return $str;

        for ($i = 1; $i <= $a; $i++) {
            if ($str{$i} == ".")
                $T = $i;
        }

        if (substr($str, -1) == '&')
            $str = substr($str, 0, $len - 1);

        if ($T < 1)
            return substr($str, 0, $a) . "...";
        else
            return substr($str, 0, $T + 1);
    }

    /**
     * �������������� ���� ��� Excel
     * @param float $price ����
     * @param bool $direct �������� ������
     * @return float
     */
    static function toFloat($price, $direct = false) {
        if ($direct) {
            if (strpos($price, ','))
                $price = str_replace(',', '.', $price);
        }
        else {
            if (strpos($price, '.'))
                $price = str_replace('.', ',', $price);
        }
        return trim($price);
    }

    /**
     * ����������� ������ ��������
     * @param string $agent HTTP_USER_AGENT
     * @return string
     */
    static function getBrowser($agent = false) {

        if (empty($agent))
            $agent = $_SERVER["HTTP_USER_AGENT"];

        preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // ���������� ���������, ������� ��������� ����������� 90% ���������
        list(, $browser, $version) = $browser_info;
        if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera))
            return 'Opera ' . $opera[1];
        if ($browser == 'MSIE') {
            preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
            if ($ie)
                return $ie[1] . ' based on IE ' . $version;
            return 'IE ' . $version;
        }
        if ($browser == 'Firefox') {
            preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
            if ($ff)
                return $ff[1] . ' ' . $ff[2];
        }
        if ($browser == 'Opera' && $version == '9.80')
            return 'Opera ' . substr($agent, -5);
        if ($browser == 'Version')
            return 'Safari ' . $version;
        if (!$browser && strpos($agent, 'Gecko'))
            return 'Browser based on Gecko';
        return $browser . ' ' . $version;
    }

}

?>