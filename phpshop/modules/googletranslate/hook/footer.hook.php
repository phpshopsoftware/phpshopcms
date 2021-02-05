<?php

class googletranslate {

    public function __construct() {
        
    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['googletranslate']['googletranslate_system']);
        return $PHPShopOrm->select();
    }

}

function googletranslate_footer_hook() {

    $googletranslate = new googletranslate();
    $options = $googletranslate->option();

    $dot = explode('.', $_SERVER['SERVER_NAME']);
    $c = count($dot);
    $domain = $dot[$c - 2] . '.' . $dot[$c - 1];

    $langs = unserialize($options['lang']);
    if (is_array($langs)) {
        $dis = '<div class="language">';
        foreach ($langs as $lang)
            $dis .= '<img src="/phpshop/modules/googletranslate/lib/images/lang/lang__' . $lang . '.png" alt="' . $lang . '" data-google-lang="' . $lang . '" class="language__img">';

        $dis .= '</div>
    <link rel="stylesheet" href="/phpshop/modules/googletranslate/lib/css/style.css">
    <script>var domain="'.$domain.'";</script>
    <script src="/phpshop/modules/googletranslate/lib/js/google-translate.js"></script>
    <script src="//translate.google.com/translate_a/element.js?cb=TranslateInit"></script>';
        echo $dis;
    }
}

$addHandler = array('footer' => 'googletranslate_footer_hook');
?>