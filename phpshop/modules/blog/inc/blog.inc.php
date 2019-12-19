<?php

/**
 * Элемент последние записи блога
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopBlogElement extends PHPShopElements {

    /**
     * @var bool  показывать только на главной
     */
    var $disp_only_index = false;

    /**
     * @var Int Кол-во записей
     */
    var $limit = 3;

    /**
     * Конструктор
     */
    function __construct() {

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['blog']['blog_log'];
        parent::__construct();
        $this->option();
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['blog']['blog_system']);
        $this->data = $PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['blog']['enabled'] = $this->data['enabled'];
        $this->LoadItems['modules']['blog']['flag'] = $this->data['flag'];
        $this->LoadItems['modules']['blog']['enabled_menu'] = $this->data['enabled_menu'];
    }

    /**
     * Форма последних объявлений
     * @return string
     */
    function lastblogForma() {
        $disp = null;
        $num = 0;
        $data = $this->PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => $this->limit));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('blogId', $row['id']);
                $this->set('blogZag', $row['title']);
                $this->set('blogData', $row['date']);
                $this->set('blogKratko', $row['description']);

                if (!empty($row['content'])) {
                    $this->set('blogComStart', '');
                    $this->set('blogComEnd', '');
                } else {
                    $this->set('blogComStart', '<!--');
                    $this->set('blogComEnd', '-->');
                }

                $disp.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['blog']['blog_main_mini'], true);
            }

        $this->set('leftMenuName', $this->data['title']);
        $this->set('leftMenuContent', $disp);
        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }

    /**
     * Вывод последних записей блога
     * @return string
     */
    function last() {
        global $PHPShopModules;
        $dis = '';


        $view = true;

        if ($view) {
            $data = $this->PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => $this->limit));
            if (is_array($data))
                foreach ($data as $row) {

                    // Определяем переменые
                    $this->set('blogId', $row['id']);
                    $this->set('blogZag', $row['title']);
                    $this->set('blogData', $row['date']);
                    $this->set('blogKratko', $row['description']);

                    if (!empty($row['content'])) {
                        $this->set('blogComStart', '');
                        $this->set('blogComEnd', '');
                    } else {
                        $this->set('blogComStart', '<!--');
                        $this->set('blogComEnd', '-->');
                    }
                    // Перехват модуля
                    $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.blog.blog_main_mini'));
                }
            return $dis;
        }
    }

    // Добавление ссылки в топ-меню
    function addToTopMenu() {

        // Название меню
        $this->set('topMenuName', $this->data['title']);

        // Ссылка
        $this->set('topMenuLink', 'index');

        // Данные пользователя
        $this->set('userName', $_SESSION['userName']);
        $this->set('userMail', $_SESSION['userMail']);

        // Парсируем шаблон с заменой 'page' на 'example'
        $dis = $this->PHPShopModules->Parser(array('page' => 'blog'), $this->getValue('templates.top_menu'));
        return $dis;
    }

}

$PHPShopBlogElement = new PHPShopBlogElement();

// Ссылка в навигацию
if (!empty($GLOBALS['LoadItems']['modules']['blog']['enabled_menu'])) {
    $GLOBALS['SysValue']['other']['topMenu'].=$PHPShopBlogElement->addToTopMenu();
}

if (!empty($GLOBALS['LoadItems']['modules']['blog']['enabled'])) {

    if ($GLOBALS['LoadItems']['modules']['blog']['flag'] == 1)
        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopBlogElement->lastblogForma();
    else
        $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopBlogElement->lastblogForma();
}else {
    $PHPShopBlogElement->init('lastblogForma');
}
?>