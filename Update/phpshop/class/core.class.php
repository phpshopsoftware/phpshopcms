<?php

/**
 * Родительский класс ядра
 * Примеры использования размещены в папке phpshop/core/
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopCore {

    /**
     * @var string имя БД
     */
    var $objBase;

    /**
     * @var bool режим отладки
     */
    var $debug = false;

    /**
     * @var string результат работы парсера
     */
    var $Disp, $ListInfoItems;

    /**
     * @var array массив обработки POST, GET запросов
     */
    var $action = array("nav" => "index");

    /**
     * @var string метатеги
     */
    var $title, $description, $keywords, $lastmodified;

    /**
     * @var string ссылка в навигации от корня
     */
    var $navigation_link = '';

    /**
     * @var string шаблон вывода
     */
    var $template = 'templates.shop';

    /**
     * @var string массив навигации (каталог/фото)
     */
    var $navigationArray = 'CatalogPage';

    /**
     * @var string  таблица массива навигации
     */
    var $navigationBase = 'base.table_name';

    /**
     * @var string  расширение файла навигации
     */
    var $navigationFileType = '.html';
    
    /**
     * отключение защиты проверки пустого экшена
     * @var bool
     */
    var $empty_index_action = true;

    /**
     * Конструктор
     * @global array $PHPShopSystem
     * @global array $PHPShopNav
     * @global array $PHPShopModules
     */
    function __construct() {
        global $PHPShopSystem, $PHPShopNav, $PHPShopModules;

        if ($this->objBase){
            $this->PHPShopOrm = new PHPShopOrm($this->objBase);
            $this->PHPShopOrm->debug = $this->debug;
		}

        $this->SysValue = &$GLOBALS['SysValue'];
        $this->LoadItems = &$GLOBALS['LoadItems'];
        $this->PHPShopSystem = &$PHPShopSystem;
        $this->num_row = $this->PHPShopSystem->getParam('num_row');
        $this->PHPShopNav = &$PHPShopNav;
        $this->PHPShopModules = &$PHPShopModules;
        $this->page = $this->PHPShopNav->getId();
        if (strlen($this->page) == 0)
            $this->page = 1;

        // Определяем переменные
        $this->set('pageReg', "PHPShop CMS Free");
        $this->set('pageDomen', "No");
        $this->set('pageProduct', $this->SysValue['license']['product_name']);
    }

    function getNavigationPath($id) {
        global $array;
        $PHPShopOrm = new PHPShopOrm($this->getValue($this->navigationBase));
        $PHPShopOrm->debug = $this->debug;

        if ($id)
            if (empty($this->LoadItems[$this->navigationArray][$id]['name'])) {
                $PHPShopOrm->comment = "Навигация";
                $v = $PHPShopOrm->select(array('name', 'id', 'parent_to'), array('id' => '=' . $id), false, array('limit' => 1));

                if (is_array($v)) {
                    $array[] = array('id' => $v['id'], 'name' => $v['name'], 'parent_to' => $v['parent_to']);
                    $this->getNavigationPath($v['parent_to']);
                }
            } else {
                foreach ($this->LoadItems[$this->navigationArray] as $k => $v)
                    if ($k == $id) {
                        $array[] = array('id' => $id, 'name' => $v['name'], 'parent_to' => $v['parent_to']);
                        $this->getNavigationPath($v['parent_to']);
                    }
            }
        return $array;
    }

    /**
     * Навигация хлебных крошек
     * @param int $id текущий ИД позиции
     * @param string $name имя раздела
     */
    function navigation($id, $name) {
        $dis = null;
        $spliter = ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home = ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        $arrayPath = $this->getNavigationPath($id);

        if (is_array($arrayPath)) {
            $arrayPath = array_reverse($arrayPath);

            // Убираем последнее значение если каталог
            if ($this->PHPShopNav->getNav() == "CID")
                array_pop($arrayPath);

            foreach ($arrayPath as $v) {
                $dis.= $spliter . '<A href="/' . $this->PHPShopNav->getPath() . '/CID_' . $v['id'] . '.html">' . $v['name'] . '</a>';
            }
        }

        $dis = $home . $dis . $spliter . '<b>' . $name . '</b>';
        $this->set('breadCrumbs', $dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId', $id);
    }

    /**
     * Генерация даты изменения документа
     */
    function header() {
        if ($this->getValue("my.last_modified") == "true") {
            @Header("Cache-Control: no-cache, must-revalidate");
            @Header("Pragma: no-cache");

            if (!empty($this->lastmodified)) {
                $updateDate = @gmdate("D, d M Y H:i:s", $this->lastmodified);
            } else {
                $updateDate = gmdate("D, d M Y H:i:s", (date("U") - 21600));
            }

            @Header("Last-Modified: " . $updateDate . " GMT");
        }
    }

    /**
     * Генерация заголовков документа
     */
    function meta() {

        if (!empty($this->title))
            $this->set('pageTitl', $this->title);
        else
            $this->set('pageTitl', $this->PHPShopSystem->getValue("title"));

        if (!empty($this->description))
            $this->set('pageDesc', $this->description);
        else
            $this->set('pageDesc', $this->PHPShopSystem->getValue("meta"));

        if (!empty($this->keywords))
            $this->set('pageKeyw', $this->keywords);
        else
            $this->set('pageKeyw', $this->PHPShopSystem->getValue("keywords"));
    }

    /**
     * Загрузка экшенов
     */
    function loadActions() {

        $core_file = "./pages/" . $this->PHPShopNav->getPath() . ".php";

        if (!is_file($core_file)) {
            $this->setAction();
            $this->Compile();
        } else {

            // Поддержка старого API для /pages/*.php
            include_once($core_file);
        }
    }

    /**
     * Выдача списка данных
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getListInfoItem($select = false, $where = false, $order = false) {
        $this->ListInfoItems = '';
        $this->where = $where;

        // Обработка номера страницы
        if (!PHPShopSecurity::true_num($this->page))
            return $this->setError404();

        if (empty($this->page)) {
            $num_ot = 0;
            $num_do = $this->num_row;
        } else {
            $num_ot = $this->num_row * ($this->page - 1);
            $num_do = $this->num_row;
        }

        $option = array('limit' => $num_ot . ',' . $num_do);

        $this->set('productFound', $this->getValue('lang.found_of_products'));
        $this->set('productNumOnPage', $this->getValue('lang.row_on_page'));
        $this->set('productPage', $this->getValue('lang.page_now'));

        return $this->PHPShopOrm->select($select, $where, $order, $option);
    }

    /**
     * Генерация пагинатора
     */
    function setPaginator($nav_len = 3) {
        $SQL = null;

        // Выборка по параметрам WHERE
        $nWhere = 1;
        if (is_array($this->where)) {
            $SQL.=' where ';
            foreach ($this->where as $pole => $value) {
                $SQL.=$pole . $value;
                if ($nWhere < count($this->where))
                    $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }

        // Кол-во страниц
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysqli_fetch_array($result);
        $this->num_page = $row['count'];

        $i = 1;
        //$pageDo=null;
        $navigat = null;
        $num = ceil($this->num_page / $this->num_row);

        while ($i <= $num) {

            if ($i == 1)
                $pageOt = 0 + $pageDo;
            else
                $pageOt = $i + $pageDo - $i;

            $pageDo = $i * $this->num_row;

            if ($i != $this->page) {

                if ($i > ($this->page - $nav_len) and $i < ($this->page + $nav_len))
                    $navigat.=" <a href=\"" . $this->objPath . $i . $this->navigationFileType . "\">" . ($pageOt + 1) . "-" . $pageDo . "</a> | ";
                else if ($i - ($this->page + $nav_len) < 3 and (($this->page - $nav_len) - $i) < 3)
                    $navigat.=".";
            }
            else
                $navigat.=" <b>" . ($pageOt + 1) . "-" . $pageDo . "</b> | ";
            $i++;
        }

        if ($num > 1) {
            if ($this->page >= $num) {
                $p_to = $i - 1;
                $p_do = $this->page - 1;
            } else {
                $p_to = $this->page + 1;

                if ($this->page > 1)
                    $p_do = $this->page - 1;
                else
                    $p_do = 1;
            }
            $this->set('productPageNav', $this->getValue('lang.page_now') . ":
<a href=\"" . $this->objPath . ($p_do) . $this->navigationFileType . "\">&laquo;&laquo;&nbsp;</a>&nbsp;
                    $navigat&nbsp<a href=\"" . $this->objPath . $p_to . $this->navigationFileType . "\">&nbsp;&raquo;&raquo;</a>");
        }
    }

    /**
     * Выдача подробного описания
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getFullInfoItem($select, $where, $order = false) {
        return $this->PHPShopOrm->select($select, $where, $order, array('limit' => '1'));
    }

    /**
     * Добавление данных в вывод парсера
     * @param string $template шаблон для парсинга
     * @param bool $mod работа в модуле
     * @param array $replace масив замены в шаблоне
     */
    function addToTemplate($template, $mod = false, $replace = null) {
        if ($mod)
            $template_file = $template;
        else
            $template_file = $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47) . $template;
        if (is_file($template_file)) {
            $dis = ParseTemplateReturn($template, $mod);

            // Замена в шаблоне
            if (is_array($replace)) {
                foreach ($replace as $key => $val)
                    $dis = str_replace($key, $val, $dis);
            }

            $this->ListInfoItems.=$dis;

            $this->set('pageContent', $this->ListInfoItems);
        }
        else
            $this->setError("addToTemplate", $template_file);
    }

    /**
     * Добавление данных
     * @param string $content содержание
     * @param bool $list [1] - добавление в список данных, [0] - добавление в общую переменную вывода
     */
    function add($content, $list = false) {
        if ($list)
            $this->ListInfoItems.=$content;
        else
            $this->Disp.=$content;
    }

    /**
     * Парсинг шаблона и добавление в общую переменную вывода
     * @param string $template имя шаблона
     * @param bool $mod работа в модуле
     * @param array $replace масив замены в шаблоне
     */
    function parseTemplate($template, $mod = false, $replace = null) {
        $this->set('productPageDis', $this->ListInfoItems);
        $dis = ParseTemplateReturn($template, $mod);

        // Замена в шаблоне
        if (is_array($replace)) {
            foreach ($replace as $key => $val)
                $dis = str_replace($key, $val, $dis);
        }

        $this->Disp = $dis;
    }

    /**
     * Сообщение об ошибке
     * @param string $name имя функции
     * @param string $action сообщение
     */
    function setError($name, $action) {
        echo '<p style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">
<img hspace="10" style="padding-left:10px" align="left" src="../phpshop/admpanel/img/i_domainmanager_med[1].gif"
width="32" height="32" alt="PHPShopCore Debug On"/ ><strong>Ошибка обработчика события:</strong> ' . $name . '()
	 <br><em>' . $action . '</em></p>';
    }

    /**
     * Компиляция парсинга
     */
    function Compile() {
        $this->set('DispShop', $this->Disp);

        // Мета
        $this->meta();

        // Дата модификации
        $this->header();

        // Запись файла локализации
        writeLangFile();

        ParseTemplate($this->getValue($this->template));
    }

    /**
     * Создание системной переменной для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * Выдача системной перемунной
     * @param string $name
     * @return string
     */
    function get($name) {
        return $this->SysValue['other'][$name];
    }

    /**
     * Выдача системной переменной
     * @param string $param раздел.имя переменной
     * @return mixed
     */
    function getValue($param) {
        $param = explode(".", $param);
        return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * Назначение экшена обработки перемнных POST и GET
     */
    function setAction() {
        global $SysValue;

        if (is_array($this->action)) {
            foreach ($this->action as $k => $v) {

                switch ($k) {

                    case("post"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_POST[$function]) and $this->isAction($function))
                                    call_user_func(array(&$this, $function));
                        } else {
                            // Если один экшен
                            if (!empty($_POST[$v]) and $this->isAction($v))
                                call_user_func(array(&$this, $v));
                        }
                        break;

                    case("get"):


                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_GET[$function]) and $this->isAction($function))
                                    call_user_func(array(&$this, $function));
                        } else {
                            // Если один экшен
                            if (!empty($_GET[$v]) and $this->isAction($v))
                                call_user_func(array(&$this, $v));
                        }

                        break;

                    // Экшен NAME
                    case("name"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if ($this->PHPShopNav->getName(0) == $function and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if ($this->PHPShopNav->getName(0) == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;

                    // Экшен NAV
                    case("nav"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function) {
                                if ($this->PHPShopNav->getNav() == $function and $this->isAction($function)) {
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                                    $call_user_func = true;
                                }
                            }
                            if (empty($call_user_func)) {
 
                                if ($this->isAction('index')) {

                                    // Защита от битых адресов /page/page/page/****
                                    if ($this->PHPShopNav->getNav() and !$this->empty_index_action)
                                        $this->setError404();
                                    else
                                        call_user_func(array(&$this, $this->action_prefix . 'index'));
                                }
                                else
                                    $this->setError($this->action_prefix . "index", "метод не существует");
                            }
                        } else {
                            // Если один экшен
                                                           
                            if ($this->PHPShopNav->getNav() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                            elseif ($this->isAction('index')) {
                                // Защита от битых адресов /page/page/page/****
                                if ($this->PHPShopNav->getNav() and !$this->empty_index_action)
                                    $this->setError404();
                                else
                                    call_user_func(array(&$this, $this->action_prefix . 'index'));
                            }
                            else
                                $this->setError($this->action_prefix . "phpshop" . $this->PHPShopNav->getPath() . "->index", "метод не существует");
                        }

                        break;
                }
            }
        }
        else
            $this->setError("action", "экшены объявлена неверно");
    }

    /**
     * Проверка экшена
     * @param string $method_name имя метода
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $method_name))
            return true;
    }

    /**
     * Генерация ошибки 404
     */
    function setError404() {

        // Титл
        $this->title = __("Ошибка 404 ") . " - " . $this->PHPShopSystem->getValue("name");

        // Заголовок ошибки
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.error_page_forma'));
    }

    /**
     * Назначение перехвата события выполнения модулем
     * @param string $class_name имя класса
     * @param string $function_name имя метода
     * @param mixed $data данные для обработки
     * @param string $rout позиция вызова к функции [END | START | MIDDLE], по умолчанию END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * Сообщение об неизвестном методе
     */
    function __call($name, $arguments) {
        if ($name == __CLASS__) {
            self::__construct();
        }
    }

}

?>