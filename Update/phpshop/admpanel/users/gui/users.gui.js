

$(document).ready(function() {


    // Добавить с выбранными в черный список
    $("body").on('click', ".select-action .add-blacklist-select", function(event) {
        event.preventDefault();
        var result = 1;
        if ($('#data input:checkbox:checked').length) {
            if (confirm(locale.confirm_ip)) {
                $('#data input:checkbox:checked').each(function() {

                    var data = [];
                    data.push({name: 'saveID', value: 1});
                    data.push({name: 'ip_new', value: $(this).closest('.data-row').find('td:nth-child(3)').html()});
                    data.push({name: 'actionList[saveID]', value: 'actionInsert'});

                    $.ajax({
                        mimeType: 'text/html; charset=windows-1251',
                        url: '?path=users.stoplist&action=new',
                        type: 'post',
                        data: data,
                        dataType: "json",
                        async: false,
                        success: function(json) {
                            if (json['success'] != 1) {
                                result= 0;
                                showAlertMessage(locale.save_false, true);
                            }
                        }
                    });
                });
                
                 if(result == 1)
                 showAlertMessage(locale.save_done);
            }
        }
        else
            alert(locale.select_no);

    });


    // Подсказки
    $("#rules td:nth-child(2)").each(function() {
        $(this).attr('data-toggle', 'tooltip');
        $(this).attr('data-placement', 'top');
        $(this).attr('title', 'Обзор');
    });

    $("#rules td:nth-child(3)").each(function() {
        $(this).attr('data-toggle', 'tooltip');
        $(this).attr('data-placement', 'top');
        $(this).attr('title', 'Редактирование');
    });

    $("#rules td:nth-child(4)").each(function() {
        $(this).attr('data-toggle', 'tooltip');
        $(this).attr('data-placement', 'top');
        $(this).attr('title', 'Создание');
    });

    // whois
    $(".data-row .whois").on('click', function(event) {
        event.preventDefault();
        window.open('https://www.nic.ru/whois/?query=' + $(this).closest('.data-row').find('td:nth-child(3)').html());
    });

    // Добавление в черный список
    $(".data-row .add-blacklist").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=users.stoplist&action=new&data[ip]=' + $(this).closest('.data-row').find('td:nth-child(3)').html();
    });

    // Автозаполнение дополнительных полей
    $('.autofill input:checkbox').attr('checked', 'checked');
    $('.autofill input[name=module_rul_1]').attr('checked', false);

    // Выбор всех элементов через checkbox
    $("#select_rules_view").on('click', function() {
        $(".table td:nth-child(2)>input:checkbox").each(function() {
            if ($("#select_rules_view").prop('checked'))
                this.checked = true;
            else
                this.checked = false;
        });
    });

    $("#select_rules_edit").on('click', function() {
        $(".table td:nth-child(3)>input:checkbox").each(function() {
            if ($("#select_rules_edit").prop('checked'))
                this.checked = true;
            else
                this.checked = false;
        });
    });

    $("#select_rules_creat").on('click', function() {
        $(".table td:nth-child(4)>input:checkbox").each(function() {
            if ($("#select_rules_creat").prop('checked'))
                this.checked = true;
            else
                this.checked = false;
        });
    });

    $("#select_rules_option").on('click', function() {
        $(".table td:nth-child(6) input:checkbox").each(function() {
            if ($("#select_rules_option").prop('checked') && this.name != 'module_rul_1')
                this.checked = true;
            else
                this.checked = false;
        });
    });


    // datetimepicker
    if ($(".date").length) {
        $(".date").datetimepicker({
            format: 'dd-mm-yyyy',
            language: 'ru',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    }
    
   // Поиск - очистка
    $(".btn-order-cancel").on('click', function() {
        window.location.replace('?path=users.jurnal');
    });

    // Поиск 
    $(".btn-order-search").on('click', function() {
        $('#order_search').submit();
    });

});