<?php

class Editor {

    var $InstanceName;
    var $Width;
    var $Height;
    var $Value;

    function Editor($instanceName) {
        $this->InstanceName = $instanceName;
        $this->Width = '100%';
        $this->Height = '300';
        $this->Value = '';
    }

    function AddGUI() {
        return $this->Textarea();
    }

    // Отключенный редактор
    function Textarea() {
        global $PHPShopSystem;

        if (strpos($this->Width, '%') === false)
            $WidthCSS = $this->Width . 'px';
        else
            $WidthCSS = $this->Width;

        if (strpos($this->Height, '%') === false)
            $HeightCSS = $this->Height . 'px';
        else
            $HeightCSS = $this->Height;

        // Тема
        $theme = $PHPShopSystem->getSerilizeParam('admoption.ace_theme');
        if (empty($theme))
            $theme = 'dawn';

        $html = '
        <script src="./tpleditor/gui/ace/ace.js"></script>
        ';


        $html.= "
        <script>
        $().ready(function() {
        
        var editor = ace.edit('editor_".$this->InstanceName ."');
        var mod = $('#editor_src_".$this->InstanceName ."').attr('data-mod');
        var theme = $('#editor_src_".$this->InstanceName ."').attr('data-theme');
        editor.setTheme('ace/theme/' + theme);
        editor.session.setMode('ace/mode/' + mod);
        editor.setValue($('#editor_src_".$this->InstanceName ."').val(), 1);
        editor.getSession().setUseWrapMode(true);
        editor.setShowPrintMargin(false);
        editor.setAutoScrollEditorIntoView(true);
        
        $('[name=\"editID\"], [name=\"saveID\"]').on('click', function() {
          $('#editor_src_".$this->InstanceName ."').val(editor.getValue());
        });
          
        })
        </script>";
        $html.='<textarea class="hide hidden-edit" id="editor_src_'.$this->InstanceName.'" name="' . $this->InstanceName . '" data-mod="rhtml" data-theme="' . $theme . '">' . $this->Value . '</textarea><pre id="editor_'.$this->InstanceName.'" style="width:' . $WidthCSS . '; height: ' . $HeightCSS . '">Загрузка...</pre>';


        return $html;
    }

}

?>