<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@pageTitl@</title>
        <meta name="description" content="@pageDesc@">
        <meta name="keywords" content="@pageKeyw@">
        <meta name="copyright" content="@pageReg@">
        <meta content="General" name="rating">
        <meta name="ROBOTS" content="ALL">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="icon" href="/favicon.ico"> 

        <!-- Bootstrap -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body id="body" data-dir="@ShopDir@" data-token="@dadataToken@">

        <!-- Template -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/animate.css" rel="stylesheet">
        <link href="@pageCss@" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/responsive.css" rel="stylesheet">

        <!-- Theme -->
        <link id="bootstrap_theme" data-name="@php echo $_SESSION['skin']; php@" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@astero_theme@.css" rel="stylesheet">

        <!-- Fonts -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <!-- jQuery -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.11.0.min.js"></script>

        <!-- jQuery Plugins -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery.bxslider.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery-ui.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap-select.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bar.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/suggestions.min.css" rel="stylesheet">

        <!-- Header Section Starts -->
        <header id="header-area">
            <!-- Nested Container Starts -->
            <div class="container">
                <!-- Header Top Starts -->
                <div class="header-top">
                    <!-- Row Starts -->
                    <div class="row">
                        <!-- Header Links Starts -->
                        <div class="col-sm-8 col-xs-12">
                            <div class="header-links">
                                <ul class="nav navbar-nav pull-left">
                                    <li>
                                        <a href="/">
                                            <i class="fa fa-home hidden-lg hidden-md" title="Home"></i>
                                            <span class="hidden-sm hidden-xs">
                                                Домой
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/news/" title="Новости">
                                            <i class="fa fa-newspaper-o hidden-lg hidden-md" aria-hidden="true"></i>
                                            <span class="hidden-sm hidden-xs">Новости</span></a>
                                    </li>
                                    <li>
                                        <a href="/forma/" title="Форма">
                                            <i class="fa fa-envelope-o hidden-lg hidden-md" aria-hidden="true"></i>
                                            <span class="hidden-sm hidden-xs">Форма связи</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Header Links Ends -->
                    </div>
                    <!-- Row Ends -->
                </div>
                <!-- Header Top Ends -->
                <!-- Main Header Starts -->
                <div class="main-header header-color">
                    <!-- Row Starts -->
                    <div class="row">                        
                        <!-- Logo Starts -->
                        <div class="col-md-9 col-sm-6 col-xs-12">
                            <div id="logo">
                                <a href="/">
                                    <img src="@logo@" alt="@name@" class="img-responsive" /></a>
                            </div>
                        </div>
                        <!-- Logo Starts -->
                        <!-- Search Starts -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <form id="search_form" action="/search/" role="search" method="post">
                                <div class="input-group">
                                    <input class="form-control input-lg" name="words" maxlength="50" id="search"  placeholder="Искать..." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                    <span class="input-group-btn">
                                        <button class="btn btn-lg" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- Search Ends -->
                    </div>
                    <!-- Row Ends -->
                </div>
                <!-- Main Header Ends -->
            </div>
            <!-- Nested Container Ends 
            
            <!-- Header Area Background Block Starts -->
            <div class="header-area-background-block"></div>
            <!-- Header Area Background Block Ends -->
        </header>
        <!-- Header Section Ends -->

        <!-- Main Menu Starts -->
        <nav id="main-menu" class="navbar" role="navigation">
            <!-- Nested Container Starts -->
            <div class="container">
                <!-- Nav Header Starts -->
                <div class="navbar-header">

                    <a class="navbar-brand visible-xs pull-right" href="tel:@telNumMobile@">
                        @telNumMobile@
                    </a>

                    <button type="button" class="btn btn-navbar navbar-toggle main-menu-button" data-toggle="collapse" data-target=".navbar-cat-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                <!-- Nav Header Ends -->
                <!-- Navbar Cat collapse Starts -->
                <div class="collapse navbar-collapse navbar-cat-collapse">
                    <ul class="nav navbar-nav main-navbar-top">
                        <li class="main-navbar-top-catalog">
                            <a href="javascript:void(0);" id="nav-catalog-dropdown-link" class="nav-catalog-dropdown-link" aria-expanded="false">Каталог
                            </a>
                            <ul class="main-navbar-list-catalog-wrapper fadeIn animated">
                                @mainMenuPage@
                            </ul>
                        </li>
                        @topMenu@
                        </li>
                    </ul>
                </div>
                <!-- Navbar Cat collapse Ends -->
            </div>
            <!-- Nested Container Ends -->
        </nav>
        <!-- Main Menu Ends -->

        <!-- Main Container Starts -->
        <div class="main-container container">
            <!-- Nested Row Starts -->
            <div class="row">
                <!-- Primary Content Starts -->
                <div class="col-md-9 col-xs-12">
                    @DispShop@
                    @getPhotos@
                </div>
                <!-- Primary Content Ends -->
                <!-- Sidebar Starts -->
                <div class="col-md-3 visible-lg visible-md">
                    <!-- Navigation Links Starts -->
                    <h3 class="side-heading">Навигация</h3>
                    <ul class="list-group sidebar-nav">
                        @mainMenuPage@
                        <li>
                            <a href="/news/" title="Новости">Новости</a>
                        </li>
                        <li>
                            <a href="/gbook/" title="Отзывы">Отзывы</a>
                        </li>
                        <li>
                            <a href="/links/" title="Полезные ссылки">Полезные ссылки</a>
                        </li>
                        <li>
                            <a href="/map/" title="Карта сайта">Карта сайта</a>
                        </li>
                        <li>
                            <a href="/forma/" title="Форма связи">Форма связи</a>
                        </li>
                        @mainMenuPhoto@
                    </ul>

                    @rightMenu@

                    <!-- Navigation Links Ends -->
                    @oprosDisp@
                </div>
            </div>
            <!-- Sidebar Ends -->
        </div>
        <!-- Nested Row Ends -->
    </div>
    <!-- Main Container Ends -->

    <!-- toTop -->
    <div class="visible-lg visible-md">
        <a href="#" id="toTop"><span id="toTopHover"></span>Наверх</a>
    </div>
    <!--/ toTop -->

    <!-- Footer Section Starts -->
    <footer class="visible-sm visible-md visible-lg" id="footer-area">
        <!-- Footer Links Starts -->
        <div class="footer-links">
            <!-- Container Starts -->
            <div class="container">
                <!-- Information Links Starts -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5>Информация</h5>
                    <ul>
                        @topMenu@
                    </ul>
                </div>
                <!-- Information Links Ends -->
                <!-- Customer Service Links Starts -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5>Навигация</h5>
                    <ul>
                        <li><a href="/news/" title="Новости">Новости</a></li>
                        <li><a href="/gbook/" title="Отзывы">Отзывы</a></li>
                        <li><a href="/map/" title="Карта сайта">Карта сайта</a></li>
                        <li><a href="/forma/" title="Форма связи">Форма связи</a></li>
                    </ul>
                </div>

                <!-- Customer Service Links Ends -->
                <!-- Contact Us Starts -->
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <h5>Контакты</h5>
                    <ul>
                        <li class="footer-map">@streetAddress@</li>
                        <li class="footer-email">@adminMail@</li>                              
                    </ul>
                    <h4 class="lead">
                        Tel: <span>@telNum@</span>
                    </h4>
                    @button@
                </div>
                <!-- Contact Us Ends -->
            </div>
            <!-- Container Ends -->
        </div>
        <!-- Footer Links Ends -->
        <!-- Copyright Area Starts -->
        <div class="copyright">
            <!-- Container Starts -->
            <div class="container">
                <!-- Starts -->
                <p class="pull-left"  itemscope itemtype="http://schema.org/Organization">&copy; <span itemprop="name">@company@</span> @year@, Тел: <span itemprop="telephone">@telNum@</span>, <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">Адрес: <span itemprop="streetAddress">@streetAddress@</span></span> <span itemprop="email" class="hide">@mail@</span> 
                </p>
                <!-- Ends -->
                <!-- Payment Gateway Links Starts -->
                <ul class="pull-right list-inline">
                    <span class="text-muted visible-lg visible-md small">Работает под управлением <a href="http://www.phpshopcmsfree.ru/" class="sgfooter" target="_blank" title="PHPShop.CMS Free"><span class="glyphicon glyphicon-cog"></span> PHPShop.CMS Free</a></span>
                </ul>
                <!-- Payment Gateway Links Ends -->
            </div>
            <!-- Container Ends -->
        </div>
        <!-- Copyright Area Ends -->
    </footer>
    <!-- Footer Section Ends -->

    @editor@

    <!-- Fixed mobile bar -->
    <div class="bar-padding-fix visible-xs visible-sm"> </div>
    <nav class="navbar navbar-default navbar-fixed-bottom bar bar-tab visible-xs visible-sm" role="navigation" >
        <a class="tab-item active" href="/">
            <span class="icon icon-home"></span>
            <span class="tab-label">Домой</span>
        </a>
        <a class="tab-item" href="#" data-toggle="modal" data-target="#contactModal">
            <span class="icon icon-compose"></span>
            <span class="tab-label">Сообщение</span>
        </a>
        <a class="tab-item" href="#" data-toggle="modal" data-target="#searchModal">
            <span class="icon icon-search"></span>
            <span class="tab-label">Поиск</span>
        </a>

    </nav>
    <!--/ Fixed mobile bar -->

    <!-- Notification -->
    <div id="notification" class="success-notification" style="display: none;">
        <div  class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
            <span class="notification-alert"> </span>
        </div>
    </div>
    <!--/ Notification -->

    <!-- Модальное окно авторизации-->
    <div class="modal fade bs-example-modal-sm" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Авторизация</h4>
                    <span id="usersError" class="hide">@usersError@</span>
                </div>
                <form role="form" method="post" name="user_forma">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="login" class="form-control" placeholder="Email..." required="">
                            <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                        </div>

                        <div class="form-group">
                            <label>Пароль</label>
                            <input type="password" name="password" class="form-control" placeholder="Пароль..." required="">
                            <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="safe_users" @UserChecked@> Запомнить
                            </label>
                        </div>

                        @facebookAuth@ @twitterAuth@
                    </div>
                    <div class="modal-footer">
                        <span class="pull-left"><a href="/users/sendpassword.html" class="btn btn-default">Забыли?</a>
                        </span>
                        <input type="hidden" value="1" name="user_enter">
                        <button type="submit" class="btn btn-primary">Войти</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
    <!--/ Модальное окно авторизации-->

    <!-- Модальное окно мобильного поиска -->
    <div class="modal fade bs-example-modal-sm" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Поиск</h4>
                </div>
                <div class="modal-body">
                    <form  action="/search/" role="search" method="post">
                        <div class="input-group">
                            <input name="words" maxlength="50" class="form-control" placeholder="Искать.." required="" type="search">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                            </span>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!--/ Модальное окно мобильного поиска -->

    <!-- Модальное окно сообщения -->
    <div class="modal fade bs-example-modal-sm" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Сообщение</h4>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" name="forma_message" action="/forma/">
                        <div class="form-group">
                            <label>Заголовок</label>
                            <input type="text" name="subject" value="" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" name="nameP" value="" class="form-control"  required="">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" name="mail" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Сообщение</label>
                            <textarea name="message" class="form-control" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <span class="pull-right">
                                <input type="hidden" name="send_f" value="ok">
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </span>
                            <img src="phpshop/captcha3.php" alt="" border="0" align="left" style="margin-right:10px"> <input type="text" name="key"   class="form-control" id="exampleInputEmail1" placeholder="Код..." style="width:70px" required="">

                        </div>

                    </form>  

                </div>
            </div>
        </div>
    </div>
    <!--/ Модальное окно сообщения -->

    <!-- JQuery Plugins  -->
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/astero.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap-select.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/phpshop.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-ui.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.bxslider.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/jquery.cookie.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.waypoints.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/inview.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.maskedinput.min.js"></script>
    <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.suggestions.min.js"></script>
    <script src="java/highslide/highslide-p.js"></script>
