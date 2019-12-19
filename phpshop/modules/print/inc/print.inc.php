<?php

class PHPShopPrintForma {
    var $active_path=array('page','index','market','soft');

    function __construct($name='Печатная форма') {
        $this->PHPShopNav= new PHPShopNav();
        $this->name=$name;

    }

    function forma() {
        if($this->check())
            echo '<a href="/print/'.$this->PHPShopNav->getName("/".$this->PHPShopNav->getPath()."/").'.html" target="_blank">'.$this->name.'</a>';
    }

    function check() {
        if(in_array($this->PHPShopNav->getPath(),$this->active_path)) return true;
    }

}
?>
