<?php

/**
 * Библиотека форматирования строк
 * @author PHPShop Software
 * @version 1.7
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopString {

    /**
     * Кодировка Win 1251 в UTF8
     * @param string $in_text
     * @return string
     */
    static function win_utf8($in_text) {
        if (function_exists('iconv')) {
            $output = iconv("windows-1251", "utf-8", $in_text);
        } else {

            $output = null;
            $other[1025] = "Ё";
            $other[1105] = "ё";
            $other[1028] = "Є";
            $other[1108] = "є";
            $other[1030] = "I";
            $other[1110] = "i";
            $other[1031] = "Ї";
            $other[1111] = "ї";

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
     * Кодирование utf8 в win1251
     * @param string $s строка
     * @return string
     */
    static function utf8_win1251($s) {
        $s = strtr($s, array("\xD0\xB0" => "а", "\xD0\x90" => "А", "\xD0\xB1" => "б", "\xD0\x91" => "Б", "\xD0\xB2" => "в", "\xD0\x92" => "В", "\xD0\xB3" => "г", "\xD0\x93" => "Г", "\xD0\xB4" => "д", "\xD0\x94" => "Д", "\xD0\xB5" => "е", "\xD0\x95" => "Е", "\xD1\x91" => "ё", "\xD0\x81" => "Ё", "\xD0\xB6" => "ж", "\xD0\x96" => "Ж", "\xD0\xB7" => "з", "\xD0\x97" => "З", "\xD0\xB8" => "и", "\xD0\x98" => "И", "\xD0\xB9" => "й", "\xD0\x99" => "Й", "\xD0\xBA" => "к", "\xD0\x9A" => "К", "\xD0\xBB" => "л", "\xD0\x9B" => "Л", "\xD0\xBC" => "м", "\xD0\x9C" => "М", "\xD0\xBD" => "н", "\xD0\x9D" => "Н", "\xD0\xBE" => "о", "\xD0\x9E" => "О", "\xD0\xBF" => "п", "\xD0\x9F" => "П", "\xD1\x80" => "р", "\xD0\xA0" => "Р", "\xD1\x81" => "с", "\xD0\xA1" => "С", "\xD1\x82" => "т", "\xD0\xA2" => "Т", "\xD1\x83" => "у", "\xD0\xA3" => "У", "\xD1\x84" => "ф", "\xD0\xA4" => "Ф", "\xD1\x85" => "х", "\xD0\xA5" => "Х", "\xD1\x86" => "ц", "\xD0\xA6" => "Ц", "\xD1\x87" => "ч", "\xD0\xA7" => "Ч", "\xD1\x88" => "ш", "\xD0\xA8" => "Ш", "\xD1\x89" => "щ", "\xD0\xA9" => "Щ", "\xD1\x8A" => "ъ", "\xD0\xAA" => "Ъ", "\xD1\x8B" => "ы", "\xD0\xAB" => "Ы", "\xD1\x8C" => "ь", "\xD0\xAC" => "Ь", "\xD1\x8D" => "э", "\xD0\xAD" => "Э", "\xD1\x8E" => "ю", "\xD0\xAE" => "Ю", "\xD1\x8F" => "я", "\xD0\xAF" => "Я"));
        return $s;
    }

    /**
     * Перевод в латиницу
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
        $str = str_replace("№", "N", $str);
        $str = str_replace("?", "", $str);
        $str = str_replace("&nbsp", "_", $str);
        $str = str_replace("&amp;", '_', $str);
        $str = str_replace("ь", "", $str);
        $str = str_replace("Ь", "", $str);
        $str = str_replace("ъ", "", $str);
        $str = str_replace("«", "", $str);
        $str = str_replace("»", "", $str);
        $str = str_replace("“", "", $str);
        $str = str_replace(",", "", $str);
        $str = str_replace("™", "", $str);
        $str = str_replace("’", "", $str);
        $str = str_replace("®", "", $str);

        $new_str = '';
        $_Array = array(" " => "_", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "zh", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "i", "ы" => "y", "ь" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ё" => "e", "Ж" => "zh", "З" => "z", "И" => "i", "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t", "Ы" => "Y", "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "c", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch", "Э" => "e", "Ю" => "u", "Я" => "ya", "." => "_", "$" => "i", "%" => "i", "&" => "_and_");

        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $val)
            if (empty($_Array[$val]))
                $new_str.=$val;
            else
                $new_str.=$_Array[$val];

        return preg_replace('([^a-z0-9_\.-])', '', $new_str);
    }

    // Отрезаем до точки с заменой &
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
     * Форматирование цены для Excel
     * @param float $price цена
     * @param bool $direct обратная замена
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
     * Определение версии браузера
     * @param string $agent HTTP_USER_AGENT
     * @return string
     */
    static function getBrowser($agent = false) {

        if (empty($agent))
            $agent = $_SERVER["HTTP_USER_AGENT"];

        preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // регулярное выражение, которое позволяет отпределить 90% браузеров
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