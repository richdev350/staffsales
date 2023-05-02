// JavaScript Document
/*http://www.nxworld.net/tips/page-top-appears-scroll.html*/


$(document).ready(function() {
	var pagetop = $('nav');
	$(window).scroll(function () {
		if ($(this).scrollTop() > 600) {	/*どれくらいスクロールしたら表示されるか*/
			pagetop.fadeIn();	/*fadeIn('fast')やfadeOut(1000)のように指定すれば変更可能*/
		} else {
			pagetop.fadeOut();
		}
	});
	pagetop.click(function () {
		$('body, html').animate({ scrollTop: 0 }, 500);	/*スクロールされる時の速度*/
		return false;
	});
});



$(document).ready(function() {
	var pagetop = $('.pagetop');
	$(window).scroll(function () {
		if ($(this).scrollTop() > 650) {	/*どれくらいスクロールしたら表示されるか*/
			pagetop.fadeIn();	/*fadeIn('fast')やfadeOut(1000)のように指定すれば変更可能*/
		} else {
			pagetop.fadeOut();
		}
	});
	pagetop.click(function () {
		$('body, html').animate({ scrollTop: 0 }, 500);	/*スクロールされる時の速度*/
		return false;
	});
});