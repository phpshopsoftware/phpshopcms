<?php

/**
 * Обработчик формы сообщения с сайта
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopForma extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;

        // список экшенов
        $this->action = array("post" => "message", "nav" => "index");
        parent::__construct();

        // Навигация хлебные крошки
        $this->navigation(false, __('Форма связи'));
    }

    /**
     * Экшен по умолчанию, вывод формы связи
     */
    function index() {

        // Мета
        $this->title = __("Форма связи") . " - " . $this->PHPShopSystem->getValue("name");

        // Определяем переменные
        $this->set('pageTitle', __('Форма связи'));

        // Подключаем шаблон
        $this->addToTemplate("page/page_forma_list.tpl");
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Проверка ботов
     * @param array $option параметры проверки [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;

        return $PHPShopRecaptchaElement->security($option);
    }

    /**
     * Экшен отправка формы при получении $_POST[message]
     */
    function message() {
        
        preg_match_all('/http:?/', $_POST['message'], $url, PREG_SET_ORDER);
        
        if ($this->security()) {
            $this->send();
        } else {
            $this->set('Error', __("Ошибка ключа, повторите попытку ввода ключа"));
        }
    }

    /**
     * Генерация сообщения
     */
    function send() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        // Проверяем заполненность полей
        if (PHPShopSecurity::true_param($_POST['nameP'], $_POST['subject'], $_POST['message'], $_POST['mail'])) {

            $zag = $_POST['subject'] . " - " . $this->PHPShopSystem->getValue('name');
            $message = "Вам пришло сообщение с сайта " . $this->PHPShopSystem->getValue('name') . "

Данные о пользователе:
----------------------
";
            unset($_POST['g-recaptcha-response']);

            // Информация по сообщению
            foreach ($_POST as $k => $val) {
                $message.=$val . "
";
                unset($_POST[$k]);
            }


            $message.="
Дата: " . date("d-m-y H:s a") . "
IP: " . $_SERVER['REMOTE_ADDR'] ;

            new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $zag, $message, false, false, array('replyto' => $_POST['mail']));

            $this->set('Error', __("Сообщение успешно отправлено"));
        }
        else
            $this->set('Error', __("Ошибка заполнения обязательных полей"));
    }

}

?>