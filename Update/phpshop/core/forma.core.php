<?php

/**
 * ���������� ����� ��������� � �����
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopForma extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        $this->debug=false;
        
        // ������ �������
        $this->action=array("post"=>"message","nav"=>"index");
        parent::__construct();

        // ��������� ������� ������
        $this->navigation(false,__('����� �����'));
    }


    /**
     * ����� �� ���������, ����� ����� �����
     */
    function index() {

        // ����
        $this->title=__("����� �����")." - ".$this->PHPShopSystem->getValue("name");

        // ���������� ����������
        $this->set('pageTitle',__('����� �����'));

        // ���������� ������
        $this->addToTemplate("page/page_forma_list.tpl");
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

    /**
     * ����� �������� ����� ��� ��������� $_POST[message]
     */
    function message() {
        if(!empty($_SESSION['text']) and strtoupper($_POST['key']) == strtoupper($_SESSION['text'])){
            $this->send();
        }else {
            
            
            
            $this->set('Error',__("������ �����, ��������� ������� ����� �����"));
        }
    }


    /**
     * ��������� ���������
     */
    function send() {

        // ���������� ���������� �������� �����
        PHPShopObj::loadClass("mail");

        // ��������� ������������� �����
        if(PHPShopSecurity::true_param($_POST['nameP'],$_POST['subject'],$_POST['message'],$_POST['mail'])){

            $zag=$this->$_POST['subject']." - ".$this->PHPShopSystem->getValue('name');
            $message="��� ������ ��������� � ����� ".$this->PHPShopSystem->getValue('name')."

������ � ������������:
----------------------
";

            // ���������� �� ���������
            foreach($_POST as $key=>$val)
$message.=$val."
";

            $message.="
����:               ".date("d-m-y H:s a")."
IP:
".$_SERVER['REMOTE_ADDR']."
---------------

� ���������,
http://".$_SERVER['SERVER_NAME'];

            $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('admin_mail'),$_POST['mail'],$zag,$message);
            $this->set('Error',__("��������� ������� ����������"));
        }
        else $this->set('Error',__("������ ���������� ������������ �����"));
    }

}
?>