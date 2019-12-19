(function( $ ) {
  $.fn.sneg = function(options) {
  
    var settings = $.extend( {
      'col'				: 50,						//количество снежинок - чем больше значение, тем больше нагрузка.
      'minsize'			: 10,						//минимальный размер снежинки (в пикселях)
      'maxsize' 		: 30,						//максимальный размер снежинки (в пикселях)
      'veter'			: 5,						//сила "ветра" (отклонение по горизонтали)
      'v'				: 70,						//скорость падения снежинок 
      'colour'			: '#AAAACC', 	//цвет снежинок любое rgb, rgba значение
      'array'			: Array("❄","❅","❆","❇","✶","✷","✻"), //массив символов снежинок
      'ligthning'		: true,						//включить(true) или выключить(false) text-shadow снежинок. выключение немного снижает нагрузку.
      'overall'			: false,						//включить(true) или выключить(false) отображение снежинок поверх контента.
      'r'				: 20,						//интервал обновления - чем меньше значение, тем больше нагрузка.
      'body'			: $(this),					//родительский элемент (в котором будут снежинки)
      'wrapper'			: $("body").children()		//дочерний элемент, содержащий контент (который будет над снежинками при выключенном параметре overall).
    }, options);


	var snoweearray = settings.array;
	var minsize = settings.minsize; 
	var maxsize = settings.maxsize; 
	var veter = settings.veter; 
	var v = settings.v; 

    if(typeof(mod_snow_color))
	var color = mod_snow_color;
	else var color = settings.colour;

	var col = settings.col; 
	var ligthning = settings.ligthning; 
	var r = settings.r; 
	var overall = settings.overall; 
	var body = settings.body;
	var wrapper = settings.wrapper;

	var bodyheight,bodywidth,comeon,oldtop,delta,x,silavetra;

	function startsnow(){

		$(".snowee").remove();
		clearInterval(comeon);
		bodywidth = body.width();
		if ($(window).height() < $("body").height()) { bodyheight = body.height() } else { bodyheight = $(window).height(); }
		body.css({overflowX:'hidden'});
		wrapper.css({zIndex:'2'});
		//$(".wrapper").html(bodywidth+" "+bodyheight+" "+$(window).height());
		for (i=0;i<col;i++) {
			positiontop = parseInt(Math.random()*bodyheight);
			positionleft = parseInt(Math.random()*bodywidth);
			size = parseInt((Math.random()*(maxsize - minsize))+minsize);
			snowee = snoweearray[parseInt(Math.random()*(snoweearray.length))];
			body.append("<div data-delta='"+parseFloat(size/10,2)+"' class='snowee snowee"+i+"' style='left: "+positionleft+"px; top:"+positiontop+"px; width: "+size+"px; height: "+size+"px; font-size: "+size+"px; line-height: "+size+"px;'>"+snowee+"</div>");
		}
		$(".snowee").css({position:'absolute',color: color,fontFamily:'Arial,Helvetica,sans-serif'});
		if (ligthning) {
			$(".snowee").css({textShadow:'0px 0px 7px '+color});
		}
		if (overall) {
			$(".snowee").css({zIndex:'1000'});
		}

		silavetra = 0/(5000/veter);
		body.mousemove(function(e) {
		 x = e.pageX;
		 silavetra = -((bodywidth/2)-x)/(5000/veter);
		 //$(".wrapper").html(x);
		});

		comeon = setInterval(function(){
			$(".snowee").each(function(){
				if(parseFloat($(this).css("top"),2)+parseInt($(this).height())+v >= bodyheight) {
					$(this).css("top","-"+maxsize+"px");
				}
				oldtop = parseFloat($(this).css("top"),2);
				delta = $(this).data("delta")/2;
				//console.log(oldtop+delta);
				$(this).css("top",parseFloat(oldtop+(1/r*(v*delta)),2)+"px");

				oldleft = parseFloat($(this).css("left"),4);
				//$(".wrapper").html(oldleft);
				if(oldleft < -parseInt($(this).css("width"))) {oldleft = bodywidth};
				if(oldleft > bodywidth) {oldleft = -parseInt($(this).css("width"))};
				$(this).css("left",parseFloat((oldleft+silavetra*delta),4)+"px");

			});
		},r);


	}
	$(window).load(function(){
		startsnow();
	}).resize(function(){
		startsnow();
	}).focus(function(){
		startsnow();
	})



  };
})(jQuery);