<?php

class Editor {
    var $InstanceName ;
    var $Width ;
    var $Height ;
    var $Value ;

    function Editor( $instanceName ) {
        $this->InstanceName	= $instanceName ;
        $this->Width		= '100%' ;
        $this->Height		= '300' ;
        $this->Value		= '' ;
    }

    function AddGUI() {
        return $this->Textarea() ;
    }

    // Отключенный редактор
    function Textarea() {
        if ( strpos( $this->Width, '%' ) === false )
            $WidthCSS = $this->Width . 'px' ;
        else
            $WidthCSS = $this->Width ;

        if ( strpos( $this->Height, '%' ) === false )
            $HeightCSS = $this->Height . 'px' ;
        else
            $HeightCSS = $this->Height ;
        return  "<textarea name=\"{$this->InstanceName}\" rows=\"4\" class=\"form-control\" style=\"width: {$WidthCSS}; height: {$HeightCSS}\">{$this->Value}</textarea>" ;
    }
}

?>
