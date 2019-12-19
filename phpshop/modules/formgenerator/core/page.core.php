<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

function index_formgenerator_hook($obj,$row_page) {
    $dis=null;
    $url=$_SERVER['REQUEST_URI'];
    $url=parse_url($_SERVER['REQUEST_URI']);
    $url=$url['path'];
    
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['formgenerator']['formgenerator_forms']);
    $data = $PHPShopOrm->select(array('dir','path'),array("enabled"=>"='1'"),false,array("limit"=>20));

    if(is_array($data)) {
        foreach($data as $row) {

            // Если несколько страниц
            if(strpos($row['dir'],',')) {
                $dirs= explode(",",$row['dir']);
                foreach($dirs as $dir)
                    if($dir == $url) {
                        $PHPShopFormgeneratorElement= new PHPShopFormgeneratorElement();
                        $dis.=$PHPShopFormgeneratorElement->forma($row['path']);
                    }
            }
            // Если одна страница
            elseif($row['dir'] == $url) {
                $PHPShopFormgeneratorElement= new PHPShopFormgeneratorElement();
                $dis=$PHPShopFormgeneratorElement->forma($row['path']);
            }
        }

        $obj->set('pageContent',$dis,true);
    }

}

$addHandler=array(
        'index'=>'index_formgenerator_hook',
        'page'=>'index_formgenerator_hook'
);

?>