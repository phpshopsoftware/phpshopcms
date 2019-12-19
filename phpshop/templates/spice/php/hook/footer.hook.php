<?php

function footer_copy_hook() {
    $sign = 1479292547;
    if (!empty($GLOBALS['RegTo']['SupportExpires']) and $GLOBALS['RegTo']['SupportExpires'] < $sign){
        echo ('<div class="container"><div class="alert alert-danger alert-dismissible text-center" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Внимание!</strong> Для использования этого шаблона требуется продлить <a href="http://phpshop.ru/order/?from=' . $_SERVER['SERVER_NAME'] . '&action=pay_new_template" target="_blank" class="alert-link">техническую поддержку</a>.</div></div></div>');
    }
}

$addHandler = array
    (
    'footer' => 'footer_copy_hook'
);
?>