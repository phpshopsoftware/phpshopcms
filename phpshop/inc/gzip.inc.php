<?php
ob_start();
ob_implicit_flush(0);
/**
 * Проверка заголовка
 * @package PHPShopInc
 * @return mixed
 */
function CheckCanGzip(){
    if (headers_sent() || connection_aborted()){
        return 0;
    }
   if (strpos($_SERVER["HTTP_ACCEPT_ENCODING"],'x-gzip') !== false) return "x-gzip";
   if (strpos($_SERVER["HTTP_ACCEPT_ENCODING"],'gzip') !== false) return "gzip";
    return 0;
}
/**
 * Компресия
 * @package PHPShopInc
 * @param int $level compression level 0-9
 * @param bool $debug вывод информации по сжатию
 */
function GzDocOut($level,$debug){
    $ENCODING = CheckCanGzip();
    if ($ENCODING){
        //print "<!-- Use compress $ENCODING -->";
        $Contents = ob_get_contents();
        ob_end_clean();
        if ($debug){
            $s = "<center><font style='color:#C0C0C0;font-size:9px;font-family:tahoma'>Not compress length: ".strlen($Contents)."; ";
            $s .= "Compressed length: ".strlen(gzcompress($Contents,$level))."</font></center>";
            $Contents .= $s;
        }
        header("Content-Encoding: $ENCODING");
        print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
        $Size = strlen($Contents);
        $Crc = crc32($Contents);
        $Contents = gzcompress($Contents,$level);
        $Contents = substr($Contents, 0, strlen($Contents) - 4);
        print $Contents;
        print pack('V',$Crc);
        print pack('V',$Size);
        exit;
    }else{
        ob_end_flush();
        exit;
    }
}
?>
