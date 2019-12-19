<?php

/**
 * Библиотека локализации
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopGUI
 */
class PHPShopLang {

    var $langFile;
    var $doLang = true;
    var $UndefinedLangValue = array();

    /**
     * Включение режима создания файла локализации (требуется проставить права на запись для файла языка)
     * @var bool
     */
    var $createUndefined = false;
    var $charset = "windows-1251";

    /**
     * Конструктор
     */
    function __construct($option = array('locale' => 'russian', 'path' => 'shop')) {

        $this->option = $option;
        if (empty($this->option['locale']))
            $_SESSION['lang'] = $this->option['locale'] = 'russian';

        // Чтение локали файла
        $this->langFile = $GLOBALS['_classPath'] . 'locale/' . $this->option['locale'] . '/' . $this->option['path'] . '.ini';
        if (is_file($this->langFile)) {
            if ($langArray = parse_ini_file_true($this->langFile, 1)) {
                $this->doLang = $this->check($langArray);
                $this->charset = $langArray['charset']['html'];
                $this->code = $langArray['charset']['code'];
            }
            else
                echo "Error parsing locale " . $this->langFile;
        }
    }

    /**
     * Анализ локали
     * @param array $langArray
     * @return bool
     */
    function check($langArray) {
        $GLOBALS['SysValue']['lang'] = $langArray['lang'];
        if ($_SESSION['lang'] != 'russian') {

            $this->LangValue['lang'] = $langArray['locale'];

            if (!empty($langArray['charset']['html'])) {
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

        if ($this->doLang and !empty($value)) {

            $sourceValue = $value;
            $value = md5($value);

            if (isset($this->LangValue['lang'][$value]))
                $locValue = $this->LangValue['lang'][$value];
            else {
                $locValue = strip_tags($sourceValue);
                $this->UndefinedLangValue[$value] = strip_tags($sourceValue);
            }
        }
        else
            $locValue = $value;

        if (!empty($locValue))
            return $locValue;
        else
            return $value;
    }

    /**
     * Запись в файл локали новых данных
     */
    function write() {
        $updateLang = '';

        if (is_array($this->UndefinedLangValue)) {

            // Массив нехватающего перевода
            foreach ($this->UndefinedLangValue as $key => $val)
                $updateLang.= $key . '="' . str_replace('"', '', $val) . '";
';
            if ($this->doLang) {
                if (is_writable($this->langFile)) {

                    $fp = fopen($this->langFile, "a");
                    if ($fp) {
                        fputs($fp, $updateLang);
                        fclose($fp);
                    }
                }
                else
                    echo 'Нет файла ' . $this->langFile;
            }
        }
    }

}

/**
 * Локализация
 * @param string $value значение
 * @return string
 */
function __($value) {
    global $PHPShopLang;
    if ($PHPShopLang)
        return $PHPShopLang->gettext($value);
    else return $value;
}

/**
 * Локализация вывод
 * @param string $value значение
 */
function _e($value) {
    echo __($value);
}

/**
 * Запись недостующих данные в локализацию
 */
function writeLangFile() {
    global $PHPShopLang;
    if ($PHPShopLang->createUndefined) {
        $PHPShopLang->write();
    }
}

?>