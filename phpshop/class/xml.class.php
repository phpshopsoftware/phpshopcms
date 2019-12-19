<?php

// ����� ��������� ��� xml2array
function array2iconv(&$value) {
    $value = iconv("UTF-8", "CP1251", $value);
}

/**
 * XML ���������� �� ������  JSON
 * @param string $filename ����� ����� xml
 * @param string $keyName �������� xml ��� ��������
 * @param bool $file xml �������� �� ����� [true] ��� ����������� $filename ��� �������� [false]
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
        echo ('�� ������� ���������� SimpleXML<br>');

    

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
 * ���������� �������
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
 * XML ����������
 * @param string $filename ����� ����� xml
 * @param string $keyName �������� xml ��� ��������
 * @param bool $file xml �������� �� ����� [true] ��� ����������� $filename ��� �������� [false]
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
 * �������������� UTF � Windows-1251
 * @param string $s ������
 * @return string
 */
function utf8_win1251($s) {
    $s = strtr($s, array("\xD0\xB0" => "�", "\xD0\x90" => "�", "\xD0\xB1" => "�", "\xD0\x91" => "�", "\xD0\xB2" => "�", "\xD0\x92" => "�", "\xD0\xB3" => "�", "\xD0\x93" => "�", "\xD0\xB4" => "�", "\xD0\x94" => "�", "\xD0\xB5" => "�", "\xD0\x95" => "�", "\xD1\x91" => "�", "\xD0\x81" => "�", "\xD0\xB6" => "�", "\xD0\x96" => "�", "\xD0\xB7" => "�", "\xD0\x97" => "�", "\xD0\xB8" => "�", "\xD0\x98" => "�", "\xD0\xB9" => "�", "\xD0\x99" => "�", "\xD0\xBA" => "�", "\xD0\x9A" => "�", "\xD0\xBB" => "�", "\xD0\x9B" => "�", "\xD0\xBC" => "�", "\xD0\x9C" => "�", "\xD0\xBD" => "�", "\xD0\x9D" => "�", "\xD0\xBE" => "�", "\xD0\x9E" => "�", "\xD0\xBF" => "�", "\xD0\x9F" => "�", "\xD1\x80" => "�", "\xD0\xA0" => "�", "\xD1\x81" => "�", "\xD0\xA1" => "�", "\xD1\x82" => "�", "\xD0\xA2" => "�", "\xD1\x83" => "�", "\xD0\xA3" => "�", "\xD1\x84" => "�", "\xD0\xA4" => "�", "\xD1\x85" => "�", "\xD0\xA5" => "�", "\xD1\x86" => "�", "\xD0\xA6" => "�", "\xD1\x87" => "�", "\xD0\xA7" => "�", "\xD1\x88" => "�", "\xD0\xA8" => "�", "\xD1\x89" => "�", "\xD0\xA9" => "�", "\xD1\x8A" => "�", "\xD0\xAA" => "�", "\xD1\x8B" => "�", "\xD0\xAB" => "�", "\xD1\x8C" => "�", "\xD0\xAC" => "�", "\xD1\x8D" => "�", "\xD0\xAD" => "�", "\xD1\x8E" => "�", "\xD0\xAE" => "�", "\xD1\x8F" => "�", "\xD0\xAF" => "�"));
    return $s;
}

/**
 * ������� XML � ������
 */
function parseDatabase($mvalues) {
    for ($i = 0; $i < count($mvalues); $i++)
        $mol[$mvalues[$i]["tag"]] = utf8_win1251($mvalues[$i]["value"]);

    $db = new XMLparser($mol);
    $array = $db->ar;
    return $array;
}

?>