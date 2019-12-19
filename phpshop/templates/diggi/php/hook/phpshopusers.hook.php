<?php

function template_user_info_hook($obj, $val, $rout) {
    if ($rout == 'START') {
        if ($obj->get('user_error') != '')
            $obj->set('user_error', '<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Внимание!</strong> ' . $obj->get('user_error') . '</div>');
    }
}


$addHandler = array
    (
    'user_info' => 'template_user_info_hook'
);
?>