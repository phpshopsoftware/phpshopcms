<?php

/**
 * Îáğàáîò÷èê ñòğàíèö
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopPage extends PHPShopCore {

    /**
     * Âûâîä îïèñàíèÿ êàòàëîãà â ïàãèíàòîğå
     * @var bool 
     */
    var $content_in_paginator = false;

    /**
     * Êîíñòğóêòîğ
     */
    function __construct() {

        // Èìÿ Áä
        $this->objBase = $GLOBALS['SysValue']['base']['table_name11'];

        // Îòëàäêà
        $this->debug = false;

        // Ñïèñîê ıêøåíîâ
        $this->action = array("nav" => "CID");
        $this->empty_index_action = true;
        parent::__construct();
    }

    /**
     * İêøåí ïî óìîë÷àíèş, âûâîä äàííûõ ïî ñòğàíèöå
     * @return string
     */
    function index($link = false) {
       
        // Áåçîïàñíîñòü
        if (empty($link))
            $link = PHPShopSecurity::TotalClean($this->PHPShopNav->getName(true), 2);

        // Âûáîğêà äàííûõ
        $row = parent::getFullInfoItem(array('*'), array('link' => "='$link'", 'enabled' => "!='0'"));

        // Ïğèêğûâàåì ñòğàíèöó îò äóáëÿ
        if ($row['category'] == 2000)
            return $this->setError404();
        elseif (empty($row['id']))
            return $this->setError404();

        // Îïğåäåëÿåì ïåğåìåííûå
        $this->set('pageContent', Parser($row['content']));
        $this->set('pageTitle', $row['name']);

        // Ìåòà
        if (empty($row['title']))
            $title = $row['name'];
        else
            $title = $row['title'];

        $this->title = $title . " - " . $this->PHPShopSystem->getValue("name");
        $this->description = $row['description'];
        $this->keywords = $row['keywords'];
        $this->lastmodified = $row['date'];

        // Íàâèãàöèÿ õëåáíûå êğîøêè
        $this->navigation($row['category'], $row['name']);

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, $row);

        // Ïîäêëş÷àåì øàáëîí
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * İêøåí âûáîğêè ïîäğîáíîé èíôîğìàöèè ïğè íàëè÷èè ïåğåìåííîé íàâèãàöèè CID
     */
    function CID() {

        // ID êàòåãîğèè
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category), false, array('limit' => 1));

        // Åñëè ñòğàíèöû
        if (empty($row['id'])) {

            $this->ListPage();
        }
        // Åñëè êàòàëîãè
        else {

            $this->ListCategory();
        }
    }

    /**
     * Âûâîä ñïèñêà ñòğàíèö
     * @return string
     */
    function ListPage() {
        $dis = null;

        // 404
        if (empty($this->category_name)) {
            return $this->setError404();
        }

        // Íîìåğ ñòğàíèöà íàâèãàöèè
        $this->page = $this->PHPShopNav->getPage();

        // Ïóòü äëÿ íàâèãàöèè
        $this->objPath = "/page/CID_" . $this->category . '_';


        // Âûáîğêà äàííûõ
        $dataArray = $this->PHPShopOrm->select(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'), array('limit' => 100));
        

        if (is_array($dataArray)) {

            if (count($dataArray) > 1)
                foreach ($dataArray as $row) {
                    $dis.=PHPShopText::li($row['name'], '/page/' . $row['link'] . '.html');

                }
            else {
                return $this->index($dataArray[0]['link']);
            }
        }



        //$disp = PHPShopText::h1($this->category_name);
        $disp = null;

        // Åñëè åñòü îïèñàíèå êàòàëîãà
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled'])) {
            if ($this->page < 2)
                $disp.=$this->PHPShopCategory->getContent();
            elseif ($this->page > 1 and $this->content_in_paginator)
                $disp.=$this->PHPShopCategory->getContent();
        }



        // Ñïèñîê ñòğàíèö
        $disp.=PHPShopText::ul($dis);

        // Îïèñàíèå êàòàëîãà
        $this->set('pageContent', Parser($disp));

        // Íàçâàíèå êàòàëîãà
        $this->set('pageTitle', $this->category_name);

        // Ïàãèíàòîğ @productPageNav@
        $this->setPaginator();

        // Íîìåğ ñòğàíèöû â çàãîëîâêå
        if ($this->page > 1) {
            $page_num = $this->page . ' - ';
        }
        else
            $page_num = null;

        if (!PHPShopParser::check($this->getValue('templates.page_page_list'), 'productPageNav'))
            $this->set('pageContent', $this->get('productPageNav'), true);

        // Title
        $this->title = $this->category_name . " - " . $page_num . $this->PHPShopSystem->getValue("name");

        // Íàâèãàöèÿ õëåáíûå êğîøêè
        $this->navigation($row['category'], $this->category_name);

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray);

        // Ïîäêëş÷àåì øàáëîí
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Âûâîä ñïèñêà êàòåãîğèé
     */
    function ListCategory() {

        // Âûáîğêà äàííûõ
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id'), array('parent_to' => '=' . $this->category), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], "/page/CID_" . $row['id'] . ".html");
            }

        $disp = PHPShopText::h1($this->category_name);

        // Åñëè åñòü îïèñàíèå êàòàëîãà
        if (!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.=PHPShopText::ul($dis);

        $this->set('pageContent', Parser($disp));
        $this->set('pageTitle', $this->category_name);

        // Ìåòà
        $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");

        // Íàâèãàöèÿ õëåáíûå êğîøêè
        $this->navigation($this->category, $this->category_name);

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray);

        // Ïîäêëş÷àåì øàáëîí
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Çàãîëîâêè
     */
    function meta() {
        parent::meta();

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, false);
    }

}

?>