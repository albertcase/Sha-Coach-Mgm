;(function(){
	var ua = navigator.userAgent.toLowerCase();
	var Common = {
		gotoPin:function(num){
			$('.wrapper .pin').removeClass('current');
			$('.wrapper .pin').eq(num).addClass('current');
		},
		getParameterByName:function(name){
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
			var results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		},
		setParameterByName:function(name,value){
			var query = location.search.substring(1,location.search.length);
			result = query;
			var queryArr = query.split('&');
			//update arr
			var newQuery = '';
			for(var i=0;i<queryArr.length;i++){
				if(queryArr[i].indexOf(name)>-1){
					queryArr[i] = name + '=' + value;
				}
				newQuery = newQuery+queryArr[i]+'&';
			};

			return '?'+newQuery;
		},
		msgBox:function(msg,long){
			if(long){
				$('body').append('<div class="ajaxpop msgbox minwidthbox"><div class="loading">'+msg+'</div></div>');
			}else{
				$('body').append('<div class="ajaxpop msgbox"><div class="loading"><div class="icon-loading"></div>'+msg+'</div></div>');
			}
		},
		errorMsg : {
			add:function(ele,msg){

				for(var i in ele.childNodes){
					if(ele.childNodes[i].className == 'error'){
						ele.childNodes[i].textContent = msg;
						return true;
					}else{
						if(i==ele.childNodes.length-1){
							var newDiv = document.createElement('div');
							newDiv.textContent = msg;
							newDiv.className = 'error';
							ele.appendChild(newDiv);
						}
					}
				}
			},
			remove:function(ele){

				for(var i in ele.childNodes){
					if(ele.childNodes[i].className == 'error'){
						ele.childNodes[i].parentNode.removeChild(ele.childNodes[i]);
						return;
					}
				}
			}
		},
		alertBox:{
			add:function(msg){
				$('body').append('<div class="alertpop msgbox"><div class="inner"><div class="msg">'+msg+'</div><div class="btn-alert-ok">关闭</div></div></div>');
			},
			remove:function(){
				$('.alertpop').remove();
			}
		},


	};

	this.Common = Common;

}).call(this);

var isScroll=false;
var noBounce = function() {
	var module = {};

	var settings = {
		animate: false
	};

	var track = [];

	var velocity = {
		x: 0,
		y: 0
	};

	var vector = {
		subtraction: function(v1, v2) {
			return {
				x: v1.x - v2.x,
				y: v1.y - v2.y
			};
		},
		length: function(v) {
			return Math.sqrt((v.x * v.x) + (v.y * v.y));
		},
		unit: function(v) {
			var length = vector.length(v);
			v.x /= length;
			v.y /= length;
		},
		skalarMult: function(v, s) {
			v.x *= s;
			v.y *= s;
		}
	};

	var requestAnimFrame = (function() {
		return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			window.msRequestAnimationFrame ||
			function(callback) {
				window.setTimeout(callback, 1000 / 60);
			};
	})();

	function handleTouchStart(evt) {
		var point,
			touch;

		touch = evt.changedTouches[0];
		point = {
			x: touch.clientX,
			y: touch.clientY,
			timeStamp: evt.timeStamp
		};
		track = [point];
	}

	function handleTouchMove(evt) {
		var point,
			touch;


		if(isScroll){
			//touch = evt.changedTouches[0];
			//point = {
			//	x: touch.clientX,
			//	y: touch.clientY,
			//	timeStamp: evt.timeStamp
			//};
			//track.push(point);
			//doScroll();
			evt.stopPropagation();
			return;
		}
		evt.preventDefault();


	}

	function handleTouchEnd(evt) {
		if (track.length > 2 && settings.animate) {
			velocity = calcVelocity();
			requestAnimFrame(animate);
		}
	}

	function calcVelocity() {
		var p1,
			p2,
			v,
			timeDiff,
			length;

		p1 = track[0];
		p2 = track[track.length - 1];
		timeDiff = p2.timeStamp - p1.timeStamp;
		v = vector.subtraction(p2, p1);
		length = vector.length(v);
		vector.unit(v);
		vector.skalarMult(v, length / timeDiff * 20);
		return v;
	}

	function doScroll() {
		var p1,
			p2,
			x,
			y;

		if (track.length > 1) {
			p1 = track[track.length - 1];
			p2 = track[track.length - 2];
			x = p2.x - p1.x;
			y = p2.y - p1.y;
			requestAnimFrame(function() {
				window.scrollBy(x, y);
			});
		}
	}

	function animate() {
		scrollBy(-velocity.x, -velocity.y);
		vector.skalarMult(velocity, 0.95);
		if (vector.length(velocity) > 0.2) {
			requestAnimFrame(animate);
		}
	}

	//Returns true if it is a DOM element
	function isElement(o) {
		return (
			typeof HTMLElement === "object" ? o instanceof HTMLElement : //DOM2
			o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName === "string"
		);
	}

	module.init = function(options) {
		if (typeof options.animate === "boolean") {
			settings.animate = options.animate;
		}
		if (isElement(options.element)) {
			settings.element = options.element;
		}

		var element = settings.element || document;

		element.addEventListener("touchstart", handleTouchStart);
		element.addEventListener("touchmove", handleTouchMove);
		element.addEventListener("touchend", handleTouchEnd);
		element.addEventListener("touchcancel", handleTouchEnd);
		element.addEventListener("touchleave", handleTouchEnd);
	};

	return module;
}();

noBounce.init({
	animate: false
});

$(document).ready(function(){

//	close alert pop
	$('body').on('touchstart','.btn-alert-ok',function(){
		$(this).parent().parent('.alertpop').remove();
	});
	wx.ready(function(){
		//wx.hideOptionMenu({
		//    menuList: ["menuItem:share:appMessage","menuItem:share:timeline","menuItem:share:qq","menuItem:share:weiboApp","menuItem:share:facebook","menuItem:share:QZone","menuItem:copyUrl","menuItem:openWithQQBrowser","menuItem:openWithSafari","menuItem:share:email"] // 要显示的菜单项，所有menu项见附录3
		//});
		wx.hideAllNonBaseMenuItem();

	})
});



