// ����� ���������� �� �����
var ROOT_PATH = $('#body').attr('data-dir');


function ChangeSkin() {
    document.SkinForm.submit();
}


// ���������� ������
$(".ok").addClass('btn btn-default btn-sm');
$("input:button").addClass('btn btn-default');
//$("input:submit").addClass('btn btn-primary');
$("input:text,input:password, textarea").addClass('form-control');
$(".operation").addClass('btn btn-xs');

function ChangeSkin() {
    document.SkinForm.submit();
}

// ����� ��������� 
function showAlertMessage(message) {
    var messageBox = '.success-notification';
    var innerBox = '#notification .notification-alert';

    //���� ��� �������� ��� ����������� �������, ������� ������� alert
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


$(window).on('load', function() {

    // highslide
    hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="�������"></div>', position: 'top right', fade: 2});
    hs.graphicsDir = '/java/highslide/graphics/';
    hs.wrapperClassName = 'borderless';

    // ���������� select
    $('.selectpicker').selectpicker({
        width: "auto"
    });

    // ����������� ���������
    $('.breadcrumb, .template-slider').waypoint(function() {
        $('#navigation').toggleClass('navbar-fixed-top');
    });

    // ������� �������
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


    // ��������� 
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});


    // ���� �������� �������
    $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).fadeIn("fast");
            },
            function() {
                $('.dropdown-menu', this).fadeOut("fast");
            });

    // ��������� DaData.ru
    if (typeof $('#body').attr('data-token') !== 'undefined' && $('#body').attr('data-token').length)
        var DADATA_TOKEN = $('#body').attr('data-token');

    if (DADATA_TOKEN) {

        $('[name="name_new"]').suggestions({
            token: DADATA_TOKEN,
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        $('[name="nameP"]').suggestions({
            token: DADATA_TOKEN,
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        $('[name="returncall_mod_name"]').suggestions({
            token: "",
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        $('[type="email"]').suggestions({
            token: DADATA_TOKEN,
            type: "EMAIL",
            suggest_local: false,
            count: 5
        });
    }
});

// reCAPTCHA
if ($("#recaptcha_default").length || $("#recaptcha_returncall").length || $("#recaptcha_oneclick").length) {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.defer = true;
    ga.src = '//www.google.com/recaptcha/api.js?onload=recaptchaCreate&render=explicit';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
}
recaptchaCreate = function() {

    if ($("#recaptcha_default").length)
        grecaptcha.render("recaptcha_default", {"sitekey": $("#recaptcha_default").attr('data-key'), "size": $("#recaptcha_default").attr('data-size')});

    if ($("#recaptcha_returncall").length)
        grecaptcha.render("recaptcha_returncall", {"sitekey": $("#recaptcha_returncall").attr('data-key'), "size": $("#recaptcha_returncall").attr('data-size')});
};