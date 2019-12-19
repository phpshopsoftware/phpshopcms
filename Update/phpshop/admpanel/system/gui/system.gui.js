
$().ready(function() {
    
    var theme_new=false;
    
    // ���������� ���� ����������
    $('#theme_new').on('changed.bs.select', function() {
           theme_new = true;
    });
    
    // ������������ �������� ��� ����� ����
    $("button[name=editID]").on('click', function(event) {
        event.preventDefault();
        if(theme_new === true){
            setTimeout(function() {
                window.location.reload();
            }, 5000);
        }  
    });


    $(".tree a[data-view]").on('click', function(event) {
        event.preventDefault();

        $('html, body').animate({scrollTop: $("a[name=" + $(this).attr('data-view') + "]").offset().top - 100}, 500);
    });

    // ��������� ���������
    $(".pay-support").on('click', function(event) {
        event.preventDefault();
        $('[name=product_upgrade]').submit();
    });
});