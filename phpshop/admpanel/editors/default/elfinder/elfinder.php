<?php
session_start();
if (empty($_SESSION['idPHPSHOP']))
    exit('Неавторизованный запрос');

if (empty($_GET['return']))
    $_GET['return'] = 'icon_new';

if (empty($_GET['resizable']))
    $resizable = 'false';
else
    $resizable = 'true';

//  UTF-8 Default Charset Fix
if (stristr(ini_get("default_charset"), "utf") and function_exists('ini_set')) {
    ini_set("default_charset", "cp1251");
}

// UTF-8 Env Fix
if (ini_get("mbstring.func_overload") > 0 and function_exists('ini_set')) {
    ini_set("mbstring.internal_encoding", null);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="windows-1251">
        <title>Найти файл</title>

        <!-- jQuery and jQuery UI (REQUIRED) -->
        <link rel="stylesheet" type="text/css" media="screen" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/theme.css">

        <!-- elFinder JS (REQUIRED) -->
        <script type="text/javascript" src="js/elfinder.min.js"></script>

        <!-- elFinder translation (OPTIONAL) -->
        <script type="text/javascript" src="js/i18n/elfinder.ru.js"></script>

        <!-- elFinder initialization (REQUIRED) -->
        <script>

            var FileBrowserDialogue = {
                init: function() {
                    // Here goes your code for setting your custom things onLoad.
                },
                mySubmit: function(URL) {
                    // pass selected file path to TinyMCE
                    parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

                    // force the TinyMCE dialog to refresh and fill in the image dimensions
                    var t = parent.tinymce.activeEditor.windowManager.windows[0];
                    t.find('#src').fire('change');

                    // close popup window
                    parent.tinymce.activeEditor.windowManager.close();
                }
            }

            $().ready(function() {

                var elf = $('#elfinder').elfinder({
                    getFileCallback: function(file) {
                        
                        
                         // Tinymce
                        if(parent.tinymce && parent.tinymce.activeEditor.windowManager.getParams()){
                             FileBrowserDialogue.mySubmit(file);
                        }

                        // Window
                        else if (window.opener) {
                            window.opener.window.$('[data-icon="<?php echo $_GET['return']; ?>"]').html(file);
                            window.opener.window.$('input[name="<?php echo $_GET['return']; ?>"],#<?php echo $_GET['return']; ?>').val(file).change();
                            window.opener.window.$('.img-thumbnail[data-thumbnail="<?php echo $_GET['return']; ?>"]').attr('src', file);
                            window.opener.window.$('[data-icon="<?php echo $_GET['return']; ?>"]').prev('.glyphicon').removeClass('hide');
                            self.close();
                        }
                        // Redactor Modal
                        else if (parent.window.RedactorModalOpen > 0) {
                            parent.window.$("#mymodal-textarea").val(file);
                            parent.window.$('#elfinderModal').modal('hide');
                        }
                        // Modal
                        else if (parent.window) {
                            parent.window.$('[data-icon="<?php echo $_GET['return']; ?>"]').html(file);
                            parent.window.$('input[name="<?php echo $_GET['return']; ?>"],#<?php echo $_GET['return']; ?>').val(file).change();
                            parent.window.$('.img-thumbnail[data-thumbnail="<?php echo $_GET['return']; ?>"]').attr('src', file);
                            parent.window.$('[data-icon="<?php echo $_GET['return']; ?>"]').prev('.glyphicon').removeClass('hide');
                            parent.window.$('#elfinderModal').modal('hide');
                        }
                       
                    },
                    resizable: <?php echo $resizable; ?>,
                    height: 500,
                    url: 'php/connector.php?path=<?php echo $_GET['path']; ?>',
                    lang: 'ru',
                    uiOptions: {
                        // toolbar configuration
                        toolbar: [
                            ['back', 'forward'],
                            // ['reload'],
                            // ['home', 'up'],
                            ['mkdir', 'mkfile', 'upload'],
                            ['open', 'download', 'getfile'],
                            ['info'],
                            ['quicklook'],
                            ['copy', 'cut', 'paste'],
                            ['rm'],
                            ['duplicate', 'edit', 'resize'],
                            ['extract', 'archive'],
                            ['search'],
                            ['view']
                        ],
                        // directories tree options
                        tree: {
                            // expand current root on init
                            openRootOnLoad: true,
                            // auto load current dir parents
                            syncTree: true
                        },
                        // navbar options
                        navbar: {
                            minWidth: 150,
                            maxWidth: 500
                        },
                        // current working directory options
                        cwd: {
                            // display parent directory in listing as ".."
                            oldSchool: false
                        }
                    },
                    contextmenu: {
                        // navbarfolder menu
                        navbar: ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
                        // current directory menu
                        cwd: ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'info'],
                        // current directory file menu
                        files: [
                            'getfile', '|', 'open', 'quicklook', '|', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|',
                            'rm', '|', 'edit', 'resize', '|', 'archive', 'extract', '|', 'info'
                        ]
                    },
                    onlyMimes: ["image/png", "application/x-shockwave-flash", "application/zip", "text/x-comma-separated-values", "image/jpeg", "image/gif", "application/rar", 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/x-sql', 'application/x-gzip', 'text/x-tpl', 'application/pdf', 'application/x-rar','video/mp4','application/mp4','image/svg+xml']
                }).elfinder('instance');


            });
        </script>
    </head>
    <body>

        <!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>
    </body>
</html>
