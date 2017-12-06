layui.use(['element'], function(){
	$ = layui.jquery;
  	element = layui.element(); 
  
  //导航的hover效果、二级菜单等功能，需要依赖element模块
  // 侧边栏点击隐藏兄弟元素
	$('.layui-nav-item').click(function(event) {
		$(this).siblings().removeClass('layui-nav-itemed');
		// $(this).children('.fa').css('transform','rotate(7deg)')
	});

	$('.layui-tab-title li').eq(0).find('i').remove();

	height = $('.layui-layout-admin .site-demo').height();
	$('.layui-layout-admin .site-demo').height(height-100);

	if($(window).width()<750){
		trun = 0;
		$('.x-slide_left').css('background-position','0px -61px');
	}else{
		trun = 1;
	};

	$('.layui-nav-animation').click(function(){
		var _this=this;
		if(!trun){
			$('.layui-nav-animation').animate({opacity:0},0)
			$('.layui-nav-animation').animate({opacity:1},500)
			$('.layui-nav-animation').children('a').css({
				'padding':'0 20px'
			});
			$('.layui-nav-animation').children('a').children('.fa').css({
				'display':'inline',
				'width':'auto',
				'font-size':'12px'
			});
			$(_this).children('.fa-arrow-left').css('display','inline-block');
			$(_this).children('.fa-list').css('display','none');
			setTimeout(function(){
				$(_this).children('.fa-arrow-left').css({transform:'rotate(0deg)'});;
			},1)
			$('.x-side').animate({width: '220px'},100).siblings('.x-main').animate({left: '220px'},100);
			$(this).css('background-position','0px 0px');
			trun=1;
		}
	});
	var _width = $(window).width(); 
	$('.x-slide_left').click(function(event) {
		var _this=this;
		if(trun){
			$('.layui-nav-animation').removeClass('layui-nav-itemed');
			$('.layui-nav-animation').animate({opacity:0},0);
			$('.layui-nav-animation').children('a').css({
				'padding':'0'
			});
			$('.layui-nav-animation').children('a').children('.fa').css({
				'display':'inline-block',
				'width':'60px',
				'text-align':'center',
				'font-size':'20px',
				'opacity':1,
			});
			setTimeout(function(){
				$('.layui-nav-animation').animate({opacity:1},500);	
			},100);

			$(_this).children('.fa-arrow-left').css({transform:'rotate(90deg)'});
			setTimeout(function(){
				$(_this).children('.fa-arrow-left').css('display','none');
				$(_this).children('.fa-list').css('display','inline-block');
				
			},200);
			setTimeout(function(){
				if(_width<=750){
					var allSize=$('.site-demo').width();
	                var topSize=$('.layui-tab').children().not(".layui-tab-title");
	                var index=0;
	                var idx=0;

	                $(topSize).each(function() {
	                    index += $(this).outerWidth(true)
	                });
	                index=$('.site-demo').outerWidth(true)-(index-$('.site-demo').outerWidth(true));
	                idx=$('.dv1').outerWidth(true);
	                var _this=$('.dv1 .layui-this').position().left+$('.dv1 .layui-this').outerWidth(true);
	                 console.log(_this);
	                 console.log(index);
	                if(_this>index){
	                    $(".dv1").animate({
	                        marginLeft:index-_this+"px"
	                    }, "fast")
	                }
	                else{
	                    $(".dv1").animate({
	                        marginLeft:0+"px"
	                    }, "fast")
	                }
				}
			},200);
			$('.x-side').animate({width: '60px'},100).siblings('.x-main').animate({left: '60px'},100);
			$(this).css('background-position','0px -61px');
			trun=0;

		}else{
			$('.layui-nav-animation').animate({opacity:0},0);
			$('.layui-nav-animation').animate({opacity:1},500);
			$('.layui-nav-animation').children('a').css({
				'padding':'0 20px'
			});

			$('.layui-nav-animation').children('a').children('.fa').css({
				'display':'inline',
				'width':'auto',
				'font-size':'12px'
			});
			$(_this).children('.fa-arrow-left').css('display','inline-block');
			$(_this).children('.fa-list').css('display','none');
			setTimeout(function(){
				$(_this).children('.fa-arrow-left').css({transform:'rotate(0deg)'});
			},1);
			$('.x-side').animate({width: '220px'},100).siblings('.x-main').animate({left: '220px'},100);
			$(this).css('background-position','0px 0px');
			trun=1;
		}
		
	});



  	//监听导航点击
  	element.on('nav(side)', function(elem){
    	title = elem.find('cite').text();
    	url = elem.find('a').attr('_href');
    	// alert(url);

    	for (var i = 0; i <$('.x-iframe').length; i++) {
    		if($('.x-iframe').eq(i).attr('src')==url){
    			element.tabChange('x-tab', i);
    			return;
    		}
    	};

    	res = element.tabAdd('x-tab', {
	        title: title//用于演示
	        ,content: '<iframe frameborder="0" src="'+url+'" class="x-iframe"></iframe>'
		    });


		element.tabChange('x-tab', $('.layui-tab-title li').length-1);

    	$('.layui-tab-title li').eq(0).find('i').remove();
  	});
});

