<!DOCTYPE html>
<html lang="@code@">
    <head>
        <meta charset="@charset@">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@title@ - @version@">
        <meta name="author" content="PHPShop Software">
        <link rel="apple-touch-icon" href="./apple-touch-icon.png">
        <link rel="icon" href="./favicon.ico"> 
        <title>@title@</title>

        <!-- Bootstrap -->
        <link id="bootstrap_theme" href="./css/bootstrap-theme-@theme@.css" rel="stylesheet">
        <link href="./css/bootstrap-select.min.css" rel="stylesheet">
        <link  href="./css/signin.css" rel="stylesheet">
        <link  href="./css/bar.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body id="form-signin">

        <!-- jQuery -->
        <script src="js/jquery-1.11.0.min.js"></script>

        <header class="bar bar-nav navbar-action visible-xs">
            <h1 class="title">Авторизация</h1>
        </header>

        <!-- container -->
        <div class="container">
            
            <div class="row">

            <form class="form-signin "  method="post" action="./">

                <h3 class="form-signin-heading hidden-xs">Авторизация<a class="pull-right hidden-xs @hide@" href="../../" title="Вернуться на сайт"><span class="glyphicon glyphicon-home"></span></a> <div class="pull-right">@themeSelect@</div></h3>

                <div class="input-group @error@">
                    <span class="input-group-addon" id="input-group-addon1"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" name="log" class="form-control" value="@user@" placeholder="Пользователь" required @autofocus@ @readonly@>
                </div>
                <div class="input-group @error@">
                    <span class="input-group-addon" id="input-group-addon2"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" name="pas" class="form-control" value="@password@" placeholder="Пароль"  required @readonly@>
                </div>

                <div class="checkbox">
                    <label  class="hidden-xs @hide@">
                        <input type="checkbox" name="actionHash" value="true" id="remember-me" @disabled@> Восстановить пароль
                        
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button>
                <input type="hidden" name="actionID" value="true">
                <input type="hidden" name="actionList[actionHash]" value="actionHash">
                <input type="hidden" name="actionList[actionID]" value="actionEnter">
            </form>
                </div>
        </div> <!-- /container -->
        <!-- Fixed mobile bar -->
        <div class="bar-padding-fix visible-xs"> </div>
        <nav class="navbar navbar-statick navbar-fixed-bottom bar bar-tab visible-xs" role="navigation">
            <a class="tab-item active" href="../../">
                <span class="icon icon-home"></span>
                <span class="tab-label">Домой</span>
            </a>
            <a class="tab-item" href="?path=page.catalog">
                <span class="icon icon-compose"></span>
                <span class="tab-label">Страницы</span>
            </a>
            <a class="tab-item"  href="?path=photo.catalog">
                <span class="icon icon-pages"></span>
                <span class="tab-label">Фото</span>
            </a>
        </nav>
        <!--/ Fixed mobile bar -->

        <!-- Notification -->
        <div id="notification" class="success-notification hide">
            <div  class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <span class="notification-alert">@notification@</span>
            </div>
        </div>
        <!--/ Notification -->

        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/bootstrap-select.min.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/signin.js"></script>
    </body>
</html>