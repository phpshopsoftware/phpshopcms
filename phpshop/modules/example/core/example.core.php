<?

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

// Example - проверка работы
class PHPShopExample extends PHPShopCore {

    // Конструктор
    function PHPShopExample() {
        $this->objBase=$GLOBALS['SysValue']['base']['example']['example_system'];
        $this->debug=false;
        parent::PHPShopCore();

        // Информация по серверу
        if($this->PHPShopNav->objNav['query']['info']) exit(phpinfo());
    }


    function index() {

        // Выборка данных
        $row=$this->PHPShopOrm->select();

        // Определяем переменые
        $this->set('pageContent',Parser($row['example']));
        $this->set('pageTitle','Example');

        // Добавляем справочную информацию
        $info='<p>Информация сгенерирована файлом <mark>phpshop/modules/example/core/example.core.php</mark><br>
            Для получения информации по серверу <kbd>phpinfo</kbd> нажмите на <a href="?info=true">ссылку</a>.
            <p>
            <h4>Описание API PHPShopCore</h4>
            <iframe src="http://doc.phpshop.ru/package-PHPShopCore.html" width="100%" height="500" frameborder="0"></iframe>
            <p><a href="http://doc.phpshop.ru/package-PHPShopCore.html" target="_blank">Открыть в отдельном окне</a>';
        $this->set('pageContent',$info,true);

        // Мета
        $this->title="Example - ".$this->PHPShopSystem->getValue("name");

        // Навигация хлебные крошки
        $this->navigation(false,'Example');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

}

?>