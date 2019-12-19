<?php

function pagemap_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['content_locale'])) $obj->set('productKey',substr(strip_tags($row['content_locale']),0,300)."...");
        if(!empty($row['name_locale'])) $obj->set('productName',$row['name_locale']);

    }
}



$addHandler=array(
        'pagemap'=>'pagemap_hook'
);

?>