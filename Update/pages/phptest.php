<?php

/**
 * Тестовая фукция, выводит имя сайта
 * @package PHPShopTest
 * @return string
 */
function myTest(){
$PHPShopOrm=new PHPShopOrm($GLOBALS['SysValue']['base']['table_name3']);
$row=$PHPShopOrm->select(array('name'));
$disp='
<p><br></p>
<div align="right">
<strong>См. также:</strong><br>
<a href="/doc/license.html">&raquo; Лицензионное соглашение</a><br>
<a href="/doc/design.html">&raquo; Редактирование дизайна</a><br>
<a href="/skin/">&raquo; База бесплатных шаблонов PHPShop</a><br>
<a href="/doc/test.html">&raquo; Подключение HTML файлов</a><br>
<a href="/phptest/">&raquo; Подключение PHP логики</a><br>
<a href="/coretest/">&raquo; Подключение PHP логики через API</a><br>
</div>
<h2>Подключение PHP логики</h2>
<p>
Исходник этого файла расположен по адресу: /pages/phptest.php<br>
Возможно использование логики php.<br>
Для подключения  HTML файлов используйте файлы в папке <a href="/doc/test.html">/doc/test.html</a>
</p>
<p>Существует возможность использования <a href="/doc/design.html#id8">PHP логики в шаблонах</a> через встроенный парсер. Существует возможность использовать  <a href="/coretest/">API PHPShop Core</a>, намного упрощающее написание сложных модулей с использованием внутренних функций и методов.</p>
<p>
<h3>Имя вашего сайта: "'.$row['name'].'"</h3>
<p>Разберем модуль PHPTEST:</p>

<ol>
  <li>Создаем функцию<br>
  <pre>
function myTest(){
   return "Hellow world!";
}

Все переменные возвращаются только return, 
никаких echo и print!!!
</pre>

   <li>Создаем Title<br>
      <pre>
$SysValue["other"]["pageTitl"] ="Подключение PHP логики - ".$PHPShopSystem->getValue("name");
   </pre>
   <li>Подключаем шаблонизатор<br>
   <pre>
$SysValue[\'other\'][\'DispShop\']=myTest();
ParseTemplate($SysValue[\'templates\'][\'shop\']);
   </pre>
   
   <li>В итоге получаем вывод сообщения "Hellow world!" в общем дизайне сайта.
</ol>

</p>
';
return $disp;
}

// Title
$SysValue['other']['pageTitl'] ="Подключение PHP логики - ".$PHPShopSystem->getValue('name');

// Подключаем шаблон
$SysValue['other']['DispShop']=myTest();
ParseTemplate($SysValue['templates']['shop']);
?>