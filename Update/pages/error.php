<?php
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");

$SysValue['other']['pageTitl']=__("������ 404 ");
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['error_page_forma']);

// ���������� ������ 
ParseTemplate($SysValue['templates']['shop']);
?>