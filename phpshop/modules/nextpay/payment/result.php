<?php

/**
 * Обработчик оповещения о платеже NextPay
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);

$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('nextpay');

class NextPayPayment extends PHPShopPaymentResult {

    function __construct() {

        $this->option();

        parent::__construct();
    }

    /**
     * Настройка модуля 
     */
    function option() {
        $this->payment_name = 'NextPay';
        include_once('../hook/mod_option.hook.php');
        $PHPShopNextPayArray = new PHPShopNextPayArray();
        $this->option = $PHPShopNextPayArray->getArray();
    }

    /**
     * Удачное завершение поверки 
     */
    function done() {
        echo "ok";
        $this->log();
    }

    /**
     * Ошибка 
     */
    function error($type = 1) {
        if ($type == 1)
            echo "bad order num\n";
        else
            echo "bad cost\n";
        $this->log();
    }

    /**
     * Проверка подписи
     * @return boolean 
     */
    function check() {

        # hash = sha1(test+ product_id+order_id+currency+cost_general+cost+profit+commission+SECRET_KEY)
        $r = '';
        $HASH = $_REQUEST['test'] . $r . $_REQUEST['product_id'] . $r . $_REQUEST['order_id'] . $r . $_REQUEST['currency'] . $r . $_REQUEST['cost_general'] . $r . $_REQUEST['cost'] . $r . $_REQUEST['profit'] . $r . $_REQUEST['commission'] . $r . $this->option['merchant_skey'];

        $this->crc = $_REQUEST['hash'];
        $this->my_crc = sha1("$HASH");
        $this->inv_id = $_REQUEST['seller_ext_order_id'];

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->select(array('*'), array('uid' => '="' . $this->true_num($_REQUEST['seller_ext_order_id']) . '"'), false, array('limit' => 1));

        if (is_array($data)) {
            if (number_format($data['sum'], 2, '.', '') == number_format($_REQUEST['cost_general'], 2, '.', '') and $this->crc == $this->my_crc) {
                return true;
            } 
        }
    }

}

new NextPayPayment();
?>
