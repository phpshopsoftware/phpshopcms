<?php

class SeoPultCrypt {

    static public function encrypt($string, $key = '%key&') {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $ordChar = ord($char);
            $ordKeychar = ord($keychar);
            $sum = $ordChar + $ordKeychar;
            $char = chr($sum);
            $result.=$char;
        }
        return base64_encode($result);
    }

    static public function decrypt($string, $key = '%key&') {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $ordChar = ord($char);
            $ordKeychar = ord($keychar);
            $sum = $ordChar - $ordKeychar;
            $char = chr($sum);
            $result.=$char;
        }
        return $result;
    }

}

function register_seopult() {
    global $_classPath, $PHPShopSystem;

    $PHPShopSystem = new PHPShopSystem();
    $result=array();

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    PHPShopObj::loadClass("string");
    $PHPShopModules = new PHPShopModules($_classPath . "modules/");


    $params = array(
        'login' => PHPShopString::win_utf8($_SERVER['SERVER_NAME']),
        'url' => $_SERVER['SERVER_NAME'],
        'email' => $PHPShopSystem->getParam('adminmail2'),
        'hash' => md5($_SERVER['SERVER_NAME'] . time()),
        'partner' => '7a52518f2d1b22983a51a2fbf2a8ec75'
    );

    $request = http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://i.seopult.pro/iframe/getCryptKeyWithUserReg?" . $request);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CRLF, true);

    $json = curl_exec($ch);

    $result = json_decode($json, true);

    // Регистрация нового пользователя
    if ($result['status']['code'] == 0) {

        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seopult.seopult_system"));
        $PHPShopOrm->debug = false;
        $params['cryptkey'] = $result['data']['cryptKey'];
        $PHPShopOrm->update($params, false, '');
    }


    if (curl_error($ch) != '' || $json == false) {
        echo "Error: " . curl_error($ch);
        curl_close($ch);
        return 5;
    }

    curl_close($ch);

    return $result['status']['code'];
}

function actionStart() {
    global $PHPShopInterface,$PHPShopModules,$TitlePage, $select_name;


    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seopult.seopult_system"));
    $option = $PHPShopOrm->select();



    // Регистрация нового пользователя 
    if (empty($option['cryptkey'])) {
        $register_code = register_seopult();
        if ($register_code == 0) {
            $PHPShopOrm->clean();
            $option = $PHPShopOrm->select();
        } else {
            //$PHPShopInterface->_CODE.=$PHPShopInterface->setDiv($align, $PHPShopInterface->setButton('Регистрация пользователя', '../icon/group.gif', 300, 50, $float = "none", $onclick = "javascript:miniWin('../modules/seopult/admpanel/adm_module.php',510,450);return false;"),'padding:100px;text-align:center;');
        }
    } else {

        $data = array(
            'login' => $option['login'],
            'hash' => $option['hash'],
            'createdOn' => date('Y-m-d h:i:s')
        );

        $k = json_encode($data);
        $code = SeoPultCrypt::encrypt($k, $option['cryptkey']);
        $url = 'http://i.seopult.pro/iframe/cryptLogin?k=zaa' . $option['hash'] . urlencode($code);

        // Фрейм
        $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Закрыть'));
        $PHPShopInterface->_CODE.=$PHPShopInterface->setFrame('seopult', $url, '99%', '700', $float = 'none', $border = 0);
    }

    echo $PHPShopInterface->actionPanel.$PHPShopInterface->_CODE;
}

?>