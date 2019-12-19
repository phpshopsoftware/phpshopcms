<?php

class PHPShopLocaleElement extends PHPShopElements {

    function __construct() {
        parent::PHPShopElements();
    }

    /**
     * ���� ����� �����
     * @return string
     */
    function rightMenu() {

        if($this->check()) {
            $this->set('leftMenuName',"Translate");
            $this->set('leftMenuContent','<p><a href="?lang=default" class="btn btn-default btn-sm">RUS</a>  <bspan class="btn btn-success btn-sm">ENG</span></p>');
        }
        else {
            $this->set('leftMenuName',"����");
            $this->set('leftMenuContent','<p><span class="btn btn-success btn-sm">RUS</span>  <a href="?lang=english" class="btn btn-default btn-sm">ENG</a></p>');
        }

        return $this->parseTemplate($this->getValue('templates.right_menu'));
    }

    /**
     * �������� �����
     * @return bool
     */
    function check() {
        if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') return true;
    }


    function option(){
          $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['locale']['locale_system']);
          $data = $PHPShopOrm->select();
          return $data;
    }


    /**
     * ��������� �������� ����������
     */
    function setlang() {
        global $PHPShopSystem;

        // ������ ��� �����
        if($this->check()) {
            $option = $this->option();
            $PHPShopSystem->setParam('name',$option['name']);
            $PHPShopSystem->setParam('title',$option['name']);
        }

        if(!empty($_GET['lang'])) {

            $_SESSION['mod_locale'] = $_GET['lang'];
            //$_SESSION['lang'] = $_GET['lang'];

            if($this->check()) {

                if(empty($option)) $option = $this->option();

                if (!empty($option['skin_enabled']) and file_exists("phpshop/templates/".$option['skin']."/index.html"))
                    $_SESSION['skin'] = $option['skin'];
            }
            else $_SESSION['skin'] = $PHPShopSystem->getValue('skin');
            
            header('Location: '.$this->PHPShopNav->objNav['truepath']);
        }
    }
}

$PHPShopLocaleElement = new PHPShopLocaleElement();
$PHPShopLocaleElement->setlang();
$PHPShopLocaleElement->init('rightMenu',true);

?>