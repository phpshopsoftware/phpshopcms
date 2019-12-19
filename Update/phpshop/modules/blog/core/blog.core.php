<?php
/**
 * Обработчик блога
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopBlog extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['blog']['blog_log'];

        // Путь для навигации
        $this->objPath="/blog/blog_";

        // Отладка
        $this->debug=false;

        // Список экшенов
        $this->action=array("nav"=>"ID","post"=>"blog_plus","get"=>"blog_del");
        parent::__construct();
        
    }

    /**
     * Экшен по умолчанию
     */
  /**
     * Экшен по умолчанию
     */
    function index() {
        global $PHPShopModules;

        // Выборка данных
       $this->dataArray=parent::getListInfoItem(array('*'),false,array('order'=>'id DESC'));

        // 404
        if(!isset($this->dataArray)) return $this->setError404();

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменные
                $this->set('blogId',$row['id']);
                $this->set('blogData',$row['date']);
                $this->set('blogZag',$row['title']);
                                
                                // Добавлено ниже
                $this->set('blogKratko',$row['description']);

                if(!empty($row['content'])){
                $this->set('blogComStart','');
                $this->set('blogComEnd','');
                }
                else {
                       $this->set('blogComStart','<!--');
                       $this->set('blogComEnd','-->');
                         }
                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

                // Подключаем шаблон
                $this->addToTemplate($GLOBALS['SysValue']['templates']['blog']['main_blog_forma'],true);
            }

        // Пагинатор
        $this->setPaginator();

        // Мета
        $this->title="Блог - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($GLOBALS['SysValue']['templates']['blog']['blog_page_list'],true);
    }


    /**
     * Пагинация в подробном описании
     * @return string
     */
    function setPaginatorContent() {

        // Расчет записей
        $curId = $this->PHPShopNav->getId();
        $prevId = $curId-1;
        $nextId = $curId+1;

        // Проверка записей
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->Option['where'] = ' or ';
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->sql = 'select id from '.$this->objBase.' where id='.$prevId.' or id='.$nextId;
        $row = $PHPShopOrm->select();

        // Проверка на последнюю запись
        if(count($row) == 1) $data[0] = $row;
        else $data = $row;

        if(is_array($data)) {

            if($data[0]['id'] == $prevId) $navigat='<a href="./ID_'.$prevId.'.html" title="'.$this->getValue('lang.prev_page').'">'.
                        $this->getValue('lang.prev_page').'</a>';
            else $navigat='';

            if($data[1]['id'] == $nextId) $navigat.=' | <a href="./ID_'.$nextId.'.html" title="'.$this->getValue('lang.next_page').'">'.
                        $this->getValue('lang.next_page').'</a>';
            else $navigat.='';
        }
        return $navigat;
    }


    /**
     * Экшен выборки подробной информации при наличии переменной навигации ID
     * @return string
     */
    function ID() {
        global $PHPShopModules;

        // Безопасность
        if(!PHPShopSecurity::true_num($this->PHPShopNav->getId())) return $this->setError404();

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('id'=>'='.$this->PHPShopNav->getId()));

        // 404
        if(!is_array($row)) return $this->setError404();

        // Определяем переменые
        $this->set('blogData',$row['date']);
        $this->set('blogZag',$row['title']);
        $this->set('blogKratko',$row['description']);
        $this->set('blogPodrob',$row['content']);

        // Пагинатор
        $this->set('paginatorContent',$this->setPaginatorContent());

        // Перехват модуля
        $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

        // Подключаем шаблон
        $this->addToTemplate($GLOBALS['SysValue']['templates']['blog']['main_blog_forma_full'],true);

        // Мета
        $this->title=$row['title']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['description']);
        $this->lastmodified=PHPShopDate::GetUnixTime($row['date']);

        // Подключаем шаблон
        $this->parseTemplate($GLOBALS['SysValue']['templates']['blog']['blog_page_full'],true);
    }

    /**
     * Экшен записи новости при получении $_POST[blog_plus]
     */
    function blog_plus() {
        $mail=PHPShopSecurity::TotalClean($_POST['mail'],3);

        switch($_POST['status']) {

            case 1:
                $this->write($mail);
                break;

            case 0:
                $this->del($mail);
                break;
        }

        // Мета
        $this->title="Блог - Подписка - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($GLOBALS['SysValue']['templates']['blog']['blog_forma_message'],true);
    }


    /**
     * Есть ли адрес в базе
     * @param string $mail почта
     * @return bool
     */
    function chek($mail) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
        $PHPShopOrm->debug=$this->debug;
        $num=$PHPShopOrm->select(array('id'),array('mail'=>"='$mail'"),false,array('limit'=>1));
        if(empty($num['id'])) return true;
    }

    /**
     * Добавление адреса  в БД
     * @param string $mail
     */
    function write($mail) {

        if(!empty($mail)) {

            if($this->chek($mail)) {
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
                $PHPShopOrm->debug=$this->debug;
                $PHPShopOrm->insert(array('date'=>date("d-m-y"),'mail'=>$mail),$prefix='');


                $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.good_news_message_1')."</B></FONT><BR>".$this->getValue('lang.good_news_message_2');
            }else {
                $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_message_1')."</B></FONT><BR>".$this->getValue('lang.good_news_message_2');
            }

        }

        else {
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_message_3')."</B></FONT><BR>".$this->getValue('lang.good_news_message_2');
        }

        $this->set('mesageText',$mes);
    }

    /**
     * Удаление адреса из БД
     * @param string $mail
     */
    function del($mail) {

        if(!$this->chek($mail)) {
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
            $PHPShopOrm->debug=$this->debug;
            $PHPShopOrm->delete(array('mail'=>"='$mail'"));
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_message_2')."</B></FONT><BR>".$this->getValue('lang.good_news_message_2');

        }else {
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_message_3')."</B></FONT><BR>".$this->getValue('lang.good_news_message_2');
        }

        $this->set('mesageText',$mes);
    }


    /**
     * Экшен удаления подписчика при получении $_GET[blog_del]
     */
    function blog_del() {

        // Проверка на безопсность
        $mail=PHPShopSecurity::TotalClean($_GET['mail'],3);

        // Удаление записи
        $this->del($mail);

        // Мета
        $this->title="Блог - Отписка - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($GLOBALS['SysValue']['templates']['blog']['blog_forma_message'],true);
    }


    function meta() {
        global $PHPShopModules;
        parent::meta();

        // Перехват модуля
        $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this);
    }
}
?>