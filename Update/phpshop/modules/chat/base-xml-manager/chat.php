<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
include($_classPath . "lib/phpass/passwordhash.php");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");

// Подключаем БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Пример запроса
$_POST['sql_test'] = '<?xml version="1.0" encoding="windows-1251"?>
<phpshop><sql>
<from>chat.chat_jurnal</from>
<method>select</method>
<vars>*</vars>
<where>id>0</where>
<order></order>
<limit>1000</limit>
</sql>
</phpshop>';

class PHPShopChat extends PHPShopBaseXml {

    function __construct() {
        $this->debug = false;
        $this->true_method = array('select', 'update', 'insert','delete');
        $this->true_from = array('chat.chat_jurnal', 'chat.chat_users','chat.chat_system','chat.chat_operators');
        $this->log = $_POST['log'];
        $this->pas = $_POST['pas'];
        parent::__construct();
    }

    function decode($code) {
        $decode = substr($code, 0, strlen($code) - 4);
        $decode = str_replace("I", 11, $decode);
        $decode = explode("O", $decode);
        $disp_pass = "";
        for ($i = 0; $i < (count($decode) - 1); $i++)
            $disp_pass.=chr($decode[$i]);
        return $disp_pass;
    }

    function admin() {
        $hasher = new PasswordHash(8, false);
        $PHPShopOrm = new PHPShopOrm($this->PHPShopBase->getParam('base.table_name19'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('login,password,status'), array('enabled' => "='1'"), false, array('limit' => 10));
        if (is_array($data)) {
            foreach ($data as $v)
                if ($_POST['log'] == $v['login'] and  $hasher->CheckPassword($this->decode($_POST['pas']), $v['password'])) {
                    return true;
                }
        }
        return false;
    }

}

$PHPShopChat = new PHPShopChat();
?>