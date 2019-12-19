
$().ready(function() {

    // Модальное окно таблицы описаний переменных
    $('#selectModal').on('show.bs.modal', function(event) {
        $('#selectModal .modal-title').html(locale.templater_table_title + $('[data-target="#selectModal"]').attr('data-title'));
        $('#selectModal .modal-footer .btn-primary').addClass('hidden');
        $('#selectModal .modal-footer [data-dismiss="modal"]').html(locale.close);
        $('#selectModal .modal-body').css('max-height', ($(window).height() - 200) + 'px');
        $('#selectModal .modal-body').css('overflow-y', 'auto');
    });

    // Загрузка шаблона
    $('.skin-load').on('click', function() {
        var data = [];
        var id = $(this);
        var path = $(this).attr('data-path');
        var parent = $(this).closest('.panel');
        id.tooltip('toggle');
        parent.find('.panel-heading').append(' - Load...');
        id.addClass('glyphicon glyphicon-save');
        data.push({name: 'template_load', value: path});
        data.push({name: 'editID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[editID]', value: 'actionLoad.system.edit'});
        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=tpleditor',
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function(json) {
                if (json['success'] == 1) {
                    showAlertMessage(json['result']);
                    parent.addClass('panel-success');
                    id.remove();
                    parent.find('.panel-heading').html(path);
                    $('.tree').append('<tr class="treegrid-all"><td><span class="treegrid-expander"></span><a href="?path=tpleditor&name=' + path + '">' + path + '</a></td></tr>');
                } else {
                    showAlertMessage(json['result'], true);
                    parent.addClass('panel-warning');
                    if (confirm(locale.confirm_load_template)) {
                        window.open('http://' + json['zip']);
                    }
                }
            }
        });
    });

    // закрепление навигации
    if ($('#fix-check:visible').length && typeof(WAYPOINT_LOAD) != 'undefined')
        var waypoint = new Waypoint({
            element: document.getElementById('fix-check'),
            handler: function(direction) {
                $('.navbar-action').toggleClass('navbar-fixed-top');
            },
            offset: '10%'
        });

    // Ace
    if ($('#editor_src').length) {
        var editor = ace.edit("editor");
        var mod = $('#editor_src').attr('data-mod');
        var theme = $('#editor_src').attr('data-theme');
        editor.setTheme("ace/theme/" + theme);
        editor.session.setMode("ace/mode/" + mod);
        editor.setValue($('#editor_src').val(), 1);
        editor.getSession().setUseWrapMode(true);
        editor.setShowPrintMargin(false);
        editor.setAutoScrollEditorIntoView(true);
        //$('#editor').height($('.tree').height() - $('#editor').offset().top + 100);
        $('#editor').height($(window).height());
        editor.resize();
    }

    // Вставить @VAR@ в Ace
    $(".editor_var").on('click', function() {
        editor.insert($(this).attr('data-insert'));
        $(this).removeClass('btn-info');
        $(this).addClass('btn-default');
        $(this).find('.glyphicon').addClass('glyphicon-tag');
    });

    // Увеличить Ace
    $(".ace-full").on('click', function() {
        $(this).find('span').toggleClass('glyphicon-fullscreen');
        if ($('#editor').css('position') == 'relative') {
            $('#editor').css('position', 'fixed');
        }
        else {
            $('#editor').css('position', 'relative');
        }
    });

    // Уменьшить Ace [escape key]
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            if ($('#editor').css('position') == 'fixed') {
                $('.glyphicon-resize-small').toggleClass('glyphicon-fullscreen');
                $('#editor').css('position', 'relativee');
            }
        }
    });


    // Сохранить Ace
    $(".ace-save").on('click', function() {
        $('#editor_src').val(editor.getValue());
    });

    // Управление деревом категорий
    $('.title-icon .glyphicon-chevron-down').on('click', function() {
        $('.tree').treegrid('expandAll');
    });

    $('.title-icon .glyphicon-chevron-up').on('click', function() {
        $('.tree').treegrid('collapseAll');
    });

    // Дерево категорий
    $('.tree').treegrid({
        saveState: true,
        expanderExpandedClass: 'glyphicon glyphicon-triangle-bottom',
        expanderCollapsedClass: 'glyphicon glyphicon-triangle-right'
    });

    // Раскрытие категорий
    $(".treegrid-parent").on('click', function(event) {
        event.preventDefault();
        $('.' + $(this).attr('data-parent')).treegrid('toggle');
    });

});