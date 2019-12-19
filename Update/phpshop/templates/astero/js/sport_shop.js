/** Измение вида рейтинга товара начало **/
function changeOfProductRatingView () {
	var raitingWidth = $('#raiting_votes').css('width');
	var raitingstarZero = ('<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>')
	var raitingstarOne = ('<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarTwo = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarThree = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarFour = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>');
	var raitingstarFive = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>');

	if (raitingWidth == ('0px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarZero);
	}
	if (raitingWidth == ('24px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarOne);
	}
	if (raitingWidth == ('32px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarTwo);
	}
	if (raitingWidth == ('48px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarThree);
	}
	if (raitingWidth == ('64px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarFour);
	}
	if (raitingWidth == ('80px')) {
		$('#raiting_star').remove();
		$('.rating').append(raitingstarFive);
	} 
}
/** Измение вида рейтинга товара конец **/

/** Изменение вида рейтинга отзыва начало **/
function changeOfReviewsRatingView () {
	var imgRaitingSrcZero = ('/phpshop/templates/astero/images/stars/stars1-0.png')
	var imgRaitingSrcOne = ('/phpshop/templates/astero/images/stars/stars1-1.png')
	var imgRaitingSrcTwo = ('/phpshop/templates/astero/images/stars/stars1-2.png')
	var imgRaitingSrcThree = ('/phpshop/templates/astero/images/stars/stars1-3.png')
	var imgRaitingSrcFour = ('/phpshop/templates/astero/images/stars/stars1-4.png')
	var imgRaitingSrcFive = ('/phpshop/templates/astero/images/stars/stars1-5.png')
	var raitingstarZero = ('<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>')
	var raitingstarOne = ('<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarTwo = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarThree = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
	var raitingstarFour = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>');
	var raitingstarFive = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>');
	$('.comments-raiting-wrapper').each(function () {
		var imgRaitingSrc = $(this).children('img').attr('src');
		if ($(this).find('img')) {
			$(this).children('img').remove();
			if (imgRaitingSrc == imgRaitingSrcZero) {
				$(this).append(raitingstarZero);
			}if (imgRaitingSrc == imgRaitingSrcOne) {
				$(this).append(raitingstarOne);
			}if (imgRaitingSrc == imgRaitingSrcTwo) {
				$(this).append(raitingstarTwo);
			}if (imgRaitingSrc == imgRaitingSrcThree) {
				$(this).append(raitingstarThree);
			}if (imgRaitingSrc == imgRaitingSrcFour) {
				$(this).append(raitingstarFour);
			}if (imgRaitingSrc == imgRaitingSrcFive) {
				$(this).append(raitingstarFive);
			}
		}
	});
}
/** Изменение вида рейтинга отзыва конец **/

$(document).ready(function () {
	$('#body').mousewheel( function () {
		$(window).on('scroll', function () {
	        
	    if($(window).scrollTop() >= $('#header-area').offset().top) {
	        $('#main-menu').addClass('navbar-fixed-top')
	    } else {
	        $('#main-menu').removeClass('navbar-fixed-top')
	    }
	})
});
	changeOfProductRatingView();
	setInterval(changeOfReviewsRatingView, 100)
	$(document).on('click', function () {
		changeOfReviewsRatingView();
	})
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
	$('#nav-catalog-dropdown-link').on('click', function (){
		if ($('.main-navbar-list-catalog-wrapper').hasClass('open')) {
			$('.main-navbar-list-catalog-wrapper').removeClass('open');
			$('#nav-catalog-dropdown-link').removeClass('open');
			$('.main-navbar-list-catalog-wrapper').removeClass('fadeIn animated');
		}else {
			$('.main-navbar-list-catalog-wrapper').addClass('open');
			$('.main-navbar-list-catalog-wrapper').addClass('fadeIn animated');
			$('#nav-catalog-dropdown-link').addClass('open');
			$('.main-navbar-list-catalog-hidden').removeClass('active');
		}
	});
	$('.main-navbar-list-catalog-wrapper > li > a').on('click', function(e){
    	if($(e.target).hasClass('active')){
    		$(e.target).removeClass('active');
        	$(e.target).siblings('ul').removeClass('active');
        	$(e.target).siblings('ul').removeClass('fadeIn animated');
    	} else {
    		$(e.target).addClass('active');
    		$(e.target).siblings('ul').addClass('active');
    		$(e.target).siblings('ul').addClass('fadeIn animated');
          }
	});
	var pathname = self.location.pathname;
    //активация меню
    $( ".sidebar-nav li" ).each(function( index ) {

      if( $( this ).attr("data-cid")==pathname ) {
        var cid = $( this ).attr("data-cid-parent");
        $("#cid"+cid).addClass("active");
        $("#cid"+cid).attr("aria-expanded", "false");
        $("#cid-ul"+cid).addClass("active");
        $(this).addClass("active");
        $(this).parent("ul").addClass("active");
        $(this).parent("ul").siblings('a').addClass("active");
      }
    });
});
