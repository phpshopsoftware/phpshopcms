<?php

/**
 * Обработчик подключаемых html файлов
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopDoc extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        $this->setMeta();
        $this->empty_index_action = true;
        parent::__construct();
    }

    /**
     * Возврат содержимого файла
     * @global array $SysValue настройки
     * @param string $pages имя файла без расширения
     * @return string
     */
    function OpenHTML($pages) {
        global $SysValue;
        $dir = "pageHTML/";
        $pages = $pages . ".php";
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if ($file == $pages) {
                $urlfile = fopen("$dir$file", "r");
                $text = fread($urlfile, 1000000);
                return $text;
            }
        }
        return false;
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

        // Читаем файл
        $dis = $this->OpenHTML($this->SysValue['nav']['name']);

        // 404 ошибка при отсутствии файла
        if (empty($dis))
            return $this->setError404();

        // Мета
        $this->title = $this->meta[$this->SysValue['nav']['name']] . ' - ' . $this->PHPShopSystem->getValue("name");
        $this->description = $this->meta_description[$this->SysValue['nav']['name']];
        $this->keywords = $this->meta_keywords[$this->SysValue['nav']['name']];

        // Определяем переменые
        $this->set('pageContent', $dis);
        $this->set('pageTitle', $this->meta[$this->SysValue['nav']['name']]);

        // Навигация хлебные крошки
        $this->navigation(null, $this->get('pageTitle'));


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    // Настройка титлов
    function setMeta() {
        $this->meta = array(
            'license' => 'Лицензионное соглашение',
            'design' => 'Редактирование дизайна',
            'test' => 'Подключение HTML файлов',
            'phpshop-response' => 'PHPShop отзывы',
            'laboratornoe-oborudovanie-avtoritet-otziv' => 'Лабораторное оборудование Авторитет'
        );

        $this->meta_description = array(
            'license' => 'Лицензионное соглашение',
            'design' => 'Редактирование дизайна',
            'test' => 'Подключение HTML файлов',
            'phpshop-response' => 'PHPShop отзывы пользователей',
            'laboratornoe-oborudovanie-avtoritet-otziv' => 'Лабораторное оборудование Авторитет, отзывы.'
        );

        $this->meta_keywords = array(
            'license' => 'Лицензионное соглашение',
            'design' => 'Редактирование дизайна',
            'test' => 'Подключение HTML файлов',
            'phpshop-response' => 'phpshop отзывы',
            'laboratornoe-oborudovanie-avtoritet-otziv' => 'лабораторная мебель, оборудование, отзывы, авторитет лабораторное оборудование, bioscorp.ru'
        );
    }

}

?>
