<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sitemap.sitemap_system"));

function sitemaptime($nowtime) {
    return PHPShopDate::dataV($nowtime, false, true);
}

// Создание sitemap
function setGeneration() {
    global $PHPShopModules;

    // Библиотека
    $title = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $title.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";

    // Страницы
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
    $data = $PHPShopOrm->select(array('*'), array('enabled' => "!='0'"), array('order' => 'date DESC'));

    if (is_array($data))
        foreach ($data as $row) {
            $stat_pages.= '<url>' . "\n";

            // Проверка модуля Seourl
            if ($PHPShopModules->getParam("base.seourl.seourl_system") != "")
                $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $row['link'] . '.html</loc>' . "\n";
            else
                $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/' . $row['link'] . '.html</loc>' . "\n";

            $stat_pages.= '<lastmod>' . sitemaptime($row['date']) . '</lastmod>' . "\n";
            $stat_pages.= '<changefreq>daily</changefreq>' . "\n";
            $stat_pages.= '<priority>1.0</priority>' . "\n";
            $stat_pages.= '</url>' . "\n";
        }

    // Каталоги
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'));

    if (is_array($data))
        foreach ($data as $row) {
            $stat_cat.= '<url>' . "\n";

            // Проверка модуля Seourl
            if ($PHPShopModules->getParam("base.seourl.seourl_system") != "")
                $stat_cat.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/cat/' . $row['seoname'] . '.html</loc>' . "\n";
            else
                $stat_cat.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/CID_' . intval($row['id']) . '.html</loc>' . "\n";

            $stat_cat.= '<changefreq>daily</changefreq>' . "\n";
            $stat_cat.= '<priority>1.0</priority>' . "\n";
            $stat_cat.= '</url>' . "\n";
        }

    // Новости
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'date DESC'));

    if (is_array($data))
        foreach ($data as $row) {
            $stat_news.= '<url>' . "\n";

            // Проверка модуля Seourl
            if ($PHPShopModules->getParam("base.seourl.seourl_system") != "")
                $stat_news.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . strip_tags($row['seo_name']) . '.html</loc>' . "\n";
            else
                $stat_news.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . intval($row['id']) . '.html</loc>' . "\n";

            $stat_news.= '<lastmod>' . sitemaptime(PHPShopDate::GetUnixTime($row['date'])) . '</lastmod>' . "\n";
            $stat_news.= '<changefreq>daily</changefreq>' . "\n";
            $stat_news.= '<priority>1.0</priority>' . "\n";
            $stat_news.= '</url>' . "\n";
        }

    $sitemap = $title . $stat_pages . $stat_cat . $stat_news . '</urlset>';

    // Запись в файл
    fwrite(fopen('../../UserFiles/Files/sitemap.xml', "w+"), $sitemap);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    // Создание sitemap
    if (!empty($_POST['generation']))
        setGeneration();

    header('Location: ?path=modules&install=check');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    $Info = "
        <ol>
        <li>Для автоматического создания sitemap.xml установите модуль <kbd>Cron</kbd> и добавьте в него новую задачу с адресом
        исполняемого файла:<br>  <code>phpshop/modules/sitemap/cron/sitemap_generator.php</code> или <code>phpshop/modules/sitemap/cron/sitemap_generator_ssl.php</code> для поддердки HTTPS.
        <li>В поисковиках укажите адрес <code>http://" . $_SERVER['SERVER_NAME'] . "/UserFiles/Files/sitemap.xml</code> для автоматической обработки поисковыми ботами.
        <li>Установите опцию CHMOD 775 на папку /UserFiles/Files/ для записи в нее файла sitemap.xml
        </ol>";


// Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Создание файла", $PHPShopGUI->setCheckbox("generation", 1, "Запустить атоматическую генерацию файла Sitemap.", false));
    $Tab1.=$PHPShopGUI->setField("Настройка", $PHPShopGUI->setInfo($Info));

    $Tab3 = $PHPShopGUI->setPay();

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("О Модуле", $Tab3));

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