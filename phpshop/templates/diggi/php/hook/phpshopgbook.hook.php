<?php

function template_attachLink_hook() {
        return ' ';
}

function template_send_gb_hook($obj,$data,$rout){
    if($rout == 'END'){
        echo 111;
        
        if($obj->get('Error') != '')
            $obj->set('Error','<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Внимание!</strong> '.$obj->get('Error').'</div>');
    }
}

function template_index_gbook_hook($obj,$data,$rout){
    if($rout == 'END'){
        if($obj->get('Error') != '')
            $obj->set('Error','<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Внимание!</strong> '.$obj->get('Error').'</div>');
    }
}

$addHandler = array
    (
    'attachLink' => 'template_attachLink_hook',
    'send_gb'=>'template_send_gb_hook',
    'send'=>'template_send_gb_hook',
    'index'=>'template_index_gbook_hook'
);
?>