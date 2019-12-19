<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

// Sape
class PHPShopSapeElement extends PHPShopElements {
    var $scrolling="no";
    var $frameborder=0;

    function __construct() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['sape']['sape_system'];
        parent::__construct();
        $this->option();
    }

    function option() {
        $this->data = $this->PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['sape']['enabled']=$this->data['enabled'];
        $this->LoadItems['modules']['sape']['flag']=$this->data['flag'];
    }

    // Вывод ссылок
    function sape($num=false) {

        define('_SAPE_USER', $this->data['sape_user']);
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php')) {
            require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
            $sape = new SAPE_client();

            if(empty($num)) $name=$this->data['num'];

            $links = $sape->return_links($num);

            if(empty($links)) {
                $links='<p><b>Вывод ссылок работает!</b><br>Добавьте свой сайт в <img src="phpshop/modules/sape/install/add.png" width="16" height="16" alt="add" align="absmiddle"/>
<a href="http://www.sape.ru/site.php?act=add" target="_blank">базу площадок Sape</a>.<p>';
            }
        }
        else $links='<p><b>Вывод ссылок не работает!</b><br>Загрузите в корень сайта папку '.$this->data['sape_user'].', полученную в <img src="phpshop/modules/sape/install/add.png" width="16" height="16" alt="add" align="absmiddle"/>
<a href="http://www.sape.ru/site.php?act=add" target="_blank">базе площадок Sape</a>.<p>';

        $this->set('leftMenuName',$this->data['title']);
        $this->set('leftMenuContent',$links);


        if(empty($this->data['flag'])) $templates=$this->getValue('templates.right_menu');
        else $templates=$this->getValue('templates.left_menu');


        if(empty($num))  return $this->parseTemplate($templates);
        else return $links;
    }

    // Вывод через редактор
    function links($num) {
        echo $this->sape($num);
    }


}

// Вывод 
$PHPShopSapeElement = new PHPShopSapeElement();
if($GLOBALS['LoadItems']['modules']['sape']['enabled']==1) {

    if($GLOBALS['LoadItems']['modules']['sape']['flag']==1)  $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopSapeElement->sape();
    else $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopSapeElement->sape();

}else $PHPShopSapeElement->init('sape');
?>