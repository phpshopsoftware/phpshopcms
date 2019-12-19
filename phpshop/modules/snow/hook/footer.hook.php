<?php

function snow_footer_hook() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['snow']['snow_system']);
    $option = $PHPShopOrm->select();
    
   
    switch($option['flag']){

case "1":
        $dis='
       <script language="JavaScript" src="/phpshop/modules/snow/js/jquery.snow.js"></script>            
// Snow           
<script>
var mod_snow_color="'.$option['color'].'";
	(function($){
		$("body").sneg();
	})(jQuery)
</script>';
    break;

case "2":
      $dis='     
<script>
// Snow    
var mod_snow_color="'.$option['color'].'";  
</script>
<script language="JavaScript" src="phpshop/modules/snow/js/snow.js"></script>';  
        
    }
        echo $dis;
    
}


$addHandler=array
        (
        'footer'=>'snow_footer_hook'
);
?>