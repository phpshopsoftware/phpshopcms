<?php


class PHPShopPage extends PHPShopCore{


    function index(){
        @header('HTTP/1.1 301 Moved Permanently');
        @header("Location: /".$this->PHPShopNav->getName().'.html');
    }

}

?>
