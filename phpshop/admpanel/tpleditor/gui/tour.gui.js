$().ready(function() {

    // Instance the tour
    var tour = new Tour({
        storage: window.sessionStorage,
        debug: true,
        template: '<div class="popover" role="tooltip"> <div class="arrow"></div> <h3 class="popover-title"></h3> <div class="popover-content"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-default" data-role="prev">&laquo; �����</button> <button class="btn btn-sm btn-default" data-role="next">����� &raquo;</button> <button class="btn btn-sm btn-default" data-role="pause-resume" data-pause-text="Pause" data-resume-text="Resume">�����</button> </div> <button class="btn btn-sm btn-default" data-role="end">���������</button> </div> </div>',
        steps: [
            {
                element: ".navbar-action .navbar-brand",
                title: "��������",
                content: '�������������� �������� � ����� ���������� �������������',
                placement: 'right',
                onNext: function() {
                    document.location.href = '?path=tpleditor&name=bootstrap&file=/main/index.tpl&mod=html';
                }
            },
            {
                element: "#templatename",
                title: "������� ������",
                content: '�������� ������ ��� ��������������',
                placement: 'right'
            },
            {
                element: '#editor',
                title: "��������������",
                content: '�������������� ������, ��������� HTML-���� � ���������� ������������� <kbd>@imageSlider@</kbd>.<p></p> ��� ������� �� HTML-���� �������������� <a href="http://www.wisdomweb.ru/HTML5/" target="_blank">��������� HTML</a>',
                placement: 'top'
            },
            {
                element: '#varlist',
                title: "����������",
                content: '������ ��������� ���������� ��� �������� �������. �������������� ���������� �������� <button class="btn btn-xs btn-info"><span><span class="glyphicon glyphicon-plus"></span> ������</button> <p></p>���� �� ������ � ������ ���������� ��������� ���������� � ������ � ��������� �������� �����.',
                placement: 'bottom'
            },
            {
                element: '#vartable',
                title: "����������",
                content: '������ ������� ������� � ��������� ���� ���������� �������� �� ������ <a href="#" id="vartable" data-toggle="modal" data-target="#selectModal">�������� ����������</a>.',
                placement: 'top'
            },
            {
                element: '.navbar-btn > .glyphicon-cog',
                title: "����� ��������������",
                content: '������������ ����� ���������� � ����������� �������� ���������� ������� ���� <span class="glyphicon glyphicon-cog"></span>. <p></p>���������� ����� ���������� ���������� ����� ���������� ������� � ������������ �������������� ������ ����� �������� ������. � ����������� ������ �������������� ���������� ��� ����� �� ������ ������� ��� ���������.',
                placement: 'left'
            },
            {
                element: '.ace-full',
                title: "������ �����",
                content: '�������� ������� ����� ���������� �� ������ ����� ������� <kbd>������</kbd>. ��� ������ �� ������ ������������� �������������� ����������� ������������ ������� <kbd>Esc</kbd>',
                placement: 'left'
            },
            {
                element: '.ace-save',
                title: "����������",
                content: '���������� ���������� �������������� ���������� �� ������� �� ������ <kbd>���������</kbd>',
                placement: 'bottom'

            }
        ]
    });

    // Initialize the tour
    tour.init();

    // ������ ����
    $(".presentation").on('click', function(event) {
        event.preventDefault();
        tour.goTo(0);
        tour.restart();
    });

    if (typeof video != 'undefined') {
        tour.goTo(0);
        tour.restart();
    }

});