<?php

function menu_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['name_menu_locale'])) $obj->set('leftMenuName',$row['name_menu_locale']);
        if(!empty($row['content_menu_locale'])) $obj->set('leftMenuContent',$row['content_menu_locale']);

    }
}

function top_menu_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {

        if(!empty($row['name_locale'])) $obj->set('topMenuName',$row['name_locale']);
    }
}

$addHandler=array(
    'rightMenu'=>'menu_hook',
    'leftMenu'=>'menu_hook',
    'topMenu'=>'top_menu_hook',
);

?>
