
$(document).ready(function() {

    if ($('.editor_var').length) {
        $('.style-toggle').hide();
    }

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


    // Template Debug
    $('.setDebug').on('click', function() {

        if ($.cookie('debug_template') != 1)
            $.cookie('debug_template', 1, {
                path: '/'
            });
        else
            $.removeCookie('debug_template', {
                path: '/'
            });

        window.location.reload();

    });

    // Editor в отдельное окно
    $('#editorwindow').on('click', function() {
        var url = $('.admin-modal-content').attr('src');
        filemanager = window.open(url.split('&frame=true').join(''));
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

    // Сolorpicker
    if ($('.color').length) {
        $('.color').colorpicker({format: 'hex'});


        // Сolorpicker Live
        $('.color').colorpicker().on('changeColor', function(e) {
            var el = $(this).find('.color-value').attr('data-option');
            var name = $(this).find('.color-value').attr('name');
            var rule = $(this).find('.color-value').attr('data-rule');
            $(el).css('cssText', name + ':' + e.color.toHex() + rule);
        });

    }

    // сохранение оформления c Сolorpicker
    $(".saveTheme").on('click', function() {

        var data = 'type=json&parser=css&';
        $('.color-value,.image-value').each(function() {
            data += 'color[' + $(this).attr('id').split('color-').join('') + '][' + $(this).attr('name') + ']=' + $(this).val() + '&';
        });

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/skin.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    showAlertMessage(json['status']);
                }
            }
        });
    });

    // смена оформления
    $(".bootstrap-theme").on('click', function() {
        $.cookie($('#bootstrap_theme').attr('data-name') + '_theme', $(this).attr('data-skin'), {
            path: '/'
        });

        $('.color').colorpicker('update');
        $('.color').colorpicker('reposition');

        window.location.replace($(this).attr('data-random'));
    });

    $("#color-slide").slider({
        range: false,
        step: 5,
        min: 0,
        max: 360,
        values: [$("#color-slide").attr('data-option')],
        slide: function(event, ui) {
            $($(".color-filter").attr('data-option')).css('cssText', 'filter: hue-rotate(' + ui.values[0] + 'deg) !important');
            $(".color-filter").val(ui.values[0]);
        }
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


    // Файл-менеджер elfinder
    $('#elfinderModal').on('show.bs.modal', function(event) {
        $('.elfinder-modal-content').attr('data-option', $(event.relatedTarget).attr('data-return'));
        var path = $(event.relatedTarget).attr('data-path');

        if (typeof path == 'undefined')
            path = $('.elfinder-modal-content').attr('data-path');

        var option = $('.elfinder-modal-content').attr('data-option');
        $('.elfinder-modal-content').attr('src', '/phpshop/admpanel/editors/default/elfinder/elfinder.php?path=' + path + '&' + option);
    });

    // Filemanager в отдельное окно
    $('#filemanagerwindow').on('click', function() {
        var w = '1240';
        var h = '550';
        var url = $('.elfinder-modal-content').attr('src');
        filemanager = window.open(url + '&resizable=1', "chat", "dependent=1,left=100,top=100,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
        filemanager.focus();
        $('#elfinderModal').modal('hide');
    });

    // Смена файла изображения
    $('body').on('change', '.image-value', function() {
        $($(this).attr('data-option')).css('cssText', 'background: url(' + $(this).val() + ') no-repeat center; background-size: cover');
    });

    // Ошибка авторизации
    $('[data-toggle="alert"]').click(function(e) {
      e.preventDefault();
      alert('Для управления текущей страницей требуется авторизоваться');
    });

});