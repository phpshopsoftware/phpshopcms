<?php

function index_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['content_locale'])) $obj->set('mainContent',Parser($row['content_locale']));
        if(!empty($row['name_locale'])) {
            $obj->set('mainContentTitle',$row['name_locale']);
        }
    }
}


$addHandler=array(
        'index'=>'index_hook'
);


?>