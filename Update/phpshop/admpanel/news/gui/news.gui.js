(function($) {
    $.fn.datetimepicker.dates['ru'] = {
        days: ["�����������", "�����������", "�������", "�����", "�������", "�������", "�������", "�����������"],
        daysShort: ["���", "���", "���", "���", "���", "���", "���", "���"],
        daysMin: ["��", "��", "��", "��", "��", "��", "��", "��"],
        months: ["������", "�������", "����", "������", "���", "����", "����", "������", "��������", "�������", "������", "�������"],
        monthsShort: ["���", "���", "���", "���", "���", "���", "���", "���", "���", "���", "���", "���"],
        today: "�������",
        suffix: [],
        meridiem: []
    };
}(jQuery));

$().ready(function() {

    // ���������� ���� ����������
    $('#theme_new').on('changed.bs.select', function() {
        theme_new = true;
    });

    // ����������� ���������
    if ($('#fix-check').length && typeof(WAYPOINT_LOAD) != 'undefined')
        var waypoint = new Waypoint({
            element: document.getElementById('fix-check'),
            handler: function(direction) {
                $('.navbar-action').toggleClass('navbar-fixed-top');
            },
            offset: '10%'
        });

    $(".tree a[data-view]").on('click', function(event) {
        event.preventDefault();

        $('html, body').animate({scrollTop: $("a[name=" + $(this).attr('data-view') + "]").offset().top - 100}, 500);
    });

    // datetimepicker
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

});
    