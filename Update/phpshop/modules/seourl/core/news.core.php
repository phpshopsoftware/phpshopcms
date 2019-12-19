<?php

class PHPShopNews extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name8'];

        // Путь для навигации
        $this->objPath="/news/news_";

        // Отладка
        $this->debug=false;
        $this->empty_index_action = true;

        // Список экшенов
        $this->action=array("nav"=>array("index","ID"),"post"=>"news_plus","get"=>"news_del");
        parent::PHPShopCore();
    }

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
                $this->set('newsId',$row['seo_name']);
                $this->set('newsData',$row['date']);
                $this->set('newsZag',$row['title']);
                $this->set('newsKratko',$row['description']);

                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_news_forma'));
            }

        // Пагинатор
        $this->setPaginator();

        // Мета
        $this->title="Новости - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_page_list'));
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
        $seo_name=PHPShopSecurity::TotalClean($this->PHPShopNav->getId(),2);

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('seo_name'=>'="'.$seo_name.'"'));

        // 404
        if(!Is_array($row)) return $this->setError404();

        // Определяем переменные
        $this->set('newsData',$row['date']);
        $this->set('newsZag',$row['title']);
        $this->set('newsKratko',$row['description']);
        $this->set('newsPodrob',$row['content']);

        // Пагинатор
        //$this->set('paginatorContent',$this->setPaginatorContent());

        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_news_forma_full'));

        // Мета
        if(empty($row['seo_title'])) $this->title=$row['title']." - ".$this->PHPShopSystem->getValue("name");
        else $this->title=$row['seo_title'];


        if(empty($row['seo_desc'])) $this->description=strip_tags($row['description']);
        else $this->description=$row['seo_desc'];

        $this->keywords=$row['seo_key'];

        $this->lastmodified=PHPShopDate::GetUnixTime($row['date']);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_page_full'));
    }

    /**
     * Экшен записи новости при получении $_POST[news_plus]
     */
    function news_plus() {
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
        $this->title="Новости - Подписка - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_forma_message'));
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
     * Экшен удаления подписчика при получении $_GET[news_del]
     */
    function news_del() {

        // Проверка на безопсность
        $mail=PHPShopSecurity::TotalClean($_GET['mail'],3);

        // Удаление записи
        $this->del($mail);

        // Мета
        $this->title="Новости - Отписка - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_forma_message'));
    }


    function meta() {
        global $PHPShopModules;
        parent::meta();

        // Перехват модуля
        $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this);
    }
}
?>