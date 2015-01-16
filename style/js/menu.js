(function($){
$.fn.extend({
	slide:function(opt,callback){
		//定义变量
		if(!opt) var opt={};
		var timerID;
		var btnLeft = $("#"+ opt.left);//向左按钮
		var btnRight = $("#"+ opt.right);//向右按钮
		var _this=this.find("ul");
			liW=opt.li_w//参数：每个li的宽度（包括border,margin,padding,都要算进去）
			li_size=opt.li_size ? parseInt(opt.li_size,10) : parseInt(this.width()/liW,10)  //每次滚动的个数，默认为一屏，即父容器宽度
			speed=opt.speed ? parseInt(opt.speed,10) : 500; //滚动速度，数值越大，速度越慢（毫秒）
			timer=opt.timer //?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
		if(li_size==0) {li_size=1};
		var leftW=0-li_size*liW;
		//向左滚动函数
		function scrollLeft(){
				btnLeft.unbind("click",scrollLeft); //滑动时先取消点击滑动事件
				btnRight.unbind("click",scrollRight);
				_this.animate({
					marginLeft:leftW
					},speed,"easeOutExpo",function(){ //参数"easeOutExpo"不需要可以删掉，需要的话必选先加载"easing.js"文件
					for(i=1;i<=li_size;i++){
							_this.find("li:first").appendTo(_this);
					}
					_this.css({'margin-left':0});
					btnLeft.bind("click",scrollLeft); //滑动结束后绑定按钮的点击事件
					btnRight.bind("click",scrollRight);
				});
		}
		//向右滚动函数
		function scrollRight(){
				btnLeft.unbind("click",scrollLeft);
				btnRight.unbind("click",scrollRight);
				for(i=1;i<=li_size;i++){
					_this.find("li:last").prependTo(_this);
				}
				_this.css({'margin-left':leftW});
				_this.animate({
					marginLeft:0
					},speed,"easeOutExpo",function(){
					btnLeft.bind("click",scrollLeft);
					btnRight.bind("click",scrollRight);
				});
		}
		//自动播放
		function autoPlay(){
				if(timer) 
				timerID = window.setInterval(scrollLeft,timer);
		};
		//停止自动播放
		function autoStop(){
				if(timer) 
				window.clearInterval(timerID);
		};
		 //鼠标事件绑定
		_this.hover(autoStop,autoPlay).mouseout() //加载完成后自动开始
		btnLeft.click( scrollLeft ).hover(autoStop,autoPlay);//向左鼠标事件绑定
		btnRight.click( scrollRight ).hover(autoStop,autoPlay);//向右鼠标事件绑定
	}       
})
})(jQuery);



$(document).ready(function(){
        $("#slideUl").slide({
			li_size:6, //每次滚动li个数,默认一屏
			speed:500, //速度：数值越大，速度越慢（毫秒）默认500
			timer:4000, //不需要自动滚动删掉该参数
			li_w:110, //每个li的宽度（包括border,margin,padding,都要算进去）
			left:"sildeLeft",
			right:"sildeRight"
		});
});




function changeover_book(object){
	var obj=$(object);
	if(obj.attr("class")!="now"){
		var showobj=obj.siblings(".now");
		showobj.removeClass("now");
		var content_div=showobj.children("div");
		var bigtemphtml=content_div.html();
		if($.trim(content_div.next("div").children().val())!="")content_div.next("div").html($.trim(content_div.next("div").children().val()));
		content_div.html(content_div.next("div").html());
		content_div.next("div").html(bigtemphtml);
		obj.addClass("now");
		var hideobj=obj.children("div");
		temphtml=hideobj.html();
		if($.trim(hideobj.next("div").children().val())!="")hideobj.next("div").html($.trim(hideobj.next("div").children().val()));
		hideobj.html(hideobj.next("div").html());
		hideobj.next("div").html(temphtml);}};





$(function(){
	var tab_menu_li = $('.tab_menu li');
	$('.tab_box div:gt(0)').hide();
	
	tab_menu_li.mouseover(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		
	var tab_menu_li_index = tab_menu_li.index(this);
	$('.tab_box div').eq(tab_menu_li_index).show().siblings().hide();
	});	
});


$(function(){
	var topbar_li = $('.topBar_l li');
	
	topbar_li.click(function(){
		$(this).addClass('sel').siblings().removeClass('sel');
	});	
});

$(function(){
	var tab_li = $('.sta_ma_l li');	
	$('.sta_ma_r div:gt(0)').hide();
	
	tab_li.hover(function(){
		$(this).addClass('addc').siblings().removeClass('addc');
		
	var tab_li_index = tab_li.index(this);
	$('.sta_ma_r div').eq(tab_li_index).show().siblings().hide();
	});
});


$(function(){
	var lib_li = $('.library_l_menu li');	
	$('.library_l_box div:gt(0)').hide();
	
	lib_li.hover(function(){
		$(this).addClass('add_lib').siblings().removeClass('add_lib');
	});
	lib_li.click(function(){
		$(this).addClass('add_libs').siblings().removeClass('add_libs');
		var lib_index = lib_li.index(this);
		$('.library_l_box div').eq(lib_index).show().siblings().hide();
	});
});



$(function(){
	$('#lib_hover li').hover(
		function(){
			$(this).children('ul').css({display:'block'});	
		},function(){
			$(this).children('ul').css({display:'none'});	
		}
	);	
	
});




$(function(){
	var lib_six_li = $('.lib_six_menu li');	
	$('.lib_six_box table:gt(0)').hide();
	
	lib_six_li.click(function(){
		$(this).addClass('add_six').siblings().removeClass('add_six');
		
	var lib_six_li_index = lib_six_li.index(this);
	$('.lib_six_box table').eq(lib_six_li_index).show().siblings().hide();
	});
});


$(function(){
	var lib_seven_li = $('.lib_seven_menu li');	
	$('.lib_seven_box form:gt(0)').hide();
	
	lib_seven_li.click(function(){
		$(this).addClass('add_seven').siblings().removeClass('add_seven');
		
	var lib_seven_li_index = lib_seven_li.index(this);
	$('.lib_seven_box form').eq(lib_seven_li_index).show().siblings().hide();
	});
});

$(function(){
	var farm_nj_li = $('.farm_nj_menu li');	
	$('.farm_nj_box ul:gt(0)').hide();
	
	farm_nj_li.click(function(){
		$(this).addClass('add_nj').siblings().removeClass('add_nj');
		
	var farm_nj_li_index = farm_nj_li.index(this);
	$('.farm_nj_box ul').eq(farm_nj_li_index).show().siblings().hide();
	});
});



$(function(){
	var farm_sh_li = $('.farm_sh_menu li');	
	$('.farm_sh_box ul:gt(0)').hide();
	
	farm_sh_li.click(function(){
		$(this).addClass('add_sh').siblings().removeClass('add_sh');
		
	var farm_sh_li_index = farm_sh_li.index(this);
	$('.farm_sh_box ul').eq(farm_sh_li_index).show().siblings().hide();
	});
});



$(function(){
	var login_li = $('.login_menu li');	
	$('.login_box form:gt(0)').hide();
	
	login_li.click(function(){
		$(this).addClass('add_login').siblings().removeClass('add_login');
		
	var login_li_index = login_li.index(this);
	$('.login_box form').eq(login_li_index).show().siblings().hide();
	});
});


$(function(){
	var family_li = $('.family_cont_menu li');	
	$('.family_cont_box div:gt(0)').hide();
	
	family_li.click(function(){
		$(this).addClass('add_fam').siblings().removeClass('add_fam');
		
	var family_li_index = family_li.index(this);
	$('.family_cont_box div').eq(family_li_index).show().siblings().hide();
	});
});



$(function(){
	var familyd_li = $('.family_cont_menu1 li');	
	$('.family_cont_box1 div:gt(0)').hide();
	
	familyd_li.click(function(){
		$(this).addClass('add_fam1').siblings().removeClass('add_fam1');
		
	var familyd_li_index = familyd_li.index(this);
	$('.family_cont_box1 div').eq(familyd_li_index).show().siblings().hide();
	});
});


$(function(){
	var ch_jt_li = $('.ch_jt_menu li');	
	$('.ch_jt_box div:gt(0)').hide();
	
	ch_jt_li.hover(function(){
		$(this).addClass('addc').siblings().removeClass('addc');
		
	var ch_jt_li_index = ch_jt_li.index(this);
	$('.ch_jt_box div').eq(ch_jt_li_index).show().siblings().hide();
	});
});



$(function(){
	var ch_zpjj_li = $('.ch_zpjj_menu li');	
	$('.ch_zpjj_box ul:gt(0)').hide();
	
	ch_zpjj_li.click(function(){
		$(this).addClass('add_sh').siblings().removeClass('add_sh');
		
	var ch_zpjj_li_index = ch_zpjj_li.index(this);
	$('.ch_zpjj_box ul').eq(ch_zpjj_li_index).show().siblings().hide();
	});
});