<?php

function cleversite_do_post_request($url, $data, $optional_headers = null) {
    $d = http_build_query($data);

    $params = array('http' => array('method' => 'POST', 'content' => $d));

    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }

    $ctx = stream_context_create($params);

    $fp = @fopen($url, 'rb', false, $ctx);

    if (!$fp) {
        throw new Exception('Ошибка');
    }
    $response = @stream_get_contents($fp);

    if ($response === false) {
        throw new Exception('Ошибка');
    }

    return json_decode($response);
}

function cleversite_get_adress_nohttp($adress) {
    $isRus = false;
    preg_match('/[а-яА-Я]/', $adress, $match);
    if (isset($match[0])) {
        $isRus = true;
    }
    $adress = preg_replace('%[^A-Za-zА-Яа-я/:0-9-_.@]%u', '', $adress);
    $adress = str_replace('https', '', $adress);
    $adress = str_replace('http', '', $adress);
    $adress = str_replace('://', '', $adress);
    $adress = str_replace('www.', '', $adress);
    if (strpos($adress, '/')) {
        $adress = substr($adress, 0, strpos($adress, '/'));
    }
    return mb_strtolower($adress);
}

function cleversite_footer_hook() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['cleversite']['cleversite_system']);
    $option = $PHPShopOrm->select();

    $url = 'http://cleversite.ru/cleversite/login/';
    $_POST = array(login => $option['client'], password => $option['password'], bitrixauth => 'true', siteUrl => cleversite_get_adress_nohttp($option['site']));
    $result = cleversite_do_post_request($url, $_POST);
    $result_array = (array) $result;

    /* $dis="
      <!-- Сleversite chat button -->
      <script type='text/javascript'>
      (function() {
      var s = document.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.charset = 'utf-8';
      s.src = '//cleversite.ru/cleversite/widget_new.php?supercode=1&referer_main='+encodeURIComponent(document.referrer)+'&clid=".$option['client']."sHGQD&siteNew=".$option['site']."';
      var ss = document.getElementsByTagName('script')[0];
      ss.parentNode.insertBefore(s, ss);
      })();
      </script>
      <!-- / End of Сleversite chat button -->"; */

    $dis = $result_array['code'];
    echo $dis;
}

$addHandler = array
    (
    'footer' => 'cleversite_footer_hook'
);
?>