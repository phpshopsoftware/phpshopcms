<?php

/**
 * ���������� �������� ��� ����������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopSkin extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        $this->empty_index_action = true;
        parent::__construct();

        // ��������� ������� ������
        $this->navigation(null, __('���������� �������'));
    }

    /**
     * ����� �� ���������, ����������� � ������� phpshopcms.ru, ����� ������ ��������
     */
    function index() {

        // ������������ � phpshopcms.ru
        $fp = fsockopen("www.phpshopcms.ru", 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "GET /pageHTML/skins.php HTTP/1.1\r\n";
            $out .= "Host: www.phpshopcms.ru\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            while (!feof($fp)) {
                $disp.= fgets($fp, 128);
            }
            fclose($fp);
        }

        // ������ �������� ��� ��������
        $skins = explode("<!-- SKINS_START -->", $disp);
        $dis = str_replace("/load/", "http://www.phpshopcms.ru/load/", $skins[1]);
        $dis = str_replace("save.gif", "zoom.gif", $dis);

        // ����
        $this->title = "���������� ������� ��� ����� - " . $this->PHPShopSystem->getValue("name");

        // ���������� ���������
        $this->set('pageContent', substr($dis,1,strlen($dis)-7));
        $this->set('pageTitle', '���������� ������� ��� �����');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

}

?>