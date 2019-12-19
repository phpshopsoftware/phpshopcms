<?php

/**
 * Библиотека локализации административных интерфейсов
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopGUI
 */
class PHPShopLang {
    var $langFile;
    var $doLang=true;
    var $UndefinedLangValue=array();
    /**
     * Включение режима создания файла локализации (требуется проставить права на запись для файла языка)
     * @var bool
     */
    var $createUndefined=true;
    var $charset="windows-1251";

    /**
     * Персональный ключ доступа к Google API для сайта smart.mysoft.ru (требуется указать свой)
     * http://code.google.com/intl/ru/apis/loader/signup.html
     * @var <type>
     */
    var $API_KEY='ABQIAAAA6omrd4WlIP7ZNsUTTc6uYhS_ps6EECQtukvcCEaHG66DCa_iExReO2aXIGbXVvsP9gKzBbXapVrZWQ';


    /**
     * Конструктор
     */
    function __construct($_classPath) {

        if(!empty($_SESSION['lang'])) {

            // Библиотека работы со строками
            PHPShopObj::loadClass("string");

            // Чтение локали файла
            $this->langFile = $_classPath.'locale/'.$_SESSION['lang'].'.ini';
            if(is_file($this->langFile)) {
                if($langArray = parse_ini_file($this->langFile,1)) {
                    $this->doLang = $this->check($langArray);
                }
                else echo "Error parsing locale ".$this->langFile;
            }
        }
        else {
            $this->doLang=false;
        }

    }

    /**
     * Анализ локали
     * @param array $langArray
     * @return bool
     */
    function check($langArray) {
        $langName = array_keys($langArray);
        if($_SESSION['lang'] != 'russian') {
            $this->LangValue['lang'] = $langArray['locale'];
            if(!empty($langArray['charset']['html'])) {
                $this->charset = $langArray['charset']['html'];
                $this->lang_name = $langArray['charset']['code'];
            }
            return true;
        }
    }

    /**
     * Перевод строки
     * @param string $value строка
     * @return string
     */
    function gettext($value) {

        if($this->doLang and !empty($value) and !preg_match("/([a-zA-Z0-9_\.-]){4,15}/",$value) and !is_numeric($value)) {
            $sourceValue = $value;
            $value = PHPShopString::toLatin($value);

            if(isset($this->LangValue['lang'][$value])) $locValue = $this->LangValue['lang'][$value];
            else {
                $locValue = 'Und: '.strip_tags($value);
                $this->UndefinedLangValue[strip_tags($value)] = $this->translate(strip_tags($sourceValue),$this->lang_name);
            }

        }else $locValue = $value;

        if(!empty($locValue))
        return $locValue;
        else return $value;
    }

    /**
     * Перевод Google API
     * @param string $text строка
     * @param string $lang_name имя локали (ru/en/uk)
     * @return string
     */
    function translate($text,$lang_name) {
        $text = urlencode(PHPShopString::win_utf8($text));
        $domain = "ajax.googleapis.com";
        $result='';
        $fp = fsockopen($domain, 80, $errno, $errstr);
        if (!$fp) {
            return exit("Ошибка соединения с сервером переводчика");
        } else {
            fputs($fp, "GET /ajax/services/language/translate?v=1.0&q=".$text."&langpair=ru%7C".$lang_name."&callback=foo&context=bar&key=".$this->API_KEY."  HTTP/1.0\r\n");
            fputs($fp, "Host: $domain\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            while (!feof($fp)) {
                $result.=fgets($fp, 1000);
            }
            fclose($fp);
        }

        preg_match('|"translatedText":"(.*?)"|is', $result, $locale);
        return $locale[1];
    }

    /**
     * Запись в файл локали новых данных
     */
    function write() {
        $updateLang='';

        if(is_array($this->UndefinedLangValue)) {

            // Массив нехватающего перевода
            foreach($this->UndefinedLangValue as $key=>$val)
                $updateLang.= $key.'="'.$val.'";
';
            if($this->doLang) {
                if (is_writable($this->langFile)) {

                    $fp = fopen($this->langFile, "a");
                    if ($fp) {
                        fputs($fp, $updateLang);
                        fclose($fp);
                    }
                }
            }
        }
    }

}

$GLOBALS['PHPShopLang'] = new PHPShopLang($GLOBALS['_classPath']);
$GLOBALS['PHPShopLangCharset'] = $GLOBALS['PHPShopLang']->charset;

/**
 * Локализация
 * @param string $value значение
 * @return string
 */
function __($value) {
    global $PHPShopLang;
    return $PHPShopLang->gettext($value);
}

/**
 * Запись недостующих данные в локализацию
 */
function writeLangFile() {
    global $PHPShopLang;
    if($PHPShopLang->createUndefined) $PHPShopLang->write();
}
?>