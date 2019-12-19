<?php
/*
 * SAPE.ru -- Интеллектуальная система купли-продажи ссылок и статей
 *
 * PHP-клиент, версия 1.0.8 от 09.04.2010
 *
 * По всем вопросам обращайтесь на support@sape.ru
 *
 * Вебмастеры! Не нужно ничего менять в этом файле! Все настройки - через параметры при вызове кода.
 * Читайте: http://help.sape.ru/
 *
 */

// Основной класс, выполняющий всю рутину
class SAPE_base {

    var $_version           = '1.0.8';

    var $_verbose           = false;

    var $_charset           = '';               // http://www.php.net/manual/en/function.iconv.php

    var $_sape_charset      = '';

    var $_server_list       = array('dispenser-01.sape.ru', 'dispenser-02.sape.ru');

    var $_cache_lifetime    = 3600;             // Пожалейте наш сервер :о)

    // Если скачать базу ссылок не удалось, то следующая попытка будет через столько секунд
    var $_cache_reloadtime  = 600;

    var $_error             = '';

    var $_host              = '';

    var $_request_uri       = '';

    var $_multi_site        = false;

    var $_fetch_remote_type = '';              // Способ подключения к удалённому серверу [file_get_contents|curl|socket]

    var $_socket_timeout    = 6;               // Сколько ждать ответа

    var $_force_show_code   = false;

    var $_is_our_bot        = false;           // Если наш робот

    var $_debug             = false;

    var $_db_file           = '';              // Путь к файлу с данными

    var $_user_agent        = 'SAPE_Client PHP';

    function SAPE_base($options = null) {

        // Поехали :o)

        $host = '';

        if (is_array($options)) {
            if (isset($options['host'])) {
                $host = $options['host'];
            }
        } elseif (strlen($options)) {
            $host = $options;
            $options = array();
        } else {
            $options = array();
        }

        // Какой сайт?
        if (strlen($host)) {
            $this->_host = $host;
        } else {
            $this->_host = $_SERVER['HTTP_HOST'];
        }

        $this->_host = preg_replace('/^http:\/\//', '', $this->_host);
        $this->_host = preg_replace('/^www\./', '', $this->_host);

        // Какая страница?
        if (isset($options['request_uri']) && strlen($options['request_uri'])) {
            $this->_request_uri = $options['request_uri'];
        } else {
            $this->_request_uri = $_SERVER['REQUEST_URI'];
        }

        // На случай, если хочется много сайтов в одной папке
        if (isset($options['multi_site']) && $options['multi_site'] == true) {
            $this->_multi_site = true;
        }

        // Сообщать об ошибках
        if (isset($options['verbose']) && $options['verbose'] == true) {
            $this->_verbose = true;
        }

        // Кодировка
        if (isset($options['charset']) && strlen($options['charset'])) {
            $this->_charset = $options['charset'];
        } else {
            $this->_charset = 'windows-1251';
        }

        if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type'])) {
            $this->_fetch_remote_type = $options['fetch_remote_type'];
        }

        if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
            $this->_socket_timeout = $options['socket_timeout'];
        }

        // Всегда выводить чек-код
        if (isset($options['force_show_code']) && $options['force_show_code'] == true) {
            $this->_force_show_code = true;
        }

        // Выводить информацию о дебаге
        if (isset($options['debug']) && $options['debug'] == true) {
            $this->_debug = true;
        }

        if (!defined('_SAPE_USER')) {
            return $this->raise_error('Не задана константа _SAPE_USER');
        }

        // Определяем наш ли робот
        if (isset($_COOKIE['sape_cookie']) && ($_COOKIE['sape_cookie'] == _SAPE_USER)) {
            $this->_is_our_bot = true;
            if (isset($_COOKIE['sape_debug']) && ($_COOKIE['sape_debug'] == 1)) {
                $this->_debug = true;
            }
        } else {
            $this->_is_our_bot = false;
        }
    }


    /*
     * Функция для подключения к удалённому серверу
     */
    function fetch_remote_file($host, $path) {

        $user_agent = $this->_user_agent.' '.$this->_version;
        @ini_set('allow_url_fopen',          1);
        @ini_set('default_socket_timeout',   $this->_socket_timeout);
        @ini_set('user_agent',               $user_agent);
        if (
            $this->_fetch_remote_type == 'file_get_contents'
            ||
            (
                $this->_fetch_remote_type == ''
                &&
                function_exists('file_get_contents')
                &&
                ini_get('allow_url_fopen') == 1
            )
        ) {
            $this->_fetch_remote_type = 'file_get_contents';
            if(
                function_exists('stream_context_create')
            ) {
                $context = array('http' => array ('header'=> 'Accept-Charset: ' . $this->_charset, ),);
                $xcontext = @stream_context_create($context);
                if ($data = @file_get_contents('http://' . $host . $path, false, $xcontext)) {
                    return $data;
                }
            }

            if ($data = @file_get_contents('http://' . $host . $path)) {
                return $data;
            }

        } elseif (
            $this->_fetch_remote_type == 'curl'
            ||
            (
                $this->_fetch_remote_type == ''
                &&
                function_exists('curl_init')
            )
        ) {
            $this->_fetch_remote_type = 'curl';
            if ($ch = @curl_init()) {

                @curl_setopt($ch, CURLOPT_URL,              'http://' . $host . $path);
                @curl_setopt($ch, CURLOPT_HEADER,           false);
                @curl_setopt($ch, CURLOPT_HTTPHEADER,       array('Accept-Charset: ' . $this->_charset));
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER,   true);
                @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,   $this->_socket_timeout);
                @curl_setopt($ch, CURLOPT_USERAGENT,        $user_agent);

                if ($data = @curl_exec($ch)) {
                    return $data;
                }

                @curl_close($ch);
            }

        } else {
            $this->_fetch_remote_type = 'socket';
            $buff = '';
            $fp = @fsockopen($host, 80, $errno, $errstr, $this->_socket_timeout);
            if ($fp) {
                @fputs($fp, "GET {$path} HTTP/1.0\r\nHost: {$host}\r\n");
                @fputs($fp, "User-Agent: {$user_agent}\r\n");
                @fputs($fp, "Accept-Charset: {$this->_charset}\r\n\r\n");
                while (!@feof($fp)) {
                    $buff .= @fgets($fp, 128);
                }
                @fclose($fp);

                $page = explode("\r\n\r\n", $buff, 2);

                return $page[1];
            }

        }

        return $this->raise_error('Не могу подключиться к серверу: ' . $host . $path.', type: '.$this->_fetch_remote_type);
    }

    /*
     * Функция чтения из локального файла
     */
    function _read($filename) {

        $fp = @fopen($filename, 'rb');
        @flock($fp, LOCK_SH);
        if ($fp) {
            clearstatcache();
            $length = @filesize($filename);
            $mqr = @get_magic_quotes_runtime();
            @set_magic_quotes_runtime(0);
            if ($length) {
                $data = @fread($fp, $length);
            } else {
                $data = '';
            }
            @set_magic_quotes_runtime($mqr);
            @flock($fp, LOCK_UN);
            @fclose($fp);

            return $data;
        }

        return $this->raise_error('Не могу считать данные из файла: ' . $filename);
    }

    /*
     * Функция записи в локальный файл
     */
    function _write($filename, $data) {

        $fp = @fopen($filename, 'ab');
        if ($fp) {
            if (flock($fp, LOCK_EX|LOCK_NB)) {
		$length = strlen($data);
		ftruncate($fp, 0);
		@fwrite($fp, $data, $length);
		@flock($fp, LOCK_UN);
		@fclose($fp);

		if (md5($this->_read($filename)) != md5($data)) {
		    @unlink($filename);
		    return $this->raise_error('Нарушена целостность данных при записи в файл: ' . $filename);
		}
	    } else {
		return false;
	    }

            return true;
        }

        return $this->raise_error('Не могу записать данные в файл: ' . $filename);
    }

    /*
     * Функция обработки ошибок
     */
    function raise_error($e) {

        $this->_error = '<p style="color: red; font-weight: bold;">SAPE ERROR: ' . $e . '</p>';

        if ($this->_verbose == true) {
            print $this->_error;
        }

        return false;
    }

    function load_data() {
        $this->_db_file = $this->_get_db_file();

        if (!is_file($this->_db_file)) {
            // Пытаемся создать файл.
            if (@touch($this->_db_file)) {
                @chmod($this->_db_file, 0666);    // Права доступа
            } else {
                return $this->raise_error('Нет файла ' . $this->_db_file . '. Создать не удалось. Выставите права 777 на папку.');
            }
        }

        if (!is_writable($this->_db_file)) {
            return $this->raise_error('Нет доступа на запись к файлу: ' . $this->_db_file . '! Выставите права 777 на папку.');
        }

        @clearstatcache();

	$data = $this->_read($this->_db_file);
        if (
            !$this->_is_our_bot
            &&
            (
                filemtime($this->_db_file) < (time()-$this->_cache_lifetime)
                ||
                filesize($this->_db_file) == 0
		||
		@unserialize($data) == false
            )
        ) {
            // Чтобы не повесить площадку клиента и чтобы не было одновременных запросов
            @touch($this->_db_file, (time() - $this->_cache_lifetime + $this->_cache_reloadtime));

            $path = $this->_get_dispenser_path();
            if (strlen($this->_charset)) {
                $path .= '&charset=' . $this->_charset;
            }

            foreach ($this->_server_list as $i => $server){
                if ($data = $this->fetch_remote_file($server, $path)) {
                    if (substr($data, 0, 12) == 'FATAL ERROR:') {
                        $this->raise_error($data);
                    } else {
                        // [псевдо]проверка целостности:
                        $hash = @unserialize($data);
                        if ($hash != false) {
                            // попытаемся записать кодировку в кеш
                            $hash['__sape_charset__'] = $this->_charset;
                            $hash['__last_update__'] = time();
                            $hash['__multi_site__'] =  $this->_multi_site;
                            $hash['__fetch_remote_type__'] = $this->_fetch_remote_type;
                            $hash['__php_version__'] = phpversion();
                            $hash['__server_software__'] = $_SERVER['SERVER_SOFTWARE'];

                            $data_new = @serialize($hash);
                            if ($data_new) {
                                $data = $data_new;
                            }

                            $this->_write($this->_db_file, $data);
                            break;
                        }
                    }
                }
            }
        }

        // Убиваем PHPSESSID
        if (strlen(session_id())) {
            $session = session_name() . '=' . session_id();
            $this->_request_uri = str_replace(array('?'.$session,'&'.$session), '', $this->_request_uri);
        }

        $this->set_data(@unserialize($data));
    }
}

class SAPE_client extends SAPE_base {

    var $_links_delimiter = '';
    var $_links = array();
    var $_links_page = array();
    var $_user_agent = 'SAPE_Client PHP';

    function SAPE_client($options = null) {
        parent::SAPE_base($options);
        $this->load_data();
    }

    /*
     * Ccылки можно показывать по частям
     */
    function return_links($n = null, $offset = 0) {

        if (is_array($this->_links_page)) {

            $total_page_links = count($this->_links_page);

            if (!is_numeric($n) || $n > $total_page_links) {
                $n = $total_page_links;
            }

            $links = array();

            for ($i = 1; $i <= $n; $i++) {
                if ($offset > 0 && $i <= $offset) {
                    array_shift($this->_links_page);
                } else {
                    $links[] = array_shift($this->_links_page);
                }
            }

            $html = join($this->_links_delimiter, $links);
            
            // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
            if (
                strlen($this->_charset) > 0
                &&
                strlen($this->_sape_charset) > 0
                &&
                $this->_sape_charset != $this->_charset
                &&
                function_exists('iconv')
            ) {
                $new_html  = @iconv($this->_sape_charset, $this->_charset, $html);
                if ($new_html) {
                    $html = $new_html;
                }
            }
            
            if ($this->_is_our_bot) {
                $html = '<sape_noindex>' . $html . '</sape_noindex>';
            }
            
            return $html;

        } else {
            return $this->_links_page;
        }

    }

    function _get_db_file() {
        if ($this->_multi_site) {
            return dirname(__FILE__) . '/' . $this->_host . '.links.db';
        } else {
            return dirname(__FILE__) . '/links.db';
        }
    }

    function _get_dispenser_path() {
        return '/code.php?user=' . _SAPE_USER . '&host=' . $this->_host;
    }

    function set_data($data) {
        $this->_links = $data;
        if (isset($this->_links['__sape_delimiter__'])) {
            $this->_links_delimiter = $this->_links['__sape_delimiter__'];
        }
        // определяем кодировку кеша
        if (isset($this->_links['__sape_charset__'])) {
            $this->_sape_charset = $this->_links['__sape_charset__'];
        } else {
            $this->_sape_charset = '';
        }
        if (@array_key_exists($this->_request_uri, $this->_links) && is_array($this->_links[$this->_request_uri])) {
            $this->_links_page = $this->_links[$this->_request_uri];
        } else {
            if (isset($this->_links['__sape_new_url__']) && strlen($this->_links['__sape_new_url__'])) {
                if ($this->_is_our_bot || $this->_force_show_code){
                    $this->_links_page = $this->_links['__sape_new_url__'];
                }
            }
        }
    }
}

class SAPE_context extends SAPE_base {

    var $_words = array();
    var $_words_page = array();
    var $_user_agent = 'SAPE_Context PHP';
    var $_filter_tags = array('a', 'textarea', 'select', 'script', 'style', 'label', 'noscript' , 'noindex', 'button');

    function SAPE_context($options = null) {
        parent::SAPE_base($options);
        $this->load_data();
    }

    /*
     * Замена слов в куске текста и обрамляет его тегами sape_index
     *
     */

    function replace_in_text_segment($text){
        $debug = '';
        if ($this->_debug) {
            $debug .= "<!-- argument for replace_in_text_segment: \r\n".base64_encode($text)."\r\n -->";
        }
        if (count($this->_words_page) > 0) {

            $source_sentence = array();
            if ($this->_debug) {
                $debug .= '<!-- sentences for replace: ';
            }            
            //Создаем массив исходных текстов для замены
            foreach ($this->_words_page as $n => $sentence){
                //Заменяем все сущности на символы
                $special_chars = array(
                    '&amp;' => '&',
                    '&quot;' => '"',                
                    '&#039;' => '\'',
                    '&lt;' => '<',
                    '&gt;' => '>' 
                );
                $sentence = strip_tags($sentence);
                foreach ($special_chars as $from => $to){
                    str_replace($from, $to, $sentence);
                }
                //Преобразуем все спец символы в сущности
                $sentence = htmlspecialchars($sentence);
                //Квотируем
                $sentence = preg_quote($sentence, '/');
                $replace_array = array();
                if (preg_match_all('/(&[#a-zA-Z0-9]{2,6};)/isU', $sentence, $out)) {
                    for ($i=0; $i<count($out[1]); $i++){
                        $unspec = $special_chars[$out[1][$i]];
                        $real = $out[1][$i];
                        $replace_array[$unspec] = $real;
                    }
                }                 
                //Заменяем сущности на ИЛИ (сущность|символ)
                foreach ($replace_array as $unspec => $real){
                    $sentence = str_replace($real, '(('.$real.')|('.$unspec.'))', $sentence);    
                }
                //Заменяем пробелы на переносы или сущности пробелов
                $source_sentences[$n] = str_replace(' ','((\s)|(&nbsp;))+',$sentence);
                
                if ($this->_debug) {
                    $debug .= $source_sentences[$n]."\r\n\r\n";
                }
            }
            
            if ($this->_debug) {
                $debug .= '-->';
            }            

            //если это первый кусок, то не будем добавлять <
            $first_part = true;
            //пустая переменная для записи
            
            if (count($source_sentences) > 0) {

                $content = '';
                $open_tags = array(); //Открытые забаненые тэги
                $close_tag = ''; //Название текущего закрывающего тэга

                //Разбиваем по символу начала тега
                $part = strtok(' '.$text, '<');

                while ($part !== false){
                    //Определяем название тэга
                    if (preg_match('/(?si)^(\/?[a-z0-9]+)/', $part, $matches)) {
                        //Определяем название тега
                        $tag_name = strtolower($matches[1]);
                        //Определяем закрывающий ли тэг
                        if (substr($tag_name,0,1) == '/') {
                            $close_tag = substr($tag_name, 1);
                            if ($this->_debug) {
                              $debug .= '<!-- close_tag: '.$close_tag.' -->';
                            }
                        } else {
                            $close_tag = '';
                            if ($this->_debug) {
                              $debug .= '<!-- open_tag: '.$tag_name.' -->';
                            }
                        }
                        $cnt_tags = count($open_tags);
                        //Если закрывающий тег совпадает с тегом в стеке открытых запрещенных тегов
                        if (($cnt_tags  > 0) && ($open_tags[$cnt_tags-1] == $close_tag)) {
                            array_pop($open_tags);
                            if ($this->_debug) {
                                $debug .= '<!-- '.$tag_name.' - deleted from open_tags -->';
                            }
                            if ($cnt_tags-1 ==0) {
                                if ($this->_debug) {
                                    $debug .= '<!-- start replacement -->';
                                }
                            }
                        }

                        //Если нет открытых плохих тегов, то обрабатываем
                        if (count($open_tags) == 0) {
                            //если не запрещенный тэг, то начинаем обработку
                            if (!in_array($tag_name, $this->_filter_tags)) {
                                $split_parts = explode('>', $part, 2);
                                //Перестраховываемся
                                if (count($split_parts) == 2) {
                                    //Начинаем перебор фраз для замены
                                    foreach ($source_sentences as $n => $sentence){
                                        if (preg_match('/'.$sentence.'/', $split_parts[1]) == 1) {
                                            $split_parts[1] = preg_replace('/'.$sentence.'/', str_replace('$','\$', $this->_words_page[$n]), $split_parts[1], 1);
                                            if ($this->_debug) {
                                                $debug .= '<!-- '.$sentence.' --- '.$this->_words_page[$n].' replaced -->';
                                            }
                                            
                                            //Если заменили, то удаляем строчку из списка замены
                                            unset($source_sentences[$n]);
                                            unset($this->_words_page[$n]);                                            
                                        }
                                    }
                                    $part = $split_parts[0].'>'.$split_parts[1];
                                    unset($split_parts);
                                }
                            } else {
                                //Если у нас запрещеный тэг, то помещаем его в стек открытых
                                $open_tags[] = $tag_name;
                                if ($this->_debug) {
                                    $debug .= '<!-- '.$tag_name.' - added to open_tags, stop replacement -->';
                                }
                            }
                        }
                    } else {
                        //Если нет названия тега, то считаем, что перед нами текст
                        foreach ($source_sentences as $n => $sentence){
                             if (preg_match('/'.$sentence.'/', $part) == 1) {
                                $part = preg_replace('/'.$sentence.'/',  str_replace('$','\$', $this->_words_page[$n]), $part, 1);

                                if ($this->_debug) {
                                    $debug .= '<!-- '.$sentence.' --- '.$this->_words_page[$n].' replaced -->';
                                }
                                
                                //Если заменили, то удаляем строчку из списка замены,
                                //чтобы было можно делать множественный вызов
                                unset($source_sentences[$n]);
                                unset($this->_words_page[$n]);                                
                            }
                        }
                    }

                    //Если у нас режим дебагинга, то выводим
                    if ($this->_debug) {
                        $content .= $debug;
                        $debug = '';
                    }
                    //Если это первая часть, то не выводим <
                    if ($first_part ) {
                        $content .= $part;
                        $first_part = false;
                    } else {
                        $content .= $debug.'<'.$part;
                    }
                    //Получаем следующу часть
                    unset($part);
                    $part = strtok('<');
                }
                $text = ltrim($content);
                unset($content);
            }
    } else {
        if ($this->_debug) {
            $debug .= '<!-- No word`s for page -->';
        }
    }

    if ($this->_debug) {
        $debug .= '<!-- END: work of replace_in_text_segment() -->';
    }

    if ($this->_is_our_bot || $this->_force_show_code || $this->_debug) {
        $text = '<sape_index>'.$text.'</sape_index>';
        if (isset($this->_words['__sape_new_url__']) && strlen($this->_words['__sape_new_url__'])) {
                $text .= $this->_words['__sape_new_url__'];
        }
    }

    if ($this->_debug) {
        if (count($this->_words_page) > 0) {
            $text .= '<!-- Not replaced: '."\r\n";
           foreach ($this->_words_page as $n => $value){
               $text .= $value."\r\n\r\n";
           }
           $text .= '-->';
        }
        
        $text .= $debug;
    }
        return $text;
    }

    /*
     * Замена слов
     *
     */
    function replace_in_page(&$buffer) {

        if (count($this->_words_page) > 0) {
            //разбиваем строку по sape_index
                 //Проверяем есть ли теги sape_index
                 $split_content = preg_split('/(?smi)(<\/?sape_index>)/', $buffer, -1);
                 $cnt_parts = count($split_content);
                 if ($cnt_parts > 1) {
                     //Если есть хоть одна пара sape_index, то начинаем работу
                     if ($cnt_parts >= 3) {
                         for ($i =1; $i < $cnt_parts; $i = $i + 2){
                             $split_content[$i] = $this->replace_in_text_segment($split_content[$i]);
                         }
                     }
                    $buffer = implode('', $split_content);
                     if ($this->_debug) {
                         $buffer .= '<!-- Split by Sape_index cnt_parts='.$cnt_parts.'-->';
                     }
                 } else {
                     //Если не нашли sape_index, то пробуем разбить по BODY
                     $split_content = preg_split('/(?smi)(<\/?body[^>]*>)/', $buffer, -1, PREG_SPLIT_DELIM_CAPTURE);
                     //Если нашли содержимое между body
                     if (count($split_content) == 5) {
                         $split_content[0] = $split_content[0].$split_content[1];
                         $split_content[1] = $this->replace_in_text_segment($split_content[2]);
                         $split_content[2] = $split_content[3].$split_content[4];
                         unset($split_content[3]);
                         unset($split_content[4]);
                         $buffer = $split_content[0].$split_content[1].$split_content[2];
                         if ($this->_debug) {
                             $buffer .= '<!-- Split by BODY -->';
                         }
                     } else {
                        //Если не нашли sape_index и не смогли разбить по body
                         if ($this->_debug) {
                             $buffer .= '<!-- Can`t split by BODY -->';
                         }
                     }
                 }

        } else {
            if (!$this->_is_our_bot && !$this->_force_show_code && !$this->_debug) {
                $buffer = preg_replace('/(?smi)(<\/?sape_index>)/','', $buffer);
            } else {
                if (isset($this->_words['__sape_new_url__']) && strlen($this->_words['__sape_new_url__'])) {
                        $buffer .= $this->_words['__sape_new_url__'];
                }
            }
            if ($this->_debug) {
               $buffer .= '<!-- No word`s for page -->';
            }
        }
        return $buffer;
    }

    function _get_db_file() {
        if ($this->_multi_site) {
            return dirname(__FILE__) . '/' . $this->_host . '.words.db';
        } else {
            return dirname(__FILE__) . '/words.db';
        }
    }
    
    function _get_dispenser_path() {
        return '/code_context.php?user=' . _SAPE_USER . '&host=' . $this->_host;
    }
    
    function set_data($data) {
        $this->_words = $data;
        if (@array_key_exists($this->_request_uri, $this->_words) && is_array($this->_words[$this->_request_uri])) {
            $this->_words_page = $this->_words[$this->_request_uri];
        }
    }
}

class SAPE_articles extends SAPE_base {

    var $_request_mode;

    var $_server_list             = array('dispenser.articles.sape.ru');

    var $_data                    = array();

    var $_article_id;

    var $_save_file_name;

    var $_announcements_delimiter = '';

    var $_images_path;

    var $_template_error = false;

    var $_noindex_code = '<!--sape_noindex-->';

    var $_headers_enabled = false;

    var $_mask_code;

    var $_real_host;

    function SAPE_articles($options = null){
        parent::SAPE_base($options);
        if (is_array($options) && isset($options['headers_enabled'])) {
            $this->_headers_enabled = $options['headers_enabled'];
        }
        // Кодировка
        if (isset($options['charset']) && strlen($options['charset'])) {
            $this->_charset = $options['charset'];
        } else {
            $this->_charset = '';
        }
        $this->_get_index();
        if (!empty($this->_data['index']['announcements_delimiter'])) {
            $this->_announcements_delimiter = $this->_data['index']['announcements_delimiter'];
        }
        if (!empty($this->_data['index']['charset'])
            and !(isset($options['charset']) && strlen($options['charset']))) {
            $this->_charset = $this->_data['index']['charset'];
        }
        if (is_array($options)) {
            if (isset($options['host'])) {
                $host = $options['host'];
            }
        } elseif (strlen($options)) {
            $host = $options;
            $options = array();
        }
        if (isset($host) && strlen($host)) {
             $this->_real_host = $host;
        } else {
             $this->_real_host = $_SERVER['HTTP_HOST'];
        }
    }

    function return_announcements($n = null, $offset = 0){
        $output = '';
        if ($this->_force_show_code || $this->_is_our_bot) {
            if (isset($this->_data['index']['checkCode'])) {
                $output .= $this->_data['index']['checkCode'];
            }
        }

        if (isset($this->_data['index']['announcements'][$this->_request_uri])) {

            $total_page_links = count($this->_data['index']['announcements'][$this->_request_uri]);

            if (!is_numeric($n) || $n > $total_page_links) {
                $n = $total_page_links;
            }

            $links = array();

            for ($i = 1; $i <= $n; $i++) {
                if ($offset > 0 && $i <= $offset) {
                    array_shift($this->_data['index']['announcements'][$this->_request_uri]);
                } else {
                    $links[] = array_shift($this->_data['index']['announcements'][$this->_request_uri]);
                }
            }

            $html = join($this->_announcements_delimiter, $links);

            if ($this->_is_our_bot) {
                $html = '<sape_noindex>' . $html . '</sape_noindex>';
            }

            $output .= $html;

        }

        return $output;
    }

    function _get_index(){
        $this->_set_request_mode('index');
        $this->_save_file_name = 'articles.db';
        $this->load_data();
    }

    function process_request(){

        if (!empty($this->_data['index']) and isset($this->_data['index']['articles'][$this->_request_uri])) {
			return $this->_return_article();
        } elseif (!empty($this->_data['index']) and isset($this->_data['index']['images'][$this->_request_uri])) {
            return $this->_return_image();
          } else {
                if ($this->_is_our_bot) {
                    return $this->_return_html($this->_data['index']['checkCode'] . $this->_noindex_code);
                } else {
                    return $this->_return_not_found();
                }
          }
    }

    function _return_article(){
        $this->_set_request_mode('article');
        //Загружаем статью
        $article_meta = $this->_data['index']['articles'][$this->_request_uri];
        $this->_save_file_name = $article_meta['id'] . '.article.db';
        $this->_article_id = $article_meta['id'];
        $this->load_data();

        //Обновим если устарела
        if (!isset($this->_data['article']['date_updated']) OR $this->_data['article']['date_updated']  < $article_meta['date_updated']) {
            unlink($this->_get_db_file());
            $this->load_data();
        }

        //Получим шаблон
        $template = $this->_get_template($this->_data['index']['templates'][$article_meta['template_id']]['url'], $article_meta['template_id']);
	
        //Выведем статью
        $article_html = $this->_fetch_article($template);

        if ($this->_is_our_bot) {
            $article_html .= $this->_noindex_code;
        }

        return $this->_return_html($article_html);

    }

    function _prepare_path_to_images(){
        if ($this->_multi_site) {
            $this->_images_path = dirname(__FILE__) . '/' . $this->_host . '/images/';
        } else {
            $this->_images_path = dirname(__FILE__) . '/images/';
          }

        if (!is_dir($this->_images_path)) {
            // Пытаемся создать папку.
            if (@mkdir($this->_images_path)) {
                @chmod($this->_images_path, 0777);    // Права доступа
            } else {
                return $this->raise_error('Нет папки ' . $this->_images_path . '. Создать не удалось. Выставите права 777 на папку.');
              }
        }
    }

    function _return_image(){
        $this->_set_request_mode('image');
        $this->_prepare_path_to_images();

        //Проверим загружена ли картинка
        $image_meta = $this->_data['index']['images'][$this->_request_uri];
        $image_path = $this->_images_path . $image_meta['id']. '.' . $image_meta['ext'];

        if (!is_file($image_path) or filemtime($image_path) > $image_meta['date_updated']) {
            // Чтобы не повесить площадку клиента и чтобы не было одновременных запросов
            @touch($image_path, $image_meta['date_updated']);

            $path = $image_meta['dispenser_path'];
            if (strlen($this->_charset)) {
                $path .= '&charset=' . $this->_charset;
            }

            foreach ($this->_server_list as $i => $server){
                if ($data = $this->fetch_remote_file($server, $path)) {
                    if (substr($data, 0, 12) == 'FATAL ERROR:') {
                        $this->raise_error($data);
                    } else {
                        // [псевдо]проверка целостности:
                        if (strlen($data) > 0) {
                            $this->_write($image_path, $data);
                            break;
                        }
                    }
                }
            }
        }

        unset($data);
        if (!is_file($image_path)) {
            return $this->_return_not_found();
        }
        $image_file_meta = @getimagesize($image_path);
        $content_type = isset($image_file_meta['mime'])?$image_file_meta['mime']:'image';
        if ($this->_headers_enabled) {
            header('Content-Type: ' . $content_type);
        }
        return $this->_read($image_path);
    }

    function _fetch_article($template){
        if (strlen($this->_charset)) {
            $template = str_replace('{meta_charset}',  $this->_charset, $template);
        }
        foreach ($this->_data['index']['template_fields'] as $field){
            if (isset($this->_data['article'][$field])) {
                $template = str_replace('{' . $field . '}',  $this->_data['article'][$field], $template);
            } else {
                $template = str_replace('{' . $field . '}',  '', $template);
            }
        }
        return ($template);
    }

    function _get_template($template_url, $templateId){
        //Загрузим индекс если есть
        $this->_save_file_name = 'tpl.articles.db';
        $index_file = $this->_get_db_file();

        if (file_exists($index_file)) {
            $this->_data['templates'] = unserialize($this->_read($index_file));
        }


        //Если шаблон не найден или устарел в индексе, обновим его
        if (!isset($this->_data['templates'][$template_url])
            or (mktime() - $this->_data['templates'][$template_url]['date_updated']) > $this->_data['index']['templates'][$templateId]['lifetime']) {
            $this->_refresh_template($template_url, $index_file);
        }
        //Если шаблон не обнаружен - ошибка
        if (!isset($this->_data['templates'][$template_url])) {
            if ($this->_template_error){
                return $this->raise_error($this->_template_error);
            }
            return $this->raise_error('Не найден шаблон для статьи');
        }

        return $this->_data['templates'][$template_url]['body'];
    }

    function _refresh_template($template_url, $index_file){
        $parseUrl = parse_url($template_url);

        $download_url = '';
        if ($parseUrl['path']) {
            $download_url .= $parseUrl['path'];
        }
        if (isset($parseUrl['query'])) {
            $download_url .= '?' . $parseUrl['query'];
        }

        $template_body = $this->fetch_remote_file($this->_real_host, $download_url);

        //проверим его на корректность
        if (!$this->_is_valid_template($template_body)){
            return false;
        }

        $template_body = $this->_cut_template_links($template_body);

        //Запишем его вместе с другими в кэш
        $this->_data['templates'][$template_url] = array( 'body' => $template_body, 'date_updated' => mktime());
        //И сохраним кэш
        $this->_write($index_file, serialize($this->_data['templates']));
    }

    function _fill_mask ($data) {
        global $unnecessary;
        $len = strlen($data[0]);
        $mask = str_repeat($this->_mask_code, $len);
        $unnecessary[$this->_mask_code][] = array(
            'mask' => $mask,
            'code' => $data[0],
            'len'  => $len
        );

        return $mask;
    }

    function _cut_unnecessary(&$contents, $code, $mask) {
        global $unnecessary;
        $this->_mask_code = $code;
        $_unnecessary[$this->_mask_code] = array();
        $contents = preg_replace_callback($mask, array($this, '_fill_mask'), $contents);
    }

    function _restore_unnecessary(&$contents, $code) {
        global $unnecessary;
        $offset = 0;
        if (!empty($unnecessary[$code])) {
            foreach ($unnecessary[$code] as $meta) {
                $offset = strpos($contents, $meta['mask'], $offset);
                $contents = substr($contents, 0, $offset)
                    . $meta['code'] . substr($contents, $offset + $meta['len']);
            }
        }
    }

    function _cut_template_links($template_body){
        $link_pattern    = '~(\<a [^\>]*?href[^\>]*?\=["\']{0,1}http[^\>]*?\>.*?\</a[^\>]*?\>|\<a [^\>]*?href[^\>]*?\=["\']{0,1}http[^\>]*?\>)~si';
        $link_subpattern = '~\<a~si';
        $rel_pattern     = '~[\s]{1}rel\=["\']{1}[^ "\'\>]*?["\']{1}| rel\=[^ "\'\>]*?[\s]{1}~si';
        $href_pattern    = '~[\s]{1}href\=["\']{0,1}(http[^ "\'\>]*)?["\']{0,1} {0,1}~si';

        $allowed_domains = $this->_data['index']['ext_links_allowed'];
        $allowed_domains[] = $this -> _host;
        $allowed_domains[] = 'www.' . $this -> _host;
        $this->_cut_unnecessary($template_body, 'C', '|<!--(.*?)-->|smi');
        $this->_cut_unnecessary($template_body, 'S', '|<script[^>]*>.*?</script>|si');
        $this->_cut_unnecessary($template_body, 'N', '|<noindex[^>]*>.*?</noindex>|si');

        $slices = preg_split($link_pattern, $template_body, -1,  PREG_SPLIT_DELIM_CAPTURE );
        //Обрамляем все видимые ссылки в noindex
        if(is_array($slices)) {
            foreach ($slices as $id => $link) {
                if ($id % 2 == 0) {
                    continue;
                }
                if (preg_match($href_pattern, $link, $urls)) {
                    $parsed_url = @parse_url($urls[1]);
                    $host = isset($parsed_url['host'])?$parsed_url['host']:false;
                    if (!in_array($host, $allowed_domains) || !$host){
                        //Обрамляем в тэги noindex
                        $slices[$id] = '<noindex>' . $slices[$id] . '</noindex>';
                    }
                }
            }
            $template_body = implode('', $slices);
        }
        //Вновь отображаем содержимое внутри noindex
        $this->_restore_unnecessary($template_body, 'N');

        //Прописываем всем ссылкам nofollow
        $slices = preg_split($link_pattern, $template_body, -1,  PREG_SPLIT_DELIM_CAPTURE );
        if(is_array($slices)) {
            foreach ($slices as $id => $link) {
                if ($id % 2 == 0) {
                    continue;
                }
                if (preg_match($href_pattern, $link, $urls)) {
                    $parsed_url = @parse_url($urls[1]);
                    $host = isset($parsed_url['host'])?$parsed_url['host']:false;
                    if (!in_array($host, $allowed_domains) || !$host) {
                        //вырезаем REL
                        $slices[$id] = preg_replace($rel_pattern, '', $link);
                        //Добавляем rel=nofollow
                        $slices[$id] = preg_replace($link_subpattern, '<a rel="nofollow" ', $slices[$id]);
                    }
                }
            }
            $template_body = implode('', $slices);
        }

        $this->_restore_unnecessary($template_body, 'S');
        $this->_restore_unnecessary($template_body, 'C');
        return $template_body;
    }

    function _is_valid_template($template_body){
        foreach ($this->_data['index']['template_required_fields'] as $field){
            if (strpos($template_body, '{' . $field . '}') === false){
                $this->_template_error = 'В шаблоне не хватает поля ' . $field . '.';
                return false;
            }
        }
        return true;
    }

    function _return_html($html){
        if ($this->_headers_enabled){
            header('HTTP/1.x 200 OK');
            if (!empty($this->_charset)){
                    header('Content-Type: text/html; charset=' . $this->_charset);
            }
        }
        return $html;
    }

    function _return_not_found(){
        header('HTTP/1.x 404 Not Found');
    }

    function _get_dispenser_path(){
        switch ($this->_request_mode){
            case 'index':
                return '/?user=' . _SAPE_USER . '&host=' .
                $this->_host . '&rtype=' . $this->_request_mode;
            break;
            case 'article':
                return '/?user=' . _SAPE_USER . '&host=' .
                $this->_host . '&rtype=' . $this->_request_mode . '&artid=' . $this->_article_id;
            break;
            case 'image':
                return $this->image_url;
            break;
        }
    }

    function _set_request_mode($mode){
        $this->_request_mode = $mode;
    }

    function _get_db_file(){
        if ($this->_multi_site){
            return dirname(__FILE__) . '/' . $this->_host . '.' . $this->_save_file_name;
        }
        else{
            return dirname(__FILE__) . '/' . $this->_save_file_name;
        }
    }

    function set_data($data){
       $this->_data[$this->_request_mode] = $data;
    }

}
?>
