<?php

function photocatalog_hook($obj, $row) {
    $obj->set('catalogLink', '../photo/' . $row['seoname'] . '.html');
}

function photocatalogget_hook($obj, $row) {
    $obj->set('photoLink', '../photo/' . $row['seoname'] . '.html');
}

$addHandler = array(
    'mainMenuPhoto' => 'photocatalog_hook',
    'getPhotos' => 'photocatalogget_hook',
);
?>