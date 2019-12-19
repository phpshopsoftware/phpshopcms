

$(document).ready(function() {

    // Ошибка
    $(".form-control").on('input', function() {
        $(this).parent('.input-group').removeClass('has-error');
    });

    // Сообщение
    if ($('.notification-alert').html() != '') {
        $('#notification').removeClass('hide');
        $('#notification').fadeIn('slow');

        setTimeout(function() {
            $('#notification').delay(500).fadeOut(1000);
        }, 5000);

    }

    // Восстановление пароля
    $("#remember-me").on('click', function() {
        $('input[name=pas]').removeAttr('required');
        $('input[name=actionID]').detach();
    });
    
    
    $('[data-toggle="tooltip"]').tooltip()

});