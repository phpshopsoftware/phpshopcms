

$(document).ready(function() {

    // ������
    $(".form-control").on('input', function() {
        $(this).parent('.input-group').removeClass('has-error');
    });

    // ���������
    if ($('.notification-alert').html() != '') {
        $('#notification').removeClass('hide');
        $('#notification').fadeIn('slow');

        setTimeout(function() {
            $('#notification').delay(500).fadeOut(1000);
        }, 5000);

    }

    // �������������� ������
    $("#remember-me").on('click', function() {
        $('input[name=pas]').removeAttr('required');
        $('input[name=actionID]').detach();
    });
    
    
    $('[data-toggle="tooltip"]').tooltip()

});