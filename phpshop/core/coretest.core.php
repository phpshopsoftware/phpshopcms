<?php
/**
 * ���������� �������� ��������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopTest
 */
class PHPShopCoretest extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * ����� �� ���������
     */
    function index() {

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
<h3>����������� PHP ������ ����� PHPShop Core</h3>
<p>
�������� ����� ����� ���������� �� ������: phpshop/core/coretest.php<br>
�������� ������������� ������ php.<br>
��� �����������  HTML ������ ����������� ����� � ����� <a href="/doc/test.html">/doc/test.html</a>
</p>

<h3>��� ������ �����: "'.$this->PHPShopSystem->getValue('name').'"</h3>
<p>�������� ������ CoreTest:</p>

<ol>
<li> C������ ���� � �������� ������
<p>
C������ ���� � �������� ������ � ����� phpshop/core/,
���������� ������������� ����, ��������, ���� ���� ����������
<b>coretest.class.php</b> � �������������� ��� ������ ������
http://'.$_SERVER['SERVER_NAME'].'/coretest/
 </p>

<li>������� ����� ��������� �������<br>
<p>
��� ������ ������ ��������� ������������� ���� � ��������� �
������ �����,��������, ���� ����� ���������� <b>PHPShopCoretest</b>


<pre>
class PHPShopCoretest extends PHPShopCore {
  function __construct() {
        parent::__construct();
  }

function index() {

 // ����
 $this->title="����������� PHP ������ ����� API - ".$this->PHPShopSystem->getValue("name");
 $this->description=\'����������� PHP ������\';
 $this->keywords=\'php\';

 // ���������� ����������
 $this->set(\'pageContent\',\'PHPShop Core �������!\');
 $this->set(\'pageTitle\',\'����������� PHP ������ ����� API\');

 // ���������� ������
 $this->parseTemplate($this->getValue(\'templates.page_page_list\'));
    }
}
</pre>
   <li>� ����� �������� ����� ��������� "PHPShop Core �������!" � ����� ������� �����.
</ol>

</p>
';

        // ����
        $this->title='����������� PHP ������ ����� API - '.$this->PHPShopSystem->getValue("name");
        $this->description='����������� PHP ������';
        $this->keywords='php';

        // ���������� ���������
        $this->set('pageContent',$disp);
        $this->set('pageTitle','����������� PHP ������ ����� API');


        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }
}

?>
