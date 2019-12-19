<?php

class PHPShopExampleElement extends PHPShopElements {

    // �����������
    function __construct() {
        parent::__construct();
    }

    // ���������� ������ Example
    function addToTopMenu() {

        // �������� ����
        $this->set('topMenuName','Example');

        // ������
        $this->set('topMenuLink','index');

        // ��������� ������ � ������� 'page' �� 'example'
        $dis=$this->PHPShopModules->Parser(array('page'=>'example'),$this->getValue('templates.top_menu'));
        return $dis;
    }

    // ���������� ���������� ����� Example
    function addToTextMenu() {

        // �������� ����
        $this->set('leftMenuName','Example');

        // ������
        $this->set('leftMenuContent','<p>��������� ���� Example ������������ ������� Example � ����� <mark>example.inc.php</mark></p>');

        // ��������� ������
        $dis=$this->parseTemplate($this->getValue('templates.right_menu'));
        return $dis;
    }
}



// ��������� ������ Example � �������������� ����
$PHPShopExampleElement = new PHPShopExampleElement();
$GLOBALS['SysValue']['other']['topMenu'].=$PHPShopExampleElement->addToTopMenu();

// ��������� ������ Example � ��������� ����
$GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopExampleElement->addToTextMenu();
?>