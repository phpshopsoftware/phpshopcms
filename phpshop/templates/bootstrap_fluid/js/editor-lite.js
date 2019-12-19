
$(document).ready(function() {
    var path = $('#body').attr('data-path');
    var subpath = $('#body').attr('data-subpath');
    var id = $('#body').attr('data-id');

    switch (path) {
        case 'shop':

            if (subpath == 'UID') {
                var pathEdit = 'product';
                var idEdit = id;
            }
            else if (subpath == 'CID') {
                var pathEdit = 'catalog';
                var idEdit = id;
            }
            break;

        case 'page':

            if (subpath == 'CID') {
                var pathEdit = 'page.catalog';
                var idEdit = id;
            }
            else {
                var pathEdit = 'page';
                var idEdit = id;
            }
            break;

        case 'index':
            var pathEdit = 'page';
            var idEdit = id;
            break;


        default:
            if (id > 0) {
                var pathEdit = path;
                var idEdit = id;
            }

    }

    // Скрыть кнопку
    if (pathEdit !== undefined && idEdit !== undefined)
        $('#adminModalHelp').show();

    $(".openAdminModal").on('click', function() {
        $('.admin-modal-content').attr('height', $(window).height() - 150);
        var frame = $('.admin-modal-content').attr('src');

        if (frame === undefined) {
            $('.progress-bar').css('width', '30%');
            $('.admin-modal-content').attr('src', '/phpshop/admpanel/admin.php?path=' + pathEdit + '&id=' + idEdit + '&frame=true');
            $('.progress-bar').css('width', '40%');
        }

        $('#adminModal').modal('toggle');

    });

    // Editor в отдельное окно
    $('#editorwindow').on('click', function() {
        var url = $('.admin-modal-content').attr('src');
        filemanager = window.open(url.split('&frame').join(''));
        filemanager.focus();
        $('#adminModal').modal('hide');
    });


    // Сохранение статуса меню
    $('#collapseCSS,#collapseAdmin').on('hide.bs.collapse', function() {
        $('[data-parent="' + $(this).attr('id') + '"]').toggleClass('glyphicon-menu-down').toggleClass('glyphicon-menu-up');

        $.cookie('style_collapse_' + $(this).attr('id'), 'enabled', {
            path: '/'
        });
    });

    // Сохранение статуса меню
    $('#collapseCSS,#collapseAdmin').on('show.bs.collapse', function() {
        $('[data-parent="' + $(this).attr('id') + '"]').toggleClass('glyphicon-menu-down').toggleClass('glyphicon-menu-up');
        $.removeCookie('style_collapse_' + $(this).attr('id'), {
            path: '/'
        });
    });



    // сохранение оформления
    $(".saveTheme").on('click', function() {

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/skin.php',
            type: 'post',
            data: 'template='+$('#bootstrap_theme').attr('data-name')+'&type=json',
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    showAlertMessage(json['status']);
                }
            }
        });
    });


    // смена оформления
    $(".bootstrap-theme, .non-responsive-switch").on('click', function() {
        var theme = $(this).attr('data-skin');
        var skin = $('#bootstrap_theme').attr('data-name');
        var cookie = $.cookie('bootstrap_theme');

        $(".bootstrap-theme, .non-responsive-switch").each(function() {
            $(this).html('');
        });

        $(this).html('<span class="glyphicon glyphicon-ok"></span>');

        // переход на responsive
        if (theme == 'non-responsive' && cookie == 'non-responsive')
            theme = 'bootstrap';

        $('#body').fadeOut('slow', function() {
            $('#bootstrap_theme').attr('href', ROOT_PATH + '/phpshop/templates/'+skin+'/css/' + theme + '.css');
        });

        setTimeout(function() {
            $('#body').fadeIn();
        }, 1000);

        $.cookie(skin+'_theme', theme, {
            path: '/'
        });

    });


    $('#style-selector .style-toggle').click(function(e) {
        e.preventDefault();
        if ($(this).hasClass('ss-close')) {
            $(this).removeClass('ss-close');
            $(this).addClass('ss-open');
            $('#style-selector').animate({'right': '-' + $('#style-selector').width() + 'px'}, 'slow');

            $.cookie('style_selector_status', 'disabled', {
                path: '/'
            });
        } else {
            $(this).removeClass('ss-open');
            $(this).addClass('ss-close');
            $('#style-selector').animate({'right': '0px'}, 'slow');

            $.cookie('style_selector_status', 'enabled', {
                path: '/'
            });
        }
    });

});

