<?php

class PHPShopMailFile {

    function __construct($to,$from,$zag,$content,$filename,$file) {
        $this->from=$from;
        $this->un = strtoupper(uniqid(time()));
        $this->to=$to;
        $this->filename=$filename;
        $this->file=$file;
        $this->zag=$this->getZag($content);
        $header=$this->getHeader();
        mail($this->to,$this->from,$this->zag,$header);
    }



    function getZag($text) {
        $f = fopen($this->file,"rb");
        $zag= "------------".$this->un."\nContent-Type:".$this->type.";\n";
        $zag.= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
        $zag.= "------------".$this->un."\n";
        $zag.= "Content-Type: application/octet-stream;";
        $zag.= "name=\"".$this->filename."\"\n";
        $zag.= "Content-Transfer-Encoding:base64\n";
        $zag.= "Content-Disposition:attachment;";
        $zag.= "filename=\"".$this->filename."\"\n\n";
        $zag.= chunk_split(base64_encode(fread($f,filesize($this->file))))."\n";
        return $zag;
    }


    function getHeader() {
        $head= "From: $this->from\n";
        $head.= "To: $this->to\n";
        $head.= "X-Mailer: PHPMail Tool\n";
        $head.= "Reply-To: $this->from\n";
        $head.= "Mime-Version: 1.0\n";
        $head.= "Content-Type:multipart/mixed;";
        $head.= "boundary=\"----------".$this->un."\"\n\n";
        return $head;
    }


}
?>