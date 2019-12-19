$().ready(function() {

    // Instance the tour
    var tour = new Tour({
        storage: window.sessionStorage,
        debug: true,
        template: '<div class="popover" role="tooltip"> <div class="arrow"></div> <h3 class="popover-title"></h3> <div class="popover-content"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-default" data-role="prev">&laquo; Назад</button> <button class="btn btn-sm btn-default" data-role="next">Далее &raquo;</button> <button class="btn btn-sm btn-default" data-role="pause-resume" data-pause-text="Pause" data-resume-text="Resume">Пауза</button> </div> <button class="btn btn-sm btn-default" data-role="end">Завершить</button> </div> </div>',
        steps: [
            {
                element: ".navbar-action .navbar-brand",
                title: "Обучение",
                content: 'Редактирование шаблонов и выбор переменных шаблонизатора',
                placement: 'right',
                onNext: function() {
                    document.location.href = '?path=tpleditor&name=bootstrap&file=/main/index.tpl&mod=html';
                }
            },
            {
                element: "#templatename",
                title: "Выбрать шаблон",
                content: 'Выберите шаблон для редактирования',
                placement: 'right'
            },
            {
                element: '#editor',
                title: "Редактирование",
                content: 'Отредактируйте шаблон, используя HTML-теги и переменные шаблонизатора <kbd>@imageSlider@</kbd>.<p></p> Для справки по HTML-Тгам воспользуйтесь <a href="http://www.wisdomweb.ru/HTML5/" target="_blank">Учебником HTML</a>',
                placement: 'top'
            },
            {
                element: '#varlist',
                title: "Переменные",
                content: 'Список доступных переменных для текущего шаблона. Неиспользуемые переменные помечены <button class="btn btn-xs btn-info"><span><span class="glyphicon glyphicon-plus"></span> меткой</button> <p></p>Клик по кнопке с именем переменной вставляет переменную в шаблон в указанное курсором место.',
                placement: 'bottom'
            },
            {
                element: '#vartable',
                title: "Переменные",
                content: 'Полная сводная таблица с описанием всех переменных доступна по ссылке <a href="#" id="vartable" data-toggle="modal" data-target="#selectModal">Описание переменных</a>.',
                placement: 'top'
            },
            {
                element: '.navbar-btn > .glyphicon-cog',
                title: "Режим редактирования",
                content: 'Переключение между упрощенным и расширенным режимами происходит вызовом меню <span class="glyphicon glyphicon-cog"></span>. <p></p>Упрощенный режим отличается подсказкой файла назначения шаблона и возможностью редактирования только самых основных файлов. В расширенном режиме редактирования присутвуют все файлы со своими именами без подсказок.',
                placement: 'left'
            },
            {
                element: '.ace-full',
                title: "Полный экран",
                content: 'Редактор шаблона можно развернуть на полный экран кнопкой <kbd>Размер</kbd>. Для выхода их режима полнооконного редактирования используйте клавиатурную клавишу <kbd>Esc</kbd>',
                placement: 'left'
            },
            {
                element: '.ace-save',
                title: "Сохранение",
                content: 'Сохранение результата редактирования происходит по нажатию на кнопку <kbd>Сохранить</kbd>',
                placement: 'bottom'

            }
        ]
    });

    // Initialize the tour
    tour.init();

    // Запуск тура
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