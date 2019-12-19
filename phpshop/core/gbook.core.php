<?php
/**
 * Обработчик гостевой книги
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopGbook extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name7'];

        // Путь для навигации
        $this->objPath="/gbook/gbook_";

        // Отладка
        $this->debug=false;
        $this->empty_index_action = true;

        // Список экшенов
        $this->action=array("post"=>"send_gb","nav"=>array("index","ID"),"get"=>"add_forma");
        parent::__construct();
    }

    /**
     * Экшен по умолчанию, вывод отзывов
     */
    function index() {

        // Мета
        $this->title="Отзывы - ".$this->PHPShopSystem->getValue("name");

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('enabled'=>"='1'"),array('order'=>'id DESC'));

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Ссылка на автора
                if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row[mail],PHPShopText::b($row['name']),$row['name']);
                else  $d_mail=PHPShopText::b($row['name']);

                // Определяем переменые
                $this->set('gbookData',PHPShopDate::dataV($row['date'],false));
                $this->set('gbookName',$row['name']);
                $this->set('gbookTema',$row['title']);
                $this->set('gbookMail',$d_mail);
                $this->set('gbookOtsiv',$row['question']);
                $this->set('gbookOtvet',$row['answer']);
                $this->set('gbookId',$row['id']);

                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_gbook_forma'));
            }

        // Пагинатор
        $this->setPaginator();


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));

        // Ссылка на новый отзыв
        $this->add($this->attachLink());
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации ID
     * @return string
     */
    function ID() {

        // Безопасность
        if(!PHPShopSecurity::true_num($this->PHPShopNav->getId())) return $this->setError404();

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('id'=>'='.$this->PHPShopNav->getId()));

        // 404
        if(!isset($row)) return $this->setError404();

        
        // Ссылка на автора
        if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row[mail],PHPShopText::b($row['name']),$row['name']);
        else  $d_mail=PHPShopText::b($row['name']);

        // Определяем переменные
        $this->set('gbookData',PHPShopDate::dataV($row['date']));
        $this->set('gbookName',$row['name']);
        $this->set('gbookTema',$row['title']);
        $this->set('gbookMail',$d_mail);
        $this->set('gbookOtsiv',$row['question']);
        $this->set('gbookOtvet',$row['answer']);
        $this->set('gbookId',$row['id']);

        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_gbook_forma'));

        // Мета
        $this->title=$row['title']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['question']);
        $this->lastmodified=$row['date'];


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));
    }

    /**
     * Ссылка на новый отзыв
     * @return string
     */
    function attachLink() {
        return PHPShopText::div(PHPShopText::a('/gbook/?add_forma=true','Оставить отзыв'),'center','padding:20');
    }

    /**
     * Новый отзыв
     */
    function add_forma() {
        $this->parseTemplate($this->getValue('templates.gbook_forma_question'));
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
     * Экшен записи отзыва при получении $_POST[send_gb]
     */
    function send_gb() {
        
        preg_match_all('/http:?/', $_POST['otsiv_new'], $url, PREG_SET_ORDER);
        
        if($this->security()) {
            $this->write();
            header("Location: ../gbook/?write=ok");
        }else {
            $this->set('Error',"Ошибка ключа, повторите попытку ввода ключа");
            $this->parseTemplate($this->getValue('templates.gbook_forma_question'));
        }
    }

    /**
     * Запись отзыва в базу
     */
    function write() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        if(isset($_POST['send_gb'])) {
            if(!preg_match("/@/",$_POST['mail_new']))//проверка почты
            {
                $_POST['mail_new']="";
            }
            if(PHPShopSecurity::true_param($_POST['name_new'],$_POST['otsiv_new'],$_POST['tema_new'])) {
                $name_new=PHPShopSecurity::TotalClean($_POST['name_new'],2);
                $question_new=PHPShopSecurity::TotalClean($_POST['otsiv_new'],2);
                $title_new=PHPShopSecurity::TotalClean($_POST['tema_new'],2);
                $mail_new=addslashes($_POST['mail_new']);
                $date = date("U");

                // Запись в базу
                $this->PHPShopOrm->insert(array('date'=>$date,'name'=>$name_new,'mail'=>$mail_new,'title'=>$title_new,'question'=>$question_new),
                        $prefix='');

                $zag=$this->PHPShopSystem->getValue('name')." - Уведомление о добалении отзыва / ".$date;
                $message="
Доброго времени!
---------------

С сайта ".$this->PHPShopSystem->getValue('name')." пришло уведомление о добавлении отзыва в гостевую книгу.

Данные о пользователе:
----------------------

Имя:                ".$name_new."
E-mail:             ".$mail_new."
Тема:     ".$title_new."
Сообщение:          ".$question_new."
IP:                 ".$_SERVER['REMOTE_ADDR']."
REFERER: " . $_SERVER["HTTP_REFERER"];

                           new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $zag, $message, false, false, array('replyto'=>$mail_new));
           


            }
        }
    }

}
?>