// ссылка на экран
function ajax_link() {
    $.ajax({
        type: 'POST',
        dataType: "json",
        data: {msg: msg, send: "2", phone: phone},
        success: function (result) {
            var rest = result['res'];
            document.getElementById("send_res2").innerHTML = rest;
        },
        error: function () {
        }
    });
}


function ajax_link_email() {

    var oder = $("input[name='f_oder']").val();
    var sum = $("input[name='f_sum']").val();
    var mail = $("input[name='f_email']").val();

    if (oder == '') {
        $('#text_link').text('Заполните поле "Номер заказа"');
        $("input[name='f_oder']").focus();
        return;
    }
    if (sum == '') {
        $('#text_link').text('Заполните поле "Сумма к оплате"');
        $("input[name='f_sum']").focus();
        return;
    }
    if (mail == '') {
        $('#text_link').text('Заполните поле "E-mail клиента"');
        $("input[name='f_email']").focus();
        return;
    }


    $.ajax({
        type: 'POST',
        data: {type: 'email', oder: oder, sum: sum, mail: mail},
        dataType: 'json',
        url: '../../phpshop/modules/nextpay/admpanel/ajax/ajax.php',
        success: function (data) {
            $('#text_link').text(data.text);
        },
        complete: function () {

        }
    });

}

function ajax_link() {

    var oder = $("input[name='f_oder']").val();
    var sum = $("input[name='f_sum']").val();
    var mail = $("input[name='f_email']").val();

    if (oder == '') {
        $('#text_link').text('Заполните поле "Номер заказа"');
        $("input[name='f_oder']").focus();
        return;
    }
    if (sum == '') {
        $('#text_link').text('Заполните поле "Сумма к оплате"');
        $("input[name='f_sum']").focus();
        return;
    }


    $.ajax({
        type: 'POST',
        data: {type: 'link', oder: oder, sum: sum, mail: mail},
        dataType: 'json',
        url: '../../phpshop/modules/nextpay/admpanel/ajax/ajax.php',
        success: function (data) {
            $('#text_link').text(data.text);
        },
        complete: function () {

        }
    });

}