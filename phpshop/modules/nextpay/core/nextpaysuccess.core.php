<?php

/**
 * Обработчик успешной оплаты ссылкой nextpay
 */
class PHPShopNextpaysuccess extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        parent::__construct();

        // Мета
        $this->title = "Заказ успешно оплачен";
    }

    /**
     *  Сообщение об успешном платеже
     */
    function index(){

        // Настройки модуля
        include_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
        $PHPShopNextPayArray = new PHPShopNextPayArray();
        $option = $PHPShopNextPayArray->getArray();

        // Сообщение пользователю об успешном платеже
        $text = PHPShopText::h3($option['link_top_text'], 'text-success') . $option['link_text'];
        $this->set('mesageText', $text);
        $this->set('orderMesage', ParseTemplateReturn($this->getValue('templates.order_forma_mesage')));

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }
}

?>