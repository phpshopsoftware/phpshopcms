
$(document).ready(function() {

    // Указать ID товара в виде тега - Поиск
    $("body").on('click', "#selectModal .search-action", function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 2});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search&words=' + escape($('input:text[name=search_name]').val()) + '&cat=' + $('select[name=search_category]').val() + '&price_start=' + $('input:text[name=search_price_start]').val() + '&price_end=' + $('input:text[name=search_price_end]').val(),
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-body').html(data);

            }

        });
    });

    // Указать ID товара в виде тега  -  2 шаг
    $("body").on('click', "#selectModal .modal-footer .id-add-send", function(event) {
        event.preventDefault();

        $('.search-list input:checkbox:checked').each(function() {
            var id = $(this).attr('data-id');
            if ($('#odnotip_new').tagExist(id))
            {
                this.disabled;
            }
            else
                $(selectTarget).addTag(id);
        });

        $('#selectModal').modal('hide');
    });


    // Выбор элемента по клику в модальном окне подбора товара
    $('body').on('click', ".search-list  td", function() {
        $(this).parent('tr').find('input:checkbox[name=items]').each(function() {
            this.checked = !this.checked && !this.disabled;
        });
    });



    // Указать ID товара в виде тега  - 1 шаг
    $(".tag-search").on('click', function(event) {
        event.preventDefault();

        selectTarget = $(this).attr('data-target');

        var data = [];
        data.push({name: 'selectID', value: 2});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'currentID', value: $(selectTarget).val()});
        data.push({name: 'actionList[selectID]', value: 'actionSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                //$('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.add_cart_value);
                $('#selectModal .modal-footer .btn-primary').removeClass('edit-select-send');
                $('#selectModal .modal-footer .btn-primary').addClass('id-add-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').css('max-height', ($(window).height() - 200) + 'px');
                $('#selectModal .modal-body').css('overflow-y', 'auto');
                $('#selectModal .modal-body').html(data);

                $(".search-list td input:checkbox").each(function() {
                    this.checked = true;
                });

                $('#selectModal').modal('show');

            }

        });
    });

    if($('#odnotip_new').length)
    $('#odnotip_new').tagsInput({
        'height': '100px',
        'width': '100%',
        'interactive': true,
        'defaultText': 'Ввод...',
        'removeWithBackspace': true,
        'minChars': 0,
        'maxChars': 0, // if not provided there is no limit
        'placeholderColor': '#666666'
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
        window.location.href = '?path=page.catalog&id=' + $(this).attr('data-id');

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
        var href = '?path=page&return=page.catalog&action=new';
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
        window.open('../../page/' + url + '.html');
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