<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

// Ipboard
class PHPShopForumElement extends PHPShopElements {

    var $scrolling = "no";
    var $frameborder = 0;

    function __construct () {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['forumlastpost']['ipboard_system'];
        parent::__construct();
        $this->option();
    }

    function option() {
        $this->data = $this->PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['forumlastpost']['enabled'] = $this->data['enabled'];
        $this->LoadItems['modules']['forumlastpost']['flag'] = $this->data['flag'];
        $this->LoadItems['modules']['forumlastpost']['connect'] = $this->data['connect'];
    }

    // Вывод сообщений
    function iframe() {

        $dis = '<IFRAME height="' . $this->data['height'] . '" src="' . $this->data['path'] . '/lastpost.php?n=' . $this->data['num'] . '"
            frameBorder="' . $this->frameborder . '" width="' . $this->data['width'] . '" scrolling="' . $this->scrolling . '"></IFRAME>';

        $this->set('leftMenuName', $this->data['title']);
        $this->set('leftMenuContent', $dis);


        if (empty($this->data['flag']))
            $templates = $this->getValue('templates.right_menu');
        else
            $templates = $this->getValue('templates.left_menu');

        return $this->parseTemplate($templates);
    }

    function socket() {
        $post = 'n=' . $this->data['num'];
        $path = parse_url($this->data['path']);
        $fp = fsockopen($path['host'], 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "Произошла ошибка связи с сервером. Пожалуйста, попробуйте позже!";
            debug('Ошибка связи с ' . $this->data['path'], 'fsockopen');
        } else {

            $out = "POST /lastpost.php  HTTP/1.0\r\n";
            $out .= "Host: " . $path['host'] . "\r\n";
            $out .= "Content-Length: " . strlen($post) . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= $post . "\r\n";

            fwrite($fp, $out);
            $res = null;
            while (!feof($fp)) {
                $res.=fgets($fp, 1280);
            }
            fclose($fp);
        }

        $result = explode('<body>', $res);
        $dis = str_replace('/comment.gif', $this->data['path'] . '/comment.gif', $result[1]);
        $dis = str_replace('/icon-client.gif', $this->data['path'] . '/icon-client.gif', $dis);
        $dis = str_replace('/index.php', $this->data['path'] . '/index.php', $dis);

        $this->set('leftMenuName', $this->data['title']);
        $this->set('leftMenuContent', $dis);

        if (empty($this->data['flag']))
            $templates = $this->getValue('templates.left_menu');
        else
            $templates = $this->getValue('templates.right_menu');

        return $this->parseTemplate($templates);
    }

    function forumlastpost() {
        if ($GLOBALS['LoadItems']['modules']['forumlastpost']['connect'] == 1)
            $post = $this->iframe();
        else
            $post = $this->socket();
        return $post;
    }

}

// Вывод 
$PHPShopForumElement = new PHPShopForumElement();
if ($GLOBALS['LoadItems']['modules']['forumlastpost']['enabled'] == 1) {
    if ($GLOBALS['LoadItems']['modules']['forumlastpost']['flag'] == 1) {
        if ($GLOBALS['LoadItems']['modules']['forumlastpost']['connect'] == 1)
            $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopForumElement->iframe();
        else
            $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopForumElement->socket();
    }
    else {
        if ($GLOBALS['LoadItems']['modules']['forumlastpost']['connect'] == 1)
            $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopForumElement->iframe();
        else
            $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopForumElement->socket();
    }
}else {
    $PHPShopForumElement->init('forumlastpost');
}
?>