<?php

class PHPShopUsersElement extends PHPShopElements {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['users']['users_base'];
        $this->debug=false;
        
        PHPShopObj::loadClass("text");

        // Поддержка логов
        $this->is_autorization();
        parent::PHPShopElements();
        $this->option();
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_system']);
        $this->data = $PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['users']=$this->data;
    }

    /**
     * Форма авторизации
     * @return string
     */
    function autorizationForma() {

        if($this->is_autorization()) {
            $this->set('leftMenuName','Личный кабинет');
            $this->set('userName',$_SESSION['userName']);
            $this->set('userId',$_SESSION['userId']);
            
            $this->set('leftMenuContent',PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_element'], true, false, true));

        }
        else {
            $this->set('leftMenuName','Авторизация');
            $this->set('leftMenuContent',PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma'], true, false, true));
        }

        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }


    /**
     * Форма он-лайн пользователей
     * @return string
     */
    function onlineForma() {
        $disp=null;
        $num=0;
        $users=null;

        // Данные по гостям из модуля Stat
        if(!empty($GLOBALS['SysValue']['base']['stat']['stat_visitors'])) {
            $guest=0;
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['stat']['stat_visitors']);
            $PHPShopOrm->debug=false;
            $data=$PHPShopOrm->select(array('distinct ip, sebot_id'),array('timestamp'=>">".(time()-60*5)),false,array('limit'=>100));
            if(is_array($data))
                foreach($data as $row) {

                    switch($row['sebot_id']) {
                        case(0): $guest++;
                            break;
                        case(1):
                            $seboot[$row['sebot_id']]='Google Bot';
                            break;
                        case(2):
                            $seboot[$row['sebot_id']]='Yandex Bot';
                            break;
                        case(3):
                            $seboot[$row['sebot_id']]='Rambler Bot';
                            break;
                    }
                }


            // Вывод ботов
            $num=count($seboot);

            if(is_array($seboot))
                foreach($seboot as $val)
                    $users.=$val.',';

        }

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_log']);
        $PHPShopOrm->debug=false;

        $data=$PHPShopOrm->select(array('DISTINCT user_name'),array('date'=>">".(time()-60*30)),false,array('limit'=>100));
        if(is_array($data))
            foreach($data as $row) {
                $users.=$row['user_name'].', ';
                $num++;
            }

        if(!empty($GLOBALS['SysValue']['base']['stat']['stat_visitors'])) {
            $other=$guest-$num;
            if($other<0) $other=0;
            $disp.=PHPShopText::b($other).' гостей,';
        }
        $disp.=' '.PHPShopText::b($num).' пользователей<br> '.$users;


        $this->set('leftMenuName',$GLOBALS['SysValue']['lang']['users_stat_title']);
        $this->set('leftMenuContent',substr($disp,0,strlen($disp)-1));
        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }


    /**
     * Запись даты авторизации
     */
    function log() {
        if(empty($_SESSION['userLog']) or $_SESSION['userLog'] < (time()-60*15))
            $_SESSION["userLog"] = time();
    }

    /**
     * Проверка авторизации
     * @return bool
     */
    function is_autorization() {
        if(!empty($_SESSION['userName'])) {
            $this->log();
            return true;
        }
    }

    /**
     * Возврат дополнительных полей
     * @param string $param
     * @return string
     */
    function getParam($param,$get_all=false) {

        if(PHPShopSecurity::true_login($_SESSION['userName'])) {

            $content=unserialize($this->data['content']);

            if($get_all) return $content;
            else return $content['dop_'.$param];
        }
    }

}


$PHPShopUsersElement = new PHPShopUsersElement();

// Форма авторизации пользователей
if($GLOBALS['SysValue']['nav']['path'] != 'user' )
    if($GLOBALS['LoadItems']['modules']['users']['enabled']==1) {

        if($GLOBALS['LoadItems']['modules']['users']['flag']==1)
            $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopUsersElement->autorizationForma();
        else $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopUsersElement->autorizationForma();

    }else $PHPShopUsersElement->init('autorizationForma');


// Форма статистики
if(!empty($GLOBALS['LoadItems']['modules']['users']['stat_flag'])) {

    if($GLOBALS['LoadItems']['modules']['users']['stat_flag']==1)
        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopUsersElement->onlineForma();
    else $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopUsersElement->onlineForma();

}else $PHPShopUsersElement->init('onlineForma');

?>