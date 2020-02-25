<?php
if (empty($_GET['path']))
    header('Location: ?path=intro');

session_start();
$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(array("base", "system", "admgui", "orm", "date", "xml", "security", "string", "parser", "mail", "lang"));


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();
$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'admin'));
$_SESSION['imageResultPath'] = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
$_SESSION['imageResultDir'] = $PHPShopBase->getParam('dir.dir');

// Редактор GUI
$PHPShopGUI = new PHPShopGUI();
$PHPShopInterface = new PHPShopInterface();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/*
 *  Роутер
 */

// Подраздел [cat.sub]
if (strpos($_GET['path'], '.')) {
    $subpath = explode(".", $_GET['path']);

    // Редирект [cat.id]
    if (is_numeric($subpath[1])) {
        header('Location: ?path=' . $subpath[0] . '&id=' . $subpath[1]);
    }
    else
        $loader_file = $subpath[0] . '/admin_' . $subpath[1] . '.php';
}
else
    $subpath = array($_GET['path'], $_GET['path']);

if (!empty($_GET['path'])) {

    if (empty($_REQUEST['id'])) {

        $loader_file = $subpath[0] . '/admin_' . $subpath[1] . '.php';
    } else {

        $loader_file = $subpath[0] . '/adm_' . $subpath[1] . 'ID.php';
    }
    if ($_REQUEST['action'] == 'new') {
        $loader_file = $subpath[0] . '/adm_' . $subpath[1] . '_new.php';
    }
    $active_path = str_replace(".", "_", $_GET['path']);
    ${'menu_active_' . $active_path} = 'active';
}

$loader_function = 'actionStart';
if (file_exists($loader_file)) {

    if (empty($_REQUEST['id']) and empty($_REQUEST['action']))
        require_once($loader_file);
    else {
        ob_start();
        require_once($loader_file);
        $interface = ob_get_clean();
    }
}

// Поиск лицензии
function getLicense($file) {
    $fstat = explode(".", $file);
    if ($fstat[1] == "lic")
        return $file;
}

// Подключение меню модулей
function modulesMenu() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
    if (!empty($_SESSION['mod_limit']))
        $mod_limit = intval($_SESSION['mod_limit']);
    else
        $mod_limit = 50;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'date desc'), array('limit' => $mod_limit));
    $dis = $db = null;
    if (is_array($data))
        foreach ($data as $row) {
            $path = $row['path'];
            $menu = "../modules/" . $path . "/install/module.xml";
            $db = xml2array($menu, "adminmenu", true);
            if ($db['capability']) {
                $dis.='<li><a href="?path=modules&id=' . $path . '">' . $db['title'] . '</a></li>';
            }
        }

    return $dis;
}

// Автостарт презентации
if (empty($_COOKIE['presentation']) or $_COOKIE['presentation'] == 'true')
    $presentation_checked = 'checked';
else
    $presentation_checked = null;

// Тема оформления
if(empty($_SESSION['admin_theme']))
$theme = PHPShopSecurity::TotalClean($PHPShopSystem->getSerilizeParam('admoption.theme'));
else $theme = $_SESSION['admin_theme'];
if (!file_exists('./css/bootstrap-theme-' . $theme . '.css'))
    $theme = 'default';

$version = null;
$adm_title = $adm_brand = $PHPShopSystem->getSerilizeParam('admoption.adm_title');
foreach (str_split($GLOBALS['SysValue']['upload']['version']) as $w)
    $version.=$w . '.';
$brand = 'PHPShop.CMS ' . substr($version, 0, 3);
if (empty($adm_title)) {
    $adm_title = 'PHPShop.CMS';
    $adm_brand = $brand;
}
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $adm_title . ' - ' . PHPShopSecurity::TotalClean($TitlePage); ?></title>
        <meta name="author" content="PHPShop Software">
        <meta name="description" content="<?php echo $brand; ?>">
        <link rel="apple-touch-icon" href="./apple-touch-icon.png">
        <link rel="icon" href="./favicon.ico"> 

        <!-- Bootstrap -->
        <link id="bootstrap_theme" href="./css/bootstrap-theme-<?php echo $theme; ?>.css" rel="stylesheet">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body role="document" id="body">

        <!-- jQuery plugins -->
        <link href="./css/jquery.dataTables.css" rel="stylesheet">
        <link href="./css/bootstrap-select.min.css" rel="stylesheet">
        <link href="./css/jquery.treegrid.css" rel="stylesheet">
        <link href="./css/admin.css" rel="stylesheet">
        <link href="./css/bar.css" rel="stylesheet">
        <link href="./css/bootstrap-tour.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="js/jquery-1.11.0.min.js"></script>

        <!-- Localization -->
        <script src="js/locale.ru.js"></script>

        <div class="container">

            <nav class="navbar navbar-default" >
                <div>

                    <!-- Brand  -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="../../" title="Перейти на сайт" target="_blank"><span class="glyphicon glyphicon-cog"></span> <?= $adm_brand ?></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div id="navbar1" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown <?= $menu_active_system . $menu_active_system_company . $menu_active_system_seo . $menu_active_system_sync . $menu_active_tpleditor.$menu_active_integration; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Настройки <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=system">Основные</a></li>
                                    <li><a href="?path=system.seo">SEO заголовки</a></li>
                                    <li><a href="?path=system.image">Изображения</a></li>
                                    <li><a href="?path=system.integration">Интеграция</a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=tpleditor"><span class="glyphicon glyphicon-picture"></span> Шаблоны дизайна</a></li>
                                </ul>
                            </li>
                            <li class="dropdown <?= $menu_active_update . $menu_active_update_restore . $menu_active_system_about ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Справка <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=system.about">О программе</a></li>
                                    <li><a href="#" id="presentation-select">Обучение</a></li>
                                    <li><a href="http://idea.phpshop.ru" target="_blank">Предложить идею</a></li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Дополнительно</li>
                                    <li><a href="https://www.phpshop.ru/?from=<?= $_SERVER['SERVER_NAME']?>" target="_blank">Платная версия магазина</a></li>
                                    <li><a href="https://www.phpshop.ru/loads/files/setup.exe" target="_blank">Утилиты EasyControl</a></li>
                                    <li><a href="https://beget.com/p566" target="_blank">Хостинг сайта</a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=update"><span class="glyphicon glyphicon-cloud-download"></span> Мастер обновления</a></li>

                                </ul>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown <?= $menu_active_users . $menu_active_users_jurnal . $menu_active_users_stoplist; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user hidden-xs"></span> <span class="visible-xs">Администратор <span class="caret"></span></span><span class="caret  hidden-xs"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="dropdown-header">Вошел как <?= $_SESSION['logPHPSHOP']; ?></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=users&id=<?= $_SESSION['idPHPSHOP']; ?>">Профиль</a></li>
                                    <li><a href="?path=users">Все администраторы</a></li>
                                    <li><a href="?path=users.jurnal">Журнал авторизации</a></li>
                                    <li class="divider"></li>
                                    <li><a href="./?logout"><span class="glyphicon glyphicon-transfer"></span> Выйти</a></li>
                                </ul>
                            </li>
                            <li><a href="../../" title="Перейти на сайт" class="home go2front hidden-xs" target="_blank"><span class="glyphicon glyphicon-share-alt"></span></a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <nav class="navbar navbar-inverse navbar-statick">
                <div>

                    <div class="navbar-header pull-left">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div id="navbar2" class="collapse navbar-collapse">

                        <ul class="nav navbar-nav">
                            <li><a href="../../" title="Сайт" target="_blank" class="visible-xs">Сайт</a></li>
                            <li><a href="./admin.php" title="Стартовая панель" class="home"><span class="glyphicon glyphicon-home hidden-xs"></span><span class="visible-xs">Домой</span></a></li>

                            <li class="dropdown <?= $menu_active_menu . $menu_active_gbook . $menu_active_page_catalog . $menu_active_page . $menu_active_news . $menu_active_news_rss.$menu_active_photo_catalog ; ?>">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false">Веб-сайт <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=page.catalog">Страницы<span class="dropdown-header">Создание и публикация страниц</span></a></li>
                                    <li><a href="?path=photo.catalog">Фотогалерея<span class="dropdown-header">Фотогалерея изображений на сайте</span></a></li>
                                    <li><a href="?path=menu">Текстовые блоки<span class="dropdown-header">Вывод текстовых блоков в блок-меню</span></a></li>
                                    <li><a href="?path=gbook">Отзывы<span class="dropdown-header">Отзывы пользователей о сайте</span></a></li>
                                    <li><a href="?path=news">Новости<span class="dropdown-header">Новостная лента сайта</span></a></li>
                                    <li><a href="?path=news.rss">RSS каналы<span class="dropdown-header">Импорт новостей из источников</span></a></li>
                                </ul>
                            </li>

                            <li class="dropdown <?= $menu_active_slider . $menu_active_links . $menu_active_banner . $menu_active_opros. $menu_active_metrica; ?>" >
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false">Маркетинг <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=slider"><span>Слайдер</span><span class="dropdown-header">Рекламный слайдер на главной странице</span></a></li>
                                    <li><a href="?path=news.sendmail">Рассылки<span class="dropdown-header">Создание email рассылок пользователям</span></a></li>

                                    <li><a href="?path=links"><span>Ссылки</span><span class="dropdown-header">Обмен ссылками с сайтами</span></a></li>
                                    <li><a href="?path=banner">Баннеры<span class="dropdown-header">Вывод графических изображений</span></a></li>
                                    <li><a href="?path=opros">Опросы<span class="dropdown-header">Опросы для пользователей на сайте</span></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=metrica"><span class="glyphicon glyphicon-equalizer"></span> Статистика посещений</a></li>
                                </ul>   
                            </li>


                            <li class="dropdown <?= $menu_active_modules; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Модули <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu" id="modules-menu">
                                    <?= modulesMenu(); ?>
                                    <li class="divider"></li>
                                    <li><a href="?path=modules"><span class="glyphicon glyphicon-tasks"></span> Управление модулями</a></li>


                                </ul>
                            </li>


                            <li class="dropdown <?= $menu_active_exchange_export . $menu_active_exchange_import . $menu_active_exchange_sql . $menu_active_exchange_backup; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">База <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=exchange.service">Обслуживание</a></li>
                                    <li><a href="?path=exchange.sql">SQL запрос к базе</a></li>
                                    <li><a href="?path=exchange.backup">Резервное копирование</a></li>
                                </ul>
                            </li>

                        </ul>
                        <?php
                        // Быстрый поиск
                        $search_class = 'hidden-xs search-product';
                        $search_placeholder = __('Поиск...');
                        $search_action = '?path=page.catalog';
                        $search_target = '_self';
                        $search_name = 'where[content]';
                        $search_value = null;
                        ?>
                        <form class="navbar-right <?php echo $search_class; ?>"  action="<?php echo $search_action; ?>" target="<?php echo $search_target; ?>">
                            <div class="input-group">
                                <input name="<?php echo $search_name; ?>" maxlength="50" value="<?php echo $search_value; ?>" id="<?php echo $search_id; ?>" class="form-control input-sm" placeholder="<?php echo $search_placeholder; ?>" required="" type="search"  data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                <input type="hidden" name="path" value="page.catalog">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-sm" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                            </div>
                        </form>
                        <?php
                        if (!empty($_SESSION['update_check']))
                            echo '<a class="navbar-btn btn btn-sm btn-info navbar-right hidden-xs" href="?path=update">Update <span class="badge">' . intval($_SESSION['update_check']) . '</span></a>';
                        ?>

                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <div class="clearfix"></div>


            <?php
            if (file_exists($loader_file)) {
                if (empty($_REQUEST['id']) and empty($_REQUEST['action'])) {

                    if ($PHPShopBase->Rule->CheckedRules($subpath[0], 'view')) {
                        if (function_exists($loader_function))
                            call_user_func($loader_function);
                        else
                            echo 'Функция ' . $loader_function . '() не найдена в файле ' . $loader_file;
                    }
                    else
                        $PHPShopBase->Rule->BadUserFormaWindow();
                }
                else
                    echo $interface;
            }
            else
                $PHPShopBase->Rule->BadUserFormaWindow();
            ?>
        </div>
        <!-- Notification -->
        <div id="notification" class="success-notification hide">
            <div  class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <span class="notification-alert"> </span>
            </div>
        </div>
        <!--/ Notification -->

        <!-- Presentation -->
        <div id="presentation" class="hide">
            <div class="panel panel-default">
                <div class="panel-heading "><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary">Урок 1: Создание страницы</b>
                    <a class="btn btn-primary btn-xs pull-right" href="?path=page&return=page.catalog&action=new&video"><span class="glyphicon glyphicon-play"></span> Старт</a></div>
                <div class="panel-body ">
                    Обучающий урок по созданию новой страницы сайта, заполнения полей и сохранения результата.
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary">Урок 2: Создание фотогалереи</b>
                    <a class="btn btn-primary btn-xs pull-right" href="?path=photo&action=new&video"><span class="glyphicon glyphicon-play"></span> Старт</a></div>
                <div class="panel-body ">
                    Обучающий урок по созданию новой фотогалереи, заполнения полей и сохранения результата.
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary">Урок 3: Редактор шаблонов</b>
                    <a class="btn btn-primary btn-xs pull-right" href="?path=tpleditor&name=bootstrap&file=/main/index.tpl&mod=html&video"><span class="glyphicon glyphicon-play"></span> Старт</a></div>
                <div class="panel-body">
                    Обучающий урок по редактированию шаблона дизайна, описание переменных шаблонизатора, управление редактором кода.
                </div>
            </div>

            <div class="checkbox text-muted">
                <label>
                    <input type="checkbox" <?php echo $presentation_checked; ?> id="presentation-check"> Показывать при входе в панель управления
                </label>
            </div>
        </div>
        <?php
        if (isset($_GET['video'])) {
            echo '<script>var video=true;</script>';
        }

        if ($_GET['path'] == 'intro' and $presentation_checked == 'checked')
            echo '<script>var presentation_start=true;</script>';
        ?>

        <!--/ Presentation -->


        <!-- Modal select -->
        <div class="modal" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="selectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="form-horizontal" role="form" data-toggle="validator" id="modal-form" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="selectModalLabel">Заголовок</h4>
                        </div>
                        <div class="modal-body">

                            <?php if (!empty($selectModalBody)) echo $selectModalBody; ?>

                        </div>
                        <div class="modal-footer">

                            <!-- Progress -->
                            <div class="progress hidden">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 5%">
                                    <span class="sr-only">45% Complete</span>
                                </div>
                            </div>   
                            <!--/ Progress -->

                            <button type="button" class="btn btn-default btn-sm pull-left hidden btn-delete"><span class="glyphicon glyphicon-trash"></span> Удалить</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Отменить</button>
                            <button type="submit" class="btn btn-primary btn-sm">Применить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Modal select-->

        <!-- Modal filemanager -->
        <div class="modal bs-example-modal-lg" id="elfinderModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                        <span class="btn btn-default btn-sm pull-left glyphicon glyphicon-fullscreen" id="filemanagerwindow" data-toggle="tooltip" data-placement="bottom" title="Увеличить размер"></span>

                        <h4 class="modal-title">Найти файл</h4>
                    </div>
                    <div class="modal-body">
                        <iframe class="elfinder-modal-content" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" data-path="image" data-option="return=icon_new"></iframe>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Modal filemanager -->

        <!-- Fixed mobile bar -->
        <div class="bar-padding-fix visible-xs visible-sm"> </div>
        <nav class="navbar navbar-statick navbar-fixed-bottom bar bar-tab visible-xs visible-sm" role="navigation">
            <a class="tab-item <?= $menu_active_intro; ?>" href="./admin.php">
                <span class="icon icon-home"></span>
                <span class="tab-label">Домой</span>
            </a>
            <a class="tab-item <?= $menu_active_page_catalog; ?>" href="?path=page.catalog">
                <span class="icon icon-compose"></span>
                <span class="tab-label">Страницы</span>
            </a>
            <a class="tab-item <?= $menu_active_photo_catalog; ?>"  href="?path=photo.catalog">
                <span class="icon icon-pages"></span>
                <span class="tab-label">Фото</span>
            </a>
            <a class="tab-item" href="./?logout">
                <span class="icon icon-share"></span>
                <span class="tab-label">Выход</span>
            </a>
        </nav>
        <!--/ Fixed mobile bar -->

        <!-- jQuery plugins -->
        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/jquery.dataTables.min.js"></script>
        <script src="./js/dataTables.bootstrap.js"></script>
        <script src="./js/phpshop.js"></script>
        <script src="./js/jquery.cookie.js"></script>
        <script src="./js/jquery.form.js"></script>
        <script src="./js/bootstrap-select.min.js"></script>

    </body>
</html>