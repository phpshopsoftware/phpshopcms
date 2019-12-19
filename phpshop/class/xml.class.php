<?php

// Смена кодировки для xml2array
function array2iconv(&$value) {
    $value = iconv("UTF-8", "CP1251", $value);
}

/**
 * XML обработчик на основе  JSON
 * @param string $filename адрес файла xml
 * @param string $keyName параметр xml для парсинга
 * @param bool $file xml читается из файла [true] или содержимого $filename при значении [false]
 * @return array
 */
function xml2array($filename, $keyName = false, $file = true) {

    if ($file)
        $data = @implode("", @file($filename));
    else
        $data = $filename;

    if (function_exists('simplexml_load_string')){
        $json = json_decode(json_encode((array) simplexml_load_string($data)), 1);
        array_walk_recursive($json, 'array2iconv');
    }
    else
        echo ('Не найдена компонента SimpleXML<br>');

    

    if ($keyName) {
        if (strpos($keyName, '.')) {
            $keys = explode(".", $keyName);
            return $json[$keys[0]][$keys[1]];
        }
        else
            return $json[$keyName];
    }
    else
        return $json;
}

/**
 * Содержание объекта
 */
class XMLparser {

    var $ar;

    function __construct($aa) {
        foreach ($aa as $k => $v) {
            $this->$k = $aa[$k];
            $this->ar[$k] = $this->$k;
        }
    }

}

/**
 * XML обработчик
 * @param string $filename адрес файла xml
 * @param string $keyName параметр xml для парсинга
 * @param bool $file xml читается из файла [true] или содержимого $filename при значении [false]
 * @return array
 */
function readDatabase($filename, $keyName, $file = true) {
    global $PHPShopSystem;

    if (!$PHPShopSystem) {
        $PHPShopSystem = new PHPShopSystem();
    }

    if ($file)
        $data = implode("", file($filename));
    else
        $data = $filename;
    
    $xmlencode = 'UTF-8';

    // Hook PHP 5.3, 5.4
    if ($xmlencode == 'UTF-8') {
        $data = str_replace('windows-1251', 'utf-8', $data);
        $data = iconv("windows-1251", "utf-8", $data);
    }

    $parser = xml_parser_create($xmlencode);
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

    foreach ($tags as $key => $val) {
        if ($key == $keyName) {
            $molranges = $val;

            for ($i = 0; $i < count($molranges); $i+=2) {
                $offset = $molranges[$i] + 1;
                $len = $molranges[$i + 1] - $offset;
                $tdb[] = parseDatabase(array_slice($values, $offset, $len));
            }
        }
        else
            continue;
    }
    return $tdb;
}

/**
 * Преобразование UTF в Windows-1251
 * @param string $s строка
 * @return string
 */
function utf8_win1251($s) {
    $s = strtr($s, array("\xD0\xB0" => "а", "\xD0\x90" => "А", "\xD0\xB1" => "б", "\xD0\x91" => "Б", "\xD0\xB2" => "в", "\xD0\x92" => "В", "\xD0\xB3" => "г", "\xD0\x93" => "Г", "\xD0\xB4" => "д", "\xD0\x94" => "Д", "\xD0\xB5" => "е", "\xD0\x95" => "Е", "\xD1\x91" => "ё", "\xD0\x81" => "Ё", "\xD0\xB6" => "ж", "\xD0\x96" => "Ж", "\xD0\xB7" => "з", "\xD0\x97" => "З", "\xD0\xB8" => "и", "\xD0\x98" => "И", "\xD0\xB9" => "й", "\xD0\x99" => "Й", "\xD0\xBA" => "к", "\xD0\x9A" => "К", "\xD0\xBB" => "л", "\xD0\x9B" => "Л", "\xD0\xBC" => "м", "\xD0\x9C" => "М", "\xD0\xBD" => "н", "\xD0\x9D" => "Н", "\xD0\xBE" => "о", "\xD0\x9E" => "О", "\xD0\xBF" => "п", "\xD0\x9F" => "П", "\xD1\x80" => "р", "\xD0\xA0" => "Р", "\xD1\x81" => "с", "\xD0\xA1" => "С", "\xD1\x82" => "т", "\xD0\xA2" => "Т", "\xD1\x83" => "у", "\xD0\xA3" => "У", "\xD1\x84" => "ф", "\xD0\xA4" => "Ф", "\xD1\x85" => "х", "\xD0\xA5" => "Х", "\xD1\x86" => "ц", "\xD0\xA6" => "Ц", "\xD1\x87" => "ч", "\xD0\xA7" => "Ч", "\xD1\x88" => "ш", "\xD0\xA8" => "Ш", "\xD1\x89" => "щ", "\xD0\xA9" => "Щ", "\xD1\x8A" => "ъ", "\xD0\xAA" => "Ъ", "\xD1\x8B" => "ы", "\xD0\xAB" => "Ы", "\xD1\x8C" => "ь", "\xD0\xAC" => "Ь", "\xD1\x8D" => "э", "\xD0\xAD" => "Э", "\xD1\x8E" => "ю", "\xD0\xAE" => "Ю", "\xD1\x8F" => "я", "\xD0\xAF" => "Я"));
    return $s;
}

/**
 * Парсинг XML в массив
 */
function parseDatabase($mvalues) {
    for ($i = 0; $i < count($mvalues); $i++)
        $mol[$mvalues[$i]["tag"]] = utf8_win1251($mvalues[$i]["value"]);

    $db = new XMLparser($mol);
    $array = $db->ar;
    return $array;
}

?>