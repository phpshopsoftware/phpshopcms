<?php

/**
 * Библиотека Отправление почты
 * @version 1.4
 * @package PHPShopClass
 * @tutorial http://doc.phpshop.ru/PHPShopClass/PHPShopMail.html
 * <code>
 * // example:
 * $PHPShopMail= new PHPShopMail('user@localhost','admin@localhost','Test','Hi, user!');
 * </code>
 * @param string $to куда
 * @param string $from от кого
 * @param string $zag заголовок письма
 * @param string $content содержание письма
 * @param boolean $type тип письма, текст или html, true ='text/html', false = text/plain
 * @param boolean $noSend true - не отсылать письмо при создании экземпляра класса. 
 */
class PHPShopMail {

    /**
     * @var string кодировка письма
     */
    var $codepage = "windows-1251";

    /**
     * @var string MIME тип
     */
    var $mime = "1.0";

    /**
     * @var string Тип содержания
     */
    var $type = "text/plain";
    var $f = false;

    /**
     * Конструктор
     * @param string $to куда
     * @param string $from от кого
     * @param string $zag заголовок письма
     * @param string $content содежание письма
     * @param boolean $type тип письма, текст или html, true ='text/html', false = text/plain
     * @param boolean $noSend true - не отсылать письмо при создании экземпляра класса. А назначить метки данных магазина и отправить позднее отдельным методом sendMailNow
     * @param boolean $f true - использовать флаг -f при отправке письма
     */
    function __construct($to, $from, $zag, $content, $type = false, $noSend = false, $f = true) {

        $this->f = $f;

        if (!empty($type))
            $this->type = 'text/html';

        if (class_exists('PHPShopSystem') and $noSend)
            $this->PHPShopSystem = new PHPShopSystem();

        if (strstr($from, ',')) {
            $from_array = explode(",", $from);
            $this->from = trim($from_array[0]);
        }
        else
            $this->from = $from;

        $this->zag = "=?" . $this->codepage . "?B?" . base64_encode($zag) . "?=";
        $this->to = $to;
        $header = $this->getHeader();
        if (!$noSend)
            $this->sendMail($content, $header);
        else {
            $this->header = $header;
            $this->setOrgData();
        }
    }

    /**
     * Заголовок письма
     * @return string
     */
    function getHeader() {
        $header = "MIME-Version: " . $this->mime . "\n";

        if ($this->PHPShopSystem and $this->PHPShopSystem->getEmail() == $this->from) {
            $header.= "From:  " . $this->PHPShopSystem->getParam('name') . " <" . $this->from . ">\n";
        }
        else
            $header.= "From: <" . $this->from . ">\n";

        $header.= "Reply-To: $this->from\n";
        $header.= "Content-Type: " . $this->type . "; charset=" . $this->codepage . "\n";
        $header.= "Content-Transfer-Encoding: 8bit\n";
        return $header;
    }

    /**
     * Устанавливаем метки данных магазина
     */
    function setOrgData() {

        $GLOBALS['SysValue']['other']['adminMail'] = $this->PHPShopSystem->getEmail();
        $GLOBALS['SysValue']['other']['telNum'] = $this->PHPShopSystem->getParam('tel');
        $GLOBALS['SysValue']['other']['org_name'] = $this->PHPShopSystem->getSerilizeParam('bank.org_name');
        $GLOBALS['SysValue']['other']['org_adres'] = $this->PHPShopSystem->getSerilizeParam('bank.org_adres');
        $GLOBALS['SysValue']['other']['logo'] = $this->PHPShopSystem->getParam('logo');

        $GLOBALS['SysValue']['other']['shopName'] = $this->PHPShopSystem->getName();
        $GLOBALS['SysValue']['other']['serverPath'] = $_SERVER['SERVER_NAME'] . "/" . $GLOBALS['SysValue']['dir']['dir'];
        $GLOBALS['SysValue']['other']['date'] = date("d-m-y H:i a");
    }

    /**
     * Отправление письма через php mail
     * @param string $content содержание
     */
    function sendMailNow($content) {
        return mail($this->to, $this->zag, $content, $this->header, $this->from);
    }

    /**
     * Отправление письма через php mail
     * @param string $content содержание
     * @param strong $header заголовок
     */
    function sendMail($content, $header) {
        if (!empty($this->f))
            mail($this->to, $this->zag, $content, $header, '-f' . $this->from);
        else
            mail($this->to, $this->zag, $content, $header);
    }

    /**
     * Вставка копирайта
     * @return string
     */
    function getCopyright() {
        
    }

}

class PHPShopMailFile  {

    var $codepage = "windows-1251";

    function __construct($to, $from, $zag, $content, $filename, $file, $f = true) {
        $this->from = $from;
        $this->un = strtoupper(uniqid(time()));
        $this->to = $to;
        $this->filename = $filename;
        $this->file = $file;
        $this->zag = $this->getZag($content);
        $header = $this->getHeader();
        //mail($this->to,$this->from,$this->zag,$header);
        $this->subj = $zag;
        if (empty($f))
            mail($this->to, $this->subj, $this->zag, $header);
        else
            mail($this->to, $this->subj, $this->zag, $header, '-f' . $this->from);
    }

    function getZag($text) {
        $f = fopen($this->file, "rb");
        $zag = "------------" . $this->un . "\nContent-Type:text/html; charset=" . $this->codepage . "\n";
        $zag.= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
        $zag.= "------------" . $this->un . "\n";
        $zag.= "Content-Type: application/octet-stream;";
        $zag.= "name=\"" . $this->filename . "\"\n";
        $zag.= "Content-Transfer-Encoding:base64\n";
        $zag.= "Content-Disposition:attachment;";
        $zag.= "filename=\"" . $this->filename . "\"\n\n";
        $zag.= chunk_split(base64_encode(fread($f, filesize($this->file)))) . "\n";
        return $zag;
    }

    function getHeader() {
        $head = "From: $this->from\n";
        $head.= "To: $this->to\n";
        $head.= "X-Mailer: PHPMail Tool\n";
        $head.= "Reply-To: $this->from\n";
        $head.= "Mime-Version: 1.0\n";
        $head.= "Content-Type:multipart/mixed;";
        $head.= "boundary=\"----------" . $this->un . "\"\n\n";
        return $head;
    }

}

?>