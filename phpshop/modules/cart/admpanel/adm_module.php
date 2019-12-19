<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cart.cart_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

// Копируем базу товаров
    $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
    if ($_FILES['file']['ext'] == "csv") {
        if (move_uploaded_file($_FILES['file']['tmp_name'], "../../UserFiles/Files/" . $_FILES['file']['name']))
            $_POST['filedir_new'] = $_FILES['file']['name'];
    }

// Копируем базу каталога
    $_FILES['catalog']['ext'] = PHPShopSecurity::getExt($_FILES['catalog']['name']);
    if ($_FILES['catalog']['ext'] == "csv") {
        if (move_uploaded_file($_FILES['catalog']['tmp_name'], "../../UserFiles/Files/" . $_FILES['catalog']['name']))
            $_POST['catdir_new'] = $_FILES['catalog']['name'];
    }

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['enabled_market_new']))
        $_POST['enabled_market_new'] = 0;
    if (empty($_POST['enabled_search_new']))
        $_POST['enabled_search_new'] = 0;
    if (empty($_POST['enabled_speed_new']))
        $_POST['enabled_speed_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;


    // Выборка
    $data = $PHPShopOrm->select();


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("База файлов:", $PHPShopGUI->setInput("file", "file", "") . $PHPShopGUI->setHelp('<a target="_blank" href="../../phpshop/modules/cart/install/price.csv">Пример файла</a> для заполнения. Текущее значение: <a href="/UserFiles/Files/'. $data['filedir'].'" target="_blank">/UserFiles/Price/'. $data['filedir'].'</a>'));
    $Tab1.=$PHPShopGUI->setField("База каталога:", $PHPShopGUI->setInput("file", "catalog", "") . $PHPShopGUI->setHelp('<a target="_blank" href="../../phpshop/modules/cart/install/catalog.csv">Пример файла</a> для заполнения. Текущее значение: <a href="/UserFiles/Files/'. $data['catdir'].'" target="_blank">/UserFiles/File/'. $data['catdir'].'</a>'));
    $Tab1.=$PHPShopGUI->setField("E-mail:", $PHPShopGUI->setInputText("", "email_new", $data['email'], 200));
    $Tab1.=$PHPShopGUI->setField("Валюта:", $PHPShopGUI->setInputText("", "valuta_new", $data['valuta'], 100));


    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('message_new', true);
    $oFCKeditor->Height = '200';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['message'];

    $Tab1.=$PHPShopGUI->setField("Сообщение:", $oFCKeditor->AddGUI());



    $Tab1.= $PHPShopGUI->setField("Опции вывода:", $PHPShopGUI->setCheckbox("enabled_speed_new", 1, "Обработка базы товаров только в прайсе (увеличение скорости работы остальных страниц) ", $data['enabled_speed']) .
            $PHPShopGUI->setLine() .
            //$PHPShopGUI->setCheckbox("enabled_market_new", 1, "Вывод страниц в столбик с кнопкой заказа", $data['enabled_market']) .
            //$PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox("enabled_new", 1, "Вывод корзины на сайте", $data['enabled']) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox("enabled_search_new", 1, "Вывод товаров только при поиске", $data['enabled_search']));
    $Tab1.= $PHPShopGUI->setField("Пагинация прайса:", $PHPShopGUI->setInputText("", "num_new", $data['num'], 100));

    // Содержание закладки 2
    $Info = 'Для работы модуля требуется создать текстовый файл (*.csv) с ячейками информации, содержащие данные по файлам.

Пример содержания файла базы товаров:
<pre>
ID;Артикул;Наименование;Цена;Категория
page1;prod1;Елка;100;1
page2;prod2;Дед мороз;1000;1
page3;prod3;Снегурочка;1500;2
</pre>

<p>
где:<br>
ID  - ИД товара или ссылка на страницу<br>
Категория - ID категории из файла каталога товаров
</p>

<p>
Пример содержания файла каталога товаров:</p>
<pre>
ID;Наименование
1;Игрушки
2;Напитки
</pre>

<p>
<h4>Шаблоны</h4>
<p>
Для вывода формы корзины на сайте разместите переменную <kbd>@miniCart@</kbd>в шаблонах <mark>/main/index.tpl</mark> и <mark>/main/shop.tpl</mark> в нужном вам месте.
Переменная @miniCart@ автоматически дописывается в начало левого текстового блока. Если вам нужно разместить ее в другом месте, то снимите галочку
"Вывод корзины на сайте" и впишите переменную @miniCart@ в ручном режиме.</p>
<p>
Прайс-лист доступен: http://' . $_SERVER['SERVER_NAME'] . '/price/<br>
Форма заказа:  http://' . $_SERVER['SERVER_NAME'] . '/order/
    </p>
<p>
Для добавления ссылку на прайс-лист в главное меню создайте новую страницу в главном меню с ссылкой ../price/price<br>
Шаблон формы заказа находится в файле phpshop/modules/cart/templates/order_forma.tpl<br>
Для добавления новых полей в форму заказа просто добавьте новые поля в этот файл.<br>
</p>
<p>
При включенной опции "Вывод страниц в столбик" блок генерации списка категорий будет переделан под формат ссылки market и
страницы в каталоге будут отображаться в столбик. При формате файла в виде ссылок в поле артикул, страницы имеющие такую ссылку, будут
выведены с дополнительной информацией по цене и кнопкой добавления в корзину.
</p>
<p>
Для произвольного включения формы добавления в корзину нужно снять галочку "Вывод страниц в столбик" и для нужных страниц в поле описания
добавить запись 
<pre>
@php $Product = new ProductDisp(3); php@</pre>
где 3 - это ID товара в файле базы.
';
    $Tab3 = $PHPShopGUI->setInfo($Info);

    $Tab5 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Описание", $Tab3), array("О Модуле", $Tab5));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>