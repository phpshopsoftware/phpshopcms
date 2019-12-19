<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopBoard extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['messageboard']['messageboard_log'];

        // Путь для навигации
        $this->objPath="/board/board_";

        // Отладка
        $this->debug=false;

        // Настройка
        $this->system();

        // Список экшенов
        $this->action=array("post"=>"send_gb","nav"=>"index","get"=>"add_forma");
        parent::__construct();

        // Мета
        $this->title=$this->SysValue['lang']['messageboard_title']." - ".$this->PHPShopSystem->getValue("name");
    }

    /**
     * Настройка
     */
    function system() {
        $PHPShopOrm= new PHPShopOrm($GLOBALS['SysValue']['base']['messageboard']['messageboard_system']);
        $this->system=$PHPShopOrm->select();
    }

    /**
     * Экшен по умолчанию, вывод сообщений
     */
    function index() {

        $dis=null;

        // Количество записей на страницу
        $this->num_row=$this->system['num'];

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('enabled'=>"='1'"),array('order'=>'id DESC'));

        // 404
        if(!isset($this->dataArray)) return $this->add_forma();

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменые
                $this->set('boardDate',PHPShopDate::dataV($row['date'],false));
                $this->set('boardUser',$row['name']);
                $this->set('boardId',$row['id']);
                $this->set('boardTitle',$row['title']);
                $this->set('boardTel',$row['tel']);
                $this->set('boardContent',$row['content']);
                $this->set('boardMail',$row['mail']);

                // Подключаем шаблон
          
                $this->ListInfoItems.= PHPShopParser::file($GLOBALS['SysValue']['templates']['messageboard']['messageboard_forma'], true, false, true);
            }



        // Пагинатор
        $this->setPaginator();

        if($_GET['write'] == 'ok')
            $this->set('ErrorBoard',PHPShopText::message($this->lang('messageboard_write')));

        // Подключаем шаблон
        $this->set('productPageDis', $this->ListInfoItems);
        $this->Disp= PHPShopParser::file($GLOBALS['SysValue']['templates']['messageboard']['messageboard_page_list'], true, false, true);

        // Ссылка на новый отзыв
        $this->add($this->attachLink());
    }


    /**
     * Ссылка на новый отзыв
     * @return string
     */
    function attachLink() {
        return '<div align="center" style="padding:20"><a href="/board/?add_forma=true">
        '.$this->SysValue['lang']['messageboard_add'].'</a></div>';
    }

    /**
     * Новый отзыв
     */
    function add_forma() {
        $this->Disp = PHPShopParser::file($GLOBALS['SysValue']['templates']['messageboard']['messageboard_add'], true, false, true);
    }

    /**
     * Экшен записи при получении $_POST[send_gb]
     */
    function send_gb() {
        if (!empty($_SESSION['text']) and strtoupper($_POST['key']) == strtoupper($_SESSION['text'])) {
            $this->write();
            header("Location: ../board/?write=ok");
        }else {
            $this->set('Error',"Ошибка ключа, повторите попытку ввода ключа");
            $this->Disp = PHPShopParser::file($GLOBALS['SysValue']['templates']['messageboard']['messageboard_add'], true, false, true);
        }
    }

    /**
     * Запись в базу
     */
    function write() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        if(isset($_POST['send_gb'])) {
            if(!preg_match("/@/",$_POST['mail_new']))//проверка почты
            {
                $_POST['mail_new']="";
            }
            if(PHPShopSecurity::true_param($_POST['name_new'],$_POST['content_new'],$_POST['tema_new'])) {
                $name_new=PHPShopSecurity::TotalClean($_POST['name_new'],2);
                $content_new=PHPShopSecurity::TotalClean($_POST['content_new'],2);
                $title_new=PHPShopSecurity::TotalClean($_POST['tema_new'],2);
                $tel_new=PHPShopSecurity::TotalClean($_POST['tel_new'],2);
                $mail_new=addslashes($_POST['mail_new']);
                $date = date("U");
                $ip=$_SERVER['REMOTE_ADDR'];

                // Запись в базу
                $this->PHPShopOrm->insert(array('date'=>$date,'tel'=>$tel_new,'name'=>$name_new,'mail'=>$mail_new,'title'=>$title_new,'content'=>$content_new),
                        $prefix='');

                $zag=$this->PHPShopSystem->getValue('name')." - Уведомление о добалении объявления / ".PHPShopDate::dataV($date);
                $message="
Доброго времени!
---------------

С сайта ".$this->PHPShopSystem->getValue('name')." пришло уведомление о добавлении объявления.

Данные о пользователе:
----------------------

Имя:                ".$name_new."
E-mail:             ".$mail_new."
Телефон:             ".$tel_new."
Тема сообщения:     ".$title_new."
Сообщение:          ".$content_new."
Дата:               ".PHPShopDate::dataV($date)."
IP:                 ".$ip."

---------------

С уважением,
http://".$_SERVER['SERVER_NAME'];

                $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('admin_mail'),$mail_new,$zag,$message);


            }
        }
    }

}
?>