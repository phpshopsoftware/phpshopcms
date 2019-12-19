<?php

/**
 * ����������� ������� � ������ �����
 * @author PHPShop Software
 * @version 1.14
 * @package PHPShopClass
 * @tutorial http://doc.phpshop.ru/PHPShopClass/PHPShopModules.html
 */
class PHPShopModules {

    /**
     * @var array ������ ��������� �������� �������
     */
    var $ModValue = array();

    /**
     * @var string ������������� ���������� �������
     */
    var $ModDir;

    /**
     * @var bool ����� �������
     */
    var $debug = false;

    /**
     * @var bool ����������� ���������� �������� ��������� �������
     */
    var $memory = false;
    var $unload = array();

    /**
     * �����������
     * @param string $ModDir  ������������� ���������� �������
     */
    function __construct($ModDir = "phpshop/modules/", $mod_path = false) {
        $this->ModDir = $ModDir;
        $this->objBase = $GLOBALS['SysValue']['base']['modules'];

        $this->PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;

        $this->path = $mod_path;

        $this->checkKeyBase();

        // ��������� ���� �������
        $this->addTemplateHook();

        $data = $this->PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
        if (is_array($data))
            foreach ($data as $row) {
                $path = $row['path'];
                if (empty($_SESSION[$this->getKeyName()][crc32($path)]) or $this->path)
                    $this->getIni($path);
            }


        // �������� ����������� ������� � �����
        foreach ($this->unload as $v)
            $this->getIni($v, false);
    }

    /**
     * ��������� ���������� ������� ����� ������� /php/hook/
     */
    function addTemplateHook() {
        $ini = 'phpshop/templates' . chr(47) . @$_SESSION['skin'] . "/php/inc/config.ini";
        if (file_exists($ini)) {
            $SysValue = @parse_ini_file($ini, 1);

            if (is_array($SysValue['autoload']))
                foreach ($SysValue['autoload'] as $k => $v)
                    if (!strstr($k, '#'))
                        $this->ModValue['autoload'][$k] = './phpshop/templates/' . $_SESSION['skin'] . chr(47) . $v;

            if (is_array($SysValue['unload']))
                foreach ($SysValue['unload'] as $k => $v)
                    if (!strstr($k, '#')) {
                        if (strstr($v, ','))
                            $unload_array = explode(",", $v);
                        else
                            $unload_array[] = $v;
                        foreach ($unload_array as $kill)
                            $this->unload[] = $kill;
                    }

            if (is_array($SysValue['hook']))
                foreach ($SysValue['hook'] as $k => $v)
                    if (!strstr($k, '#'))
                        $this->ModValue['hook'][$k][] = './phpshop/templates/' . $_SESSION['skin'] . chr(47) . $v;

            // ��������� HTML ��� ����� ���� �������        
            if (is_array($SysValue['html'])) {
                foreach ($SysValue['html'] as $k => $v)
                    if (!strstr($k, '#'))
                        $GLOBALS['SysValue']['html'][$k] = $v;
            }
            else
                unset($GLOBALS['SysValue']['html']);
        }
    }

    /**
     * ���������� �� ������
     * @param string $version ���������� ������
     */
    function getUpdate($version = false) {
        global $link_db;

        if (empty($version))
            $version = 'default';

        $file = '../modules/' . $this->path . '/updates/' . $version . '/update_module.sql';

        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $sqlArray = explode(";", $sql);
            if (is_array($sqlArray))
                foreach ($sqlArray as $val)
                    mysqli_query($link_db,$val);
        }
        $db = $this->getXml('../modules/' . $this->path . '../install/module.xml');
        return $db['version'];
    }

    /**
     * ��������� �������� ������� �������
     * @param string $path ���� �� ������������ ������
     * @param bool $add ����������/�������� ������
     */
    function getIni($path, $add = true) {
        $ini = $this->ModDir . $path . "/inc/config.ini";
        if (file_exists($ini)) {
            $SysValue = @parse_ini_file($ini, 1);

            if (!empty($SysValue['autoload']) and is_array($SysValue['autoload']))
                foreach ($SysValue['autoload'] as $k => $v)
                    if (!strstr($k, '#')) {
                        if ($add)
                            $this->ModValue['autoload'][$k] = $v;
                        else
                            unset($this->ModValue['autoload'][$k]);
                    }

            if (is_array($SysValue['unload']))
                foreach ($SysValue['unload'] as $k => $v)
                    if (!strstr($k, '#'))
                        $this->unload[] = $v;

            if (!empty($SysValue['core']) and is_array($SysValue['core'])) {
                foreach ($SysValue['core'] as $k => $v)
                    if (!strstr($k, '#')) {
                        if ($add)
                            $this->ModValue['core'][$k] = $v;
                        else
                            unset($this->ModValue['core'][$k]);
                    }
            }
            else
                $SysValue['core'] = null;

            if (!empty($SysValue['class']) and is_array($SysValue['class'])) {
                foreach ($SysValue['class'] as $k => $v)
                    if (!strstr($k, '#'))
                        $GLOBALS['SysValue']['class'][$k] = $v;
            }
            else
                $SysValue['class'] = null;

            if (!empty($SysValue['lang']) and is_array($SysValue['lang'])) {
                foreach ($SysValue['lang'] as $k => $v)
                    if (!strstr($k, '#'))
                        $GLOBALS['SysValue']['lang'][$k] = $v;
            }
            else
                $SysValue['lang'] = null;

            if (!empty($SysValue['admpanel']) and is_array($SysValue['admpanel']))
                foreach ($SysValue['admpanel'] as $k => $v)
                    if (!strstr($k, '#'))
                        $this->ModValue['admpanel'][][$k] = $v;

            if (!empty($SysValue['hook']) and is_array($SysValue['hook'])) {
                foreach ($SysValue['hook'] as $k => $v)
                    if (!strstr($k, '#')) {
                        if ($add)
                            $this->ModValue['hook'][$k][$path] = $v;
                        else
                            unset($this->ModValue['hook'][$k][$path]);
                    }
            }

            if (!empty($SysValue['templates']) and is_array($SysValue['templates'])) {
                $this->ModValue['templates'] = $SysValue['templates'];
                $GLOBALS['SysValue']['templates'][$path] = $SysValue['templates'];
            }


            if ($add) {
                $this->ModValue['base'][$path] = $SysValue['base'];
                $GLOBALS['SysValue']['base'][$path] = $SysValue['base'];
            } else {
                unset($this->ModValue['base'][$path]);
                unset($GLOBALS['SysValue']['base'][$path]);
            }

            $this->ModValue['class'] = $SysValue['class'];

            if (!empty($SysValue['field']) and is_array($SysValue['field']))
                $this->ModValue['field'][$path] = $SysValue['field'];
        }
    }

    function getKeyName() {
        return substr(md5($_SERVER["HTTP_USER_AGENT"]), 0, 5);
    }

    function crc16($data) {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return $crc;
    }

    function checkKey($key, $path) {
        $str = $path . str_replace('www.', '', $_SERVER['SERVER_NAME']);
        if ($this->crc16(substr($str, 0, 5)) . "-" . $this->crc16(substr($str, 5, 10)) . "-" . $this->crc16(substr($str, 10, 15)) == $key)
            return true;
    }

    function checkKeyBase($path = false) {

        if (!empty($path))
            $this->path = $path;

        if ($this->path) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
            $data = $PHPShopOrm->select(array('*'), array('path' => "='" . $this->path . "'",), false, array('limit' => 1));
            if (is_array($data)) {
                if ($data['verification'] != md5($data['path'] . $data['date'] . str_replace('www.', '', $_SERVER['SERVER_NAME']) . $data['key']) or $data['date'] < time()) {
                    return $data['date'];
                }
            }
            else
                return true;
        }

        elseif (!isset($_SESSION[$this->getKeyName()])) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
            $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
            if (is_array($data)) {
                foreach ($data as $val) {
                    if ($val['verification'] != md5($val['path'] . $val['date'] . str_replace('www.', '', $_SERVER['SERVER_NAME']) . $val['key']) or $val['date'] < time()) {
                        $_SESSION[$this->getKeyName()][crc32($val['path'])] = time();
                    }
                }
            }
            if (empty($_SESSION[$this->getKeyName()])) {
                $_SESSION[$this->getKeyName()] = array();
            }
        }
    }

    function setKeyBase() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
        $update = array();
        $update['key_new'] = time();
        $update['date_new'] = 1537777023;
        $update['verification_new'] = md5($this->path . $update['date_new'] . str_replace('www.', '', $_SERVER['SERVER_NAME']) . $update['key_new']);
        $PHPShopOrm->update($update, array('path' => "='" . $this->path . "'"));
    }

    /**
     * �������� ��������� ������������ �������
     */
    function doLoad() {
        global $SysValue, $PHPShopSystem, $PHPShopNav;
        if (is_array($this->ModValue['autoload']))
            foreach ($this->ModValue['autoload'] as $k => $v) {
                if (file_exists($v))
                    require_once($v);
                else
                    echo("������ �������� ������ " . $k . "<br>����: " . $v);
            }
    }

    /**
     * �������� ���� �������
     * @param string $path ���� ���������� core ����� ������
     * @return mixed
     */
    function doLoadPath($path) {
        global $SysValue;
        if (!empty($this->ModValue['core'][$path])) {
            if (is_file($this->ModValue['core'][$path])) {
                require_once($this->ModValue['core'][$path]);
                $classname = 'PHPShop' . ucfirst($SysValue['nav']['path']);

                if (class_exists($classname)) {
                    $PHPShopCore = new $classname ();
                    $PHPShopCore->loadActions();
                    return true;
                }
                else
                    echo PHPShopCore::setError($classname, "�� ��������� ����� phpshop/modules/*/core/$classname.core.php");
            }
            else
                PHPShopCore::setError($path, "������ �������� ������ " . $path . "<br>����: " . $this->ModValue['core'][$path]);
        }
        else
            return false;
    }

    /**
     * ������ ���������������� �������� �������
     * @param string ��� ��������� ����� ������.������������ [������.���������.������������]
     * @return array
     */
    function getParam($param) {
        $param = explode(".", $param);
        if (count($param) > 2)
            return $this->ModValue[$param[0]][$param[1]][$param[2]];
        return $this->ModValue[$param[0]][$param[1]];
    }

    /**
     * ������ ���������������� �������� �������
     * @return array
     */
    function getModValue() {
        return $this->ModValue;
    }

    /**
     * ������ � ������� ������ �� ����
     * <code>
     * // example:
     * $PHPShopModules->Parser(array('page'=>'market'),'catalog_page_1');
     * </code>
     * @param array $preg ������ ���������� ��������
     * @param string $TemplateName ��� �������
     * @return string
     */
    function Parser($preg, $TemplateName) {
        $file = newGetFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);

        // ������
        foreach ($preg as $k => $v)
            $file = str_replace($k, $v, $file);

        $dis = newParser($file);
        return @$dis;
    }

    /**
     * ������ XML ������� ������
     * @param string $path ���� �� xml �������� ������
     * @return array
     */
    function getXml($path) {
        PHPShopObj::loadClass("xml");


        $db = xml2array($path, false, true);


        if (count($db) > 1)
            return $db;
        else
            return $db[0];
    }

    /**
     * �������� �� ����������� ��������� ������
     * @param string $serial �������� �����
     * @return bool
     */
    function true_serial($serial) {
        if (preg_match('/^\d{5}-\d{5}-\d{5}$/', $serial)) {
            return true;
        }
    }

    function log($str, $var = false) {
        echo '<br>' . $str . '<br>';
        if ($var)
            print_r($var);
    }

    function setAdmHandler($path, $function_name, $data) {
        global $PHPShopGUI;
        $file = pathinfo($path, PATHINFO_FILENAME); // Moon add

        if (is_array($this->ModValue['admpanel']))
            foreach ($this->ModValue['admpanel'] as $mods) {
                $mod = $mods[$file];
                if ($mod)
                    if (is_file($this->ModDir . $mod)) {

                        include_once($this->ModDir . $mod);

                        if (is_array($addHandler))
                            $this->addHandler[$this->ModDir . $mod] = $addHandler;


                        if ((phpversion() * 1) >= '5.0') {
                            if (!empty($this->addHandler[$this->ModDir . $mod][$function_name]))
                                call_user_func($this->addHandler[$this->ModDir . $mod][$function_name], $data);
                        }
                        else {
                            // ��������� ���� ������� � ������ �������
                            if (is_array($this->addHandler[$this->ModDir . $mod]))
                                foreach ($this->addHandler[$this->ModDir . $mod] as $v => $k)
                                    $handler[strtolower($v)] = $k;

                            if (!empty($handler[$function_name])) {
                                call_user_func($handler[$function_name], $data);
                            }
                        }
                    }
                    else
                        $this->PHPShopOrm->setError('setAdmHandler', "������ ���������� ������ " . $this->ModDir . $mod);
            }
    }

    /**
     * �������� ������� Hook
     * @param string $class_name ��� ������
     * @param string $function_name ��� �������
     * @param mixed $obj ������
     * @param mixed $data ������
     * @param string �������� ���������� ���� [END|START|MIDDLE]
     */
    function setHookHandler($class_name, $function_name, $obj = false, $data = false, $rout = 'END') {

        $addHandler = null;

        // ��������� PHP 5.4
        if (!empty($obj) and is_array($obj))
            $obj = &$obj[0];

        if ((phpversion() * 1) >= '5.0')
            $class_name = strtolower($class_name);

        // �������� ����� ������� �� �����
        if (!empty($this->ModValue['hook'][$class_name]))
            foreach ($this->ModValue['hook'][$class_name] as $hook) {
                if (isset($hook))
                    if (is_file($hook)) {
                        include_once($hook);


                        if ((phpversion() * 1) >= '5.0') {

                            if (is_array($addHandler))
                                foreach ($addHandler as $v => $k)
                                    if (!strstr($v, '#'))
                                        $this->addHandler[$class_name][$v][$hook] = $k;
                        }
                        else {

                            // ��������� ���� ������� � ������ �������
                            if (!empty($addHandler) and is_array($addHandler))
                                foreach ($addHandler as $v => $k)
                                    if (!strstr($v, '#'))
                                        $this->addHandler[$class_name][strtolower($v)][$hook] = $k;
                        }
                    }
            }

        if (!empty($this->addHandler[$class_name][$function_name]) and is_array($this->addHandler[$class_name][$function_name])) {
            $user_func_result = null;
            foreach ($this->addHandler[$class_name][$function_name] as $hook_function_name) {

                // �������� ������
                $time = microtime(true);

                $user_func_result = call_user_func_array($hook_function_name, array(&$obj, &$data, $rout));

                // ��������� ������
                $seconds = round(microtime(true) - $time, 6);

                // ����� ���������� ����
                $this->handlerDone[$class_name][$hook_function_name][$rout] = $seconds;
            }

            // ��������� ���� �����
            if (!empty($user_func_result))
                return $user_func_result;
        }
    }

    /**
     * �������� ������ � ������
     * @return bool
     */
    function memory_check($class_name, $function_name) {
        if ($this->memory) {
            if ($this->memory_get($class_name . '.' . $function_name) != 1)
                return true;
        }
        else
            return true;
    }

    /**
     * ������ � ������
     * @param string $param ��� ��������� [catalog.param]
     * @param mixed $value ��������
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * ������� �� ������
     * @param string $param ��� ��������� [catalog.param]
     * @return
     */
    function memory_get($param) {
        $this->memory_clean();
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
        }
    }

    /**
     * ������ ������ �� �������
     * @param bool $clean_now �������������� ������
     */
    function memory_clean($clean_now = false) {
        if (!empty($clean_now))
            unset($_SESSION['Memory'][__CLASS__]);
        elseif ($_SESSION['Memory'][__CLASS__]['time'] < (time() - 60 * 10))
            unset($_SESSION['Memory'][__CLASS__]);
    }

    /**
     * �������� ��������� ������
     * @param string $path ���������� ������
     */
    function checkInstall($path) {
        $install = $this->ModValue['base'][$path];
        if (empty($install))
            exit('PHPShop Report: ������ "' . ucfirst($path) . '" ��������.');
    }

}

?>