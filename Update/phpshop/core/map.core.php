<?php
/**
 * Îáğàáîò÷èê êàğòû ñàéòà
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopMap extends PHPShopCore {

    /**
     * Êîíñòğóêòîğ
     */
    function __construct() {

        // Îòëàäêà
        $this->debug=false;

        parent::__construct();
    }


    /**
     * Êàğòà ñòğàíèö
     */
    function pagemap() {
        global $PHPShopModules;

        $this->set('pageFrom',"page");
        $this->set('pageDomen',$_SERVER['SERVER_NAME']."/page/");
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select(array('*'),array('category'=>'!=2000','enabled'=>"!='0'"),array('order'=>'link'),array('limit'=>1000));
        $j==0;

        if(is_array($dataArray))
            foreach($dataArray as $row) {

                // Îïğåäåëÿåì ïåğåìåííûå
                $this->set('productName',$row['name']);
                if(!empty($row['content']))
                $this->set('productKey',substr(strip_tags($row['content']),0,200)."...");
                else $this->set('productKey','');
                $this->set('pageLink',$row['link'].".html");
                $i++;
                $this->set('productNum',$i);

                if($j==0) {
                    $this->set('pageTitle',$this->PHPShopSystem->getParam('name').' / '.__('Ñòğàíèöû'));
                    $this->set('pageNumN',__("Ğåçóëüòàò"),":".__('ñòğàíèö')." - ". count($dataArray));
                }
                else {
                    $this->set('pageTitle',false);
                    $this->set('pageNumN',false);
                }

                $j++;

                // Ïåğåõâàò ìîäóëÿ
                $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__,$this,$row);

                // Ïîäêëş÷àåì øàáëîí
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        $this->add('<p><br></p>',true);
    }


    /**
     * Êàğòà íîâîñòåé
     */
    function newsmap() {
        global $PHPShopModules;
        
        $this->set('pageFrom',"news");
        $this->set('pageDomen',$_SERVER['SERVER_NAME']."/news/");
        $j=0;


        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name8'));
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->Option['where']=" or ";
        $dataArray=$PHPShopOrm->select(array('*'),false,array('order'=>'id desc'),array('limit'=>1000));
        if(is_array($dataArray))
            foreach($dataArray as $row) {

                // Îïğåäåëÿåì ïåğåìåííûå
                $this->set('productName',$row['title']);
                $this->set('pageWords',$this->words);
                $this->set('productKey',substr(strip_tags($row['description']),0,200)."...");
                $this->set('pageLink',"ID_".$row['id'].".html");
                $i++;
                $this->set('productNum',$i);

                if($j==0) {
                    $this->set('pageTitle',$this->PHPShopSystem->getParam('name').' / '.__('Íîâîñòè'));
                    $this->set('pageNumN',__("Ğåçóëüòàò").": ".__('ñòğàíèö')." - ". count($dataArray));
                }
                else {
                    $this->set('pageTitle',false);
                    $this->set('pageNumN',false);
                }

                $j++;


                // Ïåğåõâàò ìîäóëÿ
                $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

                // Ïîäêëş÷àåì øàáëîí
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        $this->add('<p><br></p>',true);

    }

    /**
     * Êàğòà îòçûâîâ
     */
    function gbookmap() {

        $this->set('pageFrom',"gbook");
        $this->set('pageDomen',$_SERVER['SERVER_NAME']."/gbook/");
        $j=0;


        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name7'));
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->Option['where']=" or ";
        $dataArray=$PHPShopOrm->select(array('*'),array('enabled'=>"='1'"),array('order'=>'id desc'),array('limit'=>1000));
        if(is_array($dataArray))
            foreach($dataArray as $row) {

                // Îïğåäåëÿåì ïåğåìåííûå
                $this->set('productName',$row['title']);
                $this->set('pageWords',$this->words);
                $this->set('productKey',substr(strip_tags($row['question']),0,200)."...");
                $this->set('pageLink',"ID_".$row['id'].".html");
                $i++;
                $this->set('productNum',$i);

                if($j==0) {
                    $this->set('pageTitle',$this->PHPShopSystem->getParam('name').' / '.__('Îòçûâû'));
                    $this->set('pageNumN',__("Ğåçóëüòàò").': '.__('ñòğàíèö')." - ". count($dataArray));
                }
                else {
                    $this->set('pageTitle',false);
                    $this->set('pageNumN',false);
                }

                $j++;

                // Ïîäêëş÷àåì øàáëîí
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }
    }

    /**
     * İêøåí ïî óìîë÷àíèş, âûâîä êàğòû ïî ñòğàíèöàì, íîâîñòÿì è îòçûâàì
     */
    function index() {

        $this->pagemap();
        $this->newsmap();
        $this->gbookmap();

        $this->set('searchString',$this->words);

        // Ìåòà
        $this->title=__("Êàğòà ñàéòà")." - ".$this->PHPShopSystem->getValue("name");

        $this->parseTemplate($this->getValue('templates.map_page_list'));
    }
}
?>