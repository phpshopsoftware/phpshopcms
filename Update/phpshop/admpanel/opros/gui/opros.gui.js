

$().ready(function() {
    
    // Добавить значение опроса
    $("body").on('change', '.editable-add', function() {
        var parent = $(this).closest('.data-row');
        var name = $(this).val();
        var num = $(this).closest('.data-row').find('input[name=num_value]').val();
        var total = $(this).closest('.data-row').find('input[name=total_value]').val();

        var data = [];
        data.push({name: 'actionList[saveID]', value: 'actionInsert.opros.create'});
        data.push({name: 'saveID', value: 1});
        data.push({name: 'name_value', value: escape(name)});
        data.push({name: 'num_value', value: num});
        data.push({name: 'total_value', value: total});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'category_value', value: $('#footer input[name=rowID]').val()});
        $.ajax({
            mimeType: 'text/html; charset=windows-1251', // ! Need set mimeType only when run from local file
            url: '?path=opros.value&action=new',
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function(json) {
                if (json['success'] != '') {
                    parent.before('<tr class="data-row" data-row="' + json['success'] + '"><td style="text-align:left"><input style="width:100%" class="form-control input-sm" name="num_value" value="' + parseInt(0 + num) + '"></td><td style="text-align:left"><input style="width:100%" data-id="' + json['success'] + '" name="name_value" class="form-control input-sm editable-add"  value="' + name + '"></td><td><input style="width:100%" data-id="' + json['success'] + '" name="total_value" class="form-control input-sm"  value="' + total + '"></td><td style="text-align:center"><div class="dropdown" id="dropdown_action"><a href="#" class="dropdown-toggle btn btn-default btn-sm" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a><ul class="dropdown-menu" role="menu" ><li><a href="#" data-id="' + json['success'] + '" class="remove">Удалить</a></li></ul></div></td><td><span data-original-title="Удалить" class="glyphicon glyphicon-remove remove" data-id="' + json['success'] + '" data-toggle="tooltip" data-placement="top" title="Удалить"></span></td></tr>');
                    showAlertMessage(locale.save_done);

                } else
                    showAlertMessage(locale.save_false, true);
            }
        });
        $(this).val('');
        $(this).closest('.data-row').find('input[name=num_value]').val('');
        $(this).closest('.data-row').find('input[name=total_value]').val('');
    });
    
    // Удалить значение опроса
    $("body").on('click', '.data-row .remove', function(event) {
        event.preventDefault();
        var id = $(this).closest('.data-row');
        if (confirm(locale.confirm_delete)) {
            var data = [];
            data.push({name: 'rowID', value: $(this).attr('data-id')});
            data.push({name: 'deleteID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[deleteID]', value: 'actionDelete.opros.edit'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251', // ! Need set mimeType only when run from local file
                url: '?path=opros.value&id=' + $(this).attr('data-id'),
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['success'] == 1) {
                        showAlertMessage(locale.save_done);
                        id.empty();
                    } else
                        showAlertMessage(locale.save_false, true);
                }
            });
        }

    });
    
    // Изменение данных из списка (имя, сортировка)
    $('.editable').on('change', function() {
        var data = [];
        data.push({name: $(this).attr('data-edit'), value: escape($(this).val())});
        data.push({name: 'rowID', value: $(this).attr('data-id')});
        data.push({name: 'editID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[editID]', value: 'actionUpdate.opros.edit'});
        $.ajax({
            mimeType: 'text/html; charset=windows-1251', // ! Need set mimeType only when run from local file
            url: '?path=opros.value&id=' + $(this).attr('data-id'),
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function(json) {
                if (json['success'] == 1) {
                    showAlertMessage(locale.save_done);
                } else
                    showAlertMessage(locale.save_false, true);
            }
        });
    });


    // Активация из списка dropdown
    $('.data-row').hover(
            function() {
                $(this).find('#dropdown_action').show();
                $(this).find('.editable').removeClass('input-hidden');
                $(this).find('.remove, .add').removeClass('hide');
            },
            function() {
                $(this).find('#dropdown_action').hide();
                $(this).find('.editable').addClass('input-hidden');
                $(this).find('.remove, .add').addClass('hide');
            });


});