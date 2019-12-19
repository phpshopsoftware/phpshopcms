<?php
/**
 * ���������� �������� �����
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopGbook extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        // ��� ��
        $this->objBase=$GLOBALS['SysValue']['base']['table_name7'];

        // ���� ��� ���������
        $this->objPath="/gbook/gbook_";

        // �������
        $this->debug=false;
        $this->empty_index_action = true;

        // ������ �������
        $this->action=array("post"=>"send_gb","nav"=>array("index","ID"),"get"=>"add_forma");
        parent::__construct();
    }

    /**
     * ����� �� ���������, ����� �������
     */
    function index() {

        // ����
        $this->title="������ - ".$this->PHPShopSystem->getValue("name");

        // ������� ������
        $this->dataArray=parent::getListInfoItem(array('*'),array('enabled'=>"='1'"),array('order'=>'id DESC'));

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // ������ �� ������
                if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row[mail],PHPShopText::b($row['name']),$row['name']);
                else  $d_mail=PHPShopText::b($row['name']);

                // ���������� ���������
                $this->set('gbookData',PHPShopDate::dataV($row['date'],false));
                $this->set('gbookName',$row['name']);
                $this->set('gbookTema',$row['title']);
                $this->set('gbookMail',$d_mail);
                $this->set('gbookOtsiv',$row['question']);
                $this->set('gbookOtvet',$row['answer']);
                $this->set('gbookId',$row['id']);

                // ���������� ������
                $this->addToTemplate($this->getValue('templates.main_gbook_forma'));
            }

        // ���������
        $this->setPaginator();


        // ���������� ������
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));

        // ������ �� ����� �����
        $this->add($this->attachLink());
    }

    /**
     * ����� ������� ��������� ���������� ��� ������� ���������� ��������� ID
     * @return string
     */
    function ID() {

        // ������������
        if(!PHPShopSecurity::true_num($this->PHPShopNav->getId())) return $this->setError404();

        // ������� ������
        $row=parent::getFullInfoItem(array('*'),array('id'=>'='.$this->PHPShopNav->getId()));

        // 404
        if(!isset($row)) return $this->setError404();

        
        // ������ �� ������
        if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row[mail],PHPShopText::b($row['name']),$row['name']);
        else  $d_mail=PHPShopText::b($row['name']);

        // ���������� ����������
        $this->set('gbookData',PHPShopDate::dataV($row['date']));
        $this->set('gbookName',$row['name']);
        $this->set('gbookTema',$row['title']);
        $this->set('gbookMail',$d_mail);
        $this->set('gbookOtsiv',$row['question']);
        $this->set('gbookOtvet',$row['answer']);
        $this->set('gbookId',$row['id']);

        // ���������� ������
        $this->addToTemplate($this->getValue('templates.main_gbook_forma'));

        // ����
        $this->title=$row['title']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['question']);
        $this->lastmodified=$row['date'];


        // ���������� ������
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));
    }

    /**
     * ������ �� ����� �����
     * @return string
     */
    function attachLink() {
        return PHPShopText::div(PHPShopText::a('/gbook/?add_forma=true','�������� �����'),'center','padding:20');
    }

    /**
     * ����� �����
     */
    function add_forma() {
        $this->parseTemplate($this->getValue('templates.gbook_forma_question'));
    }

    /**
     * �������� �����
     * @param array $option ��������� �������� [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;

        return $PHPShopRecaptchaElement->security($option);
    }

    /**
     * ����� ������ ������ ��� ��������� $_POST[send_gb]
     */
    function send_gb() {
        
        preg_match_all('/http:?/', $_POST['otsiv_new'], $url, PREG_SET_ORDER);
        
        if($this->security()) {
            $this->write();
            header("Location: ../gbook/?write=ok");
        }else {
            $this->set('Error',"������ �����, ��������� ������� ����� �����");
            $this->parseTemplate($this->getValue('templates.gbook_forma_question'));
        }
    }

    /**
     * ������ ������ � ����
     */
    function write() {

        // ���������� ���������� �������� �����
        PHPShopObj::loadClass("mail");

        if(isset($_POST['send_gb'])) {
            if(!preg_match("/@/",$_POST['mail_new']))//�������� �����
            {
                $_POST['mail_new']="";
            }
            if(PHPShopSecurity::true_param($_POST['name_new'],$_POST['otsiv_new'],$_POST['tema_new'])) {
                $name_new=PHPShopSecurity::TotalClean($_POST['name_new'],2);
                $question_new=PHPShopSecurity::TotalClean($_POST['otsiv_new'],2);
                $title_new=PHPShopSecurity::TotalClean($_POST['tema_new'],2);
                $mail_new=addslashes($_POST['mail_new']);
                $date = date("U");

                // ������ � ����
                $this->PHPShopOrm->insert(array('date'=>$date,'name'=>$name_new,'mail'=>$mail_new,'title'=>$title_new,'question'=>$question_new),
                        $prefix='');

                $zag=$this->PHPShopSystem->getValue('name')." - ����������� � ��������� ������ / ".$date;
                $message="
������� �������!
---------------

� ����� ".$this->PHPShopSystem->getValue('name')." ������ ����������� � ���������� ������ � �������� �����.

������ � ������������:
----------------------

���:                ".$name_new."
E-mail:             ".$mail_new."
����:     ".$title_new."
���������:          ".$question_new."
IP:                 ".$_SERVER['REMOTE_ADDR']."
REFERER: " . $_SERVER["HTTP_REFERER"];

                           new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $zag, $message, false, false, array('replyto'=>$mail_new));
           


            }
        }
    }

}
?>