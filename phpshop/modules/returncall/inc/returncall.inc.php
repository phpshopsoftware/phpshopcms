<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

/**
 * Элемент формы обратного звонка
 */
class AddToTemplateReturnCallElement extends PHPShopElements {

    var $debug = false;

    /**
     * Конструктор
     */
    function __construct() {
        parent::__construct();
        $this->option();
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['returncall']['returncall_system']);
        $PHPShopOrm->debug = $this->debug;
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Вывод формы
     */
    function display() {

        // Защитная каптча
        if ($this->option['captcha_enabled'] == 1) {
            $PHPShopRecaptchaElement = new PHPShopRecaptchaElement();
            $this->set('returncall_captcha', $PHPShopRecaptchaElement->captcha('returncall','compact'));
        }

        $forma = PHPShopParser::file($GLOBALS['SysValue']['templates']['returncall']['returncall_forma'], true, false, true);
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $this->option['title']);

        // Подключаем шаблон
        if (empty($this->option['windows']))
            $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
        else {
            if (empty($this->option['enabled']))
                $dis = PHPShopParser::file($GLOBALS['SysValue']['templates']['returncall']['returncall_window_forma'], true, false, true);
            else {
                 $this->set('leftMenuContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['returncall']['returncall_window_forma'], true, false, true));
                $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
            }
        }




        // Назначаем переменную шаблона
        switch ($this->option['enabled']) {

            case 1:
                $this->set('leftMenu', $dis, true);
                break;

            case 2:
                $this->set('rightMenu', $dis, true);
                break;

            default: $this->set('returncall', $dis);
        }
    }

}

// Добавляем в шаблон элемент
if ($PHPShopNav->notPath('returncall')) {
    $AddToTemplateReturnCallElement = new AddToTemplateReturnCallElement();
    $AddToTemplateReturnCallElement->display();
}
?>