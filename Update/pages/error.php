<?php
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");

$SysValue['other']['pageTitl']=__("Ошибка 404 ");
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['error_page_forma']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>