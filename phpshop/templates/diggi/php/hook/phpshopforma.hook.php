<?php

function template_index_forma_hook($obj,$data,$rout){
        if($obj->get('Error') != '')
            $obj->set('Error','<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Внимание!</strong> '.$obj->get('Error').'</div>');

}

$addHandler = array
    (
    'index'=>'template_index_forma_hook'
);
?>