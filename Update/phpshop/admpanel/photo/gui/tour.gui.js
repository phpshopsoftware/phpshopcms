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
                content: 'Инструкция по заполнению основных полей для создания новой фотогалереи',
                placement: 'right'
            },
            {
                element: "[name=category_new]",
                title: "Категория",
                content: 'Выберите категорию размещения новой фотогалереи',
                placement: 'top'
            },
            {
                element: '[name=info_new]',
                title: "Описание",
                content: 'Укажите описание фотогалереи',
                placement: 'top'
            },
            {
                element: '[name=enabled_new]',
                title: "Опции вывода",
                content: 'Укажите опции вывода страницы',
                placement: 'right'
            },
            {
                element: '.link-thumbnail',
                title: "Изображение",
                content: 'Укажите изображение для фотогалереи. Поддерживается групповая загрузка нескольких изображений по кнопке <kbd>Пакетно</kbd>',
                placement: 'top'

            },
            {
                element: '[name=num_new]',
                title: "Сортировка",
                content: 'Укажите порядок сортировки фото в этом каталоге.',
                placement: 'right'
            },
            {
                element: 'button[name="saveID"] > .glyphicon-ok',
                title: "Сохранение",
                content: 'Сохранение новой страницы  происходит по нажатию на кнопку <kbd>Сохранить и закрыть</kbd>',
                placement: 'bottom'
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