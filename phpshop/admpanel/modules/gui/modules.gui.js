// Переопределение функции
var STATUS_EVENT = true;

$().ready(function() {

    // Загрузка модуля
    $('body').on('click', '.load-module', function() {
        var file = prompt('URL');
        //file = 'http://mod.phpshop.ru/example.zip';
        if (file.length > 0) {

            var data = [];
            data.push({name: 'ajax', value: 1});
            data.push({name: 'url', value: file});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=modules.load&id=load',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['success'] == 1) {
                        showAlertMessage(json['result']);
                    } else
                        showAlertMessage(json['result'], true, true);
                }
            });
        }
    });


    // Есть идеи развития?
    $(".idea").on('click', function(event) {
        event.preventDefault();
        window.open($(this).attr('data-option'));
    });

    // Выключение из настроек модуля
    $(".select-action .off").on('click', function(event) {
        event.preventDefault();

        if (confirm(locale.confirm_off)) {
            var data = [];
            data.push({action: 0, ajax: 1, id: 'button'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=modules.action&id=' + $('#module-name').html(),
                type: 'post',
                data: data,
                dataType: "html",
                async: false,
                success: function() {
                    window.location.href = '?path=modules';
                }
            });
        }

    });

    // Выключение с выбранными модулями
    $(".select-action .module-off-select").on('click', function(event) {
        event.preventDefault();

        if ($('input:checkbox:checked').length) {
            if (confirm(locale.confirm_off)) {
                $('input:checkbox:checked').each(function() {
                    var id = $(this).attr('data-id');
                    var parent = $(this).closest('.data-row');

                    // Снятие флага выделения
                    this.checked = !this.checked && !this.disabled;

                    $('.list_edit_' + id).append('<input type="hidden" name="action" value="0">');
                    $('.list_edit_' + id).ajaxSubmit({
                        success: function(json) {
                            var data = $.parseJSON(json);

                            parent.removeClass('success');
                            parent.find('.install-date').html(data['date']);

                            // Счетчик
                            var count = $('#mod-install-count').html();
                            if (count > 0)
                                count--;
                            $('#mod-install-count').html(count);

                            // Главное меню
                            $('#modules-menu').find('a[href="' + parent.find('.modules-list>a').attr('href') + '"]').parent('li').empty();

                            // Статус меню
                            parent.find('#dropdown_status_' + id).html('Выкл.');

                            // Экшен меню
                            parent.find('.dropdown-menu .off').html('Включить <span class="glyphicon glyphicon-play"></span>');
                            parent.find('.dropdown-menu .off').attr('class', 'on');

                            // Меню настройка удаление
                            parent.find('.dropdown-menu .option').empty();

                            showAlertMessage(locale.module_done);
                        }
                    });
                });
            }
        }
        else
            alert(locale.select_no);
    });

    // Включение с выбранными модулями
    $(".select-action .module-on-select").on('click', function(event) {
        event.preventDefault();

        if ($('input:checkbox:checked').length) {
            if (confirm(locale.confirm_on)) {
                $('input:checkbox:checked').each(function() {
                    var id = $(this).attr('data-id');
                    var parent = $(this).closest('.data-row');

                    // Снятие флага выделения
                    this.checked = !this.checked && !this.disabled;

                    $('.list_edit_' + id).append('<input type="hidden" name="action" value="1">');
                    $('.list_edit_' + id).ajaxSubmit({
                        success: function(json) {
                            var data = $.parseJSON(json);

                            parent.addClass('success');
                            parent.find('.install-date').html(data['date']);

                            // Счетчик
                            var count = $('#mod-install-count').html();
                            count++;
                            $('#mod-install-count').html(count);

                            // Главное меню
                            $('#modules-menu').append('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">' + parent.find('.modules-list>a').html().split(/.\d+/).join('') + '</a></li>');

                            // Статус меню
                            parent.find('#dropdown_status_' + id).html('Вкл.');

                            // Акшион меню
                            parent.find('.dropdown-menu .on').html('Выключить <span class="glyphicon glyphicon-stop"></span>');
                            parent.find('.dropdown-menu .on').attr('class', 'off');

                            // Меню настройка добавление
                            parent.find('.dropdown-menu .divider').before('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">Настройки</a></li>');
                            showAlertMessage(locale.module_done);
                        }
                    });
                });
            }
        }
        else
            alert(locale.select_no);
    });

    // Быстрое изменение статуса модуля
    $("body").on('click', ".data-row .status", function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        var caption = $(this).html();
        var val = $(this).attr('data-val');
        var parent = $(this).closest('.data-row');
        var message;

        // Выделение выбранного элемента
        $(this).closest('ul').find('li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');

        if (val == 0)
            message = locale.confirm_off;
        else
            message = locale.confirm_on;

        if (confirm(message)) {
            $('.status_edit_' + id).append('<input type="hidden" name="action" value="' + $(this).attr('data-val') + '">');
            $('.status_edit_' + id).ajaxSubmit({
                success: function(json) {
                    var data = $.parseJSON(json);

                    $("#dropdown_status_" + id).html(caption);
                    showAlertMessage(locale.module_done);

                    var count = $('#mod-install-count').html();
                    if (val > 0)
                        count++;
                    else
                        count--;

                    $('#mod-install-count').html(count);

                    parent.toggleClass('success');
                    parent.find('.install-date').html(data['date']);

                    // Добавление/удаление меню модулю в основное меню
                    if (val == 1) {
                        $('#modules-menu').append('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">' + parent.find('.modules-list>a').html().split(/.\d+/).join('') + ' <span class="text-muted glyphicon glyphicon-star"></span></a></li>');

                        parent.find('.dropdown-menu .on').html('Выключить <span class="glyphicon glyphicon-stop"></span>');
                        parent.find('.dropdown-menu .on').attr('class', 'off');

                        // Меню настройка добавление
                        parent.find('.dropdown-menu .divider').before('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">Настройки</a></li>');
                    }
                    else {
                        $('#modules-menu').find('a[href="' + parent.find('.modules-list>a').attr('href') + '"]').parent('li').empty();

                        parent.find('.dropdown-menu .off').html('Включить <span class="glyphicon glyphicon-play"></span>');
                        parent.find('.dropdown-menu .off').attr('class', 'on');

                        // Меню настройка удаление
                        parent.find('.dropdown-menu .option').empty();
                    }

                }
            });
        }
    });

    // Управление модулями из списка dropmenu
    $("body").on('click', ".data-row .on", function(event) {
        event.preventDefault();
        var parent = $(this).closest('.data-row');
        var id = $(this);
        if (confirm(locale.confirm_on)) {
            $('.list_edit_' + $(this).attr('data-id')).append('<input type="hidden" name="action" value="1">');
            $('.list_edit_' + $(this).attr('data-id')).ajaxSubmit({
                success: function(json) {

                    var data = $.parseJSON(json);

                    if (data['success'] == 1) {

                        parent.toggleClass('success');

                        id.html('Выключить <span class="glyphicon glyphicon-stop"></span>');
                        id.attr('class', 'off');

                        var count = $('#mod-install-count').html();

                        count++;
                        $('#mod-install-count').html(count);

                        parent.find('.install-date').html(data['date']);

                        // Меню настройка добавление
                        id.closest('ul').find('.divider').before('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">Настройки</a></li>');
                        // Главное меню
                        $('#modules-menu').append('<li><a href="' + parent.find('.modules-list>a').attr('href') + '">' + parent.find('.modules-list>a').html().split(/.\d+/).join('') + ' <span class="text-muted glyphicon glyphicon-star"></span></a></li>');

                        // Статус меню
                        parent.find('#dropdown_status_' + id.attr('data-id')).html('Вкл.');

                        showAlertMessage(locale.module_done);
                    }
                    else
                        showAlertMessage(locale.save_false, true);
                }
            });
        }
    });

    $("body").on('click', ".data-row .off", function(event) {
        event.preventDefault();
        var parent = $(this).closest('.data-row');
        var id = $(this);

        if (confirm(locale.confirm_on)) {
            $('.list_edit_' + $(this).attr('data-id')).append('<input type="hidden" name="action" value="0">');
            $('.list_edit_' + $(this).attr('data-id')).ajaxSubmit({
                success: function(json) {
                    parent.toggleClass('success');

                    id.html('Включить <span class="glyphicon glyphicon-play"></span>');
                    id.attr('class', 'on');

                    var count = $('#mod-install-count').html();
                    var data = $.parseJSON(json);
                    count--;
                    $('#mod-install-count').html(count);

                    parent.find('.install-date').html(data['date']);

                    // Меню настройка удаление
                    id.closest('ul').find('.option').empty();

                    // Главное меню
                    $('#modules-menu').find('a[href="' + parent.find('.modules-list>a').attr('href') + '"]').parent('li').empty();

                    // Статус меню
                    parent.find('#dropdown_status_' + id.attr('data-id')).html('<span class="text-muted">Выкл</span>');

                    showAlertMessage(locale.module_done);
                }
            });
        }
    });

    // Иконки оформления меню
    $(".data-row .off, .select-action .off").append(' <span class="glyphicon glyphicon-stop"></span>');
    $(".data-row .on").append(' <span class="glyphicon glyphicon-play"></span>');

    // Установленные модули
    $('table#data tr').each(function(key, value) {
        if (key > 0) {
            if ($(value).find('.install-date').html() != '') {
                $(value).addClass('success');
            }
        }
    });

    // Ссылка на модуль или инструкцию
    $(".modules-list > a").on('click', function(event) {
        event.preventDefault();
        if ($(this).closest('.data-row').find('.install-date').html() == '')
            window.open($(this).attr('data-wiki'));
        else
            window.location.href = $(this).attr('href');
    });

    // Инструкция из списка
    $(".data-row .manual").on('click', function(event) {
        event.preventDefault();
        window.open($(this).closest('.data-row').find('.modules-list > a').attr('data-wiki'));
    });

    // Настройка из списка
    $(".data-row .option").on('click', function(event) {
        event.preventDefault();
        window.location.href = $(this).closest('.data-row').find('.modules-list > a').attr('href');
    });

    // Выделение текущей категории
    if (typeof modcat != 'undefined')
        $('.treegrid-' + modcat).addClass('treegrid-active');

    // Дерево категорий
    if (typeof(TREEGRID_LOAD) != 'undefined')
        $('.tree').treegrid({
            saveState: true,
            expanderExpandedClass: 'glyphicon glyphicon-triangle-bottom',
            expanderCollapsedClass: 'glyphicon glyphicon-triangle-right'
        });


    


});