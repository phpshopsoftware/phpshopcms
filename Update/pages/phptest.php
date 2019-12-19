<?php

/**
 * �������� ������, ������� ��� �����
 * @package PHPShopTest
 * @return string
 */
function myTest(){
$PHPShopOrm=new PHPShopOrm($GLOBALS['SysValue']['base']['table_name3']);
$row=$PHPShopOrm->select(array('name'));
$disp='
<p><br></p>
<div align="right">
<strong>��. �����:</strong><br>
<a href="/doc/license.html">&raquo; ������������ ����������</a><br>
<a href="/doc/design.html">&raquo; �������������� �������</a><br>
<a href="/skin/">&raquo; ���� ���������� �������� PHPShop</a><br>
<a href="/doc/test.html">&raquo; ����������� HTML ������</a><br>
<a href="/phptest/">&raquo; ����������� PHP ������</a><br>
<a href="/coretest/">&raquo; ����������� PHP ������ ����� API</a><br>
</div>
<h2>����������� PHP ������</h2>
<p>
�������� ����� ����� ���������� �� ������: /pages/phptest.php<br>
�������� ������������� ������ php.<br>
��� �����������  HTML ������ ����������� ����� � ����� <a href="/doc/test.html">/doc/test.html</a>
</p>
<p>���������� ����������� ������������� <a href="/doc/design.html#id8">PHP ������ � ��������</a> ����� ���������� ������. ���������� ����������� ������������  <a href="/coretest/">API PHPShop Core</a>, ������� ���������� ��������� ������� ������� � �������������� ���������� ������� � �������.</p>
<p>
<h3>��� ������ �����: "'.$row['name'].'"</h3>
<p>�������� ������ PHPTEST:</p>

<ol>
  <li>������� �������<br>
  <pre>
function myTest(){
   return "Hellow world!";
}

��� ���������� ������������ ������ return, 
������� echo � print!!!
</pre>

   <li>������� Title<br>
      <pre>
$SysValue["other"]["pageTitl"] ="����������� PHP ������ - ".$PHPShopSystem->getValue("name");
   </pre>
   <li>���������� ������������<br>
   <pre>
$SysValue[\'other\'][\'DispShop\']=myTest();
ParseTemplate($SysValue[\'templates\'][\'shop\']);
   </pre>
   
   <li>� ����� �������� ����� ��������� "Hellow world!" � ����� ������� �����.
</ol>

</p>
';
return $disp;
}

// Title
$SysValue['other']['pageTitl'] ="����������� PHP ������ - ".$PHPShopSystem->getValue('name');

// ���������� ������
$SysValue['other']['DispShop']=myTest();
ParseTemplate($SysValue['templates']['shop']);
?>