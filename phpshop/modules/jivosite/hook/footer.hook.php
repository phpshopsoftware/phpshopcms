<?php

class jivosite {

    public function __construct() {

    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jivosite']['jivosite_system']);
        return $PHPShopOrm->select();
    }
}

function jivosite_footer_hook() {

    $jivosite = new jivosite();
    $options = $jivosite->option();
    $dis = "<script type='text/javascript'>
                (function(){ var widget_id = '" . $options[widget_id] . "';
                    var s = document.createElement('script');
                        s.type = 'text/javascript';
                        s.async = true;
                        s.src = '//code.jivosite.com/script/widget/'+widget_id+'?plugin=phpshop';
                        var ss = document.getElementsByTagName('script')[0];
                        ss.parentNode.insertBefore(s, ss);})();
                    </script>";
    echo $dis;
}

$addHandler = array ('footer' => 'jivosite_footer_hook');
?>