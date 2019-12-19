<?php
/**
 * Обработчик тестовой страницы
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopTest
 */
class PHPShopCoretest extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

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
<h3>Подключение PHP логики через PHPShop Core</h3>
<p>
Исходник этого файла расположен по адресу: phpshop/core/coretest.php<br>
Возможно использование логики php.<br>
Для подключения  HTML файлов используйте файлы в папке <a href="/doc/test.html">/doc/test.html</a>
</p>

<h3>Имя вашего сайта: "'.$this->PHPShopSystem->getValue('name').'"</h3>
<p>Разберем модуль CoreTest:</p>

<ol>
<li> Cоздаем файл с заданным именем
<p>
Cоздаем файл с заданным именем в папке phpshop/core/,
содержаший навигационный путь, например, этот файл называется
<b>coretest.class.php</b> и обрабатывается при наборе адреса
http://'.$_SERVER['SERVER_NAME'].'/coretest/
 </p>

<li>Создаем класс заданного формата<br>
<p>
Имя класса должно содержать навигационный путь и совпадать с
именем файла,например, этот класс называется <b>PHPShopCoretest</b>


<pre>
class PHPShopCoretest extends PHPShopCore {
  function __construct() {
        parent::__construct();
  }

function index() {

 // Мета
 $this->title="Подключение PHP логики через API - ".$this->PHPShopSystem->getValue("name");
 $this->description=\'Подключение PHP логики\';
 $this->keywords=\'php\';

 // Определяем переменные
 $this->set(\'pageContent\',\'PHPShop Core работет!\');
 $this->set(\'pageTitle\',\'Подключение PHP логики через API\');

 // Подключаем шаблон
 $this->parseTemplate($this->getValue(\'templates.page_page_list\'));
    }
}
</pre>
   <li>В итоге получаем вывод сообщения "PHPShop Core работет!" в общем дизайне сайта.
</ol>

</p>
';

        // Мета
        $this->title='Подключение PHP логики через API - '.$this->PHPShopSystem->getValue("name");
        $this->description='Подключение PHP логики';
        $this->keywords='php';

        // Определяем переменые
        $this->set('pageContent',$disp);
        $this->set('pageTitle','Подключение PHP логики через API');


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }
}

?>
