<?

class PHPShopMarket extends PHPShopCore {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $this->debug=false;
        $this->action=array("nav"=>"CID");

        parent::PHPShopCore();
    }



    function index() {
        
        if(!$GLOBALS['LoadItems']['modules']['cart']['enabled_market']) return $this->setError404();

        $link=PHPShopSecurity::TotalClean($this->PHPShopNav->getName(),2);
        $link=str_replace("market","",$link);

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('link'=>"='$link'"));

        // Прикрываем страницу от дубля
        if($row['category'] == 2000)  return $this->setError404();
        elseif(empty($row['id'])) return $this->setError404();

        // Определяем переменые
        $this->set('pageContent',Parser($row['content']));
        $this->set('pageTitle',$row['name']);
        $this->set('pageLink',$row['link']);

        $this->set('marketPrice',$GLOBALS['_PRODUCT'][$row['link']]['price']);
        $this->set('marketValuta',$GLOBALS['LoadItems']['modules']['cart']['valuta']);

        // Мета
        if(empty($row['title'])) $title=$row['name'];
        else $title=$row['title'];
        $this->title=$title." - ".$this->PHPShopSystem->getValue("name");
        $this->description=$row['description'];
        $this->keywords=$row['keywords'];
        $this->lastmodified=$row['datas'];


        // Навигация хлебные крошки
        $this->navigation($row['category'],$row['name']);


        // Подключаем шаблон
        $this->set('pageContent',ParseTemplateReturn($this->PHPShopModules->getParam("templates.cart.market_page_forma"),true));
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    function CID() {

        if(!$GLOBALS['LoadItems']['modules']['cart']['enabled_market']) return $this->setError404();

        // ID категории
        $this->category=PHPShopSecurity::TotalClean($this->PHPShopNav->getId(),1);
        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name=$this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('id,name'),array('parent_to'=>"=".$this->category),false,array('limit'=>1));

        // Если страницы
        if(empty($row['id'])) {

            $this->ListPage();
        }
        // Если каталоги
        else {

            $this->ListCategory();
        }
    }




    function ListPage() {
        global $PHPShopModules;
        $dis='';

        // 404
        if(!isset($this->category_name)) return $this->setError404();

        // Выборка данных
        $this->dataArray=$this->PHPShopOrm->select(array('*'),array('category'=>'='.$this->category,'enabled'=>"='1'"),
                array('order'=>'num'),array('limit'=>100));
        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменные
                $this->set('pageTitle',$row['name']);
                $this->set('pageContent',Parser($row['content']));
                $this->set('pageLink',$row['link']);

                $this->set('marketPrice',$GLOBALS['_PRODUCT'][$row['link']]['price']);
                $this->set('marketValuta',$GLOBALS['LoadItems']['modules']['cart']['valuta']);

                // Подключаем шаблон
                $dis.=ParseTemplateReturn($PHPShopModules->getParam("templates.cart.market_page_forma"),true);



            }



        $this->set('pageContent',$dis);
        $this->set('pageTitle',$this->category_name);

        // Мета
        $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");


        // Навигация хлебные крошки
        $this->navigation($row['category'],$this->category_name);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

    function ListCategory() {

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select(array('name','id'),array('parent_to'=>'='.$this->category),array('order'=>'num'),array('limit'=>100));
        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $dis.="<li><a href=\"/market/CID_".$row['id'].".html\" title=\"".$row['name']."\">".$row['name']."</a></li>";
            }

        $disp="<h1>".$this->category_name."</h1>";

        // Если есть описание каталога
        if(!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.="<ul>$dis</ul>";


        $this->set('pageContent',$disp);
        $this->set('pageTitle',$this->category_name);

        // Мета
        $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");


        // Навигация хлебные крошки
        $this->navigation($this->category,$this->category_name);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }
}
?>