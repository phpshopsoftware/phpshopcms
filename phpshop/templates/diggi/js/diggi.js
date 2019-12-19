$(document).ready(function() {
    $(window).on('scroll', function() {

        if ($(window).scrollTop() >= $('.header-top').offset().top) {
            $('#main-menu').addClass('navbar-fixed-top');
            // toTop          
            $('#toTop').fadeIn();
        } else {
            $('#main-menu').removeClass('navbar-fixed-top');
            $('#toTop').fadeOut();
       
        }
    });

    $('.sidebar-nav > li').removeClass('dropdown');
    $('.sidebar-nav > li > ul').removeClass('dropdown-menu');
    $('.sidebar-nav > li > a').on('click', function(e){
    	if($(e.target).hasClass('active')){
    		$(e.target).removeClass('active');
        	$(e.target).siblings('ul').removeClass('active');
    	} else {
    		$(e.target).addClass('active');
    		$(e.target).siblings('ul').addClass('active');
    		$(e.target).siblings('ul').addClass('fadeIn animated');
          }
	});
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').removeClass('dropdown-menu');
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').addClass('main-navbar-list-catalog-hidden');
    $('#nav-catalog-dropdown-link').on('click', function() {
        if ($('.main-navbar-list-catalog-wrapper').hasClass('open')) {
            $('.main-navbar-list-catalog-wrapper').removeClass('open');
            $('#nav-catalog-dropdown-link').removeClass('open');
            $('.main-navbar-list-catalog-wrapper').removeClass('fadeIn animated');
            $('.main-navbar-list-catalog-wrapper').parents('.container').removeClass('border-fix')
            return false;
        } else {
            $('.main-navbar-list-catalog-wrapper').parents('.container').addClass('border-fix')
            $('.main-navbar-list-catalog-wrapper').addClass('open');
            $('.main-navbar-list-catalog-wrapper').addClass('fadeIn animated');
            $('#nav-catalog-dropdown-link').addClass('open');
            $('.main-navbar-list-catalog-hidden').removeClass('active');
            return false;
        }
    });
    $('.main-navbar-list-catalog-wrapper > li > a').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
            $(this).siblings('ul').removeClass('fadeIn animated');
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').addClass('fadeIn animated');
        }
    });

    //Активация левого меню каталога
            var pathname = self.location.pathname;
                $('.sidebar-nav li').each(function(){
                    if ($(this).attr('data-cid')==pathname) {
                        $(this).addClass("active");
                        $(this).children('a').addClass("active");
                        $(this).parent("ul").addClass("active");
                        $(this).parent("ul").siblings('a').addClass("active");
                    }
                });
                $('.sidebar-nav li ul').each(function(){
                    if ($(this).hasClass('active')) {
                        $(this).parent('li').removeClass('active');
                    }
                });
});
