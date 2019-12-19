// вывод сообщений после доабвление в корзину, сравнение, вишлист и т.д.
function showAlertMessage(message) {
    var messageBox = '.success-notification';
    var innerBox = '#notification .notification-alert';

    //если нет элемента для всплывающих сообщий, выводим обычным alert
    if ($(messageBox).length > 0) {
        $(innerBox).html(' ');
        $(innerBox).html(message);
        $(messageBox).fadeIn('slow');

        setTimeout(function() {
            $(messageBox).delay(500).fadeOut(1000);
        }, 5000);
    }
    else
        alert(message);
}

// Комментарии
function commentList(xid, comand, page, cid) {
    var message = "";
    var rateVal = 0;
    var cid = 0;
    var page = 0;
    if (comand == "add") {
        message = $('#message').val();
        if (message == "")
            return false;
        if ($('input[name=rate][type=radio]:checked').val())
            rateVal = $('input[name=rate][type=radio]:checked').val();
    }

    if (comand == "edit_add") {
        message = $('#message').val();
        cid = $('#commentEditId').val();
        $('#commentButtonAdd').show();
        $('#commentButtonEdit').hide();
    }

    if (comand == "dell") {
        if (confirm("Вы действительно хотите удалить комментарий?")) {
            cid = $('#commentEditId').val();
            $('#commentButtonAdd').show();
            $('commentButtonEdit').hide();
        }
        else
            cid = 0;
    }

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/comment.php',
        type: 'post',
        data: 'xid=' + xid + '&comand=' + comand + '&type=json&page=' + page + '&rateVal=' + rateVal + '&message=' + message + '&cid=' + cid,
        dataType: 'json',
        success: function(json) {
            if (json['success']) {

                if (comand == "edit") {
                    $('#message').val(json['comment']);
                    $('#commentButtonAdd').hide();
                    $('#commentButtonEdit').show();
                    $('#commentButtonEdit').show();
                    $('#commentEditId').val(cid);
                }
                else
                {
                    document.getElementById('message').value = "";
                    if (json['status'] == "error") {
                        mesHtml = "Функция добавления комментария возможна только для авторизованных пользователей.\n<a href='/users/?from=true'>Авторизуйтесь или пройдите регистрацию</a>.";
                        mesSimple = "Функция добавления комментария возможна только для авторизованных пользователей.\nАвторизуйтесь или пройдите регистрацию.";

                        showAlertMessage(mesHtml);

                        if ($('#evalForCommentAuth')) {
                            eval($('#evalForCommentAuth').val());
                        }
                    }
                    $('#commentList').html(json['comment']);
                }
                if (comand == "edit_add") {
                    mes = "Ваш отредактированный комментарий будет доступен другим пользователям только после прохождения модерации...";
                    showAlertMessage(mes);

                }
                if (comand == "add" && json['status'] != "error") {
                    mes = "Комментарий добавлен и будет доступен после прохождения модерации...";
                    showAlertMessage(mes);
                }
            }
        }
    });
}

// добавление товара в корзину
function addToCartList(product_id, num, parent, addname) {

    if (num === undefined)
        num = 1;

    if (addname === undefined)
        addname = '';

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/cartload.php',
        type: 'post',
        data: 'xid=' + product_id + '&num=' + num + '&xxid=0&type=json&addname=' + addname + '&xxid=' + parent,
        dataType: 'json',
        success: function(json) {
            if (json['success']) {
                showAlertMessage(json['message']);
                $("#num, #mobilnum").html(json['num']);
                $("#sum").html(json['sum']);
                $("#bar-cart, #order").addClass('active');
            }
        }
    });
}

// добавление товара в корзину
function addToCompareList(product_id) {

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/compare.php',
        type: 'post',
        data: 'xid=' + product_id + '&type=json',
        dataType: 'json',
        success: function(json) {
            if (json['success']) {
                showAlertMessage(json['message']);
                $("#numcompare").html(json['num']);
            }
        }
    });
}


// Фотогалерея
function fotoload(xid, fid) {

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/fotoload.php',
        type: 'post',
        data: 'xid=' + xid + '&fid=' + fid + '&type=json',
        dataType: 'json',
        success: function(json) {
            if (json['success']) {
                $("#fotoload").fadeOut('slow', function() {
                    $("#fotoload").html(json['foto']);
                    $("#fotoload").fadeIn('slow');
                });
            }
        }
    });
}

// оформление кнопок
$(".ok").addClass('btn btn-default btn-sm');
$("input:button").addClass('btn btn-default btn-sm');
$("input:submit").addClass('btn btn-primary');
$("input:text,input:password, textarea").addClass('form-control');


// Активная кнопка
function ButOn(Id) {
    Id.className = 'imgOn';
}

function ButOff(Id) {
    Id.className = 'imgOff';
}

function ChangeSkin() {
    document.SkinForm.submit();
}

// Смена валюты
function ChangeValuta() {
    document.ValutaForm.submit();
}

// Создание ссылки для сортировки
function ReturnSortUrl(v) {
    var s, url = "";
    if (v > 0) {
        s = document.getElementById(v).value;
        if (s != "")
            url = "v[" + v + "]=" + s + "&";
    }
    return url;
}

// Проверка наличия файла картинки, прячем картинку
function NoFoto2(obj) {
    obj.height = 0;
    obj.width = 0;
}

// Проверка наличия файла картинки, вставляем заглушку
function NoFoto(obj, pathTemplate) {
    obj.src = pathTemplate + '/images/shop/no_photo.gif';
}

// Сортировка по всем фильтрам
function GetSortAll() {
    var url = ROOT_PATH + "/shop/CID_" + arguments[0] + ".html?";

    var i = 1;
    var c = arguments.length;

    for (i = 1; i < c; i++)
        if (document.getElementById(arguments[i]))
            url = url + ReturnSortUrl(arguments[i]);

    location.replace(url.substring(0, (url.length - 1)) + "#sort");

}

// Инициализируем таблицу перевода на русский
var trans = [];
for (var i = 0x410; i <= 0x44F; i++)
    trans[i] = i - 0x350; // А-Яа-я
trans[0x401] = 0xA8;    // Ё
trans[0x451] = 0xB8;    // ё

// Таблица перевода на украинский
/*
 trans[0x457] = 0xBF;    // ї
 trans[0x407] = 0xAF;    // Ї
 trans[0x456] = 0xB3;    // і
 trans[0x406] = 0xB2;    // І
 trans[0x404] = 0xBA;    // є
 trans[0x454] = 0xAA;    // Є
 */

// Сохраняем стандартную функцию escape()
var escapeOrig = window.escape;

// Переопределяем функцию escape()
window.escape = function(str)
{
    var ret = [];
    // Составляем массив кодов символов, попутно переводим кириллицу
    for (var i = 0; i < str.length; i++)
    {
        var n = str.charCodeAt(i);
        if (typeof trans[n] != 'undefined')
            n = trans[n];
        if (n <= 0xFF)
            ret.push(n);
    }
    return escapeOrig(String.fromCharCode.apply(null, ret));
}

// Перевод раскладки в русскую
function auto_layout_keyboard(str) {
    replacer = {
        "q": "й", "w": "ц", "e": "у", "r": "к", "t": "е", "y": "н", "u": "г",
        "i": "ш", "o": "щ", "p": "з", "[": "х", "]": "ъ", "a": "ф", "s": "ы",
        "d": "в", "f": "а", "g": "п", "h": "р", "j": "о", "k": "л", "l": "д",
        ";": "ж", "'": "э", "z": "я", "x": "ч", "c": "с", "v": "м", "b": "и",
        "n": "т", "m": "ь", ",": "б", ".": "ю", "/": "."
    };

    return str.replace(/[A-z/,.;\'\]\[]/g, function(x) {
        return x == x.toLowerCase() ? replacer[ x ] : replacer[ x.toLowerCase() ].toUpperCase();
    });
}


// Ajax фильтр обновление данных
function filter_load(filter_str, obj) {

    $.ajax({
        type: "POST",
        url: '?' + filter_str.split('#').join(''),
        data: {
            ajax: true
        },
        success: function(data)
        {
            if (data) {
                $(".template-product-list").html(data);
                $('#price-filter-val-max').removeClass('has-error');
                $('#price-filter-val-min').removeClass('has-error');

                // Выравнивание ячеек товара
                setEqualHeight(".description");

                // Сброс Waypoint
                Waypoint.refreshAll();
            }
        },
        error: function(data) {
            $(obj).attr('checked', false);
            //$(obj).attr('disabled', true);

            if ($(obj).attr('name') == 'max')
                $('#price-filter-val-max').addClass('has-error');
            if ($(obj).attr('name') == 'min')
                $('#price-filter-val-min').addClass('has-error');

            window.location.hash = window.location.hash.split($(obj).attr('data-url') + '&').join('');
        }


    });
}

// Ценовой слайдер
function price_slider_load(min, max, obj) {


    var hash = window.location.hash.split('min=' + $.cookie('slider-range-min') + '&').join('');
    hash = hash.split('max=' + $.cookie('slider-range-max') + '&').join('');
    hash += 'min=' + min + '&max=' + max + '&';
    window.location.hash = hash;

    filter_load(hash, obj);

    $.cookie('slider-range-min', min);
    $.cookie('slider-range-max', max);

    $(".pagination").hide();

}

// Ajax фильтр событие клика
function faset_filter_click(obj) {

    if (AJAX_SCROLL) {

        $(".pagination").hide();

        if ($(obj).prop('checked')) {
            window.location.hash += $(obj).attr('data-url') + '&';

        }
        else {
            window.location.hash = window.location.hash.split($(obj).attr('data-url') + '&').join('');
            if (window.location.hash == '')
                $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);

        }

        filter_load(window.location.hash.split(']').join('][]'), obj);
    }
    else {

        var href = window.location.href.split('?')[1];

        if (href == undefined)
            href = '';


        if ($(obj).prop('checked')) {
            var last = href.substring((href.length - 1), href.length);
            if (last != '&' && last != '')
                href += '&';

            href += $(obj).attr('data-url').split(']').join('][]');

        }
        else {
            href = href.split($(obj).attr('data-url').split(']').join('][]') + '&').join('');
        }

        window.location.href = '?' + href;
    }
}

// Выравнивание ячеек товара
function setEqualHeight(columns) {

    $(columns).closest('.row ').each(function() {
        var tallestcolumn = 0;

        $(this).find(columns).each(function() {
            var currentHeight = $(this).height();
            if (currentHeight > tallestcolumn) {
                tallestcolumn = currentHeight;
            }
        });

        if (tallestcolumn > 0) {
            $(this).find(columns).height(tallestcolumn);
        }
    });

}

// Коррекция знака рубля
function setRubznak() {
    $('.rubznak').each(function() {
        if ($(this).html() == 'руб.' || $(this).html() == 'руб' || $('this').html() == 'p') {
            $(this).html('p');
        }
    });
}

$(document).ready(function() {

    // Коррекция знака рубля
    setRubznak();

    // логика кнопки оформления заказа 
    $("button.orderCheckButton").on("click", function(e) {
        e.preventDefault();
        OrderChekJq();
    });

    // Выравнивание ячеек товара
    setEqualHeight(".description");

    // Корректировка стилей меню
    $('.mega-more-parent').each(function() {
        if ($(this).hasClass('hide') || $(this).hasClass('hidden'))
            $(this).prev().removeClass('template-menu-line');
    });

    // Вывод всех категорий в мегаменю
    $('.mega-more').on('click', function(event) {
        event.preventDefault();
        $(this).hide();
        $(this).closest('.mega-menu-block').find('.template-menu-line').removeClass('hide');
    });


    // Направление сортировки в брендах
    $('#filter-selection-well input:radio').on('change', function() {
        window.location.href = $(this).attr('data-url');
    });

    $('#price-filter-body input').on('change', function() {
        if (AJAX_SCROLL) {
            price_slider_load($('#price-filter-body input[name=min]').val(), $('#price-filter-body input[name=max]').val(), $(this));
        } else {
            $('#price-filter-form').submit();
        }

    });


    // Ценовой слайдер
    $("#slider-range").on("slidestop", function(event, ui) {

        if (AJAX_SCROLL) {

            // Сброс текущей страницы
            count = current;

            price_slider_load(ui.values[ 0 ], ui.values[ 1 ]);
        }
        else {
            $('#price-filter-form').submit();
        }
    });

    // Фасетный фильтр
    /*if (FILTER && $("#sorttable table td").html()) {
        $("#faset-filter-body").html($("#sorttable table td").html());
        $("#faset-filter").removeClass('hide');
    }
    else {

        $("#faset-filter").hide();
    }

    if (!FILTER) {
        $("#faset-filter").hide();
        $("#sorttable").removeClass('hide');
    }*/


    // Направление сортировки
    $('#filter-well input:radio').on('change', function() {
        if (AJAX_SCROLL) {

            count = current;

            window.location.hash = window.location.hash.split($(this).attr('name') + '=1&').join('');
            window.location.hash = window.location.hash.split($(this).attr('name') + '=2&').join('');
            window.location.hash += $(this).attr('name') + '=' + $(this).attr('value') + '&';

            filter_load(window.location.hash);
        }
        else {

            var href = window.location.href.split('?')[1];

            if (href == undefined)
                href = '';

            var last = href.substring((href.length - 1), href.length);
            if (last != '&' && last != '')
                href += '&';

            href = href.split($(this).attr('name') + '=1&').join('');
            href = href.split($(this).attr('name') + '=2&').join('');
            href += $(this).attr('name') + '=' + $(this).attr('value');
            window.location.href = '?' + href;
        }
    });


    // Загрузка результата отбора при переходе
    if (window.location.hash != "" && $("#sorttable table td").html()) {

        var filter_str = window.location.hash.split(']').join('][]');

        // Загрузка результата отборки
        filter_load(filter_str);

        // Проставление чекбоксов
        $.ajax({
            type: "POST",
            url: '?' + filter_str.split('#').join(''),
            data: {
                ajaxfilter: true
            },
            success: function(data)
            {
                if (data) {
                    $("#faset-filter-body").html(data);
                    $("#faset-filter-body").html($("#faset-filter-body").find('td').html());
                }
            }
        });
    }

    // Ajax фильтр
    $('#faset-filter-body').on('change', 'input:checkbox', function() {

        // Сброс текущей страницы
        count = current;

        faset_filter_click($(this));
    });


    // Сброс фильтра
    $('#faset-filter-reset').on('click', function(event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            $("#faset-filter-body").html($("#sorttable table td").html());
            filter_load('');
            $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);
            window.location.hash = '';
            $.removeCookie('slider-range-min');
            $.removeCookie('slider-range-max');
            $(".pagination").show();

            // Сброс текущей страницы
            count = current;
        }

    });


    // Пагинация товаров
    $('.pagination a').on('click', function(event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            window.location.href = $(this).attr('href') + window.location.hash;
        }
    });


    // toTop
    $('#toTop').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: $("header").offset().top - 100}, 500);
    });

    // закрепление навигации
    /*$('.breadcrumb, .slider').waypoint(function() {
        if (FIXED_NAVBAR){
            $('#navigation').toggleClass('navbar-fixed-top');
        }

        // toTop          
        $('#toTop').fadeToggle();
    });*/

    // быстрый переход
    $(document).on('keydown', function(e) {
        if (e == null) { // ie
            key = event.keyCode;
            var ctrl = event.ctrlKey;
        } else { // mozilla
            key = e.which;
            var ctrl = e.ctrlKey;
        }
        if ((key == '123') && ctrl)
            window.location.replace(ROOT_PATH + '/phpshop/admpanel/');
        if (key == '120') {
            $.ajax({
                url: ROOT_PATH + '/phpshop/ajax/info.php',
                type: 'post',
                data: 'type=json',
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        confirm(json['info']);
                    }
                }
            });
        }
    });


    // выбор каталога поиска
    $(".cat-menu-search").on('click', function() {
        $('#cat').val($(this).attr('data-target'));
        $('#catSearchSelect').html($(this).html());
    });

    /*
     hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>', position: 'top right', fade: 2});
     hs.graphicsDir = ROOT_PATH + '/java/highslide/graphics/';
     hs.wrapperClassName = 'borderless';
     */


    // увеличение изображения товара
    $("body").on('click', '.highslide', function() {
        return hs.expand(this);
    });

    // ошибка загрузки изображения
    $('.image').on('error', function() {
        $(this).attr('src', '/phpshop/templates/bootstrap/images/shop/no_photo.gif');
        return true;
    });


    // подгрузка комментариев
    $("body").on('click', '#commentLoad', function() {
        commentList($(this).attr('data-uid'), 'list');
    });


    // Validator Fix brands url
    $('#brand-menu .mega-menu a').on('click', function(event) {
        event.preventDefault();
        window.location.replace($(this).attr('data-url'));
    });

    // убираем пустые закладки подробного описания
    if ($('#files').html() != 'Нет файлов')
        $('#filesTab').addClass('show');

    if ($('#vendorenabled').html() != '')
        $('#settingsTab').addClass('show');

    if ($('#pages').html() != '')
        $('#pagesTab').addClass('show');

    /*
     if ($('#vendorActionButton').val() == 'Применить') {
     $('#sorttable').addClass('show');
     }*/

    // Иконки в основном меню категорий
    /*if (MEGA_MENU_ICON === false) {
        $('.mega-menu-block img').hide();
    }*/

    // убираем меню брендов
    /*if (BRAND_MENU === false) {
        $('#brand-menu').hide();
    }

    if (CATALOG_MENU === false) {
        $('#catalog-menu').hide();
    }
    else {
        $('#catalog-menu').removeClass('hide');
    }*/

    // добавление в корзину
    $('body').on('click', '.addToCartList', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'));
        $(this).attr('disabled', 'disabled');
        $(this).addClass('btn-success');
        $('#order').addClass('active');
    });

    // изменение количества товара для добавления в корзину
    $('body').on('change', '.addToCartListNum', function() {
        var num = (Number($(this).val()) || 1);
        var id = $(this).attr('data-uid');
        /*
         if (num > 0 && $('.addToCartList').attr('data-uid') === $(this).attr('data-uid'))
         $('.addToCartList').attr('data-num', num);*/
        if (num > 0) {
            $(".addToCartList").each(function() {
                if ($(this).attr('data-uid') === id)
                    $('.addToCartList[data-uid=' + id + ']').attr('data-num', num);
            });
        }

    });

    // добавление в корзину подтипа
    $(".addToCartListParent").on('click', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'), $(this).attr('data-parent'));
    });

    // добавление в корзину опции
    $(".addToCartListOption").on('click', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'), $(this).attr('data-uid'), $('#allOptionsSet' + $(this).attr('data-uid')).val());
    });

    // добавление в wishlist
    $('body').on('click', '.addToWishList', function() {
        addToWishList($(this).attr('data-uid'));
    });

    // добавление в compare
    $('body').on('click', '.addToCompareList', function() {
        addToCompareList($(this).attr('data-uid'));
    });

    // отправка сообщения администратору из личного кабинета
    $("#CheckMessage").on('click', function() {
        if ($("#message").val() != '')
            $("#forma_message").submit();
    });

    // Визуальная корзина
    if ($("#cartlink").attr('data-content') == "") {
        $("#cartlink").attr('href', '/order/');
    }
    $('[data-toggle="popover"]').popover();
    $('a[data-toggle="popover"]').on('show.bs.popover', function() {
        $('a[data-toggle="popover"]').attr('data-content', $("#visualcart_tmp").html());
    });

    // Подсказки 
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    // Стилизация select
    $('.selectpicker').selectpicker({
        width: "auto"
    });

    // Переход из прайса на форму с описанием
    $('#price-form').on('click', function(event) {
        event.preventDefault();
        if ($(this).attr('data-uid') != "" && $(this).attr('data-uid') != "ALL")
            window.location.replace("../shop/CID_" + $(this).attr('data-uid') + ".html");
    });

    // Ajax поиск
    $("#search").on('input', function() {
        var words = $(this).val();
        if (words.length > 2) {
            $.ajax({
                type: "POST",
                url: ROOT_PATH + "/search/",
                data: {
                    words: escape(words + ' ' + auto_layout_keyboard(words)),
                    set: 2,
                    ajax: true
                },
                success: function(data)
                {

                    // Результат поиска
                    if (data != 'false') {

                        if (data != $("#search").attr('data-content')) {
                            $("#search").attr('data-content', data);

                            $("#search").popover('show');
                        }
                    } else
                        $("#search").popover('hide');
                }
            });
        }
        else {
            $("#search").popover('hide');
        }
    });

    // Повторная авторизация
    if ($('#usersError').html()) {
        $('form[name=user_forma] .form-group').addClass('has-error has-feedback');
        $('form[name=user_forma] .glyphicon').removeClass('hide');
        $('#userModal').modal('show');
        $('#userModal').on('shown.bs.modal', function() {
            $(this).animate({paddingLeft: '+=20px'}, 150);
            $(this).animate({paddingRight: '+=20px'}, 150);
            $(this).animate({paddingLeft: '+=20px'}, 100);
            $(this).animate({paddingRight: '+=20px'}, 100);
        });
    }

    // Проверка синхронности пароля регистрации
    $("form[name=user_forma_register] input[name=password_new2]").on('blur', function() {
        if ($(this).val() != $("form[name=user_forma_register] input[name=password_new]").val()) {
            $('form[name=user_forma_register] #check_pass').addClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').removeClass('hide');
        }
        else {
            $('form[name=user_forma_register] #check_pass').removeClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').addClass('hide');
        }
    });

    // Регистрация пользователя
    $("form[name=user_forma_register]").on('submit', function() {
        if ($(this).find("input[name=password_new]").val() != $(this).find("input[name=password_new2]").val()) {
            $(this).find('#check_pass').addClass('has-error has-feedback');
            $(this).find('.glyphicon').removeClass('hide');
            return false;
        }
        else
            $(this).submit();
    });

    // Ошибка регистрации
    if ($("#user_error").html()) {
        $("#user_error").find('.list-group-item').addClass('list-group-item-warning');
    }


    // формат ввода телефона
    $("form[name='forma_order'], input[name=returncall_mod_tel],input[name=tel]").on('click', function() {
        if (PHONE_FORMAT && PHONE_MASK && $('.bar-padding-fix').is(":hidden")) {
            $('input[name=tel_new], input[name=returncall_mod_tel],input[name=tel]').mask(PHONE_MASK);
        }
    });

    // меню каталога товаров
    /*
     $(".dropdown").hover(
     function() {
     $('.dropdown-menu', this).fadeIn("fast");
     },
     function() {
     $('.dropdown-menu', this).fadeOut("fast");
     });
     */

// Фотогалерея в по карточке товара
    if ($('.bxslider').length) {
        $('.bxslider-pre').addClass('hide');
        $('.bxslider').removeClass('hide');
        slider = $('.bxslider').bxSlider({
            mode: 'fade',
            pagerCustom: '.bx-pager'
        });
    }



    // Фотогалерея в по карточке товара с большими изображениями
    $(document).on('click', '.bxslider a', function(event) {
        event.preventDefault();
        $('#sliderModal').modal('show');
        $('.bxsliderbig').html($('.bxsliderbig').attr('data-content'));

        sliderbig = $('.bxsliderbig').bxSlider({
            mode: 'fade',
            pagerCustom: '.bx-pager-big'
        });


        if ($('.bx-pager-big').length == 0) {
            $('.modal-body').append('<div class="bx-pager-big">' + $('.bxsliderbig').attr('data-page') + '</div>');
            sliderbig.reloadSlider();
        }

        sliderbig.goToSlide(slider.getCurrentSlide());

    });

    // Закрытие модального окна фотогарелерии, клик по изображению
    $(document).on('click', '.bxsliderbig a', function(event) {
        event.preventDefault();
        slider.goToSlide(sliderbig.getCurrentSlide());
        $('#sliderModal').modal('hide');
    });

    // Закрытие модального окна фотогарелерии
    $('#sliderModal').on('hide.bs.modal', function() {
        slider.goToSlide(sliderbig.getCurrentSlide());
        sliderbig.destroySlider();
        delete sliderbig;
    });

    // Сolorpicker
    if ($('.color').length) {
        $('.color').colorpicker({format: 'hex'});


        // Сolorpicker Live
        $('.color').colorpicker().on('changeColor', function(e) {
            var el = $(this).find('.color-value').attr('data-option');
            var name = $(this).find('.color-value').attr('name');
            $(el).css('cssText', name + ':' + e.color.toHex() + ' !important');
        });

    }

    // сохранение оформления c Сolorpicker
    $(".saveTheme").on('click', function() {

        var data = 'template=astero&type=json&parser=css&';
        $('.color-value').each(function() {
            data += 'color[' + $(this).attr('id').split('color-').join('') + '][' + $(this).attr('name') + ']=' + $(this).val() + '&';
        });

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/skin.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    showAlertMessage(json['status']);
                }
            }
        });
    });

    // смена оформления
    $(".bootstrap-theme").on('click', function() {
        $.cookie($('#bootstrap_theme').attr('data-name') + '_theme', $(this).attr('data-skin'), {
            path: '/'
        });

        setTimeout(function() {

            $('#body').fadeIn("slow", window.location.reload());
            //$('.color').colorpicker('update');
            //$('.color').colorpicker('reposition');

        }, 1000);


    });

    $("#color-slide").slider({
        range: false,
        step: 5,
        min: 0,
        max: 360,
        values: [$("#color-slide").attr('data-option')],
        slide: function(event, ui) {
            $($(".color-filter").attr('data-option')).css('cssText', 'filter: hue-rotate(' + ui.values[0] + 'deg) !important');
            $(".color-filter").val(ui.values[0]);
        }
    });


    $('#style-selector .style-toggle').click(function(e) {
        e.preventDefault();
        if ($(this).hasClass('ss-close')) {
            $(this).removeClass('ss-close');
            $(this).addClass('ss-open');
            $('#style-selector').animate({'right': '-' + $('#style-selector').width() + 'px'}, 'slow');

            $.cookie('style_selector_status', 'disabled', {
                path: '/'
            });
        } else {
            $(this).removeClass('ss-open');
            $(this).addClass('ss-close');
            $('#style-selector').animate({'right': '0px'}, 'slow');

            $.cookie('style_selector_status', 'enabled', {
                path: '/'
            });
        }
    });


    // Скрыть пустые блоки в описании товара
    $('.empty-check').each(function() {
        if ($(this).find('a').html()  === undefined || $(this).find('.vendorenabled').html() == ''){
            $(this).fadeOut('slow');
        }
    });

});
