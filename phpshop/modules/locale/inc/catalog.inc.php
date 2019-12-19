<?php

class PHPShopCatalogLocaleElement extends PHPShopElements {
    /**
     * @var bool проверять на единичные каталоги
     */
    var $chek_page=true;

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
        parent::PHPShopElements();
    }

    /**
     * Вывод навигации каталогов
     * @return string
     */
    function mainMenuPage() {
        $dis='';
        $i=0;

        $data = $this->PHPShopOrm->select(array('*'),array('parent_to'=>'=0'),array('order'=>'num'),array("limit"=>100));
        if(is_array($data))

            foreach($data as $row) {

                // Определяем переменные
                $this->set('catalogId',$row['id']);
                $this->set('catalogI',$i);
                $this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

                // Глобальный массив для навигации хлебных крошек
                if(!empty($row['name_cat_locale'])) $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name_cat_locale'];
                else $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];

                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // Если есть страницы
                if($this->chek($row['id'])) {

                    $link=$this->chek_page($row['id']);
                    if($link and $this->chek_page) {

                        // Подстановка языка
                        if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                        else $this->set('catalogName',$row['name']);

                        $this->set('catalogId',$link);
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
                    }
                    else {

                        $this->set('catalogPodcatalog',$this->page($row['id']));

                        // Подстановка языка
                        if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                        else $this->set('catalogName',$row['name']);

                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));
                    }

                }  else {
                    $this->set('catalogPodcatalog',$this->podcatalog($row['id']));

                    // Подстановка языка
                    if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                    else $this->set('catalogName',$row['name']);

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
                }

                $i++;
            }
        return $dis;
    }

    /**
     * Проверка подкатлогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $num=$PHPShopOrm->select(array('id'),array('parent_to'=>"=$id"),false,array('limit'=>1));
        if(empty($num['id'])) return true;
    }

    /**
     * Проверка страниц
     * @param int $id ИД каталога
     * @return mixed
     */
    function chek_page($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug=false;
        $num=$PHPShopOrm->select(array('link'),array('category'=>"=$id"),false,array('limit'=>5));
        if(is_array($num))
            if(count($num)==1) return $num[0]['link'];
    }


    /**
     * Вывод страниц
     * @param Int $n ИД каталога
     * @return string
     */
    function page($n) {
        global $PHPShopModules,$dis;
        $dis='';
        $n=PHPShopSecurity::TotalClean($n,1);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug=$this->debug;
        $data = $PHPShopOrm->select(array('*'),array('category'=>'='.$n,'enabled'=>"='1'"),array('order'=>'num'),array("limit"=>100));
        if(is_array($data))
            foreach($data as $row) {

                $id=$row['id'];
                $name=$row['name'];
                $link=$row['link'];
                $category=$row['category'];

                // Определяем переменные
                $this->set('catalogId',$n);
                $this->set('catalogUid',$row['id']);
                $this->set('catalogLink',$row['link']);

                // Подстановка языка
                if(empty($row['name_locale'])) $this->set('catalogName',$row['name']);
                else $this->set('catalogName',$row['name_locale']);

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }

        return $dis;
    }


    /**
     * Вывод подкаталогов
     * @param Int $n ИД каталога
     * @return string
     */
    function podcatalog($n) {
        $dis='';
        $i=0;
        $n=PHPShopSecurity::TotalClean($n,1);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $data = $PHPShopOrm->select(array('*'),array('parent_to'=>'='.$n),array('order'=>'num'),array("limit"=>100));
        if(is_array($data))
            foreach($data as $row) {


                // Определяем переменные
                $this->set('catalogId',$n);
                $this->set('catalogI',$i);
                $this->set('catalogLink','CID_'.$row['id']);

                // Подстановка языка
                if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                else $this->set('catalogName',$row['name']);

                $this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));
                $this->set('catalogName',$row['name']);
                $i++;


                // Глобальный массив для навигации хлебных крошек
                if(!empty($row['name_cat_locale'])) $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name_cat_locale'];
                else $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];


                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }
        return $dis;
    }
}




// Каталог
if($PHPShopLocaleElement->check()) {
    $PHPShopCatalogLocaleElement = new PHPShopCatalogLocaleElement();
    $PHPShopCatalogLocaleElement->init('mainMenuPage');
}

?>