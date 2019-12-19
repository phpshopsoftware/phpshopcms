<?php
function newselements_index_hook($obj,$row) {
    if(isset($_SESSION['mod_locale']) and $_SESSION['mod_locale'] != 'default') {
        if(!empty($row['title_news_locale'])) $obj->set('newsZag',$row['title_news_locale']);
        if(!empty($row['description_news_locale'])) $obj->set('newsKratko',$row['description_news_locale']);

    }
}

$addHandler=array(
        'index'=>'newselements_index_hook'
);
?>
