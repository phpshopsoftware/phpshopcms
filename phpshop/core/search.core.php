<?php

/**
 * Îáğàáîò÷èê ïîèñêà
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopSearch extends PHPShopCore {

    /**
     * Êîíñòğóêòîğ
     */
    function __construct() {

        // Îòëàäêà
        $this->debug = false;

        // Ñïèñîê ıêøåíîâ
        $this->action = array("post" => "words", "nav" => "index");
        parent::__construct();

        $this->title = __('Ïîèñê') . " - " . $this->PHPShopSystem->getValue("name");

        // Îáëàñòü ïîèñêà
        $this->target();
    }

    /**
     * İêøåí ïî óìîë÷àíèş, âûâîä ôîğìû
     */
    function index() {
        // Ïîäêëş÷àåì øàáëîí
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    // Âûáîğêà äàííûõ ñòğàíèöû
    function searchpage() {

        // Ïåğåõâàò ìîäóëÿ
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return $hook;

        $this->set('pageFrom', "page");
        $this->set('pageDomen', $_SERVER['SERVER_NAME'] . "/page/");
        $j = 0;
        $i = 0;

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name11'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('*'), array('content' => " LIKE '%" . $this->words . "%'", "enabled" => "!='0'"), array('order' => 'link'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                // Îïğåäåëÿåì ïåğåìåííûå
                $this->set('productName', $row['name']);
                $this->set('pageWords', $this->words);
                $this->set('productKey', substr(strip_tags($row['content']), 0, 300) . "...");
                $this->set('pageLink', $row['link'] . ".html");
                $i++;
                $this->set('productNum', $i);

                if ($j == 0) {
                    $this->set('pageTitle', $this->PHPShopSystem->getParam('name') . ' / ' . __('Ñòğàíèöû'));
                    $this->set('pageNumN', __("Ğåçóëüòàò") . " " . __('ñòğàíèö') . " - " . count($dataArray));
                } else {
                    $this->set('pageTitle', false);
                    $this->set('pageNumN', false);
                }

                $j++;

                // Ïåğåõâàò ìîäóëÿ
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Ïîäêëş÷àåì øàáëîí
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        $this->add('<p><br></p>', true);

        return $i;
    }

    // Âûáîğêà äàííûõ íîâîñòè
    function searchnews() {
        global $PHPShopModules;

        $this->set('pageFrom', "news");
        $this->set('pageDomen', $_SERVER['SERVER_NAME'] . "/news/");
        $j = 0;

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name8'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->Option['where'] = " or ";
        $dataArray = $PHPShopOrm->select(array('*'), array('description' => " LIKE '%" . $this->words . "%'", 'title' => " LIKE '%" . $this->words . "%'",
            'content' => " LIKE '%" . $this->words . "%'"), array('order' => 'id desc'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                // Îïğåäåëÿåì ïåğåìåííûå
                $this->set('productName', $row['title']);
                $this->set('pageWords', $this->words);
                $this->set('productKey', substr(strip_tags($row['description']), 0, 300) . "...");
                $this->set('pageLink', "ID_" . $row['id'] . ".html");
                $i++;
                $this->set('productNum', $i);

                if ($j == 0) {
                    $this->set('pageTitle', $this->PHPShopSystem->getParam('name') . ' / ' . __('Íîâîñòè'));
                    $this->set('pageNumN', __("Ğåçóëüòàò") . ": " . __('ñòğàíèö') . " - " . count($dataArray));
                } else {
                    $this->set('pageTitle', false);
                    $this->set('pageNumN', false);
                }

                $j++;

                // Ïåğåõâàò ìîäóëÿ
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Ïîäêëş÷àåì øàáëîí
                $this->addToTemplate($this->getValue('templates.main_search_forma'));
            }

        // Ïåğåõâàò ìîäóëÿ
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        return $i;
    }

    /**
     * İêøåí âûáîğêè èíôîğìàöèè ïîèñêà ïğè íàëè÷èè ïåğåìåííîé $_POST[words]
     * Ïîèñê ïî ñòğàíèöàì è íîâîñòÿì
     */
    function words() {

        // Ïğîâåğêà íà ïëîõèå ñèìâîëû
        $this->words = PHPShopSecurity::TotalClean($_POST['words'], 4);

        switch ($_POST['target']) {

            case 'a':
                $i = $this->searchpage() + $this->searchnews();
                break;

            case 'b':
                $i = $this->searchpage();
                break;

            case 'c':
                $i = $this->searchnews();
                break;

            default:
                $i = $this->searchpage() + $this->searchnews();
        }

        $this->set('searchString', $this->words);

        // Ïîäêëş÷àåì øàáëîí
        if ($i == 0) {
            $message = PHPShopText::h3(__('Íè÷åãî íå íàéäåíî'));
            $message.=PHPShopText::div(__('Åñëè âû íå íàøëè íóæíóş èíôîğìàöèş, âîñïîëüçóéòåñü') . ' ' .
                            PHPShopText::a('../map/', __('êàğòîé ñàéòà'), __('êàğòîé ñàéòà')), $align = "left", $style = "padding:5;border-style: dashed;border-width: 1px;border-color:#D3D3D3");
            $this->add($message, true);
        }

        // Ìåòà
        $this->title = __("Ïîèñê") . " - " . $this->PHPShopSystem->getValue("name");

        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    function target() {
        if (isset($_POST['target'])) {
            $$_POST['target'] = 'selected';
        }
        else
            $a = 'selected';

        $value[] = array(__('Âåçäå'), 'a', $a);
        $value[] = array(__('Ñòğàíèöû'), 'b', $b);
        $value[] = array(__('Íîâîñòè'), 'c', $c);
        $this->set('searchTarget', PHPShopText::select('target', $value, 100));
    }

}

?>