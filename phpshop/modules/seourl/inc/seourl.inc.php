<?php

/**
 * Список каталогов
 */
class PHPShopSeoUrlElement extends PHPShopCatalogElement {
    var $chek_page=true; //проверять на единичные каталоги

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
                $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];
                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // Если есть страницы
                if($this->chek($row['id'])) {

                    $link=$this->chek_page($row['id']);
                    if($link and $this->chek_page) {
                        $this->set('catalogName',$row['name']);
                        $this->set('catalogId',$row['seoname']);
                        $dis.=str_replace('/page/CID_', '/cat/',$this->parseTemplate($this->getValue('templates.catalog_forma_3')));
                    }
                    else {

                        $this->set('catalogPodcatalog',$this->page($row['id']));
                        $this->set('catalogName',$row['name']);
                        $this->set('catalogId',$row['seoname']);
                        $dis.=str_replace('/page/CID_', '/cat/',$this->parseTemplate($this->getValue('templates.catalog_forma')));
                    }

                }  else {
                    $this->set('catalogPodcatalog',$this->podcatalog($row['id']));
                    $this->set('catalogName',$row['name']);
                    $this->set('catalogId',$row['seoname']);
                    $dis.=str_replace('/page/CID_', '/cat/',$this->parseTemplate($this->getValue('templates.catalog_forma_2')));
                }

                $i++;
            }

        return $dis;
    }

    // Вывод страниц
    function page($n) {
        $disp='';
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
                $this->set('catalogName',$row['name']);

                // Подключаем шаблон
                $dis=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                $disp.=str_replace('/page', '', $dis);
            }
        return $disp;
    }

    // Вывод подкаталогов
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

                // SeoUrl
                $this->set('catalogLink',$row['seoname']);

                $this->set('catalogName',$row['name']);
                $this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));
                $this->set('catalogName',$row['name']);
                $i++;

                // Глобальный массив для навигации хлебных крошек
                $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];
                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // Подключаем шаблон с заменой ссылок
                $dis.=$this->PHPShopModules->Parser(array('/page/'=>'/cat/'),$this->getValue('templates.podcatalog_forma'));
            }
        return $dis;
    }
}


// Меняем вывод навигации каталога
$PHPShopSeoUrlElement = new PHPShopSeoUrlElement();
$PHPShopSeoUrlElement->init('mainMenuPage',true);

?>