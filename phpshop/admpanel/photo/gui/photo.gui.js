
$().ready(function() {
    
        // Загрузка файлов на сервер
    $("body").on('click', '.btn-upload', function(event) {
        event.preventDefault();
        $("#uploader").contents().find('#send-btn').click();
    });

    // Пакетный загрузчик
    $("body").on('click', "#uploaderModal", function(event) {
        event.preventDefault();
        var id = $('[name="category_new"]').selectpicker('val');
        $('#selectModal .modal-body').html($('#elfinderModal .modal-body').html());
        $('#selectModal .elfinder-modal-content').attr('src', './photo/gui/uploader.gui.php?id=' + id);
        $('#selectModal .elfinder-modal-content').attr('id', 'uploader');
        $('#selectModal .modal-title').html(locale.select_file+'ы');
        $('#selectModal .modal-footer .btn-primary').addClass('btn-upload');
        $('#selectModal .modal-footer .btn-primary').prop("type", "button");
        
        $('#selectModal').modal('show');
    });


    // Управление деревом категорий
    if (typeof(TREEGRID_LOAD) != 'undefined')
    $('.title-icon .glyphicon-chevron-down').on('click', function() {
        $('.tree').treegrid('expandAll');
    });

    if (typeof(TREEGRID_LOAD) != 'undefined')
    $('.title-icon .glyphicon-chevron-up').on('click', function() {
        $('.tree').treegrid('collapseAll');
    });

    // Дерево категорий
    if (typeof(TREEGRID_LOAD) != 'undefined')
    $('.tree').treegrid({
        saveState: true,
        expanderExpandedClass: 'glyphicon glyphicon-triangle-bottom',
        expanderCollapsedClass: 'glyphicon glyphicon-triangle-right'
    });

    $('.data-tree .dropdown-toggle').addClass('btn-xs');

    // Раскрытие категорий
    if (typeof(TREEGRID_LOAD) != 'undefined')
    $(".treegrid-parent").on('click', function(event) {
        event.preventDefault();
        $('.' + $(this).attr('data-parent')).treegrid('toggle');
    });

    // Редактировать категорию в дереве
    $(".tree .edit").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=photo.catalog&id=' + $(this).attr('data-id');

    });

    // Удалить категорию в дереве
    $(".tree .delete").on('click', function(event) {
        event.preventDefault();
        var id = $(this).closest('.data-tree');
        if (confirm(locale.confirm_delete)) {
            $('.list_edit_' + $(this).attr('data-id')).ajaxSubmit({
                success: function() {
                    id.empty();
                    showAlertMessage(locale.save_done);
                }
            });
        }
    });

    // Создать новый из списка
    $("button[name=addNew]").on('click', function() {
        var cat = $(this).attr('data-cat');
        var href = '?path=photo&return=photo.catalog&action=new';
        if (cat > 0)
            href += '&cat=' + cat;
        window.location.href = href;
        action = true;
    });

    // Выделение текущей категории
    if (typeof cat != 'undefined') {
        $('.treegrid-' + cat).addClass('treegrid-active');
    }

    // Переход на страницу из списка
    $("#dropdown_action  .url").on('click', function(event) {
        event.preventDefault();
        var url = $(this).closest('.data-row').find('.page-url > a').html();
        window.open('../../photo/' + url + '.html');
    });


    // Активация из списка dropdown
    $('.data-row, .data-tree').hover(
            function() {
                $(this).find('#dropdown_action').show();
                $(this).find('.editable').removeClass('input-hidden');
                $(this).find('.media-object').addClass('image-shadow');
            },
            function() {
                $(this).find('#dropdown_action').hide();
                $(this).find('.editable').addClass('input-hidden');
                $(this).find('.media-object').removeClass('image-shadow');
            });

});