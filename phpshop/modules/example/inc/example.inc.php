<?php

class PHPShopExampleElement extends PHPShopElements {

    // Конструктор
    function __construct() {
        parent::__construct();
    }

    // Прорисовка ссылки Example
    function addToTopMenu() {

        // Название меню
        $this->set('topMenuName','Example');

        // Ссылка
        $this->set('topMenuLink','index');

        // Парсируем шаблон с заменой 'page' на 'example'
        $dis=$this->PHPShopModules->Parser(array('page'=>'example'),$this->getValue('templates.top_menu'));
        return $dis;
    }

    // Прорисовка текстового блока Example
    function addToTextMenu() {

        // Название меню
        $this->set('leftMenuName','Example');

        // Ссылка
        $this->set('leftMenuContent','<p>Текстовый блок Example сгенерирован модулем Example в файле <mark>example.inc.php</mark></p>');

        // Парсируем шаблон
        $dis=$this->parseTemplate($this->getValue('templates.right_menu'));
        return $dis;
    }
}



// Добавляем ссылку Example в горизонтальное меню
$PHPShopExampleElement = new PHPShopExampleElement();
$GLOBALS['SysValue']['other']['topMenu'].=$PHPShopExampleElement->addToTopMenu();

// Добавляем ссылку Example в текстовый блок
$GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopExampleElement->addToTextMenu();
?>