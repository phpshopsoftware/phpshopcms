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
                content: 'Инструкция по заполнению основных полей для создания новой страницы сайта',
                placement: 'right'
            },
            {
                element: "[name=category_new]",
                title: "Категория",
                content: 'Выберите категорию размещения новой страницы',
                placement: 'top'
            },
            {
                element: '[name=name_new]',
                title: "Заголовок",
                content: 'Укажите заголовок страницы',
                placement: 'top'
            },
            {
                element: '[name=enabled_new]',
                title: "Опции вывода",
                content: 'Укажите опции вывода страницы',
                placement: 'right'
            },
            {
                element: '[name=link_new]',
                title: "Ссылка",
                content: 'Укажите уникальное имя ссылки страницы на латинском языке',
                placement: 'right'
            },
            {
                element: '[name=num_new]',
                title: "Сортировка",
                content: 'Укажите порядок сортировки товаров в этом каталоге.',
                placement: 'right'
            },
            {
                element: '[name=title_new]',
                title: "Изображение",
                content: 'Укажите уникальный SEO заголовок страницы',
                placement: 'top',
                onNext: function() {
                    $('[data-id="Описание"]').tab('show');
                }

            },
            {
                element: '[data-id="Содержание"]',
                title: "Содержание",
                content: 'Заполните поле содержание страницы, оно будет выводится при переходе по ссылке страницы.',
                placement: 'bottom'
            },
            {
                element: 'button[name="saveID"] > .glyphicon-floppy-saved',
                title: "Сохранение",
                content: 'Сохранение новой страницы  происходит по нажатию на кнопку <kbd>Создать и редактировать</kbd>',
                placement: 'bottom'
            },
            {
                element: '.go2front',
                title: "Результат",
                content: 'Можно сразу посмотреть как выглядит страница на сайте',
                placement: 'left'
            }
        ],
        onEnd: function() {
            $('[data-id="Основное"]').tab('show');
        },
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