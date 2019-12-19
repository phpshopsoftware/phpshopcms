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
        if (strpos($this->Width, '%') === false)
            $WidthCSS = $this->Width . 'px';
        else
            $WidthCSS = $this->Width;

        if (strpos($this->Height, '%') === false)
            $HeightCSS = $this->Height . 'px';
        else
            $HeightCSS = $this->Height;

        $html ='
        <link  href="./editors/default/redactor.css" rel="stylesheet">
        <script src="./editors/default/redactor.min.js"></script>
        <script src="./editors/default/lang/ru.js"></script>
        <script src="./editors/default/plugins/video/video.js"></script>
        <script src="./editors/default/plugins/table/table.js"></script>
        <script src="./editors/default/plugins/insertimage/insertimage.js"></script>
        <script src="./editors/default/plugins/insertfile/insertfile.js"></script>
        <script src="./editors/default/plugins/fontcolor/fontcolor.js"></script>
        <script src="./editors/default/plugins/fontsize/fontsize.js"></script>
        <script src="./editors/default/plugins/fontfamily/fontfamily.js"></script>
        <script src="./editors/default/plugins/underline/underline.js"></script>';
        
            
        $html.= "
        <script>
        $(function(){
        $('textarea[name=".$this->InstanceName."]').redactor({
        lang: 'ru',
        minHeight: '".$HeightCSS."',
        buttonSource: true,
        paragraphize: false,
        plugins: ['table', 'fontcolor', 'fontfamily', 'fontsize', 'underline', 'video','insertimage','insertfile']
        });
        })
        </script>";
        $html.='<textarea name="' . $this->InstanceName . '" class="hidden-edit form-control" style="width:'.$WidthCSS.';height:'.$HeightCSS.'">' . $this->Value . '</textarea>';

        return $html;
    }

}

?>
