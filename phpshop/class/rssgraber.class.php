<?php

/**
 * Библиотека работы с RSS каналами
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopClass
 */
class PHPShopRssParser {

    var $debug = false;

    function __construct() {
        global $PHPShopNav, $PHPShopSystem;

        // Запуск граббера только на главной странице
        if ($PHPShopNav->index() and $PHPShopSystem->getSerilizeParam('admoption.rss_graber_enabled') == 1)
            $this->rss_graber();
    }

    /**
     * Перекодировка unicode UTF-8 -> win1251
     */
    function utf8_win($s) {

        if (function_exists('iconv')) {
            $s = iconv("utf-8", "windows-1251", $s);
        } else {
            $s = strtr($s, array("\xD0\xB0" => "а", "\xD0\x90" => "А", "\xD0\xB1" => "б", "\xD0\x91" => "Б", "\xD0\xB2" => "в", "\xD0\x92" => "В", "\xD0\xB3" => "г", "\xD0\x93" => "Г", "\xD0\xB4" => "д", "\xD0\x94" => "Д", "\xD0\xB5" => "е", "\xD0\x95" => "Е", "\xD1\x91" => "ё", "\xD0\x81" => "Ё", "\xD0\xB6" => "ж", "\xD0\x96" => "Ж", "\xD0\xB7" => "з", "\xD0\x97" => "З", "\xD0\xB8" => "и", "\xD0\x98" => "И", "\xD0\xB9" => "й", "\xD0\x99" => "Й", "\xD0\xBA" => "к", "\xD0\x9A" => "К", "\xD0\xBB" => "л", "\xD0\x9B" => "Л", "\xD0\xBC" => "м", "\xD0\x9C" => "М", "\xD0\xBD" => "н", "\xD0\x9D" => "Н", "\xD0\xBE" => "о", "\xD0\x9E" => "О", "\xD0\xBF" => "п", "\xD0\x9F" => "П", "\xD1\x80" => "р", "\xD0\xA0" => "Р", "\xD1\x81" => "с", "\xD0\xA1" => "С", "\xD1\x82" => "т", "\xD0\xA2" => "Т", "\xD1\x83" => "у", "\xD0\xA3" => "У", "\xD1\x84" => "ф", "\xD0\xA4" => "Ф", "\xD1\x85" => "х", "\xD0\xA5" => "Х", "\xD1\x86" => "ц", "\xD0\xA6" => "Ц", "\xD1\x87" => "ч", "\xD0\xA7" => "Ч", "\xD1\x88" => "ш", "\xD0\xA8" => "Ш", "\xD1\x89" => "щ", "\xD0\xA9" => "Щ", "\xD1\x8A" => "ъ", "\xD0\xAA" => "Ъ", "\xD1\x8B" => "ы", "\xD0\xAB" => "Ы", "\xD1\x8C" => "ь", "\xD0\xAC" => "Ь", "\xD1\x8D" => "э", "\xD0\xAD" => "Э", "\xD1\x8E" => "ю", "\xD0\xAE" => "Ю", "\xD1\x8F" => "я", "\xD0\xAF" => "Я"));
        }

        return $s;
    }

    /**
     * Список новостей
     */
    function get_list() {
        global $SysValue, $link_db;

        $date1 = strtotime(date("Y-m-d"));
        $date = time();
        $sec_day = 86400;
        $sql = "
  SELECT f.link, f.id, f.news_num, s.date, f.day_num, f.last_load, count(s.id)
  FROM " . $SysValue['base']['table_name38'] . " as f LEFT JOIN " . $SysValue['base']['table_name39'] . " as s
  ON f.id = s.link_id AND s.status = '1' AND s.date > $date1
  WHERE
  f.enabled = '1' AND
  f.start_date <= $date AND
  f.end_date >= $date
  GROUP BY f.id
  HAVING COUNT(s.id) < f.day_num AND
  ($sec_day/(f.day_num+1)) < ($date - f.last_load)
                ";

        $result = @mysqli_query($link_db, $sql);
        return $result;
    }

    /**
     * Парсинг RSS ленты
     */
    function parse_rss($link, $news_num) {
        $rdf = new fase4_rdf;
        $rdf->use_dynamic_display(false);
        $rdf->set_max_item($news_num);
        $temp = $rdf->parse_RDF($link);
        $rdf->finish();
        $rdf->clear_cache();
        $nnn = $rdf->get_array_item();
        if ($temp) {
            return $nnn;
        }
        else
            return false;
    }

    /**
     * Добавление записи в журнал RSS
     */
    function add_rss_jurnal($link_id, $status, $last_date, $day_num) {
        global $SysValue, $link_db;

        if ($status) {
            if ($last_date < strtotime(date("Y-m-d")))
                $date = strtotime(date("Y-m-d")) + round(86400 / $day_num);
            else
                $date = $last_date + round(86400 / $day_num);
        }
        else
            $date = time();

        $sql = "
  UPDATE " . $SysValue['base']['table_name38'] . "
  set
  last_load = '$date' WHERE id = '$link_id'
                ";
        mysqli_query($link_db, $sql);

        $sql = "
  INSERT INTO " . $SysValue['base']['table_name39'] . "
  VALUES ('','$date','$link_id','$status')
                ";

        mysqli_query($link_db, $sql);
    }

    /**
     * Запись новости в БД
     */
    function add_news($news, $num) {
        global $SysValue, $PHPShopModules, $link_db;

        $date = date("d-m-Y");
        $dateU = date("U");
        for ($i = $num - 1; $i >= 0; $i--) {

            // Название
            $title = $this->utf8_win($news[$i]['title']);

            $sql = "
    SELECT id FROM " . $SysValue['base']['table_name8'] . "
    WHERE title LIKE '$title' LIMIT 1
                    ";
            @$result = mysqli_query($link_db, @$sql);
            @$n = mysqli_num_rows(@$result);

            if (empty($n) and !empty($title)) {

                $PHPShopOrm = new PHPShopOrm($SysValue['base']['table_name8']);
                $PHPShopOrm->debug = $this->debug;

                // Краткое описание
                $description = $this->utf8_win($news[$i]['description']);

                // Подробное описание
                $content = $description . '<p><a href="' . $news[$i]['link'] . '" target="_blank">Источник...</a><p>';

                // Массив данных
                $data_array = array('date_new' => $date, 'title_new' => $title, 'description_new' => $description, 'content_new' => $content, 'datau_new' => $dateU);

                // Перехват модуля
                $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, array(&$data_array));

                // Запись
                $PHPShopOrm->insert($data_array);
            }
        }
    }

    /**
     * Запуск грабера
     */
    function rss_graber() {
        global $SysValue, $link_db;
        mysqli_query($link_db, "SET NAMES `cp1251`");

        $sql = "SELECT rss_use FROM " . $SysValue['base']['table_name3'] . " WHERE 1";
        $result = mysqli_query($link_db, $sql);
        $row = mysqli_fetch_array($result);
        if (!$row['rss_use']) {

            $sql = "
  UPDATE " . $SysValue['base']['table_name3'] . "
  SET
  rss_use = '1'
  ";
            mysqli_query($link_db, $sql);

            $result = $this->get_list();
            if (!empty($result))
                while ($row = @mysqli_fetch_array($result)) {
                    $news = $this->parse_rss($row['link'], $row['news_num']);
                    if ($news) {
                        $this->add_news($news, $row['news_num']);
                        $this->add_rss_jurnal($row['id'], 1, $row['last_load'], $row['day_num']);
                    }
                    else
                        $this->add_rss_jurnal($row['id'], 0, $row['last_load'], $row['day_num']);
                }
        }

        $sql = "
  UPDATE " . $SysValue['base']['table_name3'] . "
  SET
  rss_use = '0'
  ";
        mysqli_query($link_db, $sql);
    }

}

/**
 * RSS граббер LIB
 * @package PHPShopInc
 */
class fase4_rdf {

    var $_link_target = "_blank";
    var $_phost = "";
    var $_pport = "";
    var $_pname = "";
    var $_ppasswd = "";
    var $_use_proxy = false;
    var $_use_proxy_auth = false;
    var $_refresh = 0;   // int
    var $_cached_file = "";   // String
    var $_use_cached_file = false;
    var $_cache_type = "";
    var $_remote_file = "";
    var $_cache_dir = "UserFiles/Files/";  // String
    var $_cache_dir_ok = false;
    var $_type = ""; // string (rss or rdf)
    var $_display_opt = array();  // Array
    var $_table_width = 400;
    var $_use_dynamic_display = false;
    var $_item_count = 0;
    var $_max_count = false;
    var $_array_channel = array();
    var $_array_item = array();
    var $_array_textinput = array();
    var $_array_image = array();
    var $_citem = array();
    var $_cdepth = array();
    var $_ctags = array("x");
    var $_item = array();   // Array
    var $_depth = array();  // Array
    var $_tags = array("x");  // Array
    var $gc_probability = 1;
    var $_output = "";

    function __construct() {
        // default Value, to be overwritten in set_refresh()
        $this->_refresh = (time() - 1200);
    }

    function parse_RDF($rdf) {
        unset($this->_array_item);
        $this->_remote_file = $rdf;
        $tmp = $this->cache();
        unset($this->_output);
        $this->_item_count = 0;
        return $tmp;
    }

    function finish() {
        flush();
        $this->_garbage_collection();
    }

    function use_dynamic_display($bool) {
        $this->_use_dynamic_display = $bool;
        return true;
    }

    function _parse_xRDF($data) {
        $this->_clear_Items();
        $xml_parser = xml_parser_create();
        xml_set_object($xml_parser, $this);
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler($xml_parser, "_startElement", "_endElement");
        xml_set_character_data_handler($xml_parser, "_parseData");
        if (!xml_parse($xml_parser, trim($data))) {
            $this->_throw_exception(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)) . "<br /><br />Exception in function parse_RDF().");
        }
        xml_parser_free($xml_parser);
    }

    function set_refresh($seconds) {
        $this->_refresh = (time() - $seconds);
        return true;
    }

    function set_max_item($int) {
        $this->_max_count = $int;
        return true;
    }

    function set_CacheDir($dir) {
        if (substr($dir, -1) != "/") {
            $dir = $dir . "/";
        }
        $this->_cache_dir = $dir;
    }

    function _throw_exception($msg) {
        //echo "<div style=\"font-family: verdana, helvetica, arial, sans-serif;font-size:11px; color: #6699cc;margin-top:10px;margin-bottom:10px;\" align=\"center\">fase4 RDF Error: ".$msg."</div>";
        return true;
    }

    function _clear_Items($array = "") {
        $this->_item = array("title" => "", "link" => "", "description" => "", "url" => "");
    }

    function _clear_cItems($array = "") {
        $this->_citem = array("title" => "", "link" => "", "description" => "", "url" => "");
    }

    function _startElement($parser, $name, $attrs) {
        // We have to determine, which type of xml data we have to parse
        if ($name == "rss") {
            $this->_type = "rss";
        } elseif ($name == "rdf:RDF" OR $name == "rdf") {
            $this->_type = "rdf";
        }

        if ($name == "channel" AND $this->_type != "rdf") {
            $this->_parse_mode = "channel";
        } elseif (($name == "item") || ($name == "image") || ($name == "textinput") || (($name == "channel") && ($this->_type != "rss"))) {
            if ($this->_parse_mode == "channel") {
                $this->_get_ChannelData($parser);
            }
            $this->_parse_mode = "all";
        }

        $this->_depth[$parser]++;
        array_push($this->_tags, $name);
        $this->_cdepth[$parser]++;
        array_push($this->_ctags, $name);
    }

// END _startElement()

    function _get_ChannelData($parser) {
        if (empty($this->_display_opt["channel"]) OR
                $this->_display_opt["channel"] != "hidden") {
            $this->_output .= "<tr><td>\n";
            $this->_output .= '<table border="0" width="100%" class="fase4_rdf_meta" cellspacing="5" cellpadding="2">' . "\n";
            $this->_output .= "<tr><td class=\"fase4_rdf\"><div class=\"fase4_rdf_title\">" . htmlspecialchars($this->_citem["title"]) . "</div></td></tr>\n";
            $this->_output .= "<tr><td class=\"fase4_rdf\">" . strip_tags($this->_citem["description"], "<a>, <img>") . "</td></tr>\n";
            $this->_output .= "<tr><td>&nbsp;</td></tr>\n";
            $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
            if ($this->_display_opt["build"] != "hidden") {
                if ($this->_citem["lastBuildDate"]) {
                    $this->_output .= "build: " . $this->_citem["lastBuildDate"] . "<br />";
                }
            }
            if ($this->_display_opt["cache_update"] != "hidden" && ( $_update = $this->get_cache_update_time())) {
                $this->_output .= "cache update: " . $_update . "<br />\n";
            }
            $this->_output .= "<a href=\"" . $this->_citem["link"] . "\" ";
            if (isset($this->_link_target)) {
                $this->_output .= "target=\"" . $this->_link_target . "\" ";
            }
            $this->_output .= ">" . $this->_cut_string($this->_citem["link"]) . "</a>";
            $this->_output .= "</td></tr>\n";
            $this->_output .= "</table></td></tr>";
        }
        $this->_array_channel = array("title" => $this->_citem["title"],
            "link" => $this->_citem["link"],
            "description" => $this->_citem["description"],
            "lastBuildDate" => $this->_citem["lastBuildDate"]);
    }

    function _endElement($parser, $name) {
        array_pop($this->_tags);
        $this->_depth[$parser]--;
        array_pop($this->_ctags);
        $this->_cdepth[$parser]--;
        switch ($name) {
            case "item":
                if (empty($this->_max_count) OR $this->_item_count < $this->_max_count) {
                    if ($this->_item["title"] != $this->_item["description"] AND $this->_item["description"]) {
                        $this->_output .= "<tr><td class=\"fase4_rdf\">" . strip_tags($this->_item["description"], "<a>, <img>") . "</td></tr>\n";
                        $this->_output .= "<tr><td class=\"fase4_rdf\"><a href=\"" . $this->_item["link"] . "\" ";
                        if (isset($this->_link_target)) {
                            $this->_output .= "target=\"" . $this->_link_target . "\" ";
                        }
                        $this->_output .= ">" . strip_tags($this->_item["title"], "<a>, <img>") . "</a></td></tr>\n";
                        // we just display the <hr> if there is a description
                        $this->_output .= "<tr><td><hr noshade=\"noshade\" size=\"1\" /></td></tr>\n";
                    } else {
                        $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                        $this->_output .= "<a href=\"" . $this->_item["link"] . "\" ";
                        if (isset($this->_link_target)) {
                            $this->_output .= "target=\"" . $this->_link_target . "\" ";
                        }
                        $this->_output .= ">" . $this->_item["title"] . "</a></td></tr>\n";
                    }
                    $this->_array_item[] = array("title" => $this->_item["title"],
                        "link" => $this->_item["link"],
                        "description" => $this->_item["description"]);
                    ++$this->_item_count;
                }
                $this->_clear_Items();
                break;
            case "image":
                if ($this->_display_opt["image"] != "hidden" && $this->_item["url"]) {
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                    $this->_output .= "<a href=\"" . $this->_item["link"] . "\" ";
                    if (isset($this->_link_target)) {
                        $this->_output .= "target=\"" . $this->_link_target . "\" ";
                    }
                    $this->_output .= "><img src=\"" . $this->_item["url"] . "\"";
                    if ($this->_item["width"] && $this->_item["height"]) {
                        $this->_output .= " width=\"" . $this->_item["width"] . "\" height=\"" . $this->_item["height"] . "\"";
                    }
                    $this->_output .= " alt=\"" . $this->_item["title"] . "\" border=\"0\" /></a></td></tr>\n";

                    $this->_array_image[] = array("url" => $this->_item["url"],
                        "link" => $this->_item["link"],
                        "width" => $this->_item["width"],
                        "height" => $this->_item["height"]);
                    $this->_clear_Items();
                } elseif ($this->_display_opt["image"] == "hidden") {
                    $this->_clear_Items();
                }

                break;
            case "channel":
                if ($this->_display_opt["channel"] != "hidden" AND $this->_item["title"] != "") {
                    $this->_output .= "<tr><td>\n";
                    $this->_output .= '<table border="0" width="100%" class="fase4_rdf_meta" cellspacing="5" cellpadding="2">' . "\n";
                    $this->_output .= "<tr><td class=\"fase4_rdf\"><div class=\"fase4_rdf_title\">" . htmlspecialchars($this->_item["title"]) . "</div></td></tr>\n";
                    $this->_output .= "<tr><td class=\"fase4_rdf\">" . strip_tags($this->_item["description"], "<a>, <img>") . "</td></tr>\n";
                    $this->_output .= "<tr><td>&nbsp;</td></tr>\n";
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                    if ($this->_display_opt["build"] != "hidden") {
                        if ($this->_item["lastBuildDate"]) {
                            $this->_output .= "build: " . $this->_item["lastBuildDate"] . "<br />";
                        }
                    }
                    if ($this->_display_opt["cache_update"] != "hidden" && ( $_update = $this->get_cache_update_time())) {
                        $this->_output .= "cache update: " . $_update . "<br />\n";
                    }
                    $this->_output .= "<a href=\"" . $this->_item["link"] . "\" ";
                    if (isset($this->_link_target)) {
                        $this->_output .= "target=\"" . $this->_link_target . "\" ";
                    }
                    $this->_output .= ">" . $this->_cut_string($this->_item["link"]) . "</a>\n";
                    $this->_output .= "</td></tr>\n";
                    $this->_output .= "</table></td></tr>\n";
                }
                $this->_array_channel = array("title" => $this->_item["title"],
                    "link" => $this->_item["link"],
                    "description" => $this->_item["description"],
                    "lastBuildDate" => $this->_item["lastBuildDate"]);
                $this->_clear_Items();
                $this->_clear_cItems();
                break;
            case "textinput":
                if ($this->_display_opt["textinput"] != "hidden" && $this->_item["name"] && $this->_item["link"]) {
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                    $this->_output .= "<form action=\"" . $this->_item["link"] . "\" ";
                    if (isset($this->_link_target)) {
                        $this->_output .= "target=\"" . $this->_link_target . "\" ";
                    }
                    $this->_output .= "method=\"get\">\n";
                    $this->_output .= "<div class=\"fase4_rdf_title\">" . $this->_item["title"] . "</div>";
                    $this->_output .= strip_tags($this->_item["description"], "<a>, <img>") . "<br><br>\n";
                    $this->_output .= "<input class=\"fase4_rdf_input\" type=\"text\" name=\"" . $this->_item["name"] . "\">&nbsp;\n";
                    $this->_output .= "<input class=\"fase4_rdf_input\" type=\"submit\" value=\"go\">";
                    $this->_output .= "</form>\n";
                    $this->_output .= "</td></tr>\n";
                    $this->_array_textinput = array("title" => $this->_item["title"],
                        "name" => $this->_item["name"],
                        "link" => $this->_item["link"],
                        "description" => $this->_item["description"]);
                    $this->_clear_Items();
                } elseif ($this->_display_opt["textinput"] == "hidden") {
                    $this->_clear_Items();
                }

                break;
        }
    }

    function get_array_channel() {
        return $this->_array_channel;
    }

    function get_array_item() {
        return $this->_array_item;
    }

    function get_array_textinput() {
        return $this->_array_textinput;
    }

    function get_array_image() {
        return $this->_array_image;
    }

    function _parseData($parser, $text) {
        $clean = preg_replace("/\s/", "", $text);
        if ($clean) {
            $text = preg_replace("/^\s+/", "", $text);
            if ($this->_parse_mode == "all") {
                if ($this->_item[$this->_tags[$this->_depth[$parser]]]) {
                    $this->_item[$this->_tags[$this->_depth[$parser]]] .= $text;
                } else {
                    $this->_item[$this->_tags[$this->_depth[$parser]]] = $text;
                }
            } elseif ($this->_parse_mode == "channel") {
                if ($this->_citem[$this->_ctags[$this->_cdepth[$parser]]]) {
                    $this->_citem[$this->_ctags[$this->_cdepth[$parser]]] .= $text;
                } else {
                    $this->_citem[$this->_ctags[$this->_cdepth[$parser]]] = $text;
                }
            }
        }
    }

    function set_Options($options = "") {
        if (is_array($options)) {
            $this->_display_opt = $options;
            return true;
        } else {
            unset($this->_display_opt);
            return false;
        }
    }

    function set_table_width($width = 400) {
        $this->_table_width = $width;
        return true;
    }

    function get_Options() {
        $options = array("image" => "If 'image' is set to \"hidden\" no image provided by the RDF Publisher will be displayed.",
            "channel" => "If 'channel' is set to \"hidden\" the Channel Meta Data (i.e the Title and the short description regarding the RDF Publisher will not be displayed",
            "textinput" => "If set to \"hidden\" no Input Form will be displayed",
            "build" => "If set to \"hidden\" the Build Date (if provided) of the RDF File will not be displayed",
            "cache_update" => "If set to \"hidden\" the Update Date/Time of the cached Rdf File will not be displayed");
        return $options;
    }

    function cache() {
        // checks if the cache directory already exists
        // if not, the cache directory will be created
        if (!$this->_cache_dir_ok) {
            $this->_create_cache_dir();
        }
        if ($this->_use_dynamic_display == true) {
            $this->_cached_file = md5("dynamic" . $this->_remote_file);
            $this->_cache_type = "normal";
        } else {
            $this->_cached_file = md5($this->_remote_file);
            $this->_cache_type = "fast";
        }

        $_cache_f = $this->_cache_dir . $this->_cached_file;

        if ((!file_exists($_cache_f)) || (filemtime($_cache_f) < $this->_refresh) || (filesize($_cache_f) == 0)) {
            // We have to parse the remote file
            $this->_use_cached_file = false;
            // --> we want to provide proper Information for Use in
            // get_cache_update_time()
            clearstatcache();
            if ($this->_use_dynamic_display == true) {
                $_rdf = @implode(" ", $this->_rdf_data()); // -> proxy
                if (!$_rdf) {
                    return false;
                }
                $this->_parse_xRDF($_rdf);
                $this->_update_cache($_rdf);
                $data = $this->_output;
            } else {
                $_rdf = @implode(" ", $this->_rdf_data()); // -> proxy
                if (!$_rdf) {
                    return false;
                }
                $this->_parse_xRDF($_rdf);
                $this->_update_cache($this->_output);
                $data = $this->_output;
            }
        } else {
            // we can use the cached file
            $this->_use_cached_file = true;
            if ($this->_use_dynamic_display == true) {
                $this->_parse_xRDF(implode(" ", file($_cache_f)));
                $data = $this->_output;
            } else {
                $data = @implode(" ", file($_cache_f));
            }
        }
        return true;
    }

// END cache()

    function _create_cache_dir() {
        if (!@is_dir($this->_cache_dir)) {
            $arr = explode("/", $this->_cache_dir);
            $c = count($arr);
            if ($arr[0] == "") {
                $path = "/";
            }
            for ($i = 0; $i < $c; $i++) {
                if ($arr[$i] != "") {
                    $path .= $arr[$i] . "/";
                    if (!@is_dir($path)) {
                        if (!@mkdir($path, 0777)) {
                            $this->_throw_exception("failed to create directory:<b>" . $this->_cache_dir . "</b>.<br /><br />Exception on Line: " . __LINE__);
                            return false;
                        }
                    }
                }
            }
            $this->_cache_dir_ok = true;
            return true;
        } else {
            $this->_cache_dir_ok = true;
            return true;
        }
    }

// END _create_cache_dir()

    function _update_cache($content = "") {
        $_local = @fopen($this->_cache_dir . $this->_cached_file, "w");
        if (!$_local) {
            //$this->_throw_exception( "Cannot open ".$this->_cache_dir.$this->_cached_file."<br /><br />Exception at Line: ".__LINE__ );
            return false;
        }
        if (!fwrite($_local, $content)) {
            $this->_throw_exception("Cannot write to: " . $this->_cached_file . "<br /><br />Exception at Line: " . __LINE__);
            return false;
        }
        fclose($_local);
        return true;
    }

// END _update_cache()

    function get_cache_update_time() {
        return (file_exists($this->_cache_dir . $this->_cached_file)) ? date("d.m.Y H:i:s", filemtime($this->_cache_dir . $this->_cached_file)) : "Cachemiss";
    }

// END get_cache_update_time()

    function get_CacheType() {
        return $this->_cache_type;
    }

    function is_cachedFile() {
        return $this->_use_cached_file;
    }

    function clear_cache() {
        $dir = dir($this->_cache_dir);
        while ($file = $dir->read()) {
            if ($file != "." && $file != "..") {
                if (!@unlink($dir->path . $file)) {
                    $this->_throw_exception(
                            "Unable to unlink " . $dir->path . $file
                            . "<br /><br />Exception at Line: " . __LINE__);
                    return false;
                }
            }
        }
        $dir->close();
        return true;
    }

// END clear_cache()

    function _cut_string($string, $str_len = "30") {
        if (strlen(trim($string)) > $str_len) {
            $string = substr(trim($string), 0, $str_len - 4);
            $string .= " ...";
        }
        return $string;
    }

// END _cut_string()

    function _garbage_collection() {
        srand((double) microtime() * 1000000);
        if (rand(1, 100) <= $this->gc_probability) {
            $dir = dir($this->_cache_dir);
            while ($file = $dir->read()) {
                if ($file != "." AND $file != ".." AND filemtime($dir->path . $file) <= time() - $this->_refresh) {
                    @unlink($dir->path . $file);
                }
            }
            $dir->close();
        }   // END if
    }

    function set_proxy($phost, $pport) {
        $this->_use_proxy = true;

        if ($phost != "")
            $this->_phost = $phost;

        if ($pport != "")
            $this->_pport = $pport;
    }

    function set_proxy_auth($pname, $ppasswd) {
        $this->_use_proxy_auth = true;

        if ($pname != "")
            $this->_pname = $pname;

        if ($ppasswd != "")
            $this->_ppasswd = $ppasswd;
    }

    function _rdf_data() {
        if ($this->_use_proxy == true) {
            // we need a raw socket here to connect to proxy
            $fp = fsockopen($this->_phost, $this->_pport);

            if (!$fp) {
                $this->_throw_exception($this->_remote_file . " is not available with proxy");
            } else {
                if ($this->_use_proxy_auth == true) {
                    fputs($fp, "GET " . $this->_remote_file . " HTTP/1.0\r\n\r\n");
                } else {
                    fputs($fp, "GET " . $this->_remote_file . " HTTP/1.0\r\nProxy-Authorization: Basic " . base64_encode("$this->_pname:$this->_ppasswd") . "\r\n\r\n");
                }
            }


            for ($i = 0; !feof($fp); $i++) {
                $usable_data[$i] = "";
                $usable_data[$i] = fgets($fp, 4096);

                // PARSE HEADER ---- first line has to be <?xml, second rdf or rss, and third is blank
                // strstr did not fit (ask Rasmus why), so we compare each character
                if (($usable_data[$i][0] == "<" ) &&
                        ($usable_data[$i][1] == "?" ) &&
                        ($usable_data[$i][2] == "x" ) &&
                        ($usable_data[$i][3] == "m" ) &&
                        ($usable_data[$i][4] == "l" )) {
                    $usable_data[0] = $usable_data[$i]; // save current field
                    $i = 1; // just reset array to start
                }

                // there seems to be proxystuff after the <?xml....we delete this
                if ((
                        ($usable_data[$i][0] == "<" ) &&
                        ($usable_data[$i][1] == "r" ) &&
                        ($usable_data[$i][2] == "d" ) &&
                        ($usable_data[$i][3] == "f" ) &&
                        ($usable_data[$i][4] == ":" )
                        ) ||
                        (
                        ($usable_data[$i][0] == "<" ) &&
                        ($usable_data[$i][1] == "r" ) &&
                        ($usable_data[$i][2] == "s" ) &&
                        ($usable_data[$i][3] == "s" )
                        )
                ) {

                    $usable_data[1] = $usable_data[$i]; // save current field
                    $usable_data[2] = "\n";
                    $i = 2; // just reset array to start
                }
            }

            fclose($fp);
            return $usable_data;
        } else {
            return (file($this->_remote_file));
        }
    }

}

?>