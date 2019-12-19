<?php

class PHPShopCatalogLocaleElement extends PHPShopElements {
    /**
     * @var bool ��������� �� ��������� ��������
     */
    var $chek_page=true;

    /**
     * �����������
     */
    function __construct() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
        parent::PHPShopElements();
    }

    /**
     * ����� ��������� ���������
     * @return string
     */
    function mainMenuPage() {
        $dis='';
        $i=0;

        $data = $this->PHPShopOrm->select(array('*'),array('parent_to'=>'=0'),array('order'=>'num'),array("limit"=>100));
        if(is_array($data))

            foreach($data as $row) {

                // ���������� ����������
                $this->set('catalogId',$row['id']);
                $this->set('catalogI',$i);
                $this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

                // ���������� ������ ��� ��������� ������� ������
                if(!empty($row['name_cat_locale'])) $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name_cat_locale'];
                else $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];

                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // ���� ���� ��������
                if($this->chek($row['id'])) {

                    $link=$this->chek_page($row['id']);
                    if($link and $this->chek_page) {

                        // ����������� �����
                        if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                        else $this->set('catalogName',$row['name']);

                        $this->set('catalogId',$link);
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
                    }
                    else {

                        $this->set('catalogPodcatalog',$this->page($row['id']));

                        // ����������� �����
                        if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                        else $this->set('catalogName',$row['name']);

                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));
                    }

                }  else {
                    $this->set('catalogPodcatalog',$this->podcatalog($row['id']));

                    // ����������� �����
                    if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                    else $this->set('catalogName',$row['name']);

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
                }

                $i++;
            }
        return $dis;
    }

    /**
     * �������� �����������
     * @param Int $id �� ��������
     * @return bool
     */
    function chek($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $num=$PHPShopOrm->select(array('id'),array('parent_to'=>"=$id"),false,array('limit'=>1));
        if(empty($num['id'])) return true;
    }

    /**
     * �������� �������
     * @param int $id �� ��������
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
     * ����� �������
     * @param Int $n �� ��������
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

                // ���������� ����������
                $this->set('catalogId',$n);
                $this->set('catalogUid',$row['id']);
                $this->set('catalogLink',$row['link']);

                // ����������� �����
                if(empty($row['name_locale'])) $this->set('catalogName',$row['name']);
                else $this->set('catalogName',$row['name_locale']);

                // ���������� ������
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }

        return $dis;
    }


    /**
     * ����� ������������
     * @param Int $n �� ��������
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


                // ���������� ����������
                $this->set('catalogId',$n);
                $this->set('catalogI',$i);
                $this->set('catalogLink','CID_'.$row['id']);

                // ����������� �����
                if(!empty($row['name_cat_locale'])) $this->set('catalogName',$row['name_cat_locale']);
                else $this->set('catalogName',$row['name']);

                $this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));
                $this->set('catalogName',$row['name']);
                $i++;


                // ���������� ������ ��� ��������� ������� ������
                if(!empty($row['name_cat_locale'])) $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name_cat_locale'];
                else $this->LoadItems['CatalogPage'][$row['id']]['name']=$row['name'];


                $this->LoadItems['CatalogPage'][$row['id']]['parent_to']=$row['parent_to'];
                if(!empty($row['content'])) $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=true;
                else $this->LoadItems['CatalogPage'][$row['id']]['content_enabled']=false;

                // ���������� ������
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_forma'));
            }
        return $dis;
    }
}




// �������
if($PHPShopLocaleElement->check()) {
    $PHPShopCatalogLocaleElement = new PHPShopCatalogLocaleElement();
    $PHPShopCatalogLocaleElement->init('mainMenuPage');
}

?>