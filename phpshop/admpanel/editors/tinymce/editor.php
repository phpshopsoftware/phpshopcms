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

        $html ='
        <script src="./editors/tinymce/tinymce.min.js"></script>
        <script src="./tpleditor/gui/ace/ace.js"></script>
        ';
        
            
        $html.= "
        <script>
        var ace_theme = '".$PHPShopSystem->getSerilizeParam('admoption.ace_theme')."';
        $(function(){
        tinymce.init({ 
        selector:'textarea[name=".$this->InstanceName."]',
        menubar: false,
        init_instance_callback: function (editor) {
        editor.on('Change', function (e) {
        $('textarea[name=".$this->InstanceName."]').html(tinymce.activeEditor.getContent());
        });
        },
        theme: 'modern',
        file_browser_callback : elFinderBrowser,
        relative_urls : false,
        remove_script_host : true,
        browser_spellcheck: true,
        language: 'ru',
        content_css : ['/phpshop/templates/".$PHPShopSystem->getParam('skin')."/style.css','css/bootstrap-theme-default.css'], 
        content_style: 'div,p {padding: 5px;}',
        insert_toolbar: 'quickimage quicktable',
        selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        toolbar: [
        'undo redo | styleselect | bold italic underline strikethrough forecolor backcolor removeformat formats | link image media | alignleft aligncenter alignright textcolor table | fullscreen ace'
         ],
        plugins: [
        'advlist autolink lists link image charmap print preview anchor table imagetools textpattern media searchreplace visualblocks code fullscreen insertdatetime media table contextmenu paste textcolor textpattern lists colorpicker ace'
        ],
        });
        })
        
  function elFinderBrowser (field_name, url, type, win) {
  var pathelFinderBrowser = 'image';

  tinymce.activeEditor.windowManager.open({
    
    file: './editors/default/elfinder/elfinder.php?path='+type,// use an absolute path!
    title: 'Найти файл',
    width: 900,  
    height: 520,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
    
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
}
        </script>";
        $html.='<textarea name="' . $this->InstanceName . '" class="hidden-edit form-control" style="width:'.$WidthCSS.';height:'.$HeightCSS.'">' . $this->Value . '</textarea>';

        return $html;
    }

}

?>
